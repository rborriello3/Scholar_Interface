<?php

class NotificationsDataController extends BaseController
{
    public function getAllNotifications()
    {
        $self = false;
        // Only the Financial aid admins should be able to see everything. So if there role is not either 3 or 2 then
        // They are only permitted to see what they are entitled to based on their user ID.
        if (Session::get('role') == '3' || Session::get('role') == '2' || Session::get('role') == '5')
        {
            $table = 'U2.userRole';
            $condition = 'LIKE';
            $search = '%%';
        }
        else
        {
            $table = 'U2.userId';
            $condition = '=';
            $search = Auth::user()->userId;
        }

        return Datatable::query(DB::table('messages')
                    ->join('messageType', 'messageType.id', '=', 'messages.messageType')
                    ->leftjoin('student', 'student.studentID', '=', 'messages.toStudent')
                    ->leftjoin('user as U1', 'U1.userId', '=', 'messages.toUser')
                    ->leftjoin('user as U2', 'U2.userId', '=', 'messages.from')
                    ->select('messageID', 'subject', 'U1.userId as toUserID', 'U2.userId as fromUserID', 'toStudent as studentID', 'student.firstName', 'student.lastName', 'U2.name as from', 'U1.name as toUser', 'messageType.description', DB::Raw('FROM_UNIXTIME(sentTime, \'%c/%d/%y %r\') as time'))
                    ->where($table, $condition, $search)
                    ->orderBy('sentTime', 'desc')
                )
        ->addColumn('ID', function($notification)
        {
            $crudLinks = '<div class="btn-group">';
                $crudLinks .= '<button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">' . $notification->studentID . '<span class="glyphicon glyphicon-arrow-down"></span></button>';
                $crudLinks .= '<ul class="dropdown-menu" role="menu">';
                    
                    $crudLinks .= '<li>' . link_to_route('showSingleMessage', 'View Message', $parameters = array($notification->messageID), $attributes = array('alt'   => 'viewMessage', 'title' => 'View Message')) . '</li>';

                    if ($notification->studentID !== NULL)
                    {
                        $crudLinks .= '<li>' . link_to_route('showStudentMessageHistory', 'View Studnet History', $parameters = array($notification->studentID), $attributes = array('alt' => 'showMessage', 'title' => 'Student Message History')) . '</li>';
                        $crudLinks .= '<li>' . link_to_route('showEditStudent', 'View/Edit Student', $parameters = array($notification->studentID), $attributes = array('alt'   => 'editStudent', 'title' => 'Edit ' . $notification->firstName)) . '</li>';
                        $crudLinks .= '<li>' . link_to_route('showMessageStudent', 'Message Student', $parameters = array($notification->studentID), $attributes = array('alt' => 'messageStudent')) . '</li>';
                    }

                    if ($notification->toUserID !== NULL)
                    {
                        $crudLinks .= '<li>' . link_to_route('showUserMessageHistory', 'View User History', $parameters = array($notification->toUserID), $attributes = array('alt' => 'showMessage', 'title' => 'User Message History')) . '</li>';
                    }

                    if (Session::get('role') == '3' || Session::get('role') == '2' || Session::get('role') == '5')
                    {
                        $crudLinks .= '<li>' . link_to_route('showUserFromHistory', 'View Sending History', $parameters = array($notification->fromUserID), $attributes = array('alt' => 'showSendingMessage', 'title' => 'Sending History')) . '</li>';
                    }

                $crudLinks .= '</ul>';
            $crudLinks .= '</div>';   

            return $crudLinks; 
        })
        ->showColumns('subject')
        ->addColumn('student', function($student)
        {
            return $student->firstName . ' ' . $student->lastName;
        })
        ->showColumns('toUser', 'from', 'description', 'time')
        ->setSearchWithAlias()
        ->searchColumns('studentID', 'toUser', 'description', 'ID')
        ->make();
    }
}