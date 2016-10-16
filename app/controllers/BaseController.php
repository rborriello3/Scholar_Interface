<?php

class BaseController extends Controller {

	/**
	 * Sets up a Before filter on all controllers on all POSTS to make sure the CSRF is 
	 * verified
	 */
	public function __construct()
	{
		$this->beforeFilter('csrf', array('on' => array('post', 'put', 'delete', 'update')));
  	}
  	
	/**
	 * Setup the layout used by the controller.
	 *
	 * @return void
	 */
	protected function setupLayout()
	{
		if ( ! is_null($this->layout))
		{
			$this->layout = View::make($this->layout);
		}
	}

}