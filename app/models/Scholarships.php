<?php

class Scholarships extends Eloquent
{
    /**
     * The database table
     */
    protected $table = 'scholarships';

    /**
     * We don't want any default time stamps
     */
    public $timestamps = FALSE;

    /**
     * must define a specific key for our database table
     */
    protected $primaryKey = 'fundCode';

    /**
     * Updates the scholarships in the table
     */
    public function updateScholarship($fundCode, $values)
    {
        $this->find($fundCode);
        $values['appTypes'] = implode(',', $values['appType']);
        $values = array_except($values, array('_token', 'appType'));
        return $this->where('fundCode', '=', $fundCode)->update($values);
    }

    /**
     * Creates a new scholarship
     */
    public function createScholarship($values)
    {
        $values['appTypes'] = implode(',', $values['appTypes']);
        $values = array_except($values, array('_token'));
        $this->insert($values);
    }

    /**
     * As name suggests this function will activate the scholarship
     *
     * @param $fundCode
     * @return boolean
     */
    public function activate($fundCode)
    {
        $up = $this->find($fundCode);
        $up->active = 1;
        return $up->save();
    }

    /**
     * As name suggests this function will deactivate the scholarship
     *
     * @param $fundCode
     * @return boolean
     */
    public function deactivate($fundCode)
    {
        $up = $this->find($fundCode);
        $up->active = 0;
        return $up->save();
    }

}