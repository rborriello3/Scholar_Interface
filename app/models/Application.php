<?php

class Application extends Eloquent
{
    /**
     * The database table
     */
    protected $table = 'applications';

    /**
     * We don't want any default time stamps
     */
    public $timestamps = FALSE;

    /**
     * must define a specific key for our database table
     */
    protected $primaryKey = 'applicationID';

    /**
     * This will handle the creation of the applications type, aidyear, GUID, requirements, and recommendation
     */

    protected $key;
    protected $student;

    public function __construct($appKey = NULL, $student = NULL)
    {
        if ($appKey)
        {
            $this->key = $appKey;
        }

        if ($student)
        {
            $this->student = $student;
        }
    }

    public function Student()
    {
        return $this->hasOne('Student', 'studentID', 'studentID');
    }

    public function Type()
    {
        return $this->hasOne('ApplicationType', 'typeID', 'typeID');
    }

    public function ApplicationRecommendation()
    {
        return $this->hasOne('ApplicationRecommendation', 'applicationID', 'applicationID');
    }

    public function Aidyear()
    {
        return $this->hasOne('Aidyear', 'aidyear', 'aidyear');
    }

    public function getGlobalID()
    {
        $id = bin2hex(openssl_random_pseudo_bytes('25'));

        if (Request::segment(4) == '')
        {
            Session::put('GUID', $id);

            return $id;
        }

        return Request::segment(4);
    }

    /**
     * Updates the application with specific status like deactivated or activated.
     *
     * @param int $status
     */
    public function updateAppStatus($status)
    {
        $this->where('GUID', '=', $this->key)->update(array('statusID' => $status));
        if ($status == '7')
        {
            $updates['status'] = 'Deactivated';
        }
        elseif ($status == '3')
        {
            $updates['status'] = 'Waiting';
        }

        $id = $this->where('GUID', '=', $this->key)->get(array('applicationID'));

        ApplicationAssessment::where('applicationID', '=', $id[0]->applicationID)->update($updates);
    }

    public function newApp($values, $GUID)
    {
        $applicationCheck = $this->where('GUID', '=', $GUID)->get();

        if (count($applicationCheck) == '1')
        {
            $this->updateApplication($applicationCheck[0]->applicationID, $values);
        }

        else
        {
            $student = new Student();

            if ($student->addID($values['studentID']))
            {
                $address = new StudentAddress();
                $address->initializeAddress($values['studentID']);
                $this->statusID  = 6;
                $this->GUID      = $GUID;
                $this->aidyear   = $values['aidyear'];
                $this->studentID = $values['studentID'];
                $this->typeID    = $values['types'];
                $this->received  = date('m/d/y');

                if ($this->save())
                {
                    $this->checkFurtherNeeds($values, $this->applicationID);
                }
            }
        }
    }

    private function checkFurtherNeeds($values, $appID)
    {
        // Add Recommendations
        if ($values['types'] != '1' &&$values['types'] != '3' && $values['types'] != '5' && $values['types'] != '7' && $values['types'] != '8')
        {
            $req = new ApplicationRecommendation($appID, $values['studentID']);
            $req->newRecommendation();
        }

        // Add Thank You and Acceptance
/*        if ($values['types'] != '4' && $values['types'] != '5')
        {
            $resp = new ApplicationResponse($appID, $values['studentID']);
            $resp->newResponse();
        }*/
    }

    private function updateApplication($appID, $values)
    {
        $studentInfo = $this->find($appID);
        $save        = FALSE;

        if ($studentInfo->typeID != $values['types'])
        {
            $studentInfo->typeID = $values['types'];
            $save                = TRUE;
        }

        if ($studentInfo->aidyear != $values['aidyear'])
        {
            $studentInfo->aidyear = $values['aidyear'];
            $save                 = TRUE;
        }

        if ($save)
        {
            $studentInfo->save();
        }
    }

    public function updateEssays($values)
    {
        $appID                = $this->where('GUID', '=', $this->key)->get(array('applicationID'));
        $app                  = $this->find($appID[0]->applicationID);
        $app->essay           = $values['essay'];
        $app->extraCurricular = $values['extraCurricular'];
	//*******added 2/13/16 @ 1338
	$app->desiredScholarships = $values['desiredScholarships'];
	//*******
	$app->essaySelf	      = $values['essaySelf'];
	$app->essayWhy        = $values['essayWhy'];
        $app->save();
    }

    public function submitApplication($recomm)
    {
        $appID        = $this->where('GUID', '=', $this->key)->get(array('applicationID', 'studentID'));
        $app          = $this->find($appID[0]->applicationID);
        $student      = Student::find($app->studentID);
        $emailAddress = $student->personalEmail;
        $email        = new Email();

        if ($student->cellnotifications == '1')
        {
            $number  = $student->cellPhone;
            $carrier = $student->cellCarrier;
            $SMS     = new Text($loggedIn = '');
            $cell    = TRUE;
        }

        if ($recomm == 2 && $app['typeID'] != '8' && $app['typeID'] != '1' && $app['typeID'] != '3' && $app['typeID'] != '5' && $app['typeID'] != '7')
        {
            $app->statusID = 3;
            $return        = 'Application submitted for review';
            $assessments   = new ApplicationAssessment();
            $assessments->initialize($appID[0]->applicationID, Session::get('gradeGroup'));
            $email = new Email();
            $email->completedApplication($emailAddress, $student->firstName, $student->lastName, FALSE);

            if (isset($cell))
            {
                $SMS->applicationNotifcation($number, $carrier, FALSE);
            }
        }

        elseif ($app['typeID'] == '8' || $app['typeID'] == '1' || $app['typeID'] == '3' || $app['typeID'] == '5' || $app['typeID'] == '7')
        {
            $app->statusID = 5;
            $return        = 'Student information successfully inputted - Application not submitted however due to application type.';
        }
        else
        {
            $app->statusID = 2;
            $return        = 'Application submitted with a incomplete status';
            $email->incompleteApplication($emailAddress, $student->firstName, $student->lastName, $app->applicationID);

            if (isset($cell))
            {
                $SMS->applicationNotifcation($number, $carrier, FALSE);
            }
        }

        $app->save();

        Session::forget('GUID');
        Session::forget('studentID');
        Session::forget('recommendations');

        return $return;
    }

    public function retrieveScoringInfo()
    {
        $info           = $this->where('GUID', '=', $this->key)->get(array('applicationID'));
        $application    = $this->find($info[0]->applicationID);
        $education      = $application->Student->StudentDemo;
        $studentInfo    = $application->Student;
        $recommendation = $application->ApplicationRecommendation;
        $address        = $application->Student->StudentAddress;

        $data                    = array();
        $data['studentName']     = $studentInfo->firstName . ' ' . $studentInfo->lastName;
        $data['studentID']       = $studentInfo->studentID;
        $data['county']          = $address->county;
        $data['city']            = $address->city;
        $data['type']            = $application->Type->typeName;
        $data['typeID']          = $application->Type->typeID;
        $data['education']       = $education;
        $data['essay']           = $application->essay;
        $data['extraCurricular'] = $application->extraCurricular;
	$data['essaySelf']       = $application->essaySelf;
	$data['essayWhy']        = $application->essayWhy;
        $data['recommendations'] = $recommendation;
        $data['criteria']        = $this->criteriaDescription($studentInfo->criteria);
        $data['minority']        = $this->minorityDescription($studentInfo->minority);

        return $data;
    }

    private function criteriaDescription($critera)
    {
        $descriptions   = '';
        $array_criteria = explode(',', $critera);
        $dbDesc         = DB::table('applicationCriteria')->lists('description', 'criteriaID');

        foreach ($array_criteria as $v)
        {
            $descriptions .= $dbDesc[$v] . ' - ';
        }

        return rtrim($descriptions, ' - ');
    }


    private function minorityDescription($minority)
    {
        $descriptions   = '';
        $array_minority = explode(',', $minority);
        $dbDesc         = DB::table('minority')->lists('description', 'minorityID');

        foreach ($array_minority as $v)
        {
            $descriptions .= $dbDesc[$v] . ' - ';
        }

        return rtrim($descriptions, ' - ');
    }

    public function applicationHistory()
    {
	$return = DB::table('applications')->join('student', 'student.studentID', '=', 'applications.studentID')->leftjoin('applicationRecommendations', 'applicationRecommendations.applicationID', '=', 'applications.applicationID')->join('applicationType', 'applicationType.typeID', '=', 'applications.typeID')->join('applicationStatus', 'applicationStatus.statusID', '=', 'applications.statusID')->select('GUID', 'applications.studentID', 'firstName', 'lastName', 'applications.received', 'applicationStatus.statusName', 'applicationType.typeName', 'extraCurricular', 'essay','essaySelf', 'essayWhy', 'aidyear', 'recommender1', 'email1', 'department1', 'courseName1', 'academicPotential1', 'character1', 'emotionalMaturity1', 'overallRank1', 'comments1', 'recommender2', 'email2', 'department2', 'courseName2', 'academicPotential2', 'character2', 'emotionalMaturity2', 'overallRank2', 'comments2')->where('student.studentID', '=', $this->student)->groupBy('applications.applicationID')->orderBy('applications.received', 'desc')->get();

        return $return;
    }

    public function committeeScoringCounts()
    {
        $data['all'] = $this->whereRaw('statusID IN (3,5)')->whereRaw('typeID NOT IN (1,3,5,7,8)')->where('aidyear', '=', Session::get('currentAidyear'))->count();
        $data['entering'] = $this->whereRaw('statusID IN (3,5)')->where('typeID', '=', 2)->where('aidyear', '=', Session::get('currentAidyear'))->count();
        $data['graduating'] = $this->whereRaw('statusID IN (3,5)')->where('typeID', '=', 4)->where('aidyear', '=', Session::get('currentAidyear'))->count();
        $data['returning'] = $this->whereRaw('statusID IN (3,5)')->where('typeID', '=', 6)->where('aidyear', '=', Session::get('currentAidyear'))->count();
        $data['allGraded'] = $this->whereRaw('statusID IN (5)')->whereRaw('typeID NOT IN (1,3,5,7,8)')->where('aidyear', '=', Session::get('currentAidyear'))->count();
        $data['enteringGraded'] = $this->whereRaw('statusID IN (5)')->where('typeID', '=', 2)->where('aidyear', '=', Session::get('currentAidyear'))->count();
        $data['graduatingGraded'] = $this->whereRaw('statusID IN (5)')->where('typeID', '=', 4)->where('aidyear', '=', Session::get('currentAidyear'))->count();
        $data['returningGraded'] = $this->whereRaw('statusID IN (5)')->where('typeID', '=', 6)->where('aidyear', '=', Session::get('currentAidyear'))->count();

        return $data;
    }
}
