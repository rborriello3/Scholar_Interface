<?php

class NotificationsController extends BaseController
{

    public function showHome()
    {
        return View::make('Content.Admin.Notifications.home');
    }

    public function showMessageStudent($studentID)
    {
        $data['student'] = Student::find($studentID);
        return View::make('Content.Admin.Notifications.newSingleEmail', $data);
    }

    public function doMessageStudent($studentID)
    {
        $rules = array(
            'subject' => 'Required|text',
            'messageBody' => 'Required|essay'
        );

        $student = Student::find($studentID);     

        $v = Validator::make(Input::all(), $rules);

        if ($v->passes())
        {
            $email = new Email('', '');

            if ($student->personalEmail != '')
            {
                $email->emailSentToSUNY($student->personalEmail, $student->firstName, $student->lastName);
            }

            if ($student->sunyEmail != '')
            {
                $email->sendMessage($student->sunyEmail, Input::get('subject'), Input::get('messageBody'), $studentID);
            }
            else
            {
                return Redirect::to('showMessageStudent', $studentID)->with('error', 'Student Does Not Have SUNY Email - Message Could Not Be Sent')->withInput();
            }
        }

        return Redirect::route('homeNotifications')->with('success', 'Message sent');
    }

    public function showAllIncomplete()
    {
        return View::make('Content.Admin.Notifications.emailAllInactive');
    }

    public function doAllIncomplete()
    {
        $rules = array(
            'password'    => 'Required', 'group' => 'Required|numeric', 'subject' => 'Required|text',
            'messageBody' => 'Required|essay'
        );

        $v = Validator::make(Input::all(), $rules);

        if ($v->passes())
        {
            if (Hash::check(Input::get('password'), Auth::user()->password))
            {
                $email       = new Email('', '');
                $emails_sent = $email->sendIncompleteApplication(Input::get('subject'), Input::get('messageBody'), Input::get('group'));

                if ($emails_sent[0])
                {
                    return Redirect::route('showEmailIncompleteApplications')->with('success', $emails_sent[1] . ' notification(s) sent.');
                }

                else
                {
                    return Redirect::route('showEmailIncompleteApplications')->with('error', 'There are no students within that group.');
                }
            }

            return Redirect::route('showEmailIncompleteApplications')->with('error', 'Invalid credentials.')->withInput();
        }

        return Redirect::route('showEmailIncompleteApplications')->withInput()->withErrors($v->messages());
    }
/*
    public function showMessageUser($userID)
    {

    }

    public function doMessageUser($userID)
    {

    }
*/

    public function showStudentMessageHistory($studentID)
    {
        $mess = new Messages();
        $data['info'] = $mess->getMessageHistory($studentID, null, null);
        return View::make('Content.Admin.Notifications.viewStudentHistory', $data);
    }

    public function showUserMessageHistory($userID)
    {
        $mess = new Messages();
        $data['info'] = $mess->getMessageHistory(null, $userID, null);
        return View::make('Content.Admin.Notifications.viewUserHistory', $data);
    }

    public function showUserFromHistory($userID)
    {
        $mess = new Messages();
        $data['info'] = $mess->getMessageHistory(null, null, $userID);
        return View::make('Content.Admin.Notifications.viewSendingHistory', $data);
    }

    public function showSingleMessage($messageGUID)
    {
        $msg = new Messages($messageGUID);
        $data['info'] = $msg->singleMessageData();
        return View::make('Content.Admin.Notifications.viewSpecificNotification', $data);
    }

}
