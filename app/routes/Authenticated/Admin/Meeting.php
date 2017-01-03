<?php

Route::get('new', array('uses' => 'MeetingController@showCreateMeeting', 'as' => 'showCreateMeeting'));
Route::post('new', array('uses' => 'MeetingController@doCreateMeeting', 'as' => 'doCreateMeeting'));
