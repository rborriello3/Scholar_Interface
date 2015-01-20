<?php

class ApplicationController extends BaseController
{
    /**
     * Shows user the applications table with applications for the current active aidyear
     *
     * @return Response
     */
    public function showApplications()
    {
        return View::make('Content.Admin.Applications.applicationHome');
    }

    /**
     * Shows user the type selection, aidyear (soon to be removed, applications will only be for current
     * active aidyear) and enter in StudentID
     *
     * @return Response
     */
    public function showType()
    {
        $allTypes        = new ApplicationType();
        $allAidYears     = new Aidyear();
        $data['types']   = array_add($allTypes->getAll(TRUE), '', 'Choose Type');
        $data['aidyear'] = array_add($allAidYears->getAll(TRUE), '', 'Choose Aidyear');

        return View::make('Content.Admin.Applications.appType', $data);
    }

    /**
     * Processess the application type, create a GUID for the application to be identified by
     * Instantitate a new application for, which will in turn create the address records, and than
     * will create further needs such as recommendations (if type is 2,4,6) and responses
     * (if type is 2,3,7,8)
     *
     * @return Response
     */
    public function doType()
    {
        $rules = array(
            'types'     => 'Required|integer|digits:1', 'aidyear' => 'Required|integer|digits:4',
            'studentID' => 'Required|studentid'
        );

        $v = Validator::make(Input::all(), $rules);

        if ($v->passes())
        {
            $application = new Application();
            $guid        = $application->getGlobalID();
            $application->newApp(Input::all(), $guid);

            Session::put('typeComplete', 1);
            Session::put('studentID', Input::get('studentID'));
            Session::put('gradeGroup', Input::get('types'));

            return Redirect::route('showStudentDemo', array($guid));
        }

        return Redirect::route('showType')->withInput()->withErrors($v->messages());
    }

    /**
     * Shows admin the students information view, which consits of things like email, phone and address info
     * Can be pre-populated or empty (except for A-Number)
     *
     * @param  string $guid The URL parameter from the DB to distinguish a specific application
     *
     * @return Response
     */
    public function showStudentDemographics($guid)
    {
        $data['student']     = Student::find(Session::get('studentID'));
        $data['address']     = StudentAddress::find(Session::get('studentID'));
        $data['appKey']      = $guid;
        $studentID           = Application::where('GUID', '=', $guid)->get(array('studentID'));
        $data['state']       = array_add(DB::table('states')->lists('stateFull', 'state'), '', 'Select State');
        $data['carriers']    = array_add(DB::table('cellCarriers')->lists('carrier', 'carrierId'), '', 'Select Carrier');
        $data['criteria']    = DB::table('applicationCriteria')->lists('description', 'criteriaID');
        $data['minority']    = DB::table('minority')->lists('description', 'minorityID');
        $data['studentInfo'] = array();

        if (count($studentID) == 1)
        {
            $data['studentInfo'] = Student::find($studentID);
        }

        return View::make('Content.Admin.Applications.studentDemo', $data);
    }

    /**
     * Inputs the student demographics into the studentDemographics table
     *
     * @param  string $guid The URL parameter from the DB to distinguish a specific application
     *
     * @return Response
     */
    public function doStudentDemographics($guid)
    {
        $rules = array(
            'firstName'   => 'Required|full_name', 'lastName' => 'Required|full_name',
            'studentID'   => 'Required|studentid', 'personalEmail' => 'Required|email|max:80', 'homephone' => 'phone',
            'cellCarrier' => 'Required|over:0,cellPhone|numeric', 'cellPhone' => 'phone',
            'address'     => 'Required|address', 'city' => 'Required|alpha_space_dash',
            'state'       => 'Required|alpha_space_dash', 'zipCode' => 'Required|numeric|digits:5',
            'county'      => 'Required|alpha_space_dash', 'goal' => 'text', 'criteria' => 'Required|array_num',
            'minority'    => 'Required|array_num'
        );

        $v = Validator::make(Input::all(), $rules);

        if ($v->passes())
        {
            if ($guid === Session::get('GUID'))
            {
                $student = new Student();

                if ($student->upDateStudent(Input::all()))
                {
                    Session::put('studentID', Input::get('studentID'));
                    Session::put('studentComplete', 1);

                    return Redirect::route('showSchoolInfo', array($guid));
                }

                return Redirect::route('showStudentDemo', array($guid))->withInput()->withErrors($v->messages())->with('error', 'Student ID does not match previously entered Student ID. If this persists please "Cancel Application"');
            }

            return Redirect::route('endApplication', array($guid));
        }

        return Redirect::route('showStudentDemo', array($guid))->withInput()->withErrors($v->messages())->with('error', 'Errors Detected In Form Submission');
    }

