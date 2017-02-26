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
            '/home/machform/ScholarshipApplicationEnteringFreshmen.csv'   => 'ESAPON',
            '/home/machform/ScholarshipApplicationCurrentStudents.csv'    => 'RSAPON',
            '/home/machform/ScholarshipApplicationGraduatingStudents.csv' => 'GSAPON'
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
                            $checkApps = \Application::where('studentID', '=', $app[9])->whereIn('typeID', $queryType)->whereHas('aidyear', function ($q)
                            {
                                $q->where('status', '=', 1);
                            })->get(array('typeID'));

                            // student has no applications in the database that are active in other app types
                            if (count($checkApps) == 0)
                            {
                                // check if student has an application that is active and have the typeID of 2,4 or 6.
                                $checkForApp = \Application::where('studentID', '=', $app[9])->where('typeID', '=', $single)->whereHas('aidyear', function ($q)
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
                                        $application->typeID          		= 4;
                                        $application->extraCurricular 		= $app[27];
                                        $application->essay           		= $app[28];
					$application->desiredScholarships	= $app[29];
					$application->essaySelf       		= $app[30];
                                        $application->essayWhy        		= $app[31];
                                    }

                                    elseif ($type == 'RSAPON')
                                    {
                                        $application->typeID          		= 6;
                                        $application->extraCurricular 		= $app[68];
                                        $application->essay           		= $app[69];
					$application->desiredScholarships 	= $app[70];
					$application->essaySelf       		= $app[71];
					$application->essayWhy        		= $app[72];
                                    }
                                    else
                                    {
                                        $application->typeID          		= 2;
                                        $application->extraCurricular 		= $app[50];
                                        $application->essay           		= $app[51];
					$application->essaySelf       		= $app[52];
					$application->essayWhy        		= $app[53];
					$application->desiredScholarships	= $app[54];
                                   }

                                    $application->save();

                                    /*if ($type != 'GSAPON')
                                    {
                                        $response = new \ApplicationResponse($application->studentID, $application-);
                                        $response->newResponse();
                                    }*/

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
                                        $application->typeID          = 4;
                                        $application->extraCurricular = $app[28];
                                        $application->essay           = $app[29];
					$application->desiredScholarships = $app[30];
					$application->essaySelf       = $app[31];
					$application->essayWhy	      = $app[32];
                                    }

                                    elseif ($type == 'RSAPON')
                                    {
                                        $application->typeID          = 6;
                                        $application->extraCurricular = $app[68];
                                        $application->essay           = $app[69];
					$application->desiredScholarships = $app[70];
					$application->essaySelf       = $app[71];
					$application->essayWhy        = $app[72];
                                    }
                                    else
                                    {
                                        $application->typeID          = 2;
                                        $application->extraCurricular = $app[50];
                                        $application->essay           = $app[51];
					$application->essaySelf       = $app[52];
					$application->essayWhy        = $app[53];
					$application->desiredScholarships = $app[54];
                                    }

                                    \Application::where('studentID', '=', $app[9])->where('typeID', '=', $single)->whereHas('aidyear', function ($q)
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
//Changed File name: added  Scholarship to FacultyRecommendationEnteringFreshmen.csv
        $recFiles    = array(
            '/home/machform/ScholarshipFacultyRecommendationEnteringFreshmen.csv'   => 'Entering Freshmen',
            '/home/machform/ScholarshipFacultyRecommendationCurrentStudents.csv'    => 'Returning Student',
            '/home/machform/ScholarshipFacultyRecommendationGraduatingStudents.csv' => 'Graduating'
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
			/********************
			    -- NOTE: the machform field containing student's email is in a different position for Returning Student Recommendations than for Graduating/Entering Student Recommendations.
				  Returning --> email was added before A#, so A# is now in $recs[7] and everything beyond $recs[7] has been incremented by 1 (i.e. $recs[10] was originally $recs[9])
				  Entering/Graduating --> email was added directly after A#, so A# remains in $recs[6] but everything beyond $recs[6] has still been incremented by 1
			
			    -- Per client, it is not necessary to store the student email provided in the recommendation (student-supplied email will take precedence)
			********************/
                        if($recs[4] == 'RSREC1' || $recs[4] == 'RSREC2')
			{
			    $appCount = \Application::where('studentID', '=', $recs[7])->where('typeID', '=', $singleSearch)->whereHas('aidyear', function ($q)
                            {	
                                $q->where('status', '=', 1);
                            })->get(array('applicationID'));
			}
			else
			{
			    $appCount = \Application::where('studentID', '=', $recs[6])->where('typeID', '=', $singleSearch)->whereHas('aidyear', function ($q)
                            {	
                                $q->where('status', '=', 1);
                            })->get(array('applicationID'));
			}
                        if (count($appCount) == 1)
                        {
                            $machFormDate = date('m/d/y', strtotime($recs[1]));
                            $recom        = \ApplicationRecommendation::find($appCount[0]->applicationID);

                            if ($recs[4] == 'GSREC1' || $recs[4] == 'ESREC1' || $recs[4] == 'RSREC1')
                            {
                                if ($recs[4] == 'ESREC1')
                                {
                                    $facultyName = $recs[9];
                                    $department  = $recs[8];
                                }
                                else
                                {
                                    $facultyName = $recs[8];
                                    $department  = $recs[9];
                                }

                                $recom->recommender1       = $facultyName;
                                $recom->email1             = $recs[10];
                                $recom->department1        = $department;
                                $recom->courseName1        = $recs[11];
                                $recom->academicPotential1 = $recs[12];
                                $recom->character1         = $recs[13];
                                $recom->emotionalMaturity1 = $recs[14];

                                if ($recs[15] == 'Top 10%')
                                {
                                    $recom->overallRank1 = 'Top 10';
                                }
                                else
                                {
                                    if (strpos($recs[15], '%') !== FALSE)
                                    {
                                        $recom->overallRank1 = 'Top ' . substr($recs[15], 0, 2);
                                    }
                                    else
                                    {
                                        $recom->overallRank1 = 'Bottom';
                                    }
                                }

                                $recom->comments1 = $recs[16];
                                $recom->received  = $machFormDate;
                                $recom->save();
                            }
                            elseif ($recs[4] == 'GSREC2' || $recs[4] == 'ESREC2' || $recs[4] == 'RSREC2')
                            {
                                if ((strtotime($machFormDate) <= strtotime($recom->updated)) || ($recom->updated == NULL))
                                {
                                    if ($recs[4] == 'ESREC2')
                                    {
                                        $facultyName = $recs[9];
                                        $department  = $recs[8];
                                    }
                                    else
                                    {
                                        $facultyName = $recs[8];
                                        $department  = $recs[9];
                                    }

                                    $recom->recommender2       = $facultyName;
                                    $recom->email2             = $recs[10];
                                    $recom->department2        = $department;
                                    $recom->courseName2        = $recs[11];
                                    $recom->academicPotential2 = $recs[12];
                                    $recom->character2         = $recs[13];
                                    $recom->emotionalMaturity2 = $recs[14];

                                    if ($recs[15] == 'Top 10%')
                                    {
                                        $recom->overallRank2 = 'Top 10';
                                    }
                                    elseif ($recs[15] != 'Top 10%')
                                    {
                                        if (strpos($recs[15], '%') !== FALSE)
                                        {
                                            $recom->overallRank2 = 'Top ' . substr($recs[15], 0, 2);
                                        }
                                        else
                                        {
                                            $recom->overallRank2 = 'Bottom';
                                        }
                                    }

                                    $recom->comments2 = $recs[16];
                                    $recom->updated   = $machFormDate;
                                    $recom->complete  = 1;
                                    $recom->save();
                                }
                            }

                            // Check if the students application process is complete.
                            // and that they are in the incomplete / new status.
                            $application = \Application::find($appCount[0]->applicationID);

                            if($recs[4] == 'RSREC1' || $recs[4] == 'RSREC2')
			    {
			        if ($recom->complete == 1 && ($application->statusID == 2 || $application->statusID == 1))
                                {
                                    $completeStudents[$recs[7]] = $appCount[0]->applicationID;
                                }
                                elseif ($recom->complete == 0)
                                {
                                    $incompleteStudents[$recs[7]] = $appCount[0]->applicationID;
                                }
                            }
			    else
			    {
				if ($recom->complete == 1 && ($application->statusID == 2 || $application->statusID == 1))
                                {
                                    $completeStudents[$recs[6]] = $appCount[0]->applicationID;
                                }
                                elseif ($recom->complete == 0)
                                {
                                    $incompleteStudents[$recs[6]] = $appCount[0]->applicationID;
                                }
			    }	
			}
                        else
                        {
                            ++$noAppCount;
                            if ($type == 'Entering Freshmen' || $type == 'Graduating')
                            {
                                $appNotFound[] = '[Group : ' . $recs[4] . '] [Student ID : ' . $recs[6] . '] [Student Name : ' . $recs[5] . '] [Faculty : ' . $recs[9] . '] [Date : ' . $recs[1] . ']';
                            }
                            else if($type == 'Returning Student')
                            {
                                $appNotFound[] = '[Group : ' . $recs[4] . '] [Student ID : ' . $recs[7] . '] [Student Name : ' . $recs[5] . '] [Faculty : ' . $recs[8] . '] [Date : ' . $recs[1] . ']';
                            }
                        }
                    }
                }
                // If the recommendation type doesn't match the application type create an error report and do not enter the recommendation into the system.
            }
        }

        if (isset($completeStudents) && count($completeStudents) > 0)
        {
            foreach ($completeStudents as $anum => $appID)
            {
                $application           = \Application::find($appID);
                $application->statusID = 3;
                $application->save();
                $assess = new \ApplicationAssessment();
                $assess->initialize($application->applicationID, $application->typeID);
            }
        }

        if (isset($incompleteStudents) && count($incompleteStudents) > 0)
        {
            foreach ($incompleteStudents as $anum => $appID)
            {
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
            $application           = \Application::find($r->applicationID);
            $application->statusID = 2;
            $application->save();
        }

        $this->helper->needsToRepeat($jobID, $repeat);
        $this->helper->updateCount($jobID);
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
