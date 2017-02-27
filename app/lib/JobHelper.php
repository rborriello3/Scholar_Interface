<?php

class JobHelper
{
    /**
     * Checks if the specific jobs need to repeat. If it doesn't than assign it a finished status
     * if it does assign it a passing status.
     *
     * @param int $jobID
     * @param int $repeat
     *
     * @return void
     */
    public function needsToRepeat($jobID, $repeat)
    {
        $proc = \Processes::find($jobID);

        if ($repeat == 1)
        {
            $proc->status = 'Passing';
        }
        else
        {
            $proc->status = 'Finished';
        }

        $proc->save();
    }

    /**
     * When the process first gets fired, it will report a running status, this status usually won't be seen, unless the
     * job is a really heavy process.
     *
     * @param int $jobID
     *
     * @return void
     */
    public function running($jobID)
    {
        \Processes::where('jobID', '=', $jobID)->update(array('status' => 'Running'));
    }

    /**
     * Add a fail status to a process
     *
     * @param int $jobID
     *
     * @return void
     */
    public function failJob($jobID)
    {
        \Processes::where('jobID', '=', $jobID)->update(array('status' => 'Failing'));
    }

    /**
     * Update the number of times the process has been executed no matter the status of the process
     *
     * @param int $jobID
     *
     * @return void
     */
    public function updateCount($jobID)
    {
        \Processes::where('jobID', '=', $jobID)->increment('count');
    }

    /**
     * Send job message to super user and financial aid admins
     *
     * @param string $mess
     * @param int    $group
     *
     * @return void
     */
    public function statusNotification($mess, $group)
    {
        $users = \User::where('userRole', 'LIKE', '%' . $group . '%')->where('status', '=', 'Active')->where('userId', '!=', '1')->get(array('userId', 'email', 'name'));

        foreach ($users as $v)
        {
            $data['body'] = $mess;
            $data['name'] = $v->name;

            $callback = function ($message) use ($v)
            {
                $message->to($v->email)->subject('Scholarship Interface Job Notification');
            };

            \Mail::send('OutGoingMessages.Cron.Emails.jobNotification', $data, $callback);
        }
    }

    /**
     * notifies admin group of the student update process
     *
     * @param string $bannerMessage
     * @param int    $incorrectCount
     * @param array  $incorrect
     *
     * @return void
     */
    public function studentUploadNotification($bannerMessage, $incorrect, $incorrectCount)
    {
        /*$users = \User::where('userRole', 'LIKE', '%' . 3 . '%')->where('status', '=', 'Active')->where('userId', '!=', '1')->get(array('userId', 'email', 'name'));*/

	$users = \User::where('userId', '=', '22')->get(array('userId', 'email', 'name'));

        foreach ($users as $v)
        {
            $data['body']         = $bannerMessage;
            $data['name']         = $v->name;
            $data['error']        = $incorrectCount;
            $data['errorMessage'] = $incorrect;

            $callback = function ($message) use ($v)
            {
                $message->to($v->email)->subject('Scholarship Interface Job Notification');
            };

            \Mail::send('OutGoingMessages.Cron.Emails.studentUpdateEmail', $data, $callback);
        }
    }

    /**
     * Sends an email to admin group about the application update process
     *
     * @param string $appMessage
     * @param int    $notFoundCount
     * @param array  $notFound
     * @param int    $multiAppCount
     * @param array  $multiApp
     * @param int    $noAppCount
     * @param array  $appNotFound
     *
     * @return void
     */
    public function applicationUploadNotification($appMessage, $notFoundCount, $notFound, $multiAppCount, $multiApp, $noAppCount, $appNotFound)
    {
        /*$users = \User::where('userRole', 'LIKE', '%' . 3 . '%')->where('status', '=', 'Active')->where('userId', '!=', '1')->get(array('userId', 'email', 'name'));*/
	
	$users = \User::where('userId', '=', '22')->get(array('userId', 'email', 'name'));

        foreach ($users as $v)
        {
            $data['body']          = $appMessage;
            $data['name']          = $v->name;
            $data['notFoundCount'] = $notFoundCount;
            $data['notFound']      = $notFound;
            $data['multiAppCount'] = $multiAppCount;
            $data['multiApp']      = $multiApp;
            $data['noAppCount']    = $noAppCount; // new
            $data['appNotFound']   = $appNotFound; // new

            $callback = function ($message) use ($v)
            {
                $message->to($v->email)->subject('Scholarship Interface Job Notification');
            };

            \Mail::send('OutGoingMessages.Cron.Emails.applicationUploadNotification', $data, $callback);
        }
    }


}
