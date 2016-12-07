<?php

class MeetingDataController extends BaseController
{
    public function showAllMeetingsJsonCRUD()
    {
	return Datatable::query(DB::table('meeting')
	    ->join('gradeGroup', 'meeting.gradeGroup', '=', 'gradeGroup.gradeGroup')
	    ->select('eventID', 'name', 'month', 'day', 'year', 'time', 'place', 'meeting.gradeGroup', 'gradeGroup.groupDescription', 'status')
	    ->where(function($query) 
	    {
		$query->where('month', '>=', date('m'))
		      ->where('day', '>=', date('d'))
		      ->where('year', '>=', date('y'));
		return $query;
	    })
	    ->orderBy('year', 'asc')
	    ->orderBy('month', 'asc')
	    ->orderBy('day', 'asc'))
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
	    ->showColumns('name')
	    ->addColumn('Date', function($meeting)  
	    {
		//$year = $meeting->year;
		$date = $meeting->month . '/' . $meeting->day . '/' . substr($meeting->year, -2);
		return $date;
	    })
	    ->showColumns('time', 'place', 'gradeGroup')
	    ->make();
    }
}
