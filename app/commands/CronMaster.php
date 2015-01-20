<?php

use Illuminate\Console\Command;

class CronMaster extends Command
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'CronMaster';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Controls when other jobs are run';

    /**
     * The process model
     *
     * @var Processes
     */
    protected $process;

    /**
     * The time this process executed in h:i A format
     *
     * @var $now
     */
    protected $now;

    /**
     * Todays date to check if there are any processes that need to be executed
     *
     * @var $today
     */
    protected $today;

    /**
     * Create a new command instance.
     *
     * @param Processes $processes
     *
     * @return \CronMaster
     */
    public function __construct(Processes $processes)
    {
        parent::__construct();
        $this->process = $processes;
        $this->now     = date('h:i A');
        $this->today   = date('D');
    }

    /**
     * Take all the jobs that are to run today, such as a specific users notification update
     * Run the database to check for all jobs that contain the specific day in its days column
     *
     * @return mixed
     */
    public function fire()
    {
        $jobs = $this->process->where('days', 'LIKE', '%' . $this->today . '%')->whereIn('status', array(
            'Uninitialized', 'Passing', 'Failing'
        ))
        ->where('executionTime', '=', $this->now)
        ->get(array('jobID', 'executionTime', 'repeat', 'scriptLocation'));
        
        if (count($jobs) > 0)
        {
            foreach ($jobs as $job)
            {
                $this->call($job->scriptLocation, array('jobID' => $job->jobID, 'repeat' => $job->repeat));
            }
        }
    }
}
