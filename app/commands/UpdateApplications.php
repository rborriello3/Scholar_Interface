<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;

class UpdateApplications extends Command
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'UpdateApplications';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updates student applications and recommendations and sends out notifications to students.';

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
     * @return \UpdateApplications
     */
    public function __construct(JobHelper $helper)
    {
        parent::__construct();
        $this->helper = $helper;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        $jobID  = $this->argument('jobID');
        $repeat = $this->argument('repeat');
        $this->helper->running($jobID);
        $apps = array(
            '/var/www/machform/ScholarshipApplicationEnteringFreshmen.csv'   => 'ESAPON',
            '/var/www/machform/ScholarshipApplicationCurrentStudents.csv'    => 'RSAPON',
            '/var/www/machform/ScholarshipApplicationGraduatingStudents.csv' => 'GSAPON'
        );
        $notFoundCount = 0;
        $notFound      = array();
        $multiAppCount = 0;
        $multiApp      = array();

        foreach ($apps as $file => $type)
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
                $line = 1;

                while (($app = fgetcsv($file, 0, ",")) !== FALSE)
                {
                    if ($line == 1)
                    {
                        ++$line;
                        continue;
                    }
                    else
                    {
                        ++$line;
                        // If application A-Number has a matching student
                        if (count(\Student::find($app[9])) == 1)
                        {
                            if ($type == 'GSAPON')
                            {
                                $queryType = array('2', '6');
                                $single    = '4';
                            }
                            elseif ($type == 'RSAPON')
                            {
                                $queryType = array('2', '4');
                                $single    = '6';
                            }
                            else
                            {
                                $queryType = array('4', '6');
                                $single    = '2';
                            }

                            // Check if the student has duplicate applications that are active and are in another application type
                            $checkApps = \Application::where('studentID', '=', $app[9])->whereIn('typeID', $queryType)->whereHas('Aidyear', function ($q)
                            {
                                $q->where('status', '=', 1);
                            })->get(array('typeID'));

                            // student has no applications in the database that are active in other app types
                            if (count($checkApps) == 0)
                            {
                                // check if student has an application that is active and have the typeID of 2,4 or 6.
                                $checkForApp = \Application::where('studentID', '=', $app[9])->where('typeID', '=', $single)->whereHas('Aidyear', function ($q)
                                {
                                    $q->where('status', '=', 1);
                                })->get(array('noUpdateNightly'));

                                //Student has no active applications period and the admin is allowing an update to happen on a nightly basis 
                                if (count($checkForApp) == 0)
                                {
                                    $aidyear                = \DB::table('aidyears')->where('status', '=', 1)->get(array('aidyear'));
                                    $application            = new \Application();
                                    $application->GUID      = bin2hex(openssl_random_pseudo_bytes('25'));
                                    $application->studentID = $app[9];
                                    $application->received  = date('m/d/y', strtotime($app[1]));
                                    $application->aidyear   = $aidyear[0]->aidyear;

                                    if ($type == 'GSAPON')
                                    {
                                        $application->typeID          = 4;
                                        $application->extraCurricular = $app[27];
                                        $application->essay           = $app[28];
                                    }

                                    elseif ($type == 'RSAPON')
                                    {
                                        $application->typeID          = 6;
                                        $application->extraCurricular = $app[38];
                                        $application->essay           = $app[39];
                                    }
                                    else
                                    {
                                        $application->typeID          = 2;
                                        $application->extraCurricular = $app[28];
                                        $application->essay           = $app[29];
                                    }

                                    $application->save();

                                    if ($type != 'GSAPON')
                                    {
                                        $response = new \ApplicationResponse($application->applicationID, $application->studentID);
                                        $response->newResponse();
                                    }

                                    $recomm = new \ApplicationRecommendation($application->applicationID, $application->studentID);
                                    $recomm->newRecommendation();
                                }
                                elseif (count($app) == 1 && $app[0]->noUpdateNightly == 0) // Student has an active application (only 1)! and admin allows update to happen
                                {
                                    $updates             = array();
                                    $aidyear             = \DB::table('aidyears')->where('status', '=', 1)->get(array('aidyear'));
                                    $updates['received'] = date('m/d/y', strtotime($app[1]));
                                    $updates['aidyear']  = $aidyear[0]->aidyear;

                                    if ($type == 'GSAPON')
                                    {
                                        $updates['typeID']          = 4;
                                        $updates['extraCurricular'] = $app[27];
                                        $updates['essay']           = $app[28];
                                    }

                                    elseif ($type == 'RSAPON')
                                    {
                                        $updates['typeID']          = 6;
                                        $updates['extraCurricular'] = $app[38];
                                        $updates['essay']           = $app[39];
                                    }
                                    else
                                    {
                                        $updates['typeID']          = 2;
                                        $updates['extraCurricular'] = $app[28];
                                        $updates['essay']           = $app[29];
                                    }

                                    \Application::where('studentID', '=', $app[9])->where('typeID', '=', $single)->whereHas('Aidyear', function ($q)
                                    {
                                        $q->where('status', '=', 1);
                                    })->update($updates);
                                }
                            }
                            else // Student has active applications in other types. We do not want another application to be included. Notify admins
                            {
                                $types = '';

                                if ($checkApps[0]->typeID == 2)
                                {
                                    $types .= 'RSAPON ';
                                }
                                elseif ($checkApps[0]->typeID == 4)
                                {
                                    $types .= 'GSAPON ';
                                }
                                else
                                {
                                    $types .= 'ESAPON ';
                                }

                                $types .= $type;

                                ++$multiAppCount;
                                $multiApp[] = '[StudentID: ' . $app[9] . '] [Student Name: ' . $app[5] . ' ' . $app[7] . '] [Scholarship Types: ' . $types . ']';
                            }
                        }
                        // The A-Number that is associated with the application does not exist in the database (aka Lynns Banner File).
                        // We can NOT insert this due to a fatal database error (invalid constraint.)
                        else
                        {
                            ++$notFoundCount;
                            $notFound[] = '[Group: ' . $type . '] [StudentID: ' . $app[9] . '] [Student Name: ' . $app[5] . ' ' . $app[7] . ']';
                        }
                    }
                }
            }
        }

        $recFiles    = array(
            '/var/www/machform/FacultyRecommendationEnteringFreshmen.csv'   => 'Entering Freshmen',
            '/var/www/machform/FacultyRecommendationCurrentStudents.csv'    => 'Returning Student',
            '/var/www/machform/FacultyRecommendationGraduatingStudents.csv' => 'Graduating'
        );
        $appNotFound        = array();
        $noAppCount         = 0;
        $completeStudents   = array();
        $incompleteStudents = array();

        foreach ($recFiles as $file => $type)
        {
            if ($type == 'Returning Student')
            {
                $singleSearch = 6;
            }
            elseif ($type == 'Entering Freshmen')
            {
                $singleSearch = 2;
            }
            elseif ($type == 'Graduating')
            {
                $singleSearch = 4;
            }

            if (!file_exists($file))
            {
                $this->helper->failJob($jobID);
                $this->helper->statusNotification($type . ' recommendation CSV file from Mach Form can not be located.', 23);
                continue;
            }

            if (!is_readable($file))
            {
                $this->helper->failJob($jobID);
                $this->helper->statusNotification($type . ' recommendation CSV file from Mach Form is not readable.', 23);
                unlink($file);
                continue;
            }

            $file = fopen($file, 'r');

            if ($file === FALSE)
            {
                $this->helper->failJob($jobID);
                $this->helper->statusNotification($type . ' recommendation CSV file from Mach Form is not opening.', 23);
                unlink($file);
                continue;
            }
            else
            {
                $line = 1;
                //  Returning students has a glitch in it?
                // Need shut eye, will fix it tomorrow.
                while (($recs = fgetcsv($file, 0, ",")) !== FALSE)
                {
                    if ($line == 1)
                    {
                        ++$line;
                        continue;
                    }
                    else
                    {
                        ++$line;

                        // Get the applicationID
                        $appCount = \Application::where('studentID', '=', $recs[6])->where('typeID', '=', $singleSearch)->whereHas('Aidyear', function ($q)
                        {
                            $q->where('status', '=', 1);
                        })->get(array('applicationID'));

                        if (count($appCount) == 1)
                        {
                            $machFormDate = date('m/d/y', strtotime($recs[1]));
                            $recom        = \ApplicationRecommendation::find($appCount[0]->applicationID);

                            if ($recs[4] == 'GSREC1' || $recs[4] == 'ESREC1' || $recs[4] == 'RSREC1')
                            {
                                if ($recs[4] == 'ESREC1')
                                {
                                    $facultyName = $recs[8];
                                    $department  = $recs[7];
                                }
                                else
                                {
                                    $facultyName = $recs[7];
                                    $department  = $recs[8];
                                }

                                $recom->recommender1       = $facultyName;
                                $recom->email1             = $recs[9];
                                $recom->department1        = $department;
                                $recom->courseName1        = $recs[10];
                                $recom->academicPotential1 = $recs[11];
                                $recom->character1         = $recs[12];
                                $recom->emotionalMaturity1 = $recs[13];

                                if ($recs[14] == 'Top 10%')
                                {
                                    $recom->overallRank1 = 'Top 10';
                                }
                                else
                                {
                                    if (strpos($recs[14], '%') !== FALSE)
                                    {
                                        $recom->overallRank1 = 'Top ' . substr($recs[14], 0, 2);
                                    }
                                    else
                                    {
                                        $recom->overallRank1 = 'Bottom';
                                    }
                                }

                                $recom->comments1 = $recs[15];
                                $recom->received  = $machFormDate;
                                $recom->save();
                            }
                            elseif ($recs[4] == 'GSREC2' || $recs[4] == 'ESREC2' || $recs[4] == 'RSREC2')
                            {
                                if ((strtotime($machFormDate) <= strtotime($recom->updated)) || ($recom->updated == NULL))
                                {
                                    if ($recs[4] == 'ESREC2')
                                    {
                                        $facultyName = $recs[8];
                                        $department  = $recs[7];
                                    }
                                    else
                                    {
                                        $facultyName = $recs[7];
                                        $department  = $recs[8];
                                    }

                                    $recom->recommender2       = $facultyName;
                                    $recom->email2             = $recs[9];
                                    $recom->department2        = $department;
                                    $recom->courseName2        = $recs[10];
                                    $recom->academicPotential2 = $recs[11];
                                    $recom->character2         = $recs[12];
                                    $recom->emotionalMaturity2 = $recs[13];

                                    if ($recs[14] == 'Top 10%')
                                    {
                                        $recom->overallRank2 = 'Top 10';
                                    }
                                    elseif ($recs[14] != 'Top 10%')
                                    {
                                        if (strpos($recs[14], '%') !== FALSE)
                                        {
                                            $recom->overallRank2 = 'Top ' . substr($recs[14], 0, 2);
                                        }
                                        else
                                        {
                                            $recom->overallRank2 = 'Bottom';
                                        }
                                    }

                                    $recom->comments2 = $recs[15];
                                    $recom->updated   = $machFormDate;
                                    $recom->complete  = 1;
                                    $recom->save();
                                }
                            }

                            // Check if the students application process is complete.
                            // and that they are in the incomplete / new status.
                            $application = \Application::find($appCount[0]->applicationID);

                            if ($recom->complete == 1 && ($application->statusID == 2 || $application->statusID == 1))
                            {
                                $completeStudents[$recs[6]] = $appCount[0]->applicationID;
                            }
                            elseif ($recom->complete == 0)
                            {
                                $incompleteStudents[$recs[6]] = $appCount[0]->applicationID;
                            }
                        }
                        else
                        {
                            ++$noAppCount;
                            if ($type == 'Entering Freshmen')
                            {
                                $appNotFound[] = '[Group : ' . $recs[4] . '] [Student ID : ' . $recs[6] . '] [Student Name : ' . $recs[5] . '] [Faculty : ' . $recs[8] . '] [Date : ' . $recs[1] . ']';
                            }
                            else
                            {
                                $appNotFound[] = '[Group : ' . $recs[4] . '] [Student ID : ' . $recs[6] . '] [Student Name : ' . $recs[5] . '] [Faculty : ' . $recs[7] . '] [Date : ' . $recs[1] . ']';
                            }
                        }
                    }
                }
                // If the recommendation type doesn't match the application type create an error report and do not enter the recommendation into the system.
            }
        }

        if (isset($completeStudents) && count($completeStudents) > 0)
        {
            $email = new \Email();

            foreach ($completeStudents as $anum => $appID)
            {
                $student = \Student::find($anum);

                if ($student->cellnotifications == 1 && $student->cellCarrier != 0)
                {
                    $sms = new \Text('');
                    $sms->applicationNotifcation($student->cellPhone, $student->cellCarrier, FALSE);
                }

                $email->emailSentToSUNY($student->personalEmail, $student->firstName, $student->lastName);
                $email->completedApplication($student->sunyEmail, $student->firstName, $student->lastName, $appID);
                $application           = \Application::find($appID);
                $application->statusID = 3;
                $application->save();
                $assess = new \ApplicationAssessment();
                $assess->initialize($application->applicationID, $application->typeID);
            }
        }

        if (isset($incompleteStudents) && count($incompleteStudents) > 0)
        {
            $email = new \Email();

            foreach ($incompleteStudents as $anum => $appID)
            {
                $student = \Student::find($anum);

                if ($student->cellnotifications == 1 && $student->cellCarrier != 0)
                {
                    $sms = new \Text('');
                    $sms->applicationNotifcation($student->cellPhone, $student->cellCarrier, 'checkEmail');
                }

                $email->emailSentToSUNY($student->personalEmail, $student->firstName, $student->lastName);
                $email->incompleteApplication($student->sunyEmail, $student->firstName, $student->lastName, $appID);
                $application           = \Application::find($appID);
                $application->statusID = 2;
                $application->save();
            }
        }

        // Get all recommendations that have null recommender1 and null recommender2 because these students are not in the 
        // machform csv there IS NO WAY for this script to catch them to notify them what so ever. So lets go shall we?
        $recs = DB::table('applicationRecommendations')->join('applications', 'applications.applicationID', '=', 'applicationRecommendations.applicationID')
                                                       ->join('aidyears', 'aidyears.aidyear', '=', 'applications.aidyear')
                                                       ->select('applicationRecommendations.applicationID', 'applications.studentID')
                                                       ->where('aidyears.status', '=', 1)
                                                       ->where('recommender1', '=', NULL)
                                                       ->where('recommender2', '=', NULL)
                                                       ->get();

        foreach ($recs as $r)
        {
            $email = new \Email();
            
            $student = \Student::find($r->studentID);
            
            if ($student->cellnotifications == 1 && $student->cellCarrier != 0)
            {
                $sms = new \Text('');
                $sms->applicationNotifcation($student->cellPhone, $student->cellCarrier, 'checkEmail');
            }
        
            $email->emailSentToSUNY($student->personalEmail, $student->firstName, $student->lastName);
            $email->incompleteApplication($student->sunyEmail, $student->firstName, $student->lastName, $r->applicationID);
            $application           = \Application::find($r->applicationID);
            $application->statusID = 2;
            $application->save();
        }

        //$this->helper->needsToRepeat($jobID, $repeat);
        //$this->helper->updateCount($jobID);
        $this->helper->applicationUploadNotification('Student scholarship applications and recommendations have successfully been uploaded into Scholarship Interface!', $notFoundCount, $notFound, $multiAppCount, $multiApp, $noAppCount, $appNotFound);

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