<?php

Route::get('scoring', array(
	'uses' => 'ApplicationsDataController@gradingApplications',
	'as'   => 'allGradingApplicationsJson'	
));
Route::get('data/meeting', array('uses' => 'MeetingDataController@showCommMemberMeetings', 'as' => 'showCommMemberMeetings'));
Route::get('data/deadline', array('uses' => 'DeadlineDataController@showCommMemberDeadlines', 'as' => 'showCommMemberDeadlines'));
Route::get('data/assessments/{userId}', array('uses' => 'ReportsDataController@showSingleCommMemberAssessments', 'as' => 'showSingleCommMemberAssessments'));
