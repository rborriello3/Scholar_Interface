<?php

class QueueMaster
{
	protected function addToDB($jobId, $values)
	{
		if (count(\Processes::find($jobId)) != 1)
		{
			$process = new \Processes();
			$process->processID = $jobId;

			foreach (array_except($values, array('repetition')) as $k => $v)
			{
				$process->$k = $v;
			}

			if (count ($values['repetition']) > 0)
			{
				$process->repetition = implode(',', $values['repetition']);
			}

			else
			{
				$process->repetition = null;
			}

			$process->save();	
		}
	}

	protected function processInfo($jobId, $status)
	{
		$process = \Processes::find($jobId);
		$process->status = $status;
		$process->increment('count', 1);
		$process->save();
	}

	protected function checkStatus($jobId)
	{
		$process = \Processes::find($jobId);

		if (count ($process) == 1)
		{
			if ($process->status == 'Stopped')
			{
				return 'Stopped';
			}

			elseif ($process->status == 'Passing')
			{
				return 'Passing';
			}

			elseif ($process->status == 'Deleted')
			{
				return 'Deleted';
			}
		}
	}

	protected function statusNotification($info, $group = null)
	{
		if ($group)
		{
			$users = \User::where('userRole', 'LIKE', '%' . $group . '%')->where('status', '=', 'Active')->whereHas('CellPhone', function($q){
				$q->where('verified', '=', 1);
			})->get(array('userId'));
		}

		else
		{
			$users = \User::where('status', '=', 'Active')->whereHas('CellPhone', function($q){
				$q->where('verified', '=', 1);
			})->get(array('userId'));
		}

		foreach ($users as $v)
		{
			$cellPhone       = \Usercellphone::find($v->userId);
			$carrier         = DB::table('cellCarriers')->where('carrierId', '=', $cellPhone->carrierId)->get(array('smsAddress'));
			$to              = \Crypt::decrypt($cellPhone->cellPhoneNum) . '@'. $carrier[0]->smsAddress;
			$data['info'] = $info;

			$callback = function($message) use ($to)
					{
						$message->to($to);
					};

			\Mail::send('OutGoingMessages.Queue.test', $data, $callback);
		}
	}
}