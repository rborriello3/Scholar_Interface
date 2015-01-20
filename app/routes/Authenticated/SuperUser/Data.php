<?php

Route::get('users', array('uses' => 'UsersDataController@JsonCRUD', 'as' => 'allUsersJson'));
