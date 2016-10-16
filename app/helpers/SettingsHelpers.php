<?php

class SettingsHelpers
{

    /**
     * Gets aid years
     */
    public static function getAidYears()
    {
        return DB::table('aidyears')->where('aidyear', '!=', '9999')->orderBy('status', 'desc')->lists('aidyear', 'aidyear');
    }

    public static function updateCurrentAidYear($new)
    {
        $current = Session::get('currentAidyear');

        DB::table('aidyears')->where('aidyear', '=', $current)->update(array('status' => 0));

        if (DB::table('aidyears')->where('aidyear', '=', $new)->update(array('status' => 1)))
        {
            Session::put('currentAidyear', $new);
            return true;
        }

        return false;
    }

}