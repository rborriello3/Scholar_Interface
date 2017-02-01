<?php

class MeetingController extends BaseController
{
    public function showCreateMeeting()
    {
	$data['participants'] = array(array('2', 'All Entering Student Committee Members'), array('4', 'All Graduating Student Committe Members'), array('6', 'All Returning Student Committee Members'));

	$participants = User::orderBy(DB::raw('substring_index(name, " ", -1)'), 'asc')
	    ->where(DB::raw('substr(yearTo, 2)'), '>=', date('m'))
	    ->where(DB::raw('substr(yearTo, -4)'), '>=', date('Y'))	
	    ->where('userRole', 'LIKE', '%4%')
	    ->get();

	foreach($participants as $part)
	{
	    array_push($data['participants'], array($part->userId, $part->name));
	}
	return View::make('Content.Admin.Meeting.showCreateMeeting', $data);
    }	

    public function doCreateMeeting()
    {
	$rules = array(
	    'name'		=>	'alpha_space_dash_num',
	    'date'		=>	'date_format:m/d/Y',
	    'time'		=>	'date_format:g:i A',
	    'place'		=>	'alpha_space_dash_num',
	    'participants'	=>	'array_num'
	);

	$v = Validator::make(Input::all(), $rules);

	if($v->passes())
	{
	    $data = Input::all();
	 
	    //Append name of user who created the meeting to list of participants as long as that user is not Cherie (22) or John (21)
	    $currentUser = User::find(Auth::user()->userId);
	    $currentId = $currentUser->userId;
	    if(!in_array($currentId, array(21, 22)))
	    {
	        array_push($data['participants'], $currentId);
	    }

	    //Append Cherie (22) and John (21) as participants to every meeting
	    array_push($data['participants'], '21', '22');

	    //Format meeting date in YYYY/MM/DD
	    $data['date'] = date('Y/m/d', strtotime($data['date']));

	    $meetingAttempt = new Meeting();
	    $meetingConfirmed = $meetingAttempt->createMeeting($data, FALSE);

	    if($meetingConfirmed)
	    {
		
		return Redirect::route('showDashboard')->with('success', 'Successfully scheduled ' . $meetingConfirmed . ' meeting(s).');
	    }
	    else
	    {
		return Redirect::route('showDashboard')->with('error', 'Meeting could not be scheduled due to a processing error');
	    }
	} 
	
	return Redirect::route('showDashboard')->withErrors($v->messages())->withInput()->with('error', 'Meeting could not be scheduled due to invalid characters in the text fields');
    }

    public function deactivateMeeting($meetingID)
    {
	$meeting = new Meeting();
	$meeting->deactivate($meetingID);

	return Redirect::route('showDashboard')->with('success', 'Meeting Deactivated');
    }

    public function activateMeeting($meetingID)
    {
	$meeting = new Meeting();
	$meeting->activate($meetingID);

	return Redirect::route('showDashboard')->with('success', 'Meeting Activated');
    }  		

    public function showEditMeeting($meetingId)
    {
	$data = DB::table('meeting')->where('meetingID', '=', $meetingId)->get();
	/*$data['meetingID'] = $meetingId;
	$data['name'] = $meetingInfo->name;
	$data['date'] = $meetingInfo->date;
	$data['time'] = $meetingInfo->time;
	$data['place'] = $meetingInfo->place;
	$data['meetingParticipants'] = $meetingInfo->participants;*/

	return View::make('Content.Admin.Meeting.showEditMeeting', $data);
    }

    public function doEditMeeting()
    {
	$rules = array(
	    'name'		=>	'alpha_space_dash_num',
	    'date'		=>	'date_format:m/d/Y',
	    'time'		=>	'date_format:g:i A',
	    'place'		=>	'alpha_space_dash_num',
	    'participants'	=>	'array_num'
	);

	$v = Validator::make(Input::all(), $rules);

	if($v->passes())
	{
	    $data = Input::all();
	 
	    /*//Append name of user who created the meeting to list of participants as long as that user is not Cherie (22) or John (21)
	    $currentUser = User::find(Auth::user()->userId);
	    $currentId = $currentUser->userId;
	    if(!in_array($currentId, array(21, 22)))
	    {
	        array_push($data['participants'], $currentId);
	    }

	    //Append Cherie (22) and John (21) as participants to every meeting
	    array_push($data['participants'], '21', '22');*/

	    //Format meeting date in YYYY/MM/DD
	    $data['date'] = date('Y/m/d', strtotime($data['date']));

	    $meetingAttempt = new Meeting();
	    $meetingConfirmed = $meetingAttempt->editMeeting($data['meetingID'], $data, FALSE);

	    if($meetingConfirmed)
	    {
		
		return Redirect::route('showDashboard')->with('success', 'Successfully scheduled ' . $meetingConfirmed . ' meeting(s).');
	    }
	    else
	    {
		return Redirect::route('showDashboard')->with('error', 'Meeting could not be scheduled due to a processing error');
	    }
	} 
	
	return Redirect::route('showDashboard')->withErrors($v->messages())->withInput()->with('error', 'Meeting could not be scheduled due to invalid characters in the text fields');
    }

}   
