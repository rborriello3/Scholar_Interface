<?php 

class BannerScholarShipInterFaceSync extends QueueMaster
{
	public function fire($job, $data)		
	{	
		$statusOfJob = $this->checkStatus($job->getJobId());

		if ($statusOfJob == 'Stopped')
		{
			return $job->release(5);
		}

		elseif ($statusOfJob == 'Deleted')
		{
			return $job->delete();
		}

		$this->addToDB($job->getJobId(), $data);

		$bannerFile = '/home/rixhers/Desktop/rzschol1.csv';

		if (!file_exists($bannerFile))
		{
			//$this->statusNotification('Banner CSV file can not be located', '3');
			$this->processInfo($job->getJobId(), 'Failing');
			return $job->release(5);		
		}

		if (!is_readable($bannerFile))
		{	
			//$this->statusNotification('Banner CSV file is not readable', '3');
			// unlink($bannerFile); Delete Banner File
			$this->processInfo($job->getJobId(), 'Failing');
			return $job->release(5);	
		}
		
		$openFile = fopen($bannerFile, 'r');

		if ($openFile === FALSE)
		{
			//$this->statusNotification('Banner CSV file is not opening', '3');
			// unlink($bannerFile); Delete Banner File
			$this->processInfo($job->getJobId(), 'Failing');
			return $job->release(5);
		}

		else // File opening passed
		{
			$line            = 1;
			$newStudents     = 0;
			$updatedStudents = 0;

			while (($data = fgetcsv($openFile, 0, ",")) !== FALSE)
			{	
				if ($line == 1)
				{
					++$line;
					continue;
				}

				else
				{
					// Student Table
					if (count (\Student::find($data[0])) != 1)
					{
						$student            = new \Student();
						$student->studentID = $data[0];
						$student->firstName = $data[2];
						$student->lastName  = $data[1];
						$student->email     = $data[3];
						$student->save();
						++$newStudents;
					}

					else
					{
						$updated = false;

						$student = \Student::find($data[0]);
						
						if ($student->firstName != $data[2])
						{
							$student->firstName = $data[2];
							$updated            = true;
						}

						if ($student->lastName != $data[1])
						{
							$student->lastName = $data[1];
							$updated           = true;
						}

						if ($student->email != $data[3])
						{
							$student->email = $data[3];
							$updated        = true;
						}

						if ($updated)
						{
							$student->save();
						}
					}
					// End of Student Table

					// Need to do student Demographics
					// And unmet Need - EFC - Gross

				}
			}

			fclose($openFile);

			//$this->statusNotification('Banner information successfully uploaded - ' . $newStudents . ' new students.', '3');
		}	

		$this->processInfo($job->getJobId(), 'Passing');
		return $job->release(5);
	}
}