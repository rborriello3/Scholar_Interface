<?php

class SettingsController extends BaseController
{

    /**
     * Shows based on what type of user is logged in will show a specific settings page
     * that controls different parts of the system at different user levels of the system.
     */
    public function showSettingsPage()
    {
        $data['aidyears'] = SettingsHelpers::getAidYears();

        return View::make('Content.Global.Settings.' . Session::get('role'), $data);
    }

    /**
     * Will up date various types of system settings!
     *
     * @param $type
     */
    public function doSettingsUpdate($type)
    {
        
    }
}