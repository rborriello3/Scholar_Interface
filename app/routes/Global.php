<?php 

Route::get('/', array( 
	'uses' => 'HomeController@showHome',
	'as'   => 'home.index'
));

Route::group(array('prefix' => 'session/'), function()
{
	Route::post('authenticate', array(
		'uses'   => 'SessionController@doCreate',
		'as'     => 'session.create'
	));

	Route::get('logout', array(
		'uses'   => 'SessionController@doLogout',
		'as'     => 'session.logout',
		'before' => 'auth|accessRights'
	));
});

Route::get('register', array(
	'uses' => 'RegisterController@showAccountCreate',
	'as'   => 'account.showCreate'
));

Route::post('register', array(
	'uses'   => 'RegisterController@doAccountCreate',
	'as'     => 'account.doCreate'
));

Route::group(array('prefix' => 'password/reset/'), function()
{
	Route::get('identity', array(
		'uses' => 'PassResetController@showEmailStep',
		'as'   => 'password.EmailStep'
	));
	
	Route::post('identity', array(
		'uses'   => 'PassResetController@doEmail',
		'as'     => 'password.doEmail',
		'before' => 'reCaptcha'
	));

	Route::get('questions', array(
		'uses'   => 'PassResetController@showQuestions',
		'as'     => 'password.reset.showQuestions'
	));
	
	Route::post('questions', array(
		'uses'   => 'PassResetController@doQuestions',
		'as'     => 'password.reset.doQuestions'
	));
	
	Route::get('cellPhone/{token}', array(
		'uses'   => 'PassResetController@showCellPhoneReset',
		'as'     => 'password.reset.showCellPhone',
		'before' => 'tokenPass'
	));

	Route::post('cellPhone/{token}', array(
		'uses'   => 'PassResetController@doCellPhoneReset',
		'as'     => 'password.reset.doCellPhone',
		'before' => 'tokenPass|cellToken'
	));
	
	Route::get('update/{token}', array(
		'uses'   => 'PassResetController@showUpdate',
		'as'     => 'password.reset.showUpdate',
		'before' => 'tokenPass'
	));
	
	Route::post('update/{token}', array(
		'uses'   => 'PassResetController@doUpdate',
		'as'     => 'password.reset.doUpdate',
		'before' => 'tokenPass'
	));
});