<?php

Route::get('new', array('uses' => 'MeetingController@showCreateMeeting', 'as' => 'showCreateMeeting'));
Route::post('new', array('uses' => 'MeetingController@doCreateMeeting', 'as' => 'doCreateMeeting'));
Route::get('deactivate/{meetingID}', array('uses' => 'MeetingController@deactivateMeeting', 'as' => 'deactivateMeeting', 'before' => 'meetingID'));
Route::get('activate/{meetingID}', array('uses' => 'MeetingController@activateMeeting', 'as' => 'activateMeeting', 'before' => 'meetingID'));

Route::group(array('before' => 'meetingID'), function()
{
    Route::get('edit/{meetingID}', array('uses' => 'MeetingController@showEditMeeting', 'as' => 'showEditMeeting'));
    Route::post('edit/{meetingID}', array('uses' => 'MeetingController@doEditMeeting', 'as' => 'doEditMeeting'));
});
