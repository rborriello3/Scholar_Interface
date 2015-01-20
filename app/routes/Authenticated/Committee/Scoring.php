<?php

Route::get('home', array('uses' => 'ScoringController@showApplications', 'as' => 'showCommitteeApps'));

Route::get('show/{guid}', array('uses' => 'ScoringController@showGrading', 'as' => 'showGrading', 'before' => 'appGUID'));

Route::post('show/{guid}/{paginate?}', array('uses' => 'ScoringController@processGrade', 'as' => 'processGrade', 'before' => 'appGUID'));

Route::post('mass_grading/', array('uses' => 'ScoringController@doPaginateRequest', 'as' => 'doPaginateRequest',));

Route::get('mass_grading/begin/{guid}/{page}', array('uses' => 'ScoringController@showPaginatedGrading', 'as' => 'showPaginateGrade', 'before' => 'appGUID'));