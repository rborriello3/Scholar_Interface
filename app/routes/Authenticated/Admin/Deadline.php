<?php

Route::get('new', array('uses' => 'DeadlineController@showCreateDeadline', 'as' => 'showCreateDeadline'));
Route::post('new', array('uses' => 'DeadlineController@doCreateDeadline', 'as' => 'doCreateDeadline'));
Route::get('deactivate/{deadlineID}', array('uses' => 'DeadlineController@deactivateDeadline', 'as' => 'deactivateDeadline', 'before' => 'deadlineID'));
Route::get('activate/{deadlineID}', array('uses' => 'DeadlineController@activateDeadline', 'as' => 'activateDeadline', 'before' => 'deadlineID'));
