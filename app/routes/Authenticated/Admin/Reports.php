<?php

Route::get('home', array('uses' => 'ReportsController@showHome', 'as' => 'showReportsHome'));
Route::post('home', array('uses' => 'ReportsController@doReports', 'as' => 'doReportsSelection'));
Route::get('graduating_rank', array('uses' => 'ReportsController@graduating_rank', 'as' => 'graduating_rank'));
Route::get('graduating_assessments', array('uses' => 'ReportsController@graduating_assessments', 'as' => 'graduating_assessments'));
Route::get('entering_rank', array('uses' => 'ReportsController@entering_rank', 'as' => 'entering_rank'));
Route::get('entering_assessments', array('uses' => 'ReportsController@entering_assessments', 'as' => 'entering_assessments'));
Route::get('graduating_awarded_address', array('uses' => 'ReportsController@graduating_awarded_address', 'as' => 'graduating_awarded_address'));
Route::get('graduating_regret', array('uses' => 'ReportsController@graduating_regret', 'as' => 'graduating_regret'));
Route::get('faculty_graduating_address', array('uses' => 'ReportsController@faculty_graduating_address', 'as' => 'faculty_graduating_address'));
Route::get('returning_assessments', array('uses' => 'ReportsController@returning_assessments', 'as' => 'returning_assessments'));
Route::get('returning_rank', array('uses' => 'ReportsController@returning_rank', 'as' => 'returning_rank'));
Route::get('returning_award_address', array('uses' => 'ReportsController@returning_awarded_address', 'as' => 'returning_awarded_address'));
Route::get('returning_regret', array('uses' => 'ReportsController@returning_regret', 'as' => 'returning_regret'));
Route::get('faculty_returning_address', array('uses' => 'ReportsController@faculty_returning_address', 'as' => 'faculty_returning_address'));
Route::get('entering_award_address', array('uses' => 'ReportsController@entering_awarded_address', 'as' => 'entering_awarded_address'));
Route::get('entering_regret', array('uses' => 'ReportsController@entering_regret', 'as' => 'entering_regret'));
Route::get('faculty_entering_address', array('uses' => 'ReportsController@faculty_entering_address', 'as' => 'faculty_entering_address'));
Route::get('all_awards', array('uses' => 'ReportsController@all_awards', 'as' => 'all_awards'));
Route::get('all_students', array('uses' => 'ReportsController@all_students', 'as' => 'all_students'));
Route::get('returningStudentsCriteria', array('uses' => 'ReportsDataController@returningStudentsCriteria', 'as' => 'returningStudentsCriteria'));
Route::get('choose_scholarship_award_history', array('uses' => 'ReportsController@choose_scholarship_award_history', 'as' => 'choose_scholarship_award_history'));
Route::post('show_scholarship_award_history', array('uses' => 'ReportsController@show_scholarship_award_history', 'as' => 'show_scholarship_award_history'));
