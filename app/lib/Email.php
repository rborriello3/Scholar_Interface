<?php

/**
 * All Emails will be handled by this class!
 */
class Email
{
    private $email;
    private $name;

    /**
     * Defualt will be null, however it accepts arrays or just single emails
     */
    public function __construct($email = NULL, $name = NULL)
    {
        if (is_null($email))
        {
            $this->email = \Input::get('email');
        }
        else
        {
            $this->email = $email;
        }

        if (is_null($name))
        {
            $this->name = \Input::get('name');
        }
        else
        {
            $this->name = $name;
        }
    }

    public function sendRegisterMail()
    {
        $data['name'] = $this->name;
        $userId = DB::table('user')->where('email', '=', $this->email)->get(array('userId'));

        $callback = function ($message)
        {
            $message->to($this->email)->subject('Welcome to ScholarInterface');
        };

        if (Mail::send('OutGoingMessages.Emails.registerEmail', $data, $callback))
        {
            Event::fire('notification', array($data['name'] . ' created a account', 11, 1, $userId[0]['userId'], null, null, 'Welcome to ScholarInterface'));
            return TRUE;
        }
    }

    public function notifyAdminRegister()
    {
        $data['name']  = $this->name;
        $data['email'] = $this->email;
        $info          = DB::table('user')->select('email', 'name', 'userId')->where('userRole', 'LIKE', '%2%')->where('status', '=', 'Active')->get();

        foreach ($info as $inf)
        {
            $data['adminName'] = $inf->name;
            $callback          = function ($message) use ($inf)
            {
                $message->to($inf->email)->subject($this->name . ' account registration');
            };

            if (!Mail::send('OutGoingMessages.Emails.notifyAdminRegister', $data, $callback))
            {
                return FALSE;
            }

            Event::fire('notification', array($data['name'] . ' has just created an account', 12, 1, $info[0]['userId'], null, null, $this->name . ' account registration'));
        }

        return TRUE;
    }

    public function firstLoginUpdate()
    {
        $info         = DB::table('user')->select('email', 'name')->where('userId', '=', Auth::user()->userId)->get();
        $data['name'] = $info[0]->name;

        $callback = function ($message) use ($info)
        {
            $message->to($info[0]->email)->subject('First login attempt successful!');
        };

        if (!Mail::send('OutGoingMessages.Emails.firstLoginUpdate', $data, $callback))
        {
            return FALSE;
        }

        Event::fire('notification', array('User has updated their account info', 10, 1, Auth::user()->userId, null, null, $this->name . ' account update'));

        return TRUE;
    }

    public function sendResetSteps($token)
    {
        $data['token'] = $token;
        $data['name']  = $this->name;
        $userId = DB::table('user')->where('email', '=', $this->email)->get(array('userId'));

        $callback = function ($message)
        {
            $message->to($this->email)->subject('Scholarship Interface Password Reset');
        };

        if (!Mail::send('OutGoingMessages.Emails.passwordReset', $data, $callback))
        {
            return FALSE;
        }

        Event::fire('notification', array('User has requested a password reset', 10, 1, $userId[0]->userId, null, null, $this->name . ' password reset'));

        return TRUE;
    }

    public function passwordResetConfirm()
    {
        $data['name'] = $this->name;
        $userId = DB::table('user')->where('email', '=', $this->email)->get(array('userId'));

        $callback = function ($message)
        {
            $message->to($this->email)->subject('Scholarship Interface Password Updated');
        };

        if (!Mail::send('OutGoingMessages.Emails.passwordUpdateConfirm', $data, $callback))
        {
            return FALSE;
        }

        Event::fire('notification', array('User has updated their account info', 10, 1, $userId[0]->userId, null, null, $this->name . ' password reset conformation'));

        return TRUE;
    }

    public function sendActivationEmail($password, $yearTo)
    {
        $data['name']     = $this->name;
        $data['password'] = $password;
        $data['yearTo']   = $yearTo;
        $data['email']    = $this->email;

        $userId = DB::table('user')->where('email', '=', $data['email'])->get(array('userId'));

        $callback = function ($message)
        {
            $message->to($this->email)->subject('Scholarship Interface Account Activated');
        };

        if (!Mail::send('OutGoingMessages.Emails.activationEmail', $data, $callback))
        {
            return FALSE;
        }

        Event::fire('notification', array('User was sent activation information', 11, Auth::user()->userId, $userId[0]->userId, null, null, $this->name . ' account activation'));

        return TRUE;
    }

