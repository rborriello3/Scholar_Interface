<?php

class Aidyear extends Eloquent
{
    /**
     * The database table
     */
    protected $table = 'aidyears';

    /**
     * We don't want any default time stamps
     */
    public $timestamps = FALSE;

    /**
     * must define a specific key for our database table
     */
    protected $primaryKey = 'aidyear';

    public function getAll($active = TRUE)
    {
        if ($active)
        {
            $years = $this->where('status', '=', '1')->get();
            foreach ($years as $y)
            {
                $return[$y->aidyear] = $y->aidyear;
            }
        }

        else
        {
            $years = $this->all();

            foreach ($years as $y)
            {
                if ($y->aidyear != '9999')
                {
                    $return[$y->aidyear] = $y->aidyear;
                }
            }
        }

        return $return;
    }

    public function getCurrentAidyear()
    {
        $aidyear = $this->where('status', '=', '1')->get(array('aidyear'));
        return $aidyear[0]->aidyear;
    }
}