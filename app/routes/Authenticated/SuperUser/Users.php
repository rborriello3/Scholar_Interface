<?php

Route::get('home', array('uses' => 'AccountsController@showUsers', 'as' => 'showUsers'));

Route::get('show/{id}', array('uses' => 'AccountsController@showUser', 'as' => 'showUser'));
//added Route::post and doShowUser is not created yet
//Route::post('edit/{id}', array('uses' => 'AccountsController@doEditUser', 'as' => 'doEditUser'));

Route::get('edit/{id}', array('uses' => 'AccountsController@showEditUser', 'as' => 'showEditUser'));

Route::post('edit/{id}', array('uses' => 'AccountsController@doEditUser', 'as' => 'doEditUser'));

Route::get('activate/{id}', array('uses' => 'AccountsController@showActivate', 'as' => 'showActivateUser'));

Route::post('activate/{id}', array('uses' => 'AccountsController@doActivate', 'as' => 'doActivateUser'));

Route::get('deactivate/{id}', array('uses' => 'AccountsController@showDeactivateAccount', 'as' => 'showDeactivateUser'));

Route::post('deactivate/{id}', array('uses' => 'AccountsController@doDeactivateAccount', 'as' => 'doDeactivateUser'));

Route::get('reset_password/{id}', array('uses' => 'AccountsController@showSuperResetPW', 'as' => 'showSuperResetPW'));

Route::post('reset_password/{id}', array('uses' => 'AccountsController@doSuperResetPW', 'as' => 'doSuperResetPW'));

Route::get('create', array('uses' => 'AccountsController@showCreateUser', 'as' => 'showCreateUser'));

Route::post('create', array('uses' => 'AccountsController@doCreateUser', 'as' => 'doCreateUser'));
