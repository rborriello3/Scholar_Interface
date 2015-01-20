<?php

Route::get('home/', array('uses' => 'ResponseController@showHome', 'as' => 'showResponseHome'));
Route::post('home/', array('uses' => 'ResponseController@doResponseProcess', 'as' => 'doResponseProcess'));
Route::get('/accept_award_offer/{GUID}', array('uses' => 'ResponseController@doAcceptAward', 'as' => 'doAcceptAwardsManagement'));
Route::get('/redo_award_offer/{GUID}', array('uses' => 'ResponseController@doRedoAward', 'as' => 'doRedoAward'));