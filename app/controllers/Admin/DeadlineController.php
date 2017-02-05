<?php

class DeadlineController extends BaseController
{
    public function showCreateDeadline()
    {
	$data['gradeGroup'] = array(array('2', 'All Entering Student Committee Members'), array('4', 'All Graduating Student Committee Members'), array('6', 'All Returning Student Committee Members'));
	return View::make('Content.Admin.Deadline.showCreateDeadline', $data);
    }

    public function doCreateDeadline()
    {
	$rules = array(
	    'name'		=>	'alpha_space_dash_num',
	    'date'		=>	'date_format:m/d/Y',
	    'description'	=>	'alpha_space_dash_num',
	    'gradeGroup'	=>	'array_num'
        );

	$v = Validator::make(Input::all(), $rules);

	if($v->passes())
	{
	    $data = Input::all();

	    //Format deadline date in YYYY/MM/DD 
	    $data['date'] = date('Y/m/d', strtotime($data['date']));

	    $deadlineAttempt = new Deadline();
	    $deadlineConfirmed = $deadlineAttempt->createDeadline($data, FALSE);

	    if($deadlineConfirmed)
	    {
		return Redirect::route('showDashboard')->with('success', 'Successfully scheduled ' . $deadlineConfirmed . ' deadline.');
	    }
	    else
	    {
		return Redirect::route('showDashboard')->with('error', 'Deadline could not be scheduled due to a processing error.');
	    }
	}

	return Redirect::route('showDashboard')->withErrors($v->messages())->withInput()->with('error', 'Deadline could not be scheduled due to invalid characters in the text fields.');
    }
 
    public function deactivateDeadline($deadlineID)
    {
	$deadline = new Deadline();
	$deadline->deactivate($deadlineID);
    	return Redirect::route('showDashboard')->with('success', 'Deadline Deactivated');
    }

    public function activateDeadline($deadlineID)
    {
	$deadline = new Deadline();
	$deadline->activate($deadlineID);
	return Redirect::route('showDashboard')->with('success', 'Deadline Activated');
    }
} 
