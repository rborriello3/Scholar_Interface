<?php
Route::get('users', array(
	'uses' => 'UsersDataController@JsonCRUD',
	'as'   => 'allUsersJson'	
));

//this was made for showUser and showEditUser b/c $name was not working
//this info was found in Code Bright pg. 61
/**
Route::get('/{name}', function($name)
{
	$data['name'] = $name
	return View::make('showUser',$data);
});**/
