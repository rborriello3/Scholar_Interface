<?php

class Meeting extends Eloquent
{
    /**
     * The database table
     */
    protected $table = 'meeting';

    /**
     * We don't want any default time stamps
     */
    public $timestamps = FALSE;

    /**
    * must define a specific key for our database table
    */   
    protected $primaryKey = 'meetingID';

    /**
    * Creates a new meeting 
    */
    public function createMeeting($values)
    {
	$values['participants'] = implode(',', $values['participants']);
	$values = array_except($values, array('_token'));
	$values['status'] = 1;
        return $this->insert($values);
    }

    /**
    * Updates an meeting
    */
    public function editMeeting($meetingID, $values)
    {
        $this->find($meetingID);
	$values = array_except($values, array('status'));
        return $this->where('meetingID', '=', $meetingID)->update($values);
    }

    /**
    * Activates an meeting
    */
    public function activate($meetingID)
    {
        $meeting = $this->find($meetingID);
        $meeting->status = 1;
        return $meeting->save();
    }

    /**
    * Deactivates an meeting
    */
    public function deactivate($meetingID)
    {
	$meeting = $this->find($meetingID);
	$meeting->status = 0;
	return $meeting->save();
    } 
}
