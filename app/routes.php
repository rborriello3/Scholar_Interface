<?php

/**
 * YOU WILL NOTICE THAT SOME OF THE MANAGEMENT ROUTES (THE USER:5) WILL HAVE ADMIN CONTROLLER ACTIONS.
 * THIS IS INTENDED BECAUSE WHY REWRITE THE CONTROLLERS? OR EVEN COPY AND PASTE THE CONTROLLERS? NO NEED TO DO THAT
 * SO PLEASE UNDERSTAND TO MAINTAIN SOME LEVEL OF CONSISTENCY THE ADMIN CONTROLLERS ARE USED FOR MANAGEMENT ROUTES
 *
 * THIS HAPPENED BECAUSE MANAGEMENT WANTED TO HAVE A BIT MORE POWER WHICH I AGREE WITH TO BE HONEST WITH YOU.
 * POWERS SUCH AS SCHOLARSHIP, STUDENT, AWARD, AND NOTIFICATION POWERS WHICH WERE BEFORE JUNE 11TH A ADMIN ONLY POWER
 * NOW MANAGEMENT HAS THAT AS WELL!
 */


require 'routes/Global.php';
require 'routes/Authenticated/Global.php';
require 'routes/Authenticated/Verification.php';

Route::group(array('before' => 'auth|accessRights|user:2'), function ()
{
    Route::group(array('prefix' => 'data/'), function ()
    {
        require 'routes/Authenticated/SuperUser/Data.php';
    });

    Route::group(array('prefix' => 'users/'), function ()
    {
        require 'routes/Authenticated/SuperUser/Users.php';
    });
});

Route::group(array('before' => 'auth|accessRights|user:3'), function ()
{
    Route::group(array('prefix' => 'data/'), function ()
    {
        require 'routes/Authenticated/Admin/Data.php';
    });

    Route::group(array('prefix' => 'applications/'), function ()
    {
        require 'routes/Authenticated/Admin/Applications.php';
    });

    Route::group(array('prefix' => 'student/'), function ()
    {
        require 'routes/Authenticated/Admin/Students.php';
    });

    Route::group(array('prefix' => 'notifications/'), function ()
    {
        require 'routes/Authenticated/Admin/Notifications.php';
    });

    Route::group(array('prefix' => 'processes/'), function ()
    {
        require 'routes/Authenticated/Admin/Processess.php';
    });

    Route::group(array('prefix' => 'reports/'), function()
    {
        require 'routes/Authenticated/Admin/Reports.php';
    });

    Route::group(array('prefix' => 'awards/'), function()
    {
        require 'routes/Authenticated/Admin/Awards.php';
    });

    Route::group(array('prefix' => 'scholarships/'), function()
    {
        require 'routes/Authenticated/Admin/Scholarships.php';
    });

    Route::group(array('prefix' => 'students/'), function()
    {
        require 'routes/Authenticated/Admin/Students.php';
    });

    Route::group(array('prefix' => 'notifications/'), function()
    {
        require 'routes/Authenticated/Admin/Notifications.php';
    });
});


Route::group(array('before' => 'auth|accessRights|user:4'), function ()
{

    Route::group(array('prefix' => 'data/'), function ()
    {
        require 'routes/Authenticated/Committee/Data.php';
    });

    Route::group(array('prefix' => 'scoring'), function ()
    {
        require 'routes/Authenticated/Committee/Scoring.php';
    });
});

Route::group(array('before' => 'auth|accessRights|users:5'), function()
{
    Route::group(array('prefix' => 'data/'), function ()
    {
        require 'routes/Authenticated/Management/Data.php';
    });

    Route::group(array('prefix' => 'response/'), function()
    {
        require 'routes/Authenticated/Management/Response.php';
    });

    Route::group(array('prefix' => 'notifications/'), function ()
    {
        require 'routes/Authenticated/Management/Notifications.php';
    });

    Route::group(array('prefix' => 'students/'), function()
    {
        require 'routes/Authenticated/Admin/Students.php';
    });

    Route::group(array('prefix' => 'scholarships/'), function()
    {
        require 'routes/Authenticated/Management/Scholarships.php';
    });

    Route::group(array('prefix' => 'awards/'), function()
    {
        require 'routes/Authenticated/Management/Awards.php';
    });

    Route::group(array('prefix' => 'applications/'), function()
    {
        require 'routes/Authenticated/Management/Applications.php';
    });

    Route::group(array('prefix' => 'reports/'), function()
    {
       require 'routes/Authenticated/Management/Reports.php';
    });

});
