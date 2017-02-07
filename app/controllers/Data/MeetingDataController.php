<?php

class MeetingDataController extends BaseController
{
    public function showAllMeetingsJsonCRUD()
    {
	return Datatable::query(DB::table('meeting')
	    ->select('meetingID', 'name', 'date', 'time', 'place', 'participants', 'status')
	    ->where('date', '>=', date('Y/m/d', strtotime('today')))
	    ->orderBy('date', 'asc'))
	    ->addColumn('Meeting', function($meeting) 
	    {
		$crudLinks = '<div class="btn-group">';
		if($meeting->status == '0')
		{
		    $crudLinks .= '<button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown">' . 'Deactivated' . '<span class="glyphicon glyphicon-arrow-down"></span></button>';
		    $statusLinks = '<li>' . link_to_route('activateMeeting', 'Activate Meeting', $parameters = array($meeting->meetingID), $attributes = array('alt' => 'reactivateMeeting')) . '</li>';
		}
		else
		{
			$crudLinks .= '<button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">' . $meeting->name . '<span class="glyphicon glyphicon-arrow-down"></span></button>';
		    $statusLinks = '<li>' . link_to_route('deactivateMeeting', 'Deactivate Meeting', $parameters = array($meeting->meetingID), $attributes = array('alt' => 'cancelMeeting')) . '</li>';
	 	}

		$crudLinks .= '<ul class="dropdown-menu" role="menu">';
		
		$crudLinks .= '<li>' . link_to_route('showEditMeeting', 'Edit Meeting', $parameters = array($meeting->meetingID), $attributes = array('alt' => 'editMeeting')) . '</li>';
		//$crudLinks .= '<li>' . link_to_route('deleteMeeting', 'Delete Meeting', $parameters = array($meeting->meetingID), $attributes = array('alt' => 'eraseMeeting')) . '</li>';
		$crudLinks .= $statusLinks;

		$crudLinks .= '</ul>';
		$crudLinks .= '</div>';

		return $crudLinks;
	    })
	    //Date is stored in 'YYYY/MM/DD' format so it's easier to compare dates in queries
	    //This function will change the display format to 'MM/DD/YYYY'
	    ->addColumn('date', function($meeting)
	    {
		$meeting->date = date('m/d/Y', strtotime($meeting->date));
		return $meeting->date;
	    })
	    ->showColumns('time', 'place')
	    ->addColumn('participants', function($meeting)
	    {
		$participants = explode(',', $meeting->participants);
		foreach($participants as $k => $v)
		{
		    if($v == 2)
		    {
			$participants[$k] = "All Entering Student Committee Members";
		    }
		    else if ($v == 4)
		    {
			$participants[$k] = "All Graduating Student Committee Members";
		    }
		    else if($v == 6)
		    {
			$participants[$k] = "All Returning Student Committee Members";
		    }
		    else
		    {
			$v = User::where('userId', '=', $v)->whereNotIn('userId', array(0, 1, 2))->get();
			foreach($v as $participant => $name)
			{
			    $participants[$k] = $name->name;
			}
		    }
		}
		$participants = implode(', ', $participants);
		return $participants;
	    })
	    ->make();
    }

    public function showCommMemberMeetings()
    {	
	$userId = User::find(Auth::user()->userId); 
	$userRole = User::find(Auth::user()->userRole);
	return Datatable::query(DB::table('meeting')
	    ->select('meetingID', 'name', 'date', 'time', 'place', 'participants', 'status')
	    ->where('date', '>=', date('Y/m/d', strtotime('today')))
	    ->where('participants', 'LIKE', '%' . $userId . '%')
	    ->orWhere('participants', 'LIKE', '%' . $userRole . '%')
	    ->orderBy('date', 'asc'))
	    ->addColumn('Meeting', function($meeting) 
	    {
		$crudLinks = '<div class="btn-group">';
		if($meeting->status == '0')
		{
		    $crudLinks .= '<button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown">' . 'Deactivated' . '<span class="glyphicon glyphicon-arrow-down"></span></button>';
		}
		else
		{
		    $crudLinks .= '<button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">' . $meeting->name . '<span class="glyphicon glyphicon-arrow-down"></span></button>';
		}

		$crudLinks .= '<ul class="dropdown-menu" role="menu">';
		$crudLinks .= '</ul>';
		$crudLinks .= '</div>';

		return $crudLinks;
	    })
	    //Date is stored in 'YYYY/MM/DD' format so it's easier to compare dates in queries
	    //This function will change the display format to 'MM/DD/YYYY'
	    ->addColumn('date', function($meeting)
	    {
		$meeting->date = date('m/d/Y', strtotime($meeting->date));
		return $meeting->date;
	    })
	    ->showColumns('time', 'place')
	    ->addColumn('participants', function($meeting)
	    {
		$participants = explode(',', $meeting->participants);
		foreach($participants as $k => $v)
		{
		    if($v == 2)
		    {
			$participants[$k] = "All Entering Student Committee Members";
		    }
		    else if ($v == 4)
		    {
			$participants[$k] = "All Graduating Student Committee Members";
		    }
		    else if($v == 6)
		    {
			$participants[$k] = "All Returning Student Committee Members";
		    }
		    else
		    {
			$v = User::where('userId', '=', $v)->whereNotIn('userId', array(0, 1, 2))->get();
			foreach($v as $participant => $name)
			{
			    $participants[$k] = $name->name;
			}
		    }
		}
		$participants = implode(', ', $participants);
		return $participants;
	    })
	    ->make();
    }
}
