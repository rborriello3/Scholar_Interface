<?php

class Gradegroup extends Eloquent
{
    /**
     * The database table
     */
    protected $table = 'gradeGroup';

    /**
     * We don't want any default time stamps
     */
    public $timestamps = FALSE;

    /**
     * must define a specific key for our database table
     */
    protected $primaryKey = 'gradeGroup';

    /**
     * Roles that the current user has, comes from the DB
     */
    protected $grades;

   public function descriptions($grades = '1246')
    {
        $descriptions = array();
        $array_grades  = str_split($grades);

        foreach ($array_grades as $group)
        {
            $descriptions[$group] = $this->find($group)->groupDescription;
        }

        return $descriptions;
    }
}
