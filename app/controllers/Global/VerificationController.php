<?php

class VerificationController extends BaseController
{
    public function doCellVerify()
    {
        $rules = array('cellToken' => 'Required|alpha_num');

        $v = Validator::make(Input::all(), $rules);

        if ($v->passes())
        {
            $cell = Usercellphone::find(Auth::user()->userId);

            if ($cell->cellToken === Input::get('cellToken'))
            {
                $tokenTime = strtotime($cell->cellTokenTime);
                $nowTime   = strtotime("now");

                if ($nowTime - $tokenTime < 300)
                {
                    $cell->cellToken     = NULL;
                    $cell->cellTokenTime = NULL;
                    $cell->verified      = 1;
                    $cell->save();
                    $c = new Text($loggIn = TRUE);
                    $c->sendVerificationAcknowledgement();

                    return Redirect::route('showDashboard')->with('success', 'You have verified your mobile information!');
                }

                $c = new Text($loggedIn = TRUE);
                $c->sendCode();

                return Redirect::route('showDashboard')->with('error', 'Token expiered, new token sent!');
            }

            $c = new Text($loggedIn = TRUE);
            $c->sendCode();

            return Redirect::route('showDashboard')->with('error', 'Invalid token, new token sent!');
        }
    }

    public function doNewCellCode()
    {
        $c = new Text($loggedIn = TRUE);
        $c->sendCode();

        return Redirect::route('showDashboard')->with('success', 'New token sent.');
    }

    public function doDeleteCell()
    {
        $c = Usercellphone::find(Auth::user()->userId);
        $c->delete();

        return Redirect::route('showDashboard')->with('success', 'Mobile information deleted, this can be undone in your ' . link_to_route('showDashboard', 'profile', $parameters = array(), $attributes = array('alt'   => 'userProfile',
                                                                                                                                                                                                                  'title' => 'Profile'
            )));
    }
}