    /**
     * Sends an email to their unofficial email to look at their @sunyorange.edu email
     *
     * @param string $emailAddress
     * @param string $first
     * @param string $last
     */
    public function emailSentToSUNY($emailAddress, $first, $last)
    {
        $studentID       = Student::where('personalEmail', '=', $emailAddress)->get(array('studentID'));
        $callback        = function ($message) use ($emailAddress)
        {
            $message->to($emailAddress)->subject('SUNY Orange Scholarship Information');
        };

        $data['body'] = "Hello <strong>" . $first . " "  . $last . "</strong>,<br/>
        <p>An email concerning your SUNY Orange scholarship application has
        been sent to your official SUNY Orange email. If this message was sent to your
        official <b>@sunyorange.edu</b> email than please disregard this email and continue
        to the other email sent via <b>Scholarship Interface</b>.</p>";

        if (Mail::send('OutGoingMessages.Emails.lookAtOfficialEmail', $data, $callback));
        {
            Event::fire('notification', array($data['body'], 6, Auth::user()->userId, null, $studentID[0]->studentID, null, 'SUNY Orange Scholarship Information'));
        }
    }

    /**
     * If called from the application Controller / model than it will send the email to what ever email was entered in.
     * If called from the cron jobs, it will send to their official SUNY Orange Email.
     *
     * @param $emailAddress
     * @param $first
     * @param $last
     * @param $update
     *
     * @return bool
     */
    public function completedApplication($emailAddress, $first, $last, $update)
    {
        $studentID = Student::where('personalEmail', '=', $emailAddress)->get(array('studentID'));

        if (count($studentID) == 0)
        {
            $studentID = Student::where('sunyEmail', '=', $emailAddress)->get(array('studentID'));
        }

        $callback = function ($message) use ($emailAddress)
        {
            $message->to($emailAddress)->subject('SUNY Orange Scholarship Information');
        };

        if (!$update)
        {
            $data['body'] = "<h3>Hello $first $last,</h3>
                            <p>Your scholarship application has been received in full including the
                            </b>first two</b> recommendations that have beencompleted by your professors.</p>
                            <p>Your application now qualifies for review by the scholarship committee.
                            You will be further notified when the scholarship committee has fully completed
                            scoring your application.</p> <p>If any questions arise about your application or
                            how the process works please do not hesitate to call the financial
                            aid office at: <b>845-341-4190</b></p>";

            if (!Mail::send('OutGoingMessages.Emails.completeApplication', $data, $callback))
            {
                return FALSE;
            }

            Event::fire('notification', array($data['body'], 7, Auth::user()->userId, null, $studentID[0]->studentID, null, 'SUNY Orange Scholarship Information'));
        }

        else
        {
            $data['body'] = "<h3>Hello $first $last,</h3> <p>Your scholarship application
                            is complete as of now due to receiving both recommendations from your professors.</p>
                            <p>Your application now qualifies for review by the scholarship committee.
                            You will be further notified when the scholarship committee has fully completed
                            scoring your application.</p> <p>If any questions arise about your application
                            or how the process works please do not hesitate to call the financial
                            aid office at: <b>845-341-4190</b></p>";

            if (!Mail::send('OutGoingMessages.Emails.updatedApplication', $data, $callback))
            {
                return FALSE;
            }

            Event::fire('notification', array($data['body'], 9, Auth::user()->userId, null, $studentID[0]->studentID, null, 'SUNY Orange Scholarship Information'));
        }

        return TRUE;
    }

