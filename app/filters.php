<?php

/*
|--------------------------------------------------------------------------
| Authentication Filters
|--------------------------------------------------------------------------
|
| Checks if user is logged in, if not then they will be redirected to the login page
|
*/

Route::filter('auth', function ()
{
    if (Auth::guest())
    {
        Session::flush();

        return Redirect::route('home.index')->with('error', 'You must login');
    }
});

/*
|--------------------------------------------------------------------------
| Access Filters
|--------------------------------------------------------------------------
|
| Checks if user is account is active, given a role and is effective for the
| the current aid year which means that the users yearTo is greater than the
| current month and year (mm/yyyy)
*/

Route::filter('accessRights', function ()
{
    $User = Auth::user();

    if ($User->active == 'Inactive' || $User->userRole == 1 || $User->yearTo <= date('m/Y'))
    {
        Session::flush();
        Auth::logout();

        return Redirect::route('home.index')->with('error', 'Access Denied');
    }
});

/*
|--------------------------------------------------------------------------
| CSRF Protection Filter
|--------------------------------------------------------------------------
|
| The CSRF filter is responsible for protecting your application against
| cross-site request forgery attacks. If it doesn't match, then the session
| is destroyed and will always destroy the logged in session as well. 
|
*/

Route::filter('csrf', function ()
{
    if (Request::ajax())
    {
        $token = Request::header('X-CSRF-Token');
    }
    else
    {
        $token = Input::get('_token');
    }
    
    if (Session::token() !== $token)
    {
        Auth::logout();
        Session::flush();

        return Redirect::route('home.index')->with('error', 'Invalid Security Token - Logged out');
    }
});

/*
|--------------------------------------------------------------------------
| Multi Role Filter - Only After Auth
|--------------------------------------------------------------------------
|
| Will check if the user that is *ALREADY* authenticated, has multiple roles.
| If more than 1 role, then the user will be redirected to a screen to select
| the role he or she chooses. If no more than 1 role exists nothing will happen.
| This filter is triggered only when the user goes to the dashboard. Meaning it 
| is a authenticated route / action. - MUST BE LOGGED IN and will only happen ONCE 
| per session. - The user will have the ability to switch roles on the header section
|
*/

Route::filter('multiRole', function ()
{
    $User = Auth::user();
    $role = $User->userRole;

    if (strlen($role) > 1)
    {
        Session::put('multiRoles', 1);

        if (!Session::has('role'))
        {
            return Redirect::route('showRoleSelect')->with('message', 'Please select a role below');
        }
    }
    else
    {
        Session::forget('multiRoles');
        Session::forget('roles');
        Session::put('role', $role);
    }
});

/*
|--------------------------------------------------------------------------
| First time login filter
|--------------------------------------------------------------------------
|
| Will check if the user has a NULL last login date and NULL questions + 
| answers. This will only ever trigger once in the user accounts life time
|
*/

Route::filter('firstLogin', function ()
{
    $user = Auth::user();

    if ($user->updateAcc == 1)
    {
        return Redirect::route('showFirstLogin')->with('error', 'You must update your account before using Scholarship Interface');
    }

});

/*
|--------------------------------------------------------------------------
| reCaptcha Filter - Checks if Captcha is active
|--------------------------------------------------------------------------
|
| Will check if the user has a NULL last login date and NULL questions + 
| answers. This will only ever trigger once in the user accounts life time
|
*/

Route::filter('reCaptcha', function ()
{
    /*if ($_SERVER['SERVER_NAME'] == 'schol.sunyorange.edu')
    {
        $privateKey = '6LdGXPESAAAAAId0HaJhi0G-uRvYN34v-QA96-5u';
    }
    elseif ($_SERVER['SERVER_NAME'] == 'schol.occc')
    {
        $privateKey = '6Lf1VvESAAAAAKu5ab6s7Oxv3aoytZGdqtgjEnYO';
    }
    */
   
    $privateKey = '6LdZTPESAAAAAM4g63snTgVyc1HkQnSSvYN24PsW';
    $_SERVER["REMOTE_ADDR"];
    $challenge = Input::get('recaptcha_challenge_field');
    $input     = Input::get('recaptcha_response_field');

    $cap = Recaptcha::recaptcha_check_answer($privateKey, $_SERVER["REMOTE_ADDR"], $challenge, $input);

    if (!$cap->is_valid)
    {
        return Redirect::route(Route::currentRouteName())->withInput()->with('error', 'Invalid Captcha');
    }
});

/*
|--------------------------------------------------------------------------
| Token passes - Checks if a sent token is active
|--------------------------------------------------------------------------
| Checks if token is valid time, format, and length wise. Used mostly
| for password reset, but that could change.
| 
| Note : If token does not match the validation nothing can be done to remove
| the token value from the DB since the token value needs the proper token
| Also if the token is a mismatch it still can't be removed since we base
| the query ($user) on the actual token. The token will only be set to NULL
| when the time is invalid.
|
*/

Route::filter('tokenPass', function ($route)
{
    $token = urldecode($route->getParameter('token'));

    if (strlen($token) == 256 && ctype_alnum($token))
    {
        $user = User::where('token', '=', $token)->get();

        if (!$user->isEmpty())
        {
            $tokenTime = strtotime($user[0]->tokenTime);
            $nowTime   = strtotime("now");

            if ($nowTime - $tokenTime < 300)
            {
                return;
            }

            $user[0]->token     = NULL;
            $user[0]->tokenTime = NULL;
            $user[0]->save();
            Session::flush();
        }

        Session::flush();
    }

    return Redirect::route('home.index')->with('error', 'Invalid token');
});

