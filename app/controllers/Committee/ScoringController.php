<?php

class ScoringController extends BaseController
{

    public function showApplications()
    {
        $application               = new Application();
        $data['applicationCounts'] = $application->committeeScoringCounts();
        Session::forget('group');

        return View::make('Content.Committee.Scoring.showApplications', $data);
    }

    public function showGrading($guid)
    {
        $application            = new Application($guid);
        $data['scoringInfo']    = $application->retrieveScoringInfo();
        $data['grades']         = array('' => 'Score (Higher = Better)', '1' => '1', '2' => '2', '3' => '3', '4' => '4', '5' => '5');
        $data['guid']           = $guid;
        $assessment             = new ApplicationAssessment();
        $data['insertedValues'] = $assessment->getValues($guid, Auth::user()->userId);

        return View::make('Content.Committee.Scoring.specificGrade.' . $data['scoringInfo']['typeID'], $data);
    }


/*	public function showNextGrading($received)
	{
		$guid 			= Datatable::query(DB::table('applications')
			->join('student', 'student.studentID', '=', 'applications.studentID')
			->join('applicationType', 'applicationType.typeID', '=', 'applications.typeID')
			->leftjoin('applicationAssessment', 'applicationAssessment.applicationID', '=', 'applications.applicationID')
			->select('GUID')
			->whereRaw('statusID IN (3, 5)')
			->whereRaw('applicationType.typeID NOT IN (1, 3, 5, 7, 8)')
			->where('applicationAssessment.userID', '=', Auth::user()->userId)
			->where('applicationAssessment.status', '!=', 'Deactivated')
			->where('applications.aidyear', '=', Session::get('currentAidyear'))
			->where('received', '<', $received)
			->orderBy('received', 'desc')
			->limit('1'));

		$application		= new Application($guid);
		$data['scoringInfo']	= $application->retrieveScoringInfo();
		$data['grades']		= array('' => 'Score (Higher = Better)', '1' => '1', '2' => '2', '3' => '3', '4' => '4', '5' => '5');
		$data['guid']		= $guid;
		$assessment		= new ApplicationAssessment();
		$data['insertedValues']	= $assessment->getValues($guid, Auth::user()->userId);

		return View::make('Content.Committee.Scoring.specificGrade.' . $data['scoringInfo']['typeID'], $data);
	}
*/
    public function processGrade($guid, $page = NULL)
    {
        $rules = array('faculty' => 'Required_if:action,Finish Assessment|numeric|max:5', 'essay' => 'Required_if:action,Finish Assessment|numeric|max:5', 'extra' => 'Required_if:action,Finish Assessment|numeric|max:5', 'assessorNotes' => 'essay');

        $v = Validator::make(Input::all(), $rules);

        if ($v->passes())
        {
            $assessment = new ApplicationAssessment();
            $result     = $assessment->updateAssessment(Input::all(), $guid);
		$application = new Application($guid);

            if ($result == 'Complete')
            {
                if ($page == '')
                {
                    return Redirect::route('showCommitteeApps')->with('success', 'Application Complete - you may edit below');
                }

                else
                {
                    $assessment = new ApplicationAssessment();
                    $grading    = $assessment->getPaginatedValue(Session::get('group'));

                    if (!$grading)
                    {
                        return Redirect::route('showCommitteeApps')->with('error', 'No ungraded applications exist in that group');
                    }

                    return Redirect::route('showPaginateGrade', array($grading, 0));
                }
            }

            else
            {
                if ($page == '')
                {
                    return Redirect::route('showCommitteeApps')->with('success', 'Application Saved - You may complete it below');
                }

                else
                {
                    $assessment = new ApplicationAssessment();
                    $grading    = $assessment->getPaginatedValue(Session::get('group'), $page + 1);

                    if (!$grading)
                    {
                        return Redirect::route('showCommitteeApps')->with('error', 'There are still some applications left to grade.');
                    }

                    return Redirect::route('showPaginateGrade', array($grading, $page + 1));
                }
            }
        }

        if (!$page)
        {
            return Redirect::route('showGrading', $guid)->withInput()->withErrors($v->messages())->with('error', 'All scores are required to finish the assessment');
        }

        else
        {
            return Redirect::route('showPaginateGrade', $guid)->withInput()->withErrors($v->messages())->with('error', 'All scores are required to finish the assessment');
        }
    }

    public function doPaginateRequest()
    {
        $rules = array('massGradeType' => 'Required|numeric');
        $v     = Validator::make(Input::all(), $rules);

        if ($v->passes())
        {
            $assessment = new ApplicationAssessment();
            $grading    = $assessment->getPaginatedValue(Input::get('massGradeType'));

            if (!$grading)
            {
                return Redirect::route('showCommitteeApps')->with('error', 'No ungraded applications exist in that group');
            }

            Session::put('group', Input::get('massGradeType'));

            return Redirect::route('showPaginateGrade', array($grading, 0));

        }

        return Redirect::route('showCommitteeApps')->withErrors($v->messages());
    }

    public function showPaginatedGrading($guid, $page)
    {
        $application               = new Application($guid);
        $data['scoringInfo']       = $application->retrieveScoringInfo();
        $data['applicationCounts'] = $application->committeeScoringCounts();
        $data['grades']            = array('' => 'Score (Higher = Better)', '1' => '1', '2' => '2', '3' => '3', '4' => '4', '5' => '5');
        $data['guid']              = $guid;
        $assessment                = new ApplicationAssessment();
        $data['insertedValues']    = $assessment->getValues($guid, Auth::user()->userId);
        $data['page']              = $page;

        return View::make('Content.Committee.Scoring.Paginated.' . $data['scoringInfo']['typeID'], $data);
    }

}
