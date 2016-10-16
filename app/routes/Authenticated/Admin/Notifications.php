<?php

Route::get('home/', array('uses' => 'NotificationsController@showHome', 'as' => 'homeNotifications'));

Route::group(array('prefix' => 'student/', 'before' => 'studentID'), function ()
{
    Route::get('{studentID}/new', array('uses' => 'NotificationsController@showMessageStudent', 'as' => 'showMessageStudent', 'before' => 'studentID'));
    Route::post('{studentID}/new', array('uses' => 'NotificationsController@doMessageStudent', 'as' => 'doMessageStudent', 'before' => 'studentID'));
    Route::get('{studentID}/history', array('uses' => 'NotificationsController@showStudentMessageHistory', 'as' => 'showStudentMessageHistory', 'before' => 'studentID'));
});

Route::group(array('prefix' => 'user/', 'before' => 'userID'), function()
{
	Route::get('{userID}/history', array('uses' => 'NotificationsController@showUserMessageHistory', 'as' => 'showUserMessageHistory'));
	Route::get('{userID}/sending_history', array('uses' => 'NotificationsController@showUserFromHistory', 'as' => 'showUserFromHistory'));
//    Route::get('{userID}/new', array('uses' => 'NotificationsController@showMessageUser', 'as' => 'showMessageUser'));
//    Route::post('{userID}/new', array('uses' => 'NotificationsController@doMessageUser', 'as' => 'doMessageUser'));
});

Route::group(array('prefix' => 'group/'), function ()
{
    Route::get('incomplete', array('uses' => 'NotificationsController@showAllIncomplete', 'as' => 'showEmailIncompleteApplications'));
    Route::post('incomplete', array('uses' => 'NotificationsController@doAllIncomplete', 'as' => 'doEmailIncompleteApplications'));
});

Route::get('view_message/{messageGUID}', array('uses' => 'NotificationsController@showSingleMessage', 'as' => 'showSingleMessage'));
