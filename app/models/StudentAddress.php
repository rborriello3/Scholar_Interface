<?php

class StudentAddress extends Eloquent
{
	/**
	 * The database table
	 */
	protected $table = 'studentAddress';

	/**
	 * We don't want any default time stamps
	 */
	public $timestamps = false;
	
	/**
	 * must define a specific key for our database table
	 */
	protected $primaryKey = 'studentID';

	public function initializeAddress($studentID)
	{
		if ($this->where('studentID', '=', $studentID)->count() != 1)
		{
			$this->studentID = $studentID;
			return $this->save();
		}

		return;
	}

	public function upDateAddress($addressInfo)
	{
		$update  = false;
		$address = $this->find($addressInfo['studentID']);

		if (count ($address) == 1)
		{
			foreach ($addressInfo as $k => $v)
			{
				if ($address->$k !== $v)
				{
					$update      = true;
					$address->$k = $v;
				}
			}

			return $address->save();
		}

		else
		{
			foreach ($addressInfo as $k => $v)
			{
				$this->$k = $v;
			}

			return $this->save();		
		}
	}
}