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

    public function insertAward($values, $single, $guid = NULL)
    {
        if ($single)
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
        $student    = array();
        $amount     = array();
        $department = array();
        $notes      = array();

        foreach ($values as $k => $v)
        {
            if ($k == 'fundCode')
            {
                for ($i=0; $i < count($values[$k]); ++$i)
                {
                    $fundCode[$i] = $v[$i];
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
        }

        for ($i=0; $i < count($values['fundCode']); $i++)
        {
            if (Application::where('studentID', '=', $student[$i])->where('aidyear', '=', Session::get('currentAidyear'))->where('statusID', '=', 5)->count() == 1)
            {
                Application::where('studentID', '=', $student[$i])->where('aidyear', '=', Session::get('currentAidyear'))->where('statusID', '=', 5)->update(array('statusID' => 9));
            }

            $arrays[] = array('fundCode' => $fundCode[$i], 'studentID' => $student[$i], 'awardAmount' => $amount[$i], 'department' => $department[$i], 'notes' => $notes[$i]);
            ++$count;
        }

        if (DB::table($this->table)->insert($arrays))
        {
            $return[0] = true;
            $return[1] = $count;
        }
        else
        {
            $return[0] = false;
        }

        return $return;
    }

    public function getHistory($studentID)
    {
        $award = $this->where('scholarshipAwards.studentID', '=', $studentID)->join('scholarships', 'scholarships.FundCode', '=', 'scholarshipAwards.fundCode')
                    ->join('student', 'student.studentID', '=', 'scholarshipAwards.studentID')->join('studentDemographics', 'studentDemographics.studentID', '=', 'scholarshipAwards.studentID')
                    ->join('applications', 'applications.studentID', '=', 'scholarshipAwards.studentID')
                    ->join('awardStatus', 'awardStatus.awardStatusID', '=', 'scholarshipAwards.awardStatus')
                    ->join('applicationType', 'applicationType.typeID', '=', 'applications.typeID')        
                    ->get();
        return $award;
    }
}
