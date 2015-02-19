<?php

class UserSettings extends Eloquent
{
    /**
     * The database table
     */
    protected $table = 'userSettings';

    /**
     * We don't want any default time stamps
     */
    public $timestamps = FALSE;

    /**
     * must define a specific key for our database table
     */
    protected $primaryKey = 'userId';

    public function updateSettings($values, $type)
    {
        foreach($values as $k => $v)
        {
            if ($k == 'aidyear' && $type == '2')
            {
                return SettingsHelpers::updateCurrentAidYear($v);
            }
        }
    }
}