    /**
     * Based on the scholarship type a specific school infornformation view will be displayed.
     * Below the logic is very strait forward for which view will be displayed. In some cases a view will not be displayed
     * because they do not fall into the "right" group, so their applications will just be sent to the complete page.
     *
     * @param  string $guid The URL parameter from the DB to distinguish a specific application
     *
     * @return Response
     */
    public function showSchoolInformation($guid)
    {
        $data['appKey'] = $guid;
        $type           = Application::where('GUID', '=', $data['appKey'])->get(array('typeID'));
        $data['type']   = $type[0]->typeID;
        $data['edu']    = StudentDemo::find(Session::get('studentID'));

        if ($data['type'] == '2' || $data['type'] == '3')
        {
            return View::make('Content.Admin.Applications.SchoolInfo.freshmen', $data);
        }

        elseif ($data['type'] == '4' || $data['type'] == '5')
        {
            return View::make('Content.Admin.Applications.SchoolInfo.graduating', $data);
        }

        elseif ($data['type'] == '6' || $data['type'] == '7')
        {
            return View::make('Content.Admin.Applications.SchoolInfo.returning', $data);
        }

        elseif ($data['type'] == '1')
        {
            return View::make('Content.Admin.Applications.SchoolInfo.athletes', $data);
        }

        else
        {
            return Redirect::route('showCompleteApp', array($guid));
        }
    }

    /**
     * Adds the educational information to the studentDemographics table finishing off the requirements
     * for that table
     *
     * @param  string $guid The URL parameter from the DB to distinguish a specific application
     *
     * @return Response
     */
    public function doSchoolInformation($guid)
    {
        $input = Input::all();
        // Used to display the human readable dates with in the view nothing else
        Session::put('high', isset($input['high']) ? $input['high'] : NULL);
        Session::put('coll', isset($input['coll']) ? $input['coll'] : NULL);
        unset($input['high']);
        unset($input['coll']);

        $rules = array(
            'type'           => 'numeric|digits:1', 'selecttype' => 'numeric|digits:1', // Freshmen \\
            'highSchoolName' => 'Required_if:type,2|Required_if:selecttype,2|alpha_space_dash',
            'highSchoolAvg'  => 'Required_if:type,2|Required_if:selecttype,2|decimal',
            'highGrad'       => 'Required_if:type,2|Required_if:selecttype,2|date_format:m/y',
            // Graduating + Returning \\
            'major'          => 'Required_if:type,4|Required_if:type,6|Required_if:selecttype,6|majors',
            'creditsEarned'  => 'Required_if:type,4|Required_if:type,6|Required_if:selecttype,6|decimal',
            'GPA'            => 'Required_if:type,4|Required_if:type,6|Required_if:selecttype,6|gpa',
            'collegeGrad'    => 'Required_if:type,4|date_format:m/y', 'transferMaj' => 'alpha_space_dash',
            'transferInsti'  => 'text',
        );

        $v = Validator::make($input, $rules);

        if ($v->passes())
        {
            Session::put('selecttype', Input::get('selecttype'));
            $demo = new StudentDemo();
            $demo->insertDemographics($input);
            Session::put('educationComplete', 1);

            return Redirect::route('showEssays', array($guid));
        }

        return Redirect::route('showSchoolInfo', array($guid))->withInput()->withErrors($v->messages())->with('error', 'Errors Detected In Form Submission');
    }

    /**
     * Shows the admin the essays interface. Can be pre - populated.
     *
     * @param  string $guid The URL parameter from the DB to distinguish a specific application
     *
     * @return Response
     */
    public function showEssays($guid)
    {
        $data['appKey']          = $guid;
        $app                     = Application::where('GUID', '=', $data['appKey'])->get(array(
            'typeID', 'essay', 'extraCurricular'
        ));
        $data['type']            = $app[0]->typeID;
        $data['essay']           = $app[0]->essay;
        $data['extraCurricular'] = $app[0]->extraCurricular;


        if ($data['type'] == '2' || $data['type'] == '4' || $data['type'] == '6')
        {
            return View::make('Content.Admin.Applications.essays', $data);
        }

        else
        {
            return Redirect::route('showCompleteApp', array($guid));
        }
    }

