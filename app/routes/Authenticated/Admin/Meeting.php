<?php
Route::get('home', array('uses' => 'MeetingController@showAllMeetings', 'as' => 'showAllMeetings'));
