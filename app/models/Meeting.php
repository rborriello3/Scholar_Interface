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
    protected $primaryKey = 'eventID';

    /**
    * Creates a new event 
    */
    public function createMeeting($values)
    {
        $values['gradeGroup'] = implode(',', $values['gradeGroup']);
	$values['strtotime'] = strtotime($values['date'] . $values['time']);
	$values['active'] = 1;
	//$values = array_except($values, array('date', 'time'));
        $this->insert($values);
    }

    /**
    * Updates an event
    */
    public function updateMeeting($eventID, $values)
    {
        $this->find($eventID);
        $values['gradeGroup'] = implode(',', $values['gradeGroup']);
	$values = array_except($values, array('active'));
	//$values = array_except($values, array('date', 'time'));
        return $this->where('eventID', '=', $eventID)->update($values);
    }

    /**
    * Activates an event
    */
    public function activate($eventID)
    {
        $event = $this->find($eventID);
        $event->active = 1;
        return $event->save();
    }

    /**
    * Deactivates an event
    */
    public function deactivate($eventID)
    {
	$event = $this->find($eventID);
	$event->active = 0;
	return $event->save();
    } 
}
