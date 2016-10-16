<?php

class Processes extends Eloquent
{
    /**
     * The database table
     */
    protected $table = 'automaticProcesses';

    /**
     * We don't want any default time stamps
     */
    public $timestamps = false;

    /**
     * must define a specific key for our database table
     */
    protected $primaryKey = 'jobID';

    /**
     * Creates new process with status of Uninitialized and checks if there already is a process that matches the name in
     * the database.
     *
     * @param array $values
     *
     */
    public function newProcess($values)
    {
        foreach ($values as $k => $v)
        {
            if ($k == 'days')
            {
              $this->$k = implode(',', $v);
              unset($values[$k]);
            }
            else
            {
               $this->$k = $v;
            }
        }

        $this->save();
    }

}