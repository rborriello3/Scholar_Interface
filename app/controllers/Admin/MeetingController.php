<?php

class MeetingController extends BaseController
{
    /*
    * Shows all meetings (active and deactivated) for dates after today, inclusive
    *
    * @return Response
    */
    public function showAllMeetings()
    {
	return View::make('Content.Global.Dashboard.dashboard' . Session::get('role'));
    }
