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

        $bannerFile = '/var/www/ttxt/ttxtSdt.txt';
        $extraInfo  = array(
            '/var/www/machform/ScholarshipApplicationEnteringFreshmen.csv'   => 'ESAPON',
            '/var/www/machform/ScholarshipApplicationCurrentStudents.csv'    => 'RSAPON',
            '/var/www/machform/ScholarshipApplicationGraduatingStudents.csv' => 'GSAPON'
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
                    'GPA'           => ($banner[6]  != 0 ? $banner[6] : NULL), 'major' => $banner[7],
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
                                $student->goal       = $extra[26];
                                $student->minority   = 1;
                                $student->criteria   = 1;
                                $demo->transferMaj   = $extra[24];
                                $demo->transferInsti = $extra[25];
                                $demo->collegeGrad   = date('m/y', strtotime($extra[23]));
                            }
                            elseif ($type == 'RSAPON')
                            {
                                $criteria = array($extra[25], 2, $extra[26], 3, $extra[27], 4, $extra[28], 5, $extra[29], 6, $extra[30], 7, $extra[31], 8, $extra[32], 9);
                                $checkedCriteria = array();

                                foreach ($criteria as $k => $v)
                                {
                                    if ($v == 'Checked')
                                    {
                                        $checkedCriteria[] = $criteria[$k + 1];
                                    }
                                }

                                $minority        = array($extra[33], 2, $extra[34], 3, $extra[35], 4, $extra[36], 5, $extra[7], 6);
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
                                $student->criteria    = 1;
                                $demo->highSchoolName = $extra[25];
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
            }
        }

        $this->helper->needsToRepeat($jobID, $repeat);
        $this->helper->updateCount($jobID);
        $this->helper->studentUploadNotification($bannerMessage, $incorrect, $incorrectCount);

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
