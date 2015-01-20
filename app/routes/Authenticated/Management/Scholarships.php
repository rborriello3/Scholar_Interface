<?php

Route::get('home/', array('uses' => 'ScholarshipsController@showAll', 'as' => 'showAllScholarships'));
Route::get('edit/{fundCode}', array('uses' => 'ScholarshipsController@showUpdateSchol', 'as' => 'showUpdateSchol', 'before' => 'fundCode'));
Route::post('edit/{fundCode}', array('uses' => 'ScholarshipsController@doUpdateSchol', 'as' => 'doUpdateSchol', 'before' => 'fundCode'));
Route::get('new', array('uses' => 'ScholarshipsController@showCreateSchol', 'as' => 'showCreateSchol'));
Route::post('new', array('uses' => 'ScholarshipsController@doCreateSchol', 'as' => 'doCreateSchol'));
Route::get('activate_scholarship/{fundCode}', array('uses' => 'ScholarshipsController@doActivateScholarship', 'as' => 'doActiveScholarship', 'before' => 'fundCode'));
Route::get('deactivate_scholarship/{fundCode}', array('uses' => 'ScholarshipsController@doDeactivateScholarship', 'as' => 'doDeactiveScholarship', 'before' => 'fundCode'));
