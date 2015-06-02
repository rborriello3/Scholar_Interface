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

            $arrays[] = array('fundCode' => $fundCode[$i], 'studentID' => $student[$i], 'awardAmount' => $amount[$i], 'department' => $department[$i], 'notes' => $notes[$i], 'aidyear' => Session::get('currentAidyear'));
            ++$count;
        }

        try {
            DB::table($this->table)->insert($arrays);
            $return[0] = true;
            $return[1] = $count;
            return $return;
        } catch (Exception $e)
        {
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