    /**
     * Processes the essay
     *
     * @param  string $guid The URL parameter from the DB to distinguish a specific application
     *
     * @return Response
     */
    public function doEssays($guid)
    {
        $rules = array('essay' => 'Required|essay|words:1', 'extraCurricular' => 'Required|essay');

        $v = Validator::make(Input::all(), $rules);

        if ($v->passes())
        {
            $app = new Application($guid);
            $app->updateEssays(Input::all());
            Session::put('requirementsComplete', 1);

            return Redirect::route('showRecomms', array($guid));
        }

        return Redirect::route('showEssays', array($guid))->withInput()->withErrors($v->messages())->with('error', 'Errors Detected In Form Submission');
    }

    /**
     * show the recommendations, this can be either empty or have 1 or 2 recommendations sent to the view.
     *
     * @param  string $guid The URL parameter from the DB to distinguish a specific application
     *
     * @return Response
     */
    public function showRecomms($guid)
    {
        $app                    = Application::where('GUID', '=', $guid)->get(array('applicationID'));
        $data['recommendation'] = ApplicationRecommendation::find($app[0]->applicationID);
        $data['appKey']         = $guid;

        return View::make('Content.Admin.Applications.recommendations', $data);
    }

    /**
     * Updates the recommendations with the input the user supplied
     *
     * @param  string $guid The URL parameter from the DB to distinguish a specific application
     *
     * @return Response
     */
    public function doRecomms($guid)
    {
        $rules = array(
            'recomms'            => 'Required|numeric',
            'recommender1'       => 'Required_if:recomms,1|Required_if:recomms,2|full_name|max:101',
            'email1'             => 'Required_if:recomms,1|Required_if:recomms,2|email',
            'department1'        => 'Required_if:recomms,1|Required_if:recomms,2|text',
            'courseName1'        => 'Required_if:recomms,1|Required_if:recomms,2|text',
            'academicPotential1' => 'Required_if:recomms,1|Required_if:recomms,2|numeric',
            'character1'         => 'Required_if:recomms,1|Required_if:recomms,2|numeric',
            'emotionalMaturity1' => 'Required_if:recomms,1|Required_if:recomms,2|numeric',
            'overallRank1'       => 'Required_if:recomms,1|Required_if:recomms,2|rank', 'comments1' => 'essay',
            'recommender2'       => 'Required_if:recomms,2|full_name|max:101',
            'email2'             => 'Required_if:recomms,2|email', 'department2' => 'Required_if:recomms,2|text',
            'courseName2'        => 'Required_if:recomms,2|text',
            'academicPotential2' => 'Required_if:recomms,2|numeric', 'character2' => 'Required_if:recomms,2|numeric',
            'emotionalMaturity2' => 'Required_if:recomms,2|numeric', 'overallRank2' => 'Required_if:recomms,2|rank',
            'comments2'          => 'essay'
        );

        $v = Validator::make(Input::all(), $rules);

        if ($v->passes())
        {
            $recommendations = new ApplicationRecommendation();
            $recommendations->updateRecommendation(Input::all(), $guid);
            Session::put('recommendationComplete', 1);

            return Redirect::route('showCompleteApp', array($guid))->with('message', 'Please confirm application information below!');
        }

        return Redirect::route('showRecomms', array($guid))->withInput()->withErrors($v->messages())->with('error', 'Errors Detected In Form Submission');
    }

    /**
     * Updates
     *
     * @param  [type] $guid [description]
     *
     * @return [type]       [description]
     */
    public function showComplete($guid)
    {
        //TODO: Get all the data from a specific part of the application and show it off.
        // Get all the data :D
        $data['appKey'] = $guid;

        return View::make('Content.Admin.Applications.complete', $data);
    }

    public function doComplete($guid)
    {
        $application = new Application($guid);
        Session::forget('typeComplete');
        Session::forget('studentComplete');
        Session::forget('educationComplete');
        Session::forget('requirementsComplete');
        Session::forget('recommendationComplete');
        Session::forget('studentID');
        Session::forget('high');
        Session::forget('coll');
        Session::forget('selecttype');

        return Redirect::route('showApplications')->with('message', $application->submitApplication(Session::get('recommendations')));
    }

