<?php

class SessionController extends BaseController
{
    public function doCreate()
    {
        $rules = array('user' => 'Required|email', 'password' => 'Required|password');

        $v = Validator::make(Input::all(), $rules);

        if ($v->passes())
        {
            $credentials = array('email' => Input::get('user'), 'password' => Input::get('password'));

            if (Auth::attempt($credentials))
            {
                // This will update the time stamp of the last login of the authenticated user
                $user            = User::find(Auth::user()->userId);
                $user->lastLogin = new dateTime();
                $user->save();
                //$check = new User();
                //$check->updateYearsActive(Auth::user()->userId);

                return Redirect::route('showDashboard');
            }

            return Redirect::route('home.index')->with('error', 'Invalid Credentials');
        }

        return Redirect::route('home.index')->withInput()->withErrors($v->messages());
    }

    public function doLogout()
    {
        Auth::logout();
        Session::flush();

        return View::make('Content.Global.Authentication.logout');
    }
}
