<?php

Route::group(array('prefix' => 'verify/', 'before' => 'auth|accessRights'), function ()
{
    Route::post('cellphone', array('uses' => 'VerificationController@doCellVerify', 'as' => 'doCellVerify'));

    Route::get('newCellCode', array('uses' => 'VerificationController@doNewCellCode', 'as' => 'doResendNewCode'));

    Route::get('deleteCode', array('uses' => 'VerificationController@doDeleteCell', 'as' => 'doDeleteCell'));
});