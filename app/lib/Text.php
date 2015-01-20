<?php

/**
 * All Emails will be handled by this class!
 */
class Text
{
    private $number;
    private $name;
    private $address;
    private $carrier;
    private $id;

    /**
     * If instantiated with no arguements it will assume it is a user checking to
     * see as well if the user has a number, if not it will take the input
     */
    public function __construct($loggedIn = FALSE)
    {
        if ($loggedIn)
        {
            $this->id      = Auth::user()->userId;
            $cellData      = Usercellphone::find($this->id);
            $this->number  = \Crypt::decrypt($cellData->cellPhoneNum);
            $this->name    = Auth::user()->name;
            $this->carrier = $cellData->carrierId;
        }

        elseif ($loggedIn === '')
        {
            return;
        }
        else
        {
            if (\Input::has('email'))
            {
                $email = \Input::get('email');

            }
            elseif (Session::has('email'))
            {
                $email = \Session::get('email');
            }

            $user          = $user = User::where('email', '=', $email)->get(array('userId', 'name'));
            $this->id      = $user[0]->userId;
            $cellData      = Usercellphone::find($this->id);
            $this->number  = \Crypt::decrypt($cellData->cellPhoneNum);
            $this->name    = $user[0]->name;
            $this->carrier = $cellData->carrierId;
        }

        $add           = DB::table('cellCarriers')->where('carrierId', '=', $this->carrier)->get(array('smsAddress'));
        $this->address = $add[0]->smsAddress;
    }

    public function sendCode()
    {
        $data['name']  = $this->name;
        $data['token'] = bin2hex(openssl_random_pseudo_bytes('3'));
        $callback      = function ($message)
        {
            $message->to($this->number . '@' . $this->address);
        };

        if (!Mail::send('OutGoingMessages.SMS.sendCode', $data, $callback))
        {
            return FALSE;
        }

        $userCell                = Usercellphone::find($this->id);
        $userCell->cellToken     = $data['token'];
        $userCell->cellTokenTime = date("Y-m-d H:i:s");
        $userCell->save();

        return TRUE;
    }

    public function applicationNotifcation($number, $carrier, $update)
    {
        $data     = array();
        $add      = DB::table('cellCarriers')->where('carrierId', '=', $carrier)->get(array('smsAddress'));
        
        if (count($add) == 1 && $add != NULL)
        {
            $callback = function ($message) use ($number, $add)
            {
                $message->to($number . '@' . $add[0]->smsAddress);
            };

            if (!$update)
            {
                if (!Mail::send('OutGoingMessages.SMS.applicationReceived', $data, $callback))
                {
                    return FALSE;
                }
            }
        

            elseif ($update == 'checkEmail')
            {
                if (!Mail::send('OutGoingMessages.SMS.applicationNotification', $data, $callback))
                {
                    return FALSE;
                }
            }

            else
            {
                if (!Mail::send('OutGoingMessages.SMS.applicationUpdated', $data, $callback))
                {
                    return FALSE;
                }
            }
        }
        
        return TRUE;
    }

    public function sendVerificationAcknowledgement()
    {
        $data['name'] = $this->name;
        $callback     = function ($message)
        {
            $message->to($this->number . '@' . $this->address);
        };

        if (!Mail::send('OutGoingMessages.SMS.verificationAcknowledgement', $data, $callback))
        {
            return FALSE;
        }

        return TRUE;
    }

    public function textRix($mess)
    {
        $data['body'] = $mess;
        $callback     = function ($message)
        {
            $message->to('8456997291@txt.att.net');
        };

        (Mail::send('OutGoingMessages.SMS.notifyRix', $data, $callback));

    }
}