    public function endApplication($guid)
    {
        $studentID = Application::where('GUID', '=', $guid)->get(array('studentID'));
        Session::forget('typeComplete');
        Session::forget('studentComplete');
        Session::forget('educationComplete');
        Session::forget('requirementsComplete');
        Session::forget('recommendationComplete');
        Session::forget('studentID');
        Session::forget('high');
        Session::forget('coll');
        Session::forget('selecttype');
        Session::forget('gradeGroup');

        // Deletes from the GUID from URL
        if (count($studentID) == 1)
        {
            Application::where('GUID', '=', $guid)->delete();
            Session::forget('GUID');

            return Redirect::route('showApplications')->with('message', 'Application not saved');
        }

        // If URL GUID is tampered it will check the Session stored GUID.
        elseif (count($studentID = Application::where('GUID', '=', Session::get('GUID'))->get(array('studentID'))) == 1)
        {
            Application::where('GUID', '=', Session::get('GUID'))->delete();
            Session::forget('GUID');

            return Redirect::route('showApplications')->with('message', 'Application not saved');
        }

        // the session stored GUID is tampered with it will remove the GUID from the session and return you to the base view
        else
        {
            Session::forget('GUID');

            return Redirect::route('showApplications')->with('message', 'No record found with that GUID');
        }
    }

    public function deactivateApplication($guid)
    {
        $app = new Application($guid);
        $app->updateAppStatus($status = 7);

        return Redirect::route('showApplications')->with('success', 'Application Deactivated');
    }

    public function activateApplication($guid)
    {   
        $assessments = new ApplicationAssessment();
        $assessments->checkAllAssessments($guid);

        return Redirect::route('showApplications')->with('success', 'Application Activated');
    }

    public function showEditApp($guid)
    {
        $data['guid'] = $guid;

        return View::make('Content.Admin.Applications.editApplication', $data);
    }

    public function doEditApp($guid)
    {
        dd($guid);
    }

    public function showStudentApplications($studentID)
    {
        $app             = new Application(NULL, $studentID);
        $data['history'] = $app->applicationHistory();

        return View::make('Content.Admin.Applications.viewApplications', $data);
    }

    public function showFinishApplication($guid)
    {
        $id                  = Application::where('GUID', '=', $guid)->get(array('applicationID', 'typeID'));
        $data['recommender'] = Application::find($id[0]->applicationID)->ApplicationRecommendation;
        $data['type']        = $id[0]->typeID;
        $data['guid']        = $guid;

        return View::make('Content.Admin.Applications.finishApplication', $data);
    }

    public function doFinishApplication($guid)
    {
        $rules = array(
            'recommender1'       => 'Required_if:one,1|full_name|max:101', 'email1' => 'Required_if:one,1|email',
            'department1'        => 'Required_if:one,1|text', 'courseName1' => 'Required_if:one,1|text',
            'academicPotential1' => 'Required_if:one,1|numeric', 'character1' => 'Required_if:one,1|numeric',
            'emotionalMaturity1' => 'Required_if:one,1|numeric', 'overallRank1' => 'Required_if:one,1|rank',
            'comments1'          => 'essay', 'recommender2' => 'Required|max:101', 'email2' => 'Required|email',
            'department2'        => 'Required|text', 'courseName2' => 'Required|text',
            'academicPotential2' => 'Required|numeric', 'character2' => 'Required|numeric',
            'emotionalMaturity2' => 'Required|numeric', 'overallRank2' => 'Required|rank', 'comments2' => 'essay',
            'types'              => 'Required'
        );

        $v = Validator::make(Input::all(), $rules);

        if ($v->passes())
        {
            $recommendation = new ApplicationRecommendation();
            if ($recommendation->completeApplication($guid, Input::all()))
            {
                return Redirect::route('showApplications')->with('success', 'Scholarship application updated');
            }
        }

        return Redirect::route('showFinishApplication', array($guid))->withInput()->withErrors($v->messages())->with('error', 'Errors detected in form');
    }

    public function showViewGrades($guid)
    {
        dd('view Grades ' . $guid);
    }
}
