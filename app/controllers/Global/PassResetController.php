<?php

class PassResetController extends BaseController
{
    public function showEmailStep()
    {
        Session::forget('email');
        /* if ($_SERVER['SERVER_NAME'] == 'schol.sunyorange.edu')
         {
             $publicKey = '6LdGXPESAAAAABaeqxBZ5gdq2dfU9iCXqbvADClE';
         }
         elseif ($_SERVER['SERVER_NAME'] == 'schol.occc')
         {
             $publicKey = '6Lf1VvESAAAAAJ3oFgMtAEvyITTsOOJ8w2ZKDqqE';
         }
         */

        $publicKey       = '6LdGXPESAAAAABaeqxBZ5gdq2dfU9iCXqbvADClE';
        $data['captcha'] = Recaptcha::recaptcha_get_html($publicKey, NULL, TRUE);

        return View::make('Content.Global.ResetPassword.validation', $data);
    }

    public function doEmail()
    {
        $rules = array('email' => 'Required|email');

        $v = Validator::make(Input::all(), $rules);

        if ($v->passes())
        {
            Session::put('email', Input::get('email'));

            return Redirect::route('password.reset.showQuestions');
        }

        return Redirect::route('password.EmailStep')->withInput()->withErrors($v->messages());

    }

    public function showQuestions()
    {
        if (Session::has('email'))
        {
            $user = User::where('email', '=', Session::get('email'))->get();

            if (count($user) == 1)
            {
                if ($user[0]->ques1 && $user[0]->ques2 && $user[0]->status != 'Inactive' && $user[0]->userRole != 1)
                {
                    $data['questions'] = DB::table('questions')->select('ques')->where('id', '=', $user[0]->ques1)->orWhere('id', '=', $user[0]->ques2)->get();

                    $cellPhone = Usercellphone::find($user[0]->userId);

                    if (count($cellPhone) == 1 && $cellPhone->verified == 1)
                    {
                        $data['number'] = $cellPhone->getHiddenNum();
                    }

                    return View::make('Content.Global.ResetPassword.questions', $data);
                }

                return Redirect::route('home.index')->with('error', 'This account can not be recovered at this moment');
            }

            return Redirect::route('password.EmailStep')->with('error', 'Email not found');
        }

        Session::flush();

        return Redirect::route('password.EmailStep')->with('error', 'You must validate email');
    }

    public function doQuestions()
    {
        $rules = array(
            'answ1'    => 'Required|date_format:Y', 'answ2' => 'Required|alpha_space_dash',
            'cellCode' => 'numeric|digits:1'
        );

        $v = Validator::make(Input::all(), $rules);

        if ($v->passes())
        {
            if (Session::has('email'))
            {
                $user    = User::where('email', '=', Session::get('email'))->get();
                $answer1 = Hash::check(Input::get('answ1'), $user[0]->answ1);
                $answer2 = Hash::check(Input::get('answ2'), $user[0]->answ2);

                if ($answer1 && $answer2)
                {
                    $token              = bin2hex(openssl_random_pseudo_bytes('128'));
                    $tokenTime          = date("Y-m-d H:i:s");
                    $user[0]->token     = $token;
                    $user[0]->tokenTime = $tokenTime;

                    if (Input::get('cellCode') == 1)
                    {
                        $cell = new Text($loggedIn = FALSE);
                        if ($cell->sendCode())
                        {
                            $user[0]->save();

                            return Redirect::route('password.reset.showCellPhone', array($token));
                        }

                        return Redirect::route('home.index')->with('Error', 'Could not send message, notify Super User');
                    }

                    $email = new Email(Session::get('email'), $user[0]->name);

                    if ($email->sendResetSteps($token))
                    {
                        $user[0]->save();

                        return Redirect::route('home.index')->with('success', 'Reset steps have been sent');
                    }

                    Session::flush();

                    return Redirect::route('home.index')->with('Error', 'Could not send message, notify Super User');
                }

                return Redirect::route('password.reset.showQuestions')->withInput()->with('error', 'Invalid Answer (s)');
            }

            Session::flush();

            return Redirect::route('password.EmailStep')->with('error', 'You must validate email');
        }

        return Redirect::route('password.reset.showQuestions')->withInput()->withErrors($v->messages());
    }

    // the cell phone functions bellow will only be triggered if
    // the user decides to use another communication channel (a cell phone)
    // this is not default behavior!!

    public function showCellPhoneReset($token)
    {
        if (Session::has('email'))
        {
            $data['token'] = $token;

            return View::make('Content.Global.ResetPassword.cellPhoneCode', $data);
        }

        Session::flush();

        return Redirect::route('password.EmailStep')->with('error', 'You must validate email');
    }

    public function doCellPhoneReset($token)
    {
        if (Session::has('email'))
        {
            // We only need to check the token which is done in the filter,
            // so just redirect to get rid of the refresh form submission
            if (Session::get('wrongCellToken') == 1)
            {
                Session::forget('wrongCellToken');

                return Redirect::route('password.reset.showCellPhone', array($token))->with('error', 'Token mismatch, another text message sent');
            }

            return Redirect::route('password.reset.showUpdate', array($token))->with('success', 'Cell phone token is valid - update password bellow');
        }

        Session::flush();

        return Redirect::route('password.EmailStep')->with('error', 'You must validate email');
    }

    public function showUpdate($token)
    {
        $data['token'] = $token;

        return View::make('Content.Global.ResetPassword.updatePassword', $data);
    }

    public function doUpdate($token)
    {
        $rules = array('password' => 'Required|password|confirmed', 'password_confirmation' => 'Required');

        $v = Validator::make(Input::all(), $rules);

        if ($v->passes())
        {
            $user               = User::where('token', '=', $token)->get();
            $user[0]->password  = Hash::make(Input::get('password'), array('rounds' => 17));
            $user[0]->token     = NULL;
            $user[0]->tokenTime = NULL;

            $email = new Email($user[0]->email, $user[0]->name);
            if ($email->passwordResetConfirm())
            {
                $user[0]->save();
                Session::flush();

                return Redirect::route('home.index')->with('success', 'Password reset');
            }

            Session::flush();

            return Redirect::route('home.index')->with('error', 'Password could not be updated');
        }

        return Redirect::route('password.reset.showUpdate', $token)->withErrors($v->messages());
    }
}
