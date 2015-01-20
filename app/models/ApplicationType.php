<?php

class ApplicationType extends Eloquent
{
    /**
     * The database table
     */
    protected $table = 'applicationType';

    /**
     * We don't want any default time stamps
     */
    public $timestamps = FALSE;

    /**
     * must define a specific key for our database table
     */
    protected $primaryKey = 'typeID';

    public function getAll($all = TRUE, $type = '')
    {
        if ($all)
        {
            $types = $this->all();
            foreach ($types as $t)
            {

                $return[$t->typeID] = $t->typeName;
            }
        }

        else
        {
            $types = str_split($type);
            foreach ($types as $t)
            {
                $return[$t] = $this->find($t)->typeName;
            }
        }

        return $return;
    }


}