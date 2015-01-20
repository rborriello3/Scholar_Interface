<?php

Route::get('end/{guid?}', array('uses' => 'ApplicationController@endApplication', 'as' => 'endApplication'));

Route::get('home', array('uses' => 'ApplicationController@showApplications', 'as' => 'showApplications'));

Route::get('new/type', array('uses' => 'ApplicationController@showType', 'as' => 'showType',));

Route::post('new/type', array('uses' => 'ApplicationController@doType', 'as' => 'doType',));

Route::group(array('prefix' => 'new/', 'before' => 'appGUID'), function ()
{
    Route::get('student/{guid}', array('uses' => 'ApplicationController@showStudentDemographics', 'as' => 'showStudentDemo'));

    Route::post('student/{guid}', array('uses' => 'ApplicationController@doStudentDemographics', 'as' => 'doStudentDemo'));

    Route::get('education/{guid}', array('uses' => 'ApplicationController@showSchoolInformation', 'as' => 'showSchoolInfo'));

    Route::post('education/{guid}', array('uses' => 'ApplicationController@doSchoolInformation', 'as' => 'doSchoolInfo'));

    Route::get('requirements/{guid}', array('uses' => 'ApplicationController@showEssays', 'as' => 'showEssays'));

    Route::post('requirements/{guid}', array('uses' => 'ApplicationController@doEssays', 'as' => 'doEssays'));

    Route::get('recommendations/{guid}', array('uses' => 'ApplicationController@showRecomms', 'as' => 'showRecomms'));

    Route::post('recommendations/{guid}', array('uses' => 'ApplicationController@doRecomms', 'as' => 'doRecomms'));

    Route::get('complete/{guid}', array('uses' => 'ApplicationController@showComplete', 'as' => 'showCompleteApp'));

    Route::post('complete/{guid}', array('uses' => 'ApplicationController@doComplete', 'as' => 'doCompleteApp'));
});

Route::get('edit/{guid}', array('uses' => 'ApplicationController@showEditApp', 'as' => 'showEditApplication', 'before' => 'appGUID'));

Route::post('edit/{guid}', array('uses' => 'ApplicationController@doEditApp', 'as' => 'doEditApplication', 'before' => 'appGUID'));

Route::get('complete/{guid}', array('uses' => 'ApplicationController@showFinishApplication', 'as' => 'showFinishApplication', 'before' => 'appGUID'));

Route::post('complete/{guid}', array('uses' => 'ApplicationController@doFinishApplication', 'as' => 'doFinishApplication', 'before' => 'appGUID'));

Route::get('delete/{guid}', array('uses' => 'ApplicationController@deactivateApplication', 'as' => 'doDeleteApplication', 'before' => 'appGUID'));

Route::get('activate/{guid}', array('uses' => 'ApplicationController@activateApplication', 'as' => 'doActivateApplication', 'before' => 'appGUID'));

Route::get('show/grades/{guid}', array('uses' => 'ApplicationController@showViewGrades', 'as' => 'showViewGrades', 'before' => 'appGUID'));

Route::get('show/{studentID}', array('uses' => 'ApplicationController@showStudentApplications', 'as' => 'showStudentApplications', 'before' => 'studentID:showApplications,error,Student does not exist'));


