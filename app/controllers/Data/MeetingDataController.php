<?php

class MeetingDataController extends BaseController
{
    public function showAllMeetingsJsonCRUD()
    {
	return Datatable::query(DB::table('meeting')
	    ->select('meetingID', 'name', 'date', 'time', 'place', 'participants', 'status')
	    /*->where(DB::raw('substring(date, 2)'), '>=', date('m'))
	    ->where(DB::raw('substring(date, 4, 2)'), '>=', date('d'))
	    ->where(DB::raw('substring(date, 7, 4)'), '>=', date('Y'))*/
	    ->where(function($query)
	    {
		$query->where(strtotime('MM/DD/YY', $query->date), '>=', strtotime('MM/DD/YY', date('m/d/Y')));
		//return $query;
	    })
		//strtotime('MM/DD/YY', date('m/d/Y', 'date')), '>=', strtotime('MM/DD/YY', date('m/d/Y')))
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
		
		//$crudLinks .= '<li>' . link_to_route('showEditMeeting', 'Edit Meeting', $parameters = array($meeting->meetingID), $attributes = array('alt' => 'editMeeting')) . '</li>';
		//$crudLinks .= '<li>' . link_to_route('deleteMeeting', 'Delete Meeting', $parameters = array($meeting->meetingID), $attributes = array('alt' => 'eraseMeeting')) . '</li>';
		$crudLinks .= $statusLinks;

		$crudLinks .= '</ul>';
		$crudLinks .= '</div>';

		return $crudLinks;
	    })
	    ->showColumns('name', 'date', 'time', 'place', 'participants')
	    ->make();
    }
}
