<?php

Route::group(array('before' => 'auth|accessRights'), function ()
{
    Route::get('dashboard', array('uses' => 'DashboardController@showDashboard', 'as' => 'showDashboard', 'before' => 'firstLogin|multiRole|cellCheck'));
    Route::get('role', array('uses' => 'RoleController@showSelect', 'as' => 'showRoleSelect'));
    Route::post('role', array('uses' => 'RoleController@doSelect', 'as' => 'doRoleSelect'));
    Route::get('mandatory-update', array('uses' => 'FirstLoginController@showFirstLoginUpdate', 'as' => 'showFirstLogin'));
    Route::post('mandatory-update', array('uses' => 'FirstLoginController@doFirstLoginUpdate', 'as' => 'doFirstLogin'));
    Route::post('change-aidyear', array('uses' => 'DashboardController@doAidyearSelect', 'as' => 'doAidYearSelect'));
    Route::get('settings', array('uses' => 'SettingsController@showSettingsPage', 'as' => 'showSettingsPage'));
    Route::post('settings/update/{type}', array('uses' => 'SettingsController@doSettingsUpdate', 'as' => 'doUpdate', 'before' => 'settingsUpdate'));

});
