<?php

class Deadline extends Eloquent
{
    /**
    * The database table
    */
    protected $table = 'deadline';

    /**
    * We don't want any default time stamps
    */
    public $timestamps = FALSE;

    /**
    * must define a specific key for our database table
    */
    protected $primaryKey = 'deadlineID';

    /**
    * Creates a new deadline
    */
    public function createDeadline($values)
    {
	$values['gradeGroup'] = implode(', ', $values['gradeGroup']);
 	$values = array_except($values, array('_token'));
	$values['status'] = 1;
	return $this->insert($values);
    }

    /**
    *
    */
    public function editDeadline($deadlineID, $values)
    {
	$this->find($deadlineID);
    	$values = array_except($values, array('status'));
	$values = array_except($values, array('_token'));
	return $this->where('deadlineID', '=', $deadlineID)->update($values);
    }

    /**
    * Activates a deadline
    */
    public function activate($deadlineID)
    {
	$deadline = $this->find($deadlineID);
   	$deadline->status = 1;
	return $deadline->save();
    }

    /**
    * Deactivates a deadline
    */
    public function deactivate($deadlineID)
    {
	$deadline = $this->find($deadlineID);
	$deadline->status = 0;
	return $deadline->save();
    }
}
