<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class StudentNotification extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'StudentNotification';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Notifies students on their application process';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
		//
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return array(
			array('jobID', InputArgument::REQUIRED, 'The unique identifier for this job'),
			array('repeat', InputArgument::REQUIRED, 'Does this job need to repeat? [1|0]')
		);
	}

}
