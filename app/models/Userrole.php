<?php

class Userrole extends Eloquent
{
    /**
     * The database table
     */
    protected $table = 'userRole';

    /**
     * We don't want any default time stamps
     */
    public $timestamps = FALSE;

    /**
     * must define a specific key for our database table
     */
    protected $primaryKey = 'userRole';

    /**
     * Roles that the current user has, comes from the DB
     */
    protected $roles;

    public function descriptions($roles = '123457')
    {
        $descriptions = array();
        $array_roles  = str_split($roles);

        foreach ($array_roles as $role)
        {
            $descriptions[$role] = $this->find($role)->roleDescription;
        }

        return $descriptions;
    }


}