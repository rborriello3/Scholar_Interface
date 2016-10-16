<?php

Route::get('home/', array('uses' => 'StudentController@showHome', 'as' => 'showStudentHome'));
Route::get('edit/{studentID}', array('uses' => 'StudentController@showEditStudent', 'as' => 'showEditStudent', 'before' => 'studentID'));
Route::post('edit/{studentID}', array('uses' => 'StudentController@doEditStudent', 'as' => 'doStudentUpdate', 'before' => 'studentID'));
Route::get('new/', array('uses' => 'StudentController@showNewStudent', 'as' => 'showNewStudent'));
Route::post('new/', array('uses' => 'StudentController@doNewStudent', 'as' => 'doNewStudent'));