<?php

class Usersettings extends Eloquent
{
	/**
	 * The database table
	 */
	protected $table = 'userSettings';

	/**
	 * We don't want any default time stamps
	 */
	public $timestamps = false;
	
	/**
	 * must define a specific key for our database table
	 */
	protected $primaryKey = 'userId';
	
}