/*
|--------------------------------------------------------------------------
| cellToken - Checks if a sent token is active only used for resetting PW
|--------------------------------------------------------------------------
| Validates sent cell phone token. Requires an email input / email in the
| session or for the user to be logged in so that we can check the token 
| (there is no other way) other than storing the token on client, which we will never do.
| We if the above filter fails the token for the cell phone won't be run 
| which means the token won't be removed. But that doesn't matter because
| when ever a cell phone token is needed it will be run through this filter
| which in turn will be a "garbage clearner"
|
*/
Route::filter('cellToken', function ()
{
    $cellToken = Input::get('cellToken');

    if (Session::has('email'))
    {
        $email    = Session::get('email');
        $user     = User::where('email', '=', $email)->get(array('userId'));
        $userId   = $user[0]->userId;
        $loggedIn = false;
    }
    elseif (Input::has('email'))
    {
        $email    = Input::get('email');
        $user     = User::where('email', '=', $email)->get(array('userId'));
        $userId   = $user[0]->userId;
        $loggedIn = false;

    }
    elseif (Auth::check())
    {
        $userId   = Auth::user()->userId;
        $loggedIn = true;
    }
    else
    {
        return Redirect::route('home.index')->with('error', 'Unable to process mobile token');
    }

    $cell      = Usercellphone::find($userId);
    $dbToken   = $cell->cellToken;
    $tokenTime = strtotime($cell->cellTokenTime);
    $nowTime   = strtotime("now");

    if (strlen($cellToken) == 6 && ctype_alnum($cellToken))
    {
        if ($dbToken === $cellToken)
        {
            if ($nowTime - $tokenTime < 300)
            {
                $cell->cellToken     = NULL;
                $cell->cellTokenTime = NULL;
                $cell->save();

                return;
            }
        }
    }

    $cell = new Text($loggedIn);
    if ($cell->sendCode())
    {
        Session::put('wrongCellToken', 1);
    }
});

/*
|--------------------------------------------------------------------------
| user - checks if user has proper permission to view page
|--------------------------------------------------------------------------
| 2-> super user 3-> finacial aid admin 4-> commiitee 5-> Upper management
| 6-> Scholarship Admin
| checks if $value that is passed in matches to the value of the role
|
*/
Route::filter('user', function ($route, $request, $value)
{
    if (Session::get('role') !== $value)
    {
        return Redirect::route('showDashboard')->with('error', 'You do not have access to view that page');
    }
});

/*
|--------------------------------------------------------------------------------
| appGUID - checks if URL GUID is correctly formatted. If not application ends
|--------------------------------------------------------------------------------
*/

Route::filter('appGUID', function ($route)
{
    $GUID = urldecode($route->getParameter('guid'));

    if ($GUID)
    {
        if (!ctype_alnum($GUID) || strlen($GUID) != 50)
        {
            return Redirect::route('endApplication');
        }

        else
        {
            if (Application::where('GUID', '=', $GUID)->count() != 1)
            {
                return Redirect::route('showApplications')->with('error', 'No record found for that application');
            }
        }
    }

    return;
});

/*
|--------------------------------------------------------------------------------
| cellCheck - checks if cell phone is verified
|--------------------------------------------------------------------------------
|*/

Route::filter('cellCheck', function ()
{
    $userID    = Auth::user()->userId;
    $cellPhone = Usercellphone::find($userID);

    if ($cellPhone && $cellPhone->verified == 0)
    {
        $verify = Form::open(array('url' => route('doCellVerify', array()), 'method' => 'POST', 'accept-charset' => 'UTF-8'));
        $verify .= 'Please verify Cell Phone Number by entering your token: ' . Form::text('cellToken', '', array('placeholder' => '123456', 'autocomplete' => 'off', 'maxlength' => '6', 'style' => 'width: 60px;'));
        $verify .= '&nbsp;&nbsp;' . Form::submit('Verify Token') . ' ' . link_to_route('doResendNewCode', 'Request New Code', $parameters = array(), $attributes = array('alt' => 'newCode', 'title' => 'Send New Code'));
        $verify .= '&nbsp;&nbsp;' . link_to_route('doDeleteCell', 'Delete Cell Phone', $parameters = array(), $attributes = array('alt' => 'deleteCell', 'title' => 'Delete Cell Phone'));
        $verify .= Form::close();

        return Session::flash('formMessage', $verify);
    }

    return;

});

/*
|--------------------------------------------------------------------------------
| anum - checks if url parameter is a valid A-Number
|--------------------------------------------------------------------------------
|*/

Route::filter('studentID', function ($route)
{
    $anum = $route->getParameter('studentID');

    if (strlen($anum) == 9 || strtolower($anum[0]) === 'a' || ctype_digit(substr($anum, 1, 8)))
    {
        if (Student::where('studentID', '=', $anum)->count() == 1)
        {
            return;
        }
    }

    return Redirect::back()->with('error', 'Student does not exist');
});

/*
|--------------------------------------------------------------------------------
| FundCode - Checks to see if all the funds codes in the URL are legal!
|--------------------------------------------------------------------------------
|*/

Route::filter('fundCode', function($route)
{
    $fundCode = $route->getParameter('fundCode');
    $schols = Scholarships::find($fundCode);

    if (ctype_alpha($fundCode[0]) && strlen($fundCode) == 4 && ctype_digit(substr($fundCode, 1, 3)) && $schols)
    {
        return;
    }

    return Redirect::route('showAllScholarships')->with('error', 'Invalid Fund Code detected');
});

/*
|--------------------------------------------------------------------------------
| Settings Update
|--------------------------------------------------------------------------------
|*/
Route::filter('settingsUpdate', function($route)
{
    $type = $route->getParameter('type');
    $roles = Session::get('roles');

    if (! array_key_exists($type, $roles))
    {
        return Redirect::route('showSettingsPage')->with('error', 'Invalid Role');
    }
});