    /**
     * If called from the application Controller / model than it will send the email to what ever email was entered in.
     * If called from the cron jobs, it will send to their official SUNY Orange Email.
     *
     * @param $emailAddress
     * @param $first
     * @param $last
     * @param $appID
     *
     * @return bool
     */
    public function incompleteApplication($emailAddress, $first, $last, $appID)
    {
        $data['recomm'] = \ApplicationRecommendation::where('applicationID', '=', $appID)->get(array(
            'recommender1', 'recommender2'
        ));

        $studentID       = Student::where('personalEmail', '=', $emailAddress)->get(array('studentID'));

        $callback = function ($message) use ($emailAddress)
        {
            $message->to($emailAddress)->subject('SUNY Orange Scholarship Information');
        };

        $data['body'] = "<h3>Hello $first $last,</h3><p>Your scholarship application has been received by the Financial Aid";
        $data['body'] .= "Office.</p> However please be aware your application is <strong>incomplete due to ";
        $data['body'] .= "<font color=\"red\">missing recommendations</font></strong>.<hr>";

        if ($data['recomm'][0]->recommender1 == null && $data['recomm'][0]->recommender2 == null)
        {
            $data['body'] .= "<ul><li><font color=\"red\">We are missing <strong>both</strong> recommendations from you.</font></li></ul>";
        } elseif ($data['recomm'][0]->recommender1 != null && $data['recomm'][0]->recommender2 == null)
        {
            $data['body'] .= "<ul><li><font color=\"red\">We are missing only <strong>one</strong> recommendation from you.</font></li></ul>";
        }

        $data['body'] .= "<hr><p>For you application to move forward we must have received two recommendations.
                            It is the student responsibility to ask their professors for a recommendation.</p>
                            <p>If any questions arise about your application or how the process works please
                            do not hesitate to call the financial aid office at: <b>845-341-4190</b></p>";

        if (!Mail::send('OutGoingMessages.Emails.incompleteApplication', $data, $callback))
        {
            return FALSE;
        }

        Event::fire('notification', array($data['body'], 8, Auth::user()->userId, null, $studentID[0]->studentID, null, 'SUNY Orange Scholarship Information'));

        return TRUE;
    }

    public function sendSuperResetPW($password)
    {
        $data['name']     = $this->name;
        $data['password'] = $password;

        $callback = function ($message)
        {
            $message->to($this->email)->subject('Scholarship Interface Password Reset');
        };

        if (!Mail::send('OutGoingMessages.Emails.superUserResetPW', $data, $callback))
        {
            return FALSE;
        }

        return TRUE;
    }

    /**
     * Send a personalized messages from an admin to all the students in the specific group.
     *
     * @param $subject
     * @param $adminMessage
     * @param $group
     *
     * @return array
     */
    public function sendIncompleteApplication($subject, $adminMessage, $group)
    {
        $ids = \Application::where('statusID', '=', 2)->where('typeID', '=', $group)
            ->where('applications.aidyear', '=', Session::get('currentAidyear'))
            ->lists('studentID', 'applicationID');

        $return = array(FALSE, 0);

        foreach ($ids as $appID => $studentID)
        {
            $student        = \Student::find($studentID);
            $data['name']   = $student->firstName . ' ' . $student->lastName;
            $data['body']   = $adminMessage;
            $data['recomm'] = \ApplicationRecommendation::where('applicationID', '=', $appID)->get(array(
                'recommender1', 'recommender2'
            ));

            $callback = function ($message) use ($subject, $student)
            {
                $message->to($student->email)->subject($subject);
            };

            if (!Mail::send('OutGoingMessages.Emails.incompleteApplicationNotification', $data, $callback))
            {
                $return[0] = FALSE;
            }

            else
            {
                $return[0] = TRUE;
                ++$return[1];
            }

            Event::fire('notification', array($adminMessage, 1, Auth::user()->userId, null, $studentID[0]->studentID, null, $subject));

            if ($student->cellnotifications == 1)
            {
                $SMS = new \Text('');
                $SMS->applicationNotifcation($student->cellPhone, $student->cellCarrier, 'checkEmail');
            }
        }

        return $return;
    }

    public function sendMessage($email, $subject, $body, $studentID)
    {
        $data['body'] = $body;
        
        Mail::send(array('html' => 'OutGoingMessages.Emails.message'), $data, function($message) use ($subject, $email)
        {
            $message->to($email)->subject($subject);
        });

        Event::fire('notification', array($body, 1, Auth::user()->userId, null, $studentID, null, $subject));
    
    }
}
