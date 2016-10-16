<?php

class StudentDemo extends Eloquent
{
    /**
     * The database table
     */
    protected $table = 'studentDemographics';

    /**
     * We don't want any default time stamps
     */
    public $timestamps = false;

    /**
     * must define a specific key for our database table
     */
    protected $primaryKey = 'studentID';

    public function insertDemographics($values, $studentID = null)
    {
        $studentInfo = array_except($values, array('_token', 'selecttype', 'GUID', 'type'));

        if ($studentID == null)
        {
            $studentInfo = array_add($studentInfo, 'studentID', Session::get('studentID'));
            $student     = $this->find(Session::get('studentID'));
        }
        else
        {
            $studentInfo = array_add($studentInfo, 'studentID', $studentID);
            $student     = $this->find($studentID);
        }

        if (count($student) == 1)
        {
            foreach ($studentInfo as $k => $v)
            {
                if ($v !== '')
                {
                    if ($student->$k !== $v)
                    {
                        $student->$k = $v;
                    }
                }
            }

            $student->save();
        }

        else
        {
            foreach ($studentInfo as $k => $v)
            {
                if ($v !== '')
                {
                    $this->$k = $v;
                }
            }

            $this->save();
        }
    }
}