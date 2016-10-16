<?php

Route::get('home', array('uses' => 'ProcessController@showProcess', 'as' => 'showProcesses'));

Route::get('new', array('uses' => 'ProcessController@showNewProcess', 'as' => 'showNewProcess'));

Route::post('new', array('uses' => 'ProcessController@doNewProcess', 'as' => 'doNewProcess'));

Route::get('stop/{id}', array('uses' => 'ProcessController@doStopProcess', 'as' => 'doStopProcess'));

Route::get('delete/{id}', array('uses' => 'ProcessController@doDeleteProcess', 'as' => 'doDeleteProcess'));

Route::get('data_transfer', array('uses' => 'ProcessController@showUploadData', 'as' => 'showDataUpload'));

Route::post('data_tansfer', array('uses' => 'ProcessController@doUploadData', 'as' => 'doDataUpload'));