<?php

class FirstLoginController extends BaseController
{
    public function showFirstLoginUpdate()
    {
        $data['quesGroup1'] = array_add(DB::table('questions')->where('active', '=', 1)->take(4)->lists('ques', 'id'), '', 'Select First Question');
        $data['quesGroup2'] = array_add(DB::table('questions')->where('active', '=', 1)->take(4)->skip(4)->lists('ques', 'id'), '', 'Select Second Question');
        $data['carrier']    = array_add(DB::table('cellCarriers')->where('carrierId', '!=', 0)->lists('carrier', 'carrierId'), '', 'Select Cell Carrier');

        return View::make('Content.Global.AccountManagement.firstLoginUpdate', $data);
    }

    public function doFirstLoginUpdate()
    {
        $rules = array('password'    => 'Required|password|confirmed', 'password_confirmation' => 'Required',
                       'ques1'       => 'Required|integer', 'ques2' => 'Required|integer',
                       'answ1'       => 'Required|date_format:Y', 'answ2' => 'Required|alpha_space_dash',
                       'cellnotify'  => 'numeric|digits:1|max:2', 'cellPhone' => 'required_if:cellnotify,1|phone',
                       'cellCarrier' => 'required_if:cellnotify,1|integer|max:16'
        );

        $v = Validator::make(Input::all(), $rules);

        if ($v->passes())
        {
            $user = User::find(Auth::user()->userId);
            $user->firstLoginUpdate(Input::all());

            if (Input::get('cellnotify') == 1)
            {
                $cellPhone = new Usercellphone();
                $cellPhone->newEntry(Input::get('cellPhone'), Input::get('cellCarrier'));
                $text = new Text($loggedIn = TRUE);
                $text->sendCode();
            }

            $email = new Email();

            if ($email->firstLoginUpdate())
            {
                return Redirect::route('showDashboard')->with('success', 'Account updated, you can make changes at any time');
            }

            return Redirect::route('session.logout');
        }

        return Redirect::route('showFirstLogin')->withInput()->withErrors($v->messages());
    }
}