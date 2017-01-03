<?php

class MeetingDataController extends BaseController
{
    public function showAllMeetingsJsonCRUD()
    {
	return Datatable::query(DB::table('meeting')
	    ->join('gradeGroup', 'meeting.participant', '=', 'gradeGroup.gradeGroup')
	    ->select('meetingID', 'name', 'date', 'time', 'place', 'meeting.participant', 'gradeGroup.groupDescription', 'status')
	    ->where(function($query) 
	    {
		$today = date('Y/m/d');
		$query->whereDate('date', '>=', $today);
		return $query;
	    })
	    ->orderBy('date', 'asc'))
	    ->addColumn('Actions', function($meeting) 
	    {
		$crudLinks = '<div class="btn-group">';
		if($meeting->status == '0')
		{
		    $crudLinks .= '<button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown">' . 'Deactivated' . '<span class="glyphicon glyphicon-arrow-down"></span></button>';
		    //$statusLinks = link to activate
		}
		else
		{
			$crudLinks .= '<button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">' . $meeting->name . '<span class="glyphicon glyphicon-arrow-down"></span></button>';
		    //$statusLinks = link to deactivate
	 	}

		$crudLinks .= '<ul class="dropdown-menu" role="menu">';
		
		//$crudLinks .= link to route (showEdit)
		//$crudLinks .= $statusLinks;

		$crudLinks .= '</ul>';
		$crudLinks .= '</div>';

		return $crudLinks;
	    })
	    ->showColumns('name', 'date', 'time', 'place', 'participant')
	    /*->addColumn('date', function($meeting)  
	    {
		//$year = $meeting->year;
		$date = $meeting->month . '/' . $meeting->day . '/' . substr($meeting->year, -2);
		return $date;
	    })
	    ->showColumns('time', 'place', 'participant')*/
	    ->make();
    }
}
