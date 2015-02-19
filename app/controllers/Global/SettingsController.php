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
        $settings = new UserSettings();
        $rules = array('user' => 'Required|email', 'password' => 'Required|password');
        $v = Validator::make(Input::all(), $rules);

        if ($v->passes())
        {
            if (Hash::check(Input::get('password'), Auth::user()->password))
            {
                if ($settings->updateSettings(Input::except('_token', 'Update Settings'), $type))
                {
                    return Redirect::route('showSettingsPage')->with('success', 'Settings have been updated');
                }

                return Redirect::route('showSettingsPage')->with('error', 'Settings could not be updated');
            }

            return Redirect::route('showSettingsPage')->with('error', 'Invalid credentials');
        }

        return Redirect::route('showSettingsPage')->withInput()->withErrors($v->messages())->with('error', 'Please provide password');
    }
}