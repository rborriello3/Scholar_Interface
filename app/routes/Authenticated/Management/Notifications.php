<?php

Route::get('home/', array('uses' => 'NotificationsController@showHome', 'as' => 'homeNotifications'));


Route::group(array('prefix' => 'student/', 'before' => 'studentID'), function ()
{
    Route::get('{studentID}/new', array('uses' => 'NotificationsController@showMessageStudent', 'as' => 'showMessageStudent'));
    Route::post('{studentID}/new', array('uses' => 'NotificationsController@doMessageStudent', 'as' => 'doMessageStudent'));
    Route::get('{studentID}/history', array('uses' => 'NotificationsController@showStudentMessageHistory', 'as' => 'showStudentMessageHistory'));
});

Route::group(array('prefix' => 'user/', 'before' => 'userID'), function()
{
	Route::get('{userID}/history', array('uses' => 'NotificationsController@showUserMessageHistory', 'as' => 'showUserMessageHistory'));
	Route::get('{userID}/sending_history', array('uses' => 'NotificationsController@showUserFromHistory', 'as' => 'showUserFromHistory'));
});

Route::get('view_message/{messageGUID}', array('uses' => 'NotificationsController@showSingleMessage', 'as' => 'showSingleMessage'));
