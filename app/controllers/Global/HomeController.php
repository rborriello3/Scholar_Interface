<?php

class HomeController extends BaseController
{

	public function __construct() 
	{
		parent::__construct();
		// For security reasons, if user navigates away from the site and
		// returns back to the homepage we want them logged 
		// out to help prevent CSRF - not 100% fool proof
		Auth::logout();
	}


	public function showHome()
	{	
		return View::make('Content.Global.Authentication.homepage');
	}
}