<?php

class ProcessDataController extends BaseController
{
    public function JsonCRUD()
    {
        //Waiting, Passing, Failing, Stopped, Deleted, Uninitialized, Scheduling, Finished
        return Datatable::query(DB::table('automaticProcesses')->join('user', 'user.userId', '=', 'automaticProcesses.userID')->join('processes', 'processes.scriptLocation', '=', 'automaticProcesses.scriptLocation')->select('automaticProcesses.jobID', 'automaticProcesses.processName', 'automaticProcesses.userID', 'automaticProcesses.processDescription', 'executionTime', 'days', 'automaticProcesses.status', 'user.name', 'count', 'repeat'))->addColumn('Actions', function ($process)
        {
            $crudLinks = '<div class="btn-group">';

            if ($process->status == 'Passing')
            {
                $crudLinks .= '<button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">' . $process->status . '<span class="glyphicon glyphicon-arrow-down"></span></button>';
                $statusLinks = '<li>' . link_to_route('doStopProcess', 'Stop Process', $parameters = array($process->jobID), $attributes = array('alt' => 'viewIncompleteApplication')) . '</li>';
                $statusLinks .= '<li>' . link_to_route('doDeleteProcess', 'Delete Process', $parameters = array($process->jobID), $attributes = array('alt' => 'viewIncompleteApplication')) . '</li>';
            }

            elseif ($process->status == 'Finished' || $process->status == 'Uninitialized')
            {
                $crudLinks .= '<button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown">' . $process->status . '<span class="glyphicon glyphicon-arrow-down"></span></button>';
                $statusLinks = '<li>' . link_to_route('showFinishApplication', 'Start Process', $parameters = array($process->jobID), $attributes = array('alt' => 'viewIncompleteApplication')) . '</li>';
                $statusLinks .= '<li>' . link_to_route('doDeleteProcess', 'Delete Process', $parameters = array($process->jobID), $attributes = array('alt' => 'viewIncompleteApplication')) . '</li>';
            }

            elseif ($process->status == 'Failing')
            {
                $crudLinks .= '<button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown">' . $process->status . '<span class="glyphicon glyphicon-arrow-down"></span></button>';
                $statusLinks = '<li>' . link_to_route('doStopProcess', 'Stop Process', $parameters = array($process->jobID), $attributes = array('alt' => 'viewIncompleteApplication')) . '</li>';
                $statusLinks .= '<li>' . link_to_route('doDeleteProcess', 'Delete Process', $parameters = array($process->jobID), $attributes = array('alt' => 'viewIncompleteApplication')) . '</li>';
            }

            elseif ($process->status == 'Stopped' || $process->status == 'Deleted')
            {
                $crudLinks .= '<button type="button" class="btn btn-warning dropdown-toggle" data-toggle="dropdown">' . $process->status . '<span class="glyphicon glyphicon-arrow-down"></span></button>';
                $statusLinks = '<li>' . link_to_route('showFinishApplication', 'Recreate Process', $parameters = array($process->jobID), $attributes = array('alt' => 'viewIncompleteApplication')) . '</li>';
            }

            elseif ($process->status == 'Running')
            {
                $crudLinks .= '<button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">' . $process->status . ' <span class="glyphicon glyphicon-time"></span></button>';
                $statusLinks = '';
            }

            $crudLinks .= '<ul class="dropdown-menu" role="menu">';

            $crudLinks .= '<li></li>';
            $crudLinks .= $statusLinks;

            $crudLinks .= '</ul>';
            $crudLinks .= '</div>';


            return $crudLinks;
        })

            ->showColumns('processName', 'executionTime')->addColumn('days', function ($process)
            {
                if ($process->days == 'Sun,Mon,Tue,Wed,Thur,Fri,Sat')
                {
                    return 'Every day';
                }

                elseif ($process->days == 'Mon,Tue,Wed,Thur,Fri')
                {
                    return 'Week days';
                }

                elseif ($process->days == 'Sun,Sat')
                {
                    return 'Weekends';
                }

                return $process->days;
            })

            ->addColumn('User', function ($process)
            {
                return $process->name;
            })

            ->showColumns('processDescription', 'count')

            ->addColumn('repeat', function ($process)
            {
                if ($process->repeat == 1)
                {
                    return 'Yes';
                }
                else
                {
                    return 'No';
                }
            })

            ->searchColumns('processName', 'executionTime', 'status', 'user.name')->setExactWordSearch()->make();

    }
}