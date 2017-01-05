<?php

class MeetingController extends BaseController
{
    public function showCreateMeeting()
    {
	$data['participants'] = array('2' => 'All Entering Student Committee Members', '4' => 'All Graduating Student Committee Members', '6' => 'All Returning Student Committee Members');
	$today = date('m/Y');
	$participants = DB::table('user')
	    ->where(DB::raw('substr(yearTo, 2)'), '>=', date('m'))
	    ->where(DB::raw('substr(yearTo, -4)'), '>=', date('Y'))	
	    ->where('userRole', 'LIKE', '%4%')
	    ->orderBy(DB::raw('substring_index(name, " ", -1)'), 'asc')
	    ->get();

	foreach($participants as $k => $v)
	{
	    $participants[$k] = $v->name;
	}

	$data['participants'] = array_merge($data['participants'], $participants);
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
		return Redirect::route('showDashboard')->with('success', 'Successfully scheduled ' . $meetingConfirmed[1] . ' meeting(s).');
	    }
	    else
	    {
		return Redirect::route('showDashboard')->with('error', 'Meeting(s) could not be scheduled due to a processing error');
	    }
	} 
	
	return Redirect::route('showDashboard')->withErrors($v->messages())->withInput()->with('error', 'Meeting(s) could not be saved due to invalid characters in text fields');
    }  		
}   
