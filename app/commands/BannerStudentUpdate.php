<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;

class BannerStudentUpdate extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'BannerStudentUpdate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updates the student table with new entries from Lynns process and the machform data.';

    /**
     * Helper functions for the commands we will be writing
     *
     * @var JobHelper
     */
    Protected $helper;

    /**
     * Create a new command instance.
     *
     * @param JobHelper $helper
     *
     * @return \BannerStudentUpdate
     */
    public function __construct(JobHelper $helper)
    {
        parent::__construct();
        $this->helper = $helper;
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function fire()
    {
        $jobID  = $this->argument('jobID');
        $repeat = $this->argument('repeat');
        $this->helper->running($jobID);

        $bannerFile = '/home/ttxt/ttxtSdt.txt';
        $extraInfo  = array(
            '/home/machform/ScholarshipApplicationEnteringFreshmen.csv'   => 'ESAPON',
            '/home/machform/ScholarshipApplicationCurrentStudents.csv'    => 'RSAPON',
            '/home/machform/ScholarshipApplicationGraduatingStudents.csv' => 'GSAPON'
        );

        if (!file_exists($bannerFile))
        {
            $this->helper->statusNotification('Banner CSV file can not be located.', 23);
            $this->helper->failJob($jobID);
            return;
        }

        if (!is_readable($bannerFile))
        {
            $this->helper->statusNotification('Banner CSV file is not readable.', 23);
            $this->helper->failJob($jobID);
            unlink($bannerFile);

            return;
        }

        $bannerFile = fopen($bannerFile, 'r');

        if ($bannerFile === FALSE)
        {
            $this->helper->statusNotification('Banner CSV file is not opening.', 23);
            $this->helper->failJob($jobID);
            unlink($bannerFile);

            return;
        }
        else // File opening passed
        {
            $line            = 1;
            $newStudents     = 0;
            $updatedStudents = 0;

            while (($banner = fgetcsv($bannerFile, 0, ",")) !== FALSE)
            {
                if ($line == 1)
                {
                    ++$line;
                    continue;
                }
                else
                {
                    ++$line;
                    // Student Table
                    if (count(\Student::find($banner[0])) != 1)
                    {
                        $student            = new \Student();
                        $student->studentID = $banner[0];
                        $student->firstName = $banner[2];
			$student->lastName  = $banner[1];
                        $student->sunyEmail = $banner[3];
                        $student->save();
                        ++$newStudents;
                    }
                    else // Student exists check if they needed to be updated
                    {
                        $updated = FALSE;
                        $student = \Student::find($banner[0]);

                        if ($student->firstName != $banner[2])
                        {
                            $student->firstName = $banner[2];
                            $updated            = TRUE;
                        }

                        if ($student->lastName != $banner[1])
                        {
                            $student->lastName = $banner[1];
                            $updated           = TRUE;
                        }

                        if ($student->sunyEmail != $banner[3])
                        {
                            $student->sunyEmail = $banner[3];
                            $updated            = TRUE;
                        }

                        if ($updated)
                        {
                            ++$updatedStudents;
                            $student->save();
                        }
                    }
                    // End of Student Table
                }

                // Part 1 of demographics table we need this to start here because
                //the banner file and the machform files both contain data we need!
                $demo   = new StudentDemo();
                $values = array(
                    'creditHourSP'  => ($banner[4]  != 0 ? $banner[4] : NULL),
                    'creditHourFA'  => ($banner[5]  != 0 ? $banner[5] : NULL),
                    'GPA'           => ($banner[6]  != 0 ? $banner[6] : NULL),
                    'major'         => $banner[7],
                    'creditsEarned' => ($banner[8]  != 0 ? $banner[8] : NULL),
                    'highSchoolAvg' => ($banner[10] != 0 ? $banner[10] : NULL),
                    'highGrad'      => ($banner[11] !== '' ? date('m/y', strtotime($banner[11])) : '')
                );

                $demo->insertDemographics($values, $banner[0]);
                // End of part 1
            }

            $bannerMessage = 'Banner information successfully uploaded - ' . $newStudents . ' new students and ' . $updatedStudents . ' updated students.';
            fclose($bannerFile);
        }

        foreach ($extraInfo as $file => $type)
        {
            if (!file_exists($file))
            {
                $this->helper->failJob($jobID);
                $this->helper->statusNotification('The application group ' . $type . ' CSV file from Mach Form can not be located.', 23);
                continue;
            }

            if (!is_readable($file))
            {
                $this->helper->failJob($jobID);
                $this->helper->statusNotification('The application group ' . $type . ' CSV file from Mach Form is not readable.', 23);
                unlink($file);
                continue;
            }

            $file = fopen($file, 'r');

            if ($file === FALSE)
            {
                $this->helper->failJob($jobID);
                $this->helper->statusNotification('The application group ' . $type . ' CSV file from Mach Form is not opening.', 23);
                unlink($file);
                continue;
            }
            else
            {
                $line           = 1;
                $incorrectCount = 0;
                $incorrect      = array();

                while (($extra = fgetcsv($file, 0, ",")) !== FALSE)
                {
                    if ($line == 1)
                    {
                        ++$line;
                        continue;
                    }
                    else
                    {
                        ++$line;
                        // Student table extra information
                        $student = \Student::find($extra[9]);

                        if (count($student) == 1)
                        {
                            // Student address first - why not? //
                            // We are guaranteed to have a matching student because we are going through the check above

                            $address = new \StudentAddress();
                            $address->initializeAddress($extra[9]);
                            $state = DB::table('states')->where('stateFull', '=', $extra[13])->get(array('state'));
                            $add   = array(
                                'studentID' => $extra[9], 'address' => $extra[10] . '||' . $extra[11],
                                'city'      => $extra[12], 'state' => $state[0]->state, 'zipCode' => $extra[14],
                                'county'    => $extra[16]
                            );
                            $address->upDateAddress($add);
                            // End of address//

                            $student->personalEmail = $extra[8];
                            $student->homePhone     = preg_replace('/[^A-Za-z0-9]/', "", $extra[17]);
                            $student->cellPhone     = preg_replace('/[^A-Za-z0-9]/', "", $extra[19]);

                            if ($extra[18] == 'Yes')
                            {
                                $student->cellNotifications = 1;
                                $carrierID                  = \DB::table('cellCarriers')->where('carrier', '=', $extra[20])->get(array('carrierId'));
                                if (count($carrierID) == 1)
                                {
                                    $student->cellCarrier = $carrierID[0]->carrierId;
                                }
                            }
                            else
                            {
                                $student->cellNotifications = 0;
                            }

                            $demo = StudentDemo::find($extra[9]);

                            if ($type == 'GSAPON')
                            {
				$student->goal		=$extra[26];
				$student->minority	= 1;
				$student->criteria	= 1;
				$demo->transferMaj	= $extra[24];
				$demo->transferInsti	= $extra[25];
				$demo->collegeGrad	= date('m/y', strtotime($extra[23]));
                            }
			    elseif ($type == 'RSAPON')
                            {
                                $criteria = array($extra[25], 30, $extra[26], 9, $extra[27], 20, $extra[28], 31, $extra[29], 32, $extra[30], 33, $extra[31], 5, $extra[32], 21, $extra[33], 22, $extra[34], 4, $extra[35], 23, $extra[36], 34, $extra[37], 2, $extra[38], 35, $extra[39], 18, $extra[40], 13, $extra[41], 12, $extra[42], 36, $extra[43], 11, $extra[44], 37, $extra[45], 41, $extra[46], 26, $extra[47], 3, $extra[48], 8, $extra[49], 38, $extra[50], 27, $extra[51], 6, $extra[52], 28, $extra[53], 29, $extra[54], 7, $extra[55], 10, $extra[56], 39, $extra[57], 40, $extra[58], 14, $extra[59], 15, $extra[60], 16, $extra[61], 17);

                                $checkedCriteria = array();

                                foreach ($criteria as $k => $v)
                                {
                                    if ($v == 'Checked')
                                    {
                                        $checkedCriteria[] = $criteria[$k + 1];
                                    }
                                }

                                $minority        = array($extra[62], 2, $extra[63], 5, $extra[64], 6, $extra[65], 3, $extra[66], 7, $extra[67], 4);
                                $checkedMinority = array();

                                foreach ($minority as $k => $v)
                                {
                                    if ($v == 'Checked')
                                    {
                                        $checkedMinority[] = $minority[$k + 1];
                                    }
                                }

                                if (count($checkedMinority) > 0)
                                {
                                    $student->minority = implode(',', $checkedMinority);
                                }
                                else
                                {
                                    $student->minority = 1;
                                }

                                if (count($checkedCriteria) > 0)
                                {
                                    $student->criteria = implode(',', $checkedCriteria);
                                }
                                else
                                {
                                    $student->criteria = 1;
                                }
				$student->goal = $extra[22];
                            }
                            else
                            {
                                $student->goal        = $extra[23];
                                $student->minority    = 1;	
				//$demo->highSchoolName = $extra[25];

				$highSchoolName = array($extra[25], 0, $extra[26], 1, $extra[27], 2, $extra[28], 3, $extra[29], 4, $extra[30], 5, $extra[31], 6, $extra[32], 7, $extra[33], 8, $extra[34], 9, $extra[35], 10, $extra[36], 11, $extra[37], 12, $extra[38], 13, $extra[39], 14, $extra[40], 15, $extra[41], 16, $extra[42], 17, $extra[43], 18, $extra[44], 19, $extra[45], 20, $extra[46], 21);
				$otherHighSchoolName = $extra[47];	
			
				$checkedHighSchoolName;

                                foreach ($highSchoolName as $k => $v)
                                {
                                    if ($v == 'Checked')
                                    {
                                        $checkedHighSchoolName = $highSchoolName[$k + 1];
                                    }
                                }

                                if (strlen($checkedHighSchoolName) > 0)
                                {
                                    $demo->highSchoolName = trim(',', $checkedHighSchoolName);
                                }
                                else
                                {
                                    $demo->highSchoolName = $otherHighSchoolName;
                                }

				$criteria = array($extra[55], 19, $extra[56], 20, $extra[57], 21, $extra[58], 22, $extra[59], 23, $extra[60], 24, $extra[61], 25, $extra[62], 26, $extra[63], 27, $extra[64], 28, $extra[65], 29, $extra[66], 30);

                                $checkedCriteria = array();

                                foreach ($criteria as $k => $v)
                                {
                                    if ($v == 'Checked')
                                    {
                                        $checkedCriteria[] = $criteria[$k + 1];
                                    }
                                }	
			    }

                            $student->save();
                            $demo->save();
                        }
                        elseif (count($student) == 0)
                        {
                            ++$incorrectCount;
                            $incorrect[] = '[Group: ' . $type . '] [StudentID: ' . $extra[9] . '] [Student Name: ' . $extra[5] . ' ' . $extra[7] . ']';
                        }
                    }
                }

                fclose($file);
                $this->helper->studentUploadNotification($bannerMessage, $incorrect, $incorrectCount);
            }
        }

        $this->helper->needsToRepeat($jobID, $repeat);
        $this->helper->updateCount($jobID);

        return;
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return array(
            array('jobID', InputArgument::REQUIRED, 'The unique identifier for this job'),
            array('repeat', InputArgument::REQUIRED, 'Does this job need to repeat? [1|0]')
        );
    }
}
