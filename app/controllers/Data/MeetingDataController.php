<?php

class MeetingDataController extends BaseController
{
    public function showAllMeetingsJsonCRUD()
    {
	return Datatable::query(DB::table('meeting')
	    ->join('gradeGroup', 'meeting.gradeGroup', '=', 'gradeGroup.gradeGroup')
	    ->join('applicationStatus', 'applicationStatus.statusID', '=', 'meeting.status')
	    ->select('eventID', 'name', 'month', 'day', 'year', 'time', 'place', 'gradeGroup', 'applicationStatus.statusName')
	    ->where(function($query) {
		$query->where('month', '>=', date('m'))
		      ->where('day', '>=', date('d'))
		      ->where('year', '>=', date('y'))
	    })
	    ->orderBy('day', 'asc')
	    ->orderBy('month', 'asc')
	    ->orderBy('year', 'asc')
	    ->addColumn('Actions', function($meeting) {
		$crudLinks = '<div class="btn-group">';
		if($meeting->statusName == 'Deactivated')
		{
		    $crudLinks .= '<button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown">' . $meeting->statusName . '<span class="glyphicon glyphicon-arrow-down"></span></button>';
		    //$statusLinks = link to edit meeting
		}
		else
		{
			$crudLinks .= '<button type="button" class=btn btn-success dropdown-toggle" data-toggle="dropdown">' . $meeting->statusName . '<span class="glyphicon glyphicon-arrow-down"></span></button>';
