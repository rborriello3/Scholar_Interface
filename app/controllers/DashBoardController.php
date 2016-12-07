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

    /*public function showMeetings()
    {
	$currentTime = strtotime(time());
	$values = Meeting::where('strtotime', '>=', $currentTime);
	if(Session::get('role') == 3)
		$values = Meeting::where('strtotime', '>=', $currentTime);
	else
	{
		$commMember = User::where('userId', '=', Session::get('userId'));
		$values = Meeting::where('strtotime', '>=', $currentTime)->whereIn($commMember->gradeGroup, implode('', $values['gradeGroup']));
	}
	
	return View::make('Content.Global.Dashboard.dashboard' . Session::get('role'), $values);
    }*/
}
