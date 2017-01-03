<?php

class MeetingController extends BaseController
{
    public function showCreateMeeting()
    {
        $data['participants'] = array('' => 'Choose Meeting Participant(s)');
	$data['gradeGroup'] = array('2' => 'Entering', '4' => 'Graduating', '6' => 'Returning');
	$data['participants'] = User::where(function($query)
	    {
	        $today = date('m/Y');
		$query->whereDate('yearTo', '>=', $today);
		return $query;
	    });
	//$data['participants'] = array_merge($data['participants'], $participants);
	return View::make('Content.Admin.Meeting.showCreateMeeting', $data);
    }	

    public function doCreateMeeting()
    {
	$rules = array(
	    'name'		=>	'array_text',
	    'date'		=>	'array_date',
	    'time'		=>	'array_time',
	    'place'		=>	'array_text',
	    'participant'	=>	'array_text'
	);
	$v = Validator::make(Input::all(), $rules);

	if($v->passes())
	{
	    $meetingAttempt = new Meeting();
	    $meetingConfirmed = $meetingAttempt->createMeeting(Input::al(), FALSE);

	    if($meetingConfirmed[0])
	    {
		return View::make('Content.Global.Dashboard.dashboard3.blade.php')->with('success', 'Successfully scheduled ' . $meetingConfirmed[1] . ' meeting(s).');
	    }
	    else
	    {
		return View::make('Content.Global.Dashboard.dashboard3.blade.php')->with('error', 'Meeting(s) could not be scheduled due to a processing error');
	    }
	} 
	
	return View::make('Content.Global.Dashboard.dashboard3.blade.php')->withErrors($v->messages())->withInput()->with('error', 'Meeting(s) could not be saved due to invalid characters in text fields');
    }  		
}   
