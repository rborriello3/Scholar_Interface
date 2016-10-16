<?php

class Messages extends Eloquent
{
    /**
     * The database table
     */
    protected $table = 'messages';

    /**
     * must define a specific key for our database table
     */
    protected $primaryKey = 'messageID';

    /**
     * Holds our message GUID value
     *
     * @var GUID
     */
    private $GUID;

    /**
     * it would be optimal if it would generate the created at timestamp, but not the updated. A sent message can't be updated - lol
     * @var boolean
     */
    public $timestamps = false;

    public function __construct($messageGUID = null)
    {
        if ($messageGUID)
        {
            $this->GUID = $messageGUID;
        }
    }

    public function singleMessageData($GUID = null)
    {
        $data = array();
        $message = $this->find($this->GUID);

        $data['message'] = $message->message;
        $data['time'] = date('D M d, Y - h:i A', $message->sentTime);
        $student = new Student;
        $user = new User;

        if ($message->toStudent)
        {
            $data['aNum'] = $message->toStudent;
            $data['to'] = 'Student-' . $student->getName($data['aNum']);
        }
        else
        {
            $data['to'] = 'User-' . $user->getName($message->toUser);
            $data['toUserID'] = $message->toUser;
        }

        $data['subject'] = $message->subject;
        $data['from'] = $user->getName($message->from);

        return $data;
    }

    public function getMessageHistory($studentID, $userID, $sendingUserID)
    {
        if ($studentID)
        {
            $messageIDs = $this->where('toStudent', '=', $studentID)->orderBy('sentTime', 'desc')->get(array('messageID'));
        }
        elseif ($userID)
        {
            $messageIDs = $this->where('toUser', '=', $userID)->orderBy('sentTime', 'desc')->get(array('messageID'));
        }
        else
        {
            $messageIDs = $this->where('from', '=', $sendingUserID)->orderBy('sentTime', 'desc')->get(array('messageID'));
        }

        foreach ($messageIDs as $messageID)
        {
            $this->GUID = $messageID->messageID;
            $messages[] = $this->singleMessageData();
        }

        return $messages;
    }
}
