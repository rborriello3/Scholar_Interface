<?php

class ApplicationStatus extends Eloquent
{
	/**
	 * The database table
	 */
	protected $table = 'applicationStatus';

	/**
	 * We don't want any default time stamps
	 */
	public $timestamps = false;
	
	/**
	 * must define a specific key for our database table
	 */
	protected $primaryKey = 'statusID';

	public function getAll($all = true, $status = '')
	{
		if ($all)
		{
			$stat = $this->all();
			foreach ($stat as $s)
			{
				$return[$s->statusID] = $s->statusName;
			}
		}

		else
		{
			$stat = str_split($status);
			foreach ($stat as $s)
			{
				$return[$s] = $this->find($s)->statusName;
			}
		}

		return $return;
	}
}