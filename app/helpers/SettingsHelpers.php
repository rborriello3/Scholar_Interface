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

}