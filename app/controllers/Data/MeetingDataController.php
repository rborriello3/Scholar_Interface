<?php

class MeetingDataController extends BaseController
{
    public function showAllMeetingsJsonCRUD()
    {
	return Datatable::query(DB::table('meeting')
	    //->join('gradeGroup', 'meeting.participants', '=', 'gradeGroup.gradeGroup')
	    ->select('meetingID', 'name', 'date', 'time', 'place', 'participants', /*'gradeGroup.groupDescription',*/ 'status')
	    ->where(DB::raw('substring(date, 2)'), '>=', date('m'))
	    ->where(DB::raw('substring(date, 4, 2)'), '>=', date('d'))
	    ->where(DB::raw('substring(date, 7, 4)'), '>=', date('Y'))
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
	    ->showColumns('name', 'date', 'time', 'place', 'participants')
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
