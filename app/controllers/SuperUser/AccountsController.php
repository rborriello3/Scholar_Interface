<?php

class AccountsController extends BaseController
{
    public function showUsers()
    {
        return View::make('Content.SuperUser.AccountManagement.usersManagement');
    }

    public function showUser($id)
    {
        dd($id);
    }

    public function showEditUser($id)
    {
        dd($id);
    }

    public function showActivate($id)
    {
        $role          = new Userrole();
        $data['roles'] = $role->descriptions($r = '2345');
        $data['name']  = User::where('userId', '=', $id)->get(array('name'));
        $data['id']    = $id;
        $data['month'] = array(
            ''   => 'Select Month', '01' => 'January', '02' => 'February', '03' => 'March', '04' => 'April',
            '05' => 'May', '06' => 'June', '07' => 'July', '08' => 'August', '09' => 'September', '10' => 'October',
            '11' => 'November', '12' => 'December'
        );

        return View::make('Content.SuperUser.AccountManagement.activateUser', $data);
    }

    public function doActivate($id)
    {
        $rules = array(
            'roles'   => 'Required|Required_if_in_array_digit:group,4,digit|integer_array:1',
            'monthTo' => 'Required|date_format:n', 'yearTo' => 'Required|date_format:Y', 'group' => ''
        );

        $v = Validator::make(Input::all(), $rules);

        if ($v->passes())
        {
            $password = bin2hex(openssl_random_pseudo_bytes('7'));
            $user     = User::find($id);

            if ($user->activateAccount(Input::all(), $password))
            {
                $mail = new Email($user->email, $user->name);
                $mail->sendActivationEmail($password, $user->yearTo);

                if (in_array('4', Input::get('roles')))
                {
                    $assessment = new ApplicationAssessment();
                    $assessment->newCommitteeMemberAssessment($id, Input::get('group'));
                }

                return Redirect::route('showUsers')->with('success', $user->name . ' is now active');
            }

            return Redirect::route('showUsers')->with('error', 'An error has occured while activating ' . $user->name);
        }

        return Redirect::route('showActivateUser', $id)->withInput()->withErrors($v->messages());
    }

    public function showDeactivateAccount($id)
    {
        $user = User::find($id);

        $data['userID'] = $id;
        $data['name']   = $user->name;

        return View::make('Content.SuperUser.AccountManagement.deactivateUser', $data);
    }

    public function doDeactivateAccount($id)
    {
        $rules = array('password' => 'Required',);

        $v = Validator::make(Input::all(), $rules);

        if ($v->passes())
        {
            if (Hash::check(Input::get('password'), Auth::user()->password))
            {
                $user = User::find($id);

                if ($user->deactivateUser())
                {
                    if (strpos($user->userRole, '4') !== FALSE)
                    {
                        $assessment = new ApplicationAssessment();
                        $assessment->deactivateAssessments($id);
                    }

                    return Redirect::route('showUsers')->with('success', $user->name . ' is now inactive');
                }
            }

            return Redirect::route('showDeactivateUser', $id)->with('error', 'Invalid credentials');
        }

        return Redirect::route('showDeactivateUser', $id)->withErrors($v->messages());
    }

    public function showSuperResetPW($id)
    {
        $user           = User::find($id);
        $data['userID'] = $id;
        $data['name']   = $user->name;

        return View::make('Content.SuperUser.AccountManagement.showResetPW', $data);
    }

    public function doSuperResetPW($id)
    {
        $rules = array('password' => 'Required',);

        $v = Validator::make(Input::all(), $rules);

        if ($v->passes())
        {
            ini_set('max_execution_time', 150);
            if (Hash::check(Input::get('password'), Auth::user()->password))
            {
                $user = User::find($id);

                if ($user->superUserResetPW())
                {
                    return Redirect::route('showUsers')->with('success', $user->name . ' password reset');
                }

                return Redirect::route('showSuperResetPW', $id)->with('error', 'Could not reset password');

            }

            return Redirect::route('showSuperResetPW', $id)->with('error', 'Invalid credentials');
        }

        return Redirect::route('showSuperResetPW', $id)->withErrors($v->messages())->withInput();
    }

    public function showCreateUser()
    {
        $role          = new Userrole();
        $data['roles'] = $role->descriptions($r = '2345');
        $data['month'] = array(
            ''   => 'Select Month', '01' => 'January', '02' => 'February', '03' => 'March', '04' => 'April',
            '05' => 'May', '06' => 'June', '07' => 'July', '08' => 'August', '09' => 'September', '10' => 'October',
            '11' => 'November', '12' => 'December'
        );

        return View::make('Content.SuperUser.AccountManagement.newUser', $data);
    }

    public function doCreateUser()
    {
        $rules = array(
            'name'    => 'Required|full_name|max:101', 'email' => 'Required|email|unique:user',
            'roles'   => 'Required|Required_if_in_array_digit:group,4,digit|integer_array:1',
            'monthTo' => 'Required|date_format:n', 'yearTo' => 'Required|date_format:Y', 'group' => ''
        );

        $v = Validator::make(Input::all(), $rules);

        if ($v->passes())
        {
            $password = bin2hex(openssl_random_pseudo_bytes('7'));
            $user     = new User();
            $user->createUser(Input::all(), $password);

            return Redirect::route('showUsers')->with('success', 'An account for ' . Input::get('name') . ' has been created');
        }

        return Redirect::route('showCreateUser')->withErrors($v->messages())->withInput();
    }
}