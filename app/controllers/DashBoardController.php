<?php

class DashboardController extends BaseController
{
    public function showDashboard()
    {
        if (! Session::has('aidyears'))
        {
            $aidyear = new Aidyear();
            $aidyears = $aidyear->getAll(false);

            if (! Session::has('aidYearChange'))
            {
                $current = $aidyear->getCurrentAidyear();
            }
            else
            {
                $current = Session::get('currentAidyear');
            }

            Session::put('aidyears', $aidyears);
            Session::put('currentAidyear', $current);
        }

	if(Session::get('role') == '4')
	{
	    $userId = User::find(Auth::user()->userId);
	    $gradeGroup = User::find(Auth::user()->gradeGroup);
	    $data['userId'] = $userId;
	    $data['name'] = User::find(Auth::user()->name);
	    $data['countTotal'] = DB::table('applications')
            ->join('student', 'student.studentID', '=', 'applications.studentID')
            ->join('applicationType', 'applicationType.typeID', '=', 'applications.typeID')
            ->leftjoin('applicationAssessment', 'applicationAssessment.applicationID', '=', 'applications.applicationID')
            ->select('GUID', 'firstName', 'lastName', 'student.studentID', 'applications.aidyear', 'typeName', 'applications.received', 'applicationAssessment.status')
            ->whereRaw('statusID IN (3,5)')
            ->whereRaw('applicationType.typeID NOT IN (1,3,5,7,8)')
            ->where('applicationAssessment.userID', '=', Auth::user()->userId)
            ->where('applicationAssessment.status', '!=', 'Deactivated')
            ->where('applications.aidyear', '=', Session::get('currentAidyear'))
	    ->count();

	    $data['countGraded'] = DB::table('applications')
            ->join('student', 'student.studentID', '=', 'applications.studentID')
            ->join('applicationType', 'applicationType.typeID', '=', 'applications.typeID')
            ->leftjoin('applicationAssessment', 'applicationAssessment.applicationID', '=', 'applications.applicationID')
            ->select('GUID', 'firstName', 'lastName', 'student.studentID', 'applications.aidyear', 'typeName', 'applications.received', 'applicationAssessment.status')
            ->whereRaw('statusID IN (3,5)')
            ->whereRaw('applicationType.typeID NOT IN (1,3,5,7,8)')
            ->where('applicationAssessment.userID', '=', Auth::user()->userId)
            ->where('applicationAssessment.status', '=', 'Graded')
            ->where('applications.aidyear', '=', Session::get('currentAidyear'))
	    ->count();


	    return View::make('Content.Global.Dashboard.dashboard' . Session::get('role'), $data);
	}
	else
	    return View::make('Content.Global.Dashboard.dashboard' . Session::get('role')); // View based off of session
    }

    public function doAidYearSelect()
    {
        $rules = array('globalAidYear' => 'Required|digits:4');

        $v = Validator::make(Input::all(), $rules);

        if ($v->passes())
        {
            Session::put('aidYearChange', true);
            Session::put('currentAidyear', Input::get('globalAidYear'));

            return Redirect::route('showDashboard')->with('success', 'Aidyear for this session has changed');
        }

        return Redirect::route('showDashboard')->with('error', 'You must select an aidyear');
    }
}
