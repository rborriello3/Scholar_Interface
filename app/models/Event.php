<?php

class Event extends Eloquent
{
    /**
     * The database table
     */
    protected $table = 'event';

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
    public function createEvent($values)
    {
        $values['gradeGroup'] = implode(',', $values['gradeGroup']);
	$values['active'] = 1;
        $this->insert($values);
    }

    /**
    * Updates an event
    */
    public function updateEvent($eventID, $values)
    {
        $this->find($eventID);
        $values['gradeGroup'] = implode(',', $values['gradeGroup']);
	$values = array_except($values, array('active'));
        return $this->where('eventID', '=', $eventID)->update($values);
    }

    /**
    * Activates an event
    */
    public function activate($eventID)
    {
        $event = $this->find($fundCode);
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
