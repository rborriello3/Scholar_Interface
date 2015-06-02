<?php

Route::get('home', array('uses' => 'AwardsController@index', 'as' => 'showAllAwards'));
Route::get('new', array('uses' => 'AwardsController@newAwards', 'as' => 'showNewAwards'));
Route::post('new', array('uses' => 'AwardsController@doNewAwards', 'as' => 'doNewAwards'));

Route::group(array('before' => 'studentID|fundCode'), function ()
{
	Route::get('deactivate/{fundCode}/{studentID}', array('uses' => 'AwardsController@doDeactivateAward', 'as' => 'doDeactivateAward'));
	Route::get('activate/{fundCode}/{studentID}', array('uses' => 'AwardsController@doActivateAward', 'as' => 'doActivateAward'));
	Route::get('accept/{fundCode}/{studentID}', array('uses' => 'AwardsController@doAcceptAward', 'as' => 'doAcceptAward'));
	Route::get('revoke/{fundCode}/{studentID}', array('uses' => 'AwardsController@doRevokeAward', 'as' => 'doRevokeAward'));
    Route::get('edit/{fundCode}/student/{studentID}', array('uses' => 'AwardsController@showEditAward', 'as' => 'showEditAward'));
    Route::post('edit/{fundCode}/student/{studentID}', array('uses' => 'AwardsController@doEditAward', 'as' => 'doEditAward'));
});

Route::get('history/{studentID}', array('uses' => 'AwardsController@showAwardHistory', 'as' => 'showAwardHistory', 'before' => 'studentID'));
Route::get('application/{guid}', array('uses' => 'AwardsController@showAwardSingleStudent', 'as' => 'showAwardSingleStudent', 'before' => 'appGUID'));