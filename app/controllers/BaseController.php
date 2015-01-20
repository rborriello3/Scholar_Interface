<?php

class BaseController extends Controller
{

    /**
     * Ensures we have the CSRF filter on all types of in put
     *
     * @return void
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
        if (!is_null($this->layout))
        {
            $this->layout = View::make($this->layout);
        }
    }

}