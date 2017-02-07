<?php

Route::get('applications', array('uses' => 'ApplicationsDataController@JsonCRUD', 'as' => 'allApplicationsJson'));
Route::get('meeting', array('uses' => 'MeetingDataController@showAllMeetingsJsonCRUD', 'as' => 'showAllMeetingsJsonCRUD'));
Route::get('deadline', array('uses' => 'DeadlineDataController@showAllDeadlinesJsonCRUD', 'as' => 'showAllDeadlinesJsonCRUD'));
Route::get('processes', array('uses' => 'ProcessDataController@JsonCRUD', 'as' => 'allProcessesJSON'));
Route::get('graduating_rank', array('uses' => 'ReportsDataController@graduatingRankJSON', 'as' => 'graduatingRankJSON'));
Route::get('graduating_assessments/{userId}', array('uses' => 'ReportsDataController@graduatingAssessmentsJSON', 'as' => 'graduatingAssessmentsJSON'));
Route::get('entering_rank', array('uses' => 'ReportsDataController@enteringRankJSON', 'as' => 'enteringRankJSON'));
Route::get('entering_assessments/{userId}', array('uses' => 'ReportsDataController@enteringAssessmentsJSON', 'as' => 'enteringAssessmentsJSON'));
Route::get('awards', array('uses' => 'AwardsDataController@awardsJSON', 'as' => 'showAllAwardsJSON'));
Route::get('graduating_awarded_address', array('uses' => 'ReportsDataController@graduatingStudentsAddress', 'as' => 'graduatingStudentAddress'));
Route::get('graduating_regret', array('uses' => 'ReportsDataController@gradutingStudentsRegret', 'as' => 'graduatingStudentsRegret'));
Route::get('faculty_graduating_address', array('uses' => 'ReportsDataController@graduatingFacultyAddress', 'as' => 'facultyGraduatingAddress'));
Route::get('allScholaripsJSON', array('uses' => 'ScholarshipsDataController@scholarshipsListJSON', 'as' => 'allScholarshipsJSON'));
Route::get('returning_rank', array('uses' => 'ReportsDataController@returningRankJSON', 'as' => 'returningRankJSON'));
Route::get('returning_assessments/{userId}', array('uses' => 'ReportsDataController@returningAssessmentsJSON', 'as' => 'returningAssessmentsJSON'));
Route::get('allStudents', array('uses' => 'StudentDataController@allStudents', 'as' => 'allStudentsJSON'));
Route::get('returning_awarded_address', array('uses' => 'ReportsDataController@returningStudentsAddress', 'as' => 'returningAddress'));
Route::get('returning_regret', array('uses' => 'ReportsDataController@returningStudentsRegret', 'as' => 'returningRegret'));
Route::get('faculty_returning_address', array('uses' => 'ReportsDataController@returningFacultyAddress', 'as' => 'returningFacultyAddress'));
Route::get('notifications_history', array('uses' => 'NotificationsDataController@getAllNotifications', 'as' => 'getAllNotifications'));
Route::get('entering_award_address', array('uses' => 'ReportsDataController@enteringStudentAddress', 'as' => 'getAllEnteringAwardAddress'));
Route::get('entering_regret', array('uses' => 'ReportsDataController@enteringStudentRegret', 'as' => 'enteringRegret'));
Route::get('faculty_entering_address', array('uses' => 'ReportsDataController@enteringFacultyAddress', 'as' => 'enteringFacultyAddress'));
Route::get('all_awards', array('uses' => 'ReportsDataController@all_Awards', 'as' => 'all_awards_json'));
Route::get('all_students', array('uses' => 'ReportsDataController@all_students', 'as' => 'all_students_json'));
Route::get('all_grades/{guid}', array('uses' => 'ApplicationsDataController@getSpecificAssessment', 'as' => 'specificAssessmentJSON', 'before' => 'appGUID'));
Route::get('show_scholarship_award_history_JSON/{fundCode}', array('uses' => 'ReportsDataController@show_scholarship_award_history_JSON', 'as' => 'show_scholarship_award_history_JSON', 'before' => 'fundCode'));
