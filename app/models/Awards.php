<?php

class Awards extends Eloquent
{
    /**
     * The database table
     */
    protected $table = 'scholarshipAwards';

    /**
     * We don't want any default time stamps
     */
    public $timestamps = FALSE;

    public function insertAward($values, $single = null, $guid = null, $update = null, $studentID = null, $code = null, $type = null)
    {
        if ($single && ! is_null($single))
        {
            foreach ($values['studentID'] as $v)
            {
                if (Application::where('guid', '=', $guid)->where('studentID', '=', $v)->count()== 0)
                {
                    return 'Invalid';
                }
            }
        }

        $values     = array_except($values, array('_token'));
        $arrays     = array();
        $count      = 0;
        $return     = array();
        $fundCode   = array();
	$statusID   = array();
        $student    = array();
        $amount     = array();
        $department = array();
        $notes      = array();
	$typeID	    = array();

        foreach ($values as $k => $v)
        {

	    if ($k == 'fundCode')
            {
                for ($i=0; $i < count($values[$k]); ++$i)
                {
                    $fundCode[$i] = $v[$i];
                }
            }

	   if ($k == 'statusID')
            {
                for ($i=0; $i < count($values[$k]); ++$i)
                {
                    $statusID[$i] = $v[$i];
                }
            }
		


            if ($k == 'studentID')
            {
                for ($i=0; $i < count($values[$k]); $i++)
                {
                    $student[$i] = $v[$i];
                }
            }

            if ($k == 'awardAmount')
            {
                for ($i=0; $i < count($values[$k]); $i++)
                {
                    $amount[$i] = $v[$i];
                }
            }

            if ($k == 'department')
            {
                for ($i=0; $i < count($values[$k]); $i++)
                {
                    $department[$i] = $v[$i];
                }
            }

            if ($k == 'notes')
            {
                for ($i=0; $i < count($values[$k]); $i++)
                {
                    $notes[$i] = $v[$i];
                }
            }
	
	    if ($k == 'typeID')
	    {
		for ($i=0; $i < count($values[$k]); $i++)
		{
			switch($v[$i]) 
			{
				//Entering Athlete
				case 0:
					$typeID[$i] = 9;
					break;
				//Entering Faculty Recommended
				case 1:
					$typeID[$i] = 3;
					break;
				//Entering Honors
				case 2:
					$typeID[$i] = 10;
					break;
				//Entering Committee
				case 3:
					$typeID[$i] = 2;
					break;
				//Graduating Faculty Recommended
				case 4:
					$typeID[$i] =  5;
					break;
				//Graduating Committee
				case 5:
					$typeID[$i] = 4;
					break;
				//Returning Athlete
				case 6:
					$typeID[$i] = 11;
					break;
				//Returning Faculty Recommended
				case 7:
					$typeID[$i] = 7;
					break;
				//Returning Honors
				case 8:
					$typeID[$i] = 12;
					break;
				//Returning Committee
				case 9:
					$typeID[$i] = 6;
					break;
			}
		}
	    }
        }
        
	for ($i=0; $i < count($values['fundCode']); $i++)
        {
            if (Application::where('studentID', '=', $student[$i])->where('aidyear', '=', Session::get('currentAidyear'))->count() == 1)
            {
                Application::where('studentID', '=', $student[$i])->where('aidyear', '=', Session::get('currentAidyear'))->update(array('statusID' => 9));
	    }
	  
            $arrays[] = array('fundCode' => $fundCode[$i], 'studentID' => $student[$i], 'awardAmount' => $amount[$i], 'department' => $department[$i], 'notes' => $notes[$i], 'aidyear' => Session::get('currentAidyear'), 'typeID' => $typeID[$i]);
            ++$count;

	    if(Student::where('studentID', '=', $student[$i])->count() == 1)
	    {	
		if(DB::table('applicationResponses')->where('studentID', '=', $student[$i])->where('fundCode', '=', $fundCode[$i])->where('aidyear', '=', Session::get('currentAidyear'))->count() == 1)
		{
			DB::table('applicationResponses')
				->where('studentID', '=', $student[$i])
				->where('fundCode', '=', $fundCode[$i])
				->where('aidyear', '=', Session::get('currentAidyear'))
				->update(array('fundCode' => $fundCode[$i]));
		}
		else
		{
			$resp = new ApplicationResponse($student[$i], $fundCode[$i], Session::get('currentAidyear'));
	    		$resp->newResponse();
		}
	    }
        }

        try {
            if ($update && ! is_null($update))
            {
                DB::table($this->table)->where('studentID', '=', $studentID)
                    ->where('fundCode', '=', $code)
                    ->where('aidyear', '=', Session::get('currentAidyear'))
                        ->update($arrays[0]);
		/*DB::table('scholarshipAwards')->where('studentID', '=', $studentID)
		    ->where('fundCode', '=', $code)
		    ->where('aidyear', '=', Session::get('currentAidyear'))
			->update('statusID', '=', '9');*/

                $return[1] = $arrays[0]["fundCode"];
            }
            else
            {
                DB::table($this->table)->insert($arrays);
                $return[1] = $count;
            }

            $return[0] = true;
            return $return;
        } catch (Exception $e)
        {
            dd($e->getMessage());
            $return[0] = false;
            return $return;
        }

    }

    public function getHistory($studentID)
    {
        $award = $this->where('scholarshipAwards.studentID', '=', $studentID)
                    ->join('scholarships', 'scholarships.FundCode', '=', 'scholarshipAwards.fundCode')
                    ->join('student', 'student.studentID', '=', 'scholarshipAwards.studentID')
                    ->join('awardStatus', 'awardStatus.awardStatusID', '=', 'scholarshipAwards.awardStatus')
                    ->join('applications', 'applications.studentID', '=', 'student.studentID')
                    ->join('applicationType', 'applicationType.typeID', '=', 'applications.typeID')
                    ->orderBy('scholarshipAwards.aidyear', 'desc')
                    ->orderBy('awardAmount', 'desc')
                    ->groupBy('scholarshipAwards.fundCode')
                    ->groupBy('scholarshipAwards.aidyear')
                    ->get(array('scholarshipAwards.aidyear', 'awardStatus.description', 'scholarshipAwards.fundCode', 'applicationType.typeDescription',
                                'scholarships.scholarshipName', 'scholarshipAwards.awardAmount', 'scholarshipAwards.department',
                                'scholarshipAwards.notes', 'student.firstName', 'student.lastName', 'student.studentID'));
        return $award;
    }
}
