<?php

class ReportsDataController extends BaseController
{
    public function graduatingRankJSON()
    {
        if (Request::ajax())
        {
            return Datatable::query(DB::table('applications')
                        ->join('student', 'student.studentID', '=', 'applications.studentID')
                        ->join('studentDemographics', 'studentDemographics.studentID', '=', 'student.studentID')
                        ->join('applicationAssessment', 'applicationAssessment.applicationID', '=', 'applications.applicationID')
                        ->leftjoin('unmetNeed', 'unmetNeed.studentID', '=', 'student.studentID')
                        ->select('applications.applicationID', DB::raw('CONCAT(student.lastName, ", <br>", student.firstName) as name'),
                            'studentDemographics.major', 'studentDemographics.GPA',
                            DB::raw('ROUND (AVG(applicationAssessment.total), 2) + studentDemographics.GPA AS Total'),
                            'unmetNeed.aidStatus', 'student.studentID')
                        ->where('applications.aidyear', '=', Session::get('currentAidyear'))
                        ->where('applications.typeID', '=', 4)
                        ->whereIn('applications.statusID', array(5, 8, 9))
                        ->groupBy('applications.applicationID'))
                    ->showColumns('name', 'major', 'GPA', 'Total')
                    ->addColumn('grader', function($student)
                    {
                        $graders = DB::table('activeUsers')
                            ->join('user', 'user.userId', '=', 'activeUsers.userId')
                            ->where('aidyear', '=', Session::get('currentAidyear'))
                            ->where('activeUsers.gradeGroup', 'LIKE', '%4%')
                            ->get(array('user.userId', 'name'));

                        $output = "";

                        foreach ($graders as $g)
                        {
                            $total = DB::table('applicationAssessment')
                                ->where('userId', '=', $g->userId)
                                ->where('applicationID', '=', $student->applicationID)
                                ->get(array('total'));

                            $output .= "(" . substr($g->name, strpos($g->name, " ")) . "-" . $total[0]->total . ") ";
                        }

                        return rtrim($output, " ");
                    })
                    ->addColumn('aid', function ($student)
                    {
                        if ($student->aidStatus == 'NEED')
                        {
                            return '<strong><font color="red">*' . $student->aidStatus . '*</font></strong>';
                        }
                        elseif ($student->aidStatus == 'MERIT')
                        {
                            return '<u>' . $student->aidStatus . '</u>';
                        }
                        elseif ($student->aidStatus)
                        {
                            return '<b>*$' . $student->aidStatus . '*</b>';
                        }
                    })->addColumn('awards', function ($award)
                    {
                        $awards = DB::table('scholarshipAwards')->where('studentID', $award->studentID)
                            ->where('scholarshipAwards.aidyear', '=', Session::get('currentAidyear'))
                            ->whereIn('awardStatus', array(
                            '1', '2'
                        ))->get(array('awardAmount'));
                        if (count($awards) > 0)
                        {
                            $return = '';

                            foreach ($awards as $v)
                            {
                                $return .= '<strong>$' . $v->awardAmount . '</strong> ';
                            }

                            return $return;
                        }
                    })
                    ->setSearchWithAlias()
                    ->make();
        }
        else
        {
            $students = DB::table('applications')
                        ->join('student', 'student.studentID', '=', 'applications.studentID')
                        ->join('studentDemographics', 'studentDemographics.studentID', '=', 'student.studentID')
                        ->join('applicationAssessment', 'applicationAssessment.applicationID', '=', 'applications.applicationID')
                        ->leftjoin('unmetNeed', 'unmetNeed.studentID', '=', 'student.studentID')
                        ->select('applicationAssessment.applicationID AS appID',
                            DB::raw('CONCAT(student.lastName, ", ", student.firstName) as name'),
                            'studentDemographics.major', 'studentDemographics.GPA',
                            DB::raw('ROUND (AVG(applicationAssessment.total), 2) + studentDemographics.GPA AS Total'),
                            'unmetNeed.aidStatus', 'student.studentID')
                        ->where('applications.aidyear', '=', Session::get('currentAidyear'))
                        ->where('applications.typeID', '=', 4)
                        ->whereIn('applications.statusID', array(5, 8, 9))
                        ->groupBy('applicationAssessment.applicationID')
                        ->orderBy('Total', 'desc')
                        ->get();

            $data['awards'] = DB::table('scholarshipAwards')->whereIn('awardStatus', array('1', '2'))
                ->where('scholarshipAwards.aidyear', '=', Session::get('currentAidyear'))
                ->get(array('awardAmount', 'studentID'));


            $graders = DB::table('activeUsers')
                ->join('user', 'user.userId', '=', 'activeUsers.userId')
                ->where('aidyear', '=', Session::get('currentAidyear'))
                ->where('activeUsers.gradeGroup', 'LIKE', '%4%')
                ->get(array('user.userId', 'name'));

            $output = array();
            $outPutString = "";

            for ($ii = 0; $ii < count($students); $ii++)
            {
                for ($i = 0; $i < count($graders); $i++)
                {
                    $total = DB::table('applicationAssessment')
                        ->where('userId', '=', $graders[$i]->userId)
                        ->where('applicationID', '=', $students[$ii]->appID)
                        ->get(array('total'));

                    $outPutString .= "(" . substr($graders[$i]->name, strpos($graders[$i]->name, " ")) . "-" . $total[0]->total . ") ";

                }

                $output[] = rtrim($outPutString, " ");
                $outPutString = "";
            }

            for ($i=0; $i < count($students); ++$i)
            {
                if ($i == 0)
                {
                    if ($students[$i]->Total == $students[$i + 1]->Total)
                    {
                        $students[$i]->equal = TRUE;
                    }
                }
                elseif (($i + 1 )< count($students))
                {
                    if ($students[$i]->Total == $students[$i + 1]->Total)
                    {
                        $students[$i]->equal = TRUE;
                    }

                    if ($students[$i]->Total == $students[$i - 1]->Total)
                    {
                        $students[$i]->equal = TRUE;
                    }
                }
            }
            
            $data['results'] = $students;
            $data['table'] = TRUE;
            $data['graders'] = $output;
            $pdf = PDF::loadView('Content.Admin.Reports.PrintedReports.graduatingRankPrint', $data);
            return $pdf->setPaper('a4')->setOrientation('landscape')->download(Session::get('currentAidyear') . ' - Graduating Rank');
            
        }
    }

    public function graduatingAssessmentsJSON($userId)
    {
        return Datatable::query(DB::table('applicationAssessment')
                    ->join('applications', 'applications.applicationID', '=', 'applicationAssessment.applicationID')
                    ->join('student', 'student.studentID', '=', 'applications.studentID')
                    ->select(DB::raw('CONCAT(student.lastName, ", ", student.firstName) as name'), 'applicationAssessment.essay', 'applicationAssessment.extra', 'applicationAssessment.faculty', 'Total', 'assessorNotes', 'assessmentDate')
                        ->where('applicationAssessment.status', '!=', 'Deactivated')
                        ->whereIn('applications.statusID', array(3, 5, 8, 9))
                        ->where('applications.typeID', '=', 4)
                        ->where('applications.aidyear', '=', Session::get('currentAidyear'))
                        ->where('applicationAssessment.userId', '=', $userId))
        ->showColumns('name', 'essay', 'extra', 'faculty', 'Total', 'assessorNotes', 'assessmentDate')
        ->setSearchWithAlias()
        ->make();
    }

    public function enteringRankJSON()
    {
        if (Request::ajax())
        {
            return Datatable::query(DB::table('applications')
                ->join('student', 'student.studentID', '=', 'applications.studentID')
                ->join('studentDemographics', 'studentDemographics.studentID', '=', 'student.studentID')
                ->join('applicationAssessment', 'applicationAssessment.applicationID', '=', 'applications.applicationID')
                ->leftjoin('unmetNeed', 'unmetNeed.studentID', '=', 'student.studentID')
                                ->select('applicationAssessment.applicationID AS appID', DB::raw('CONCAT(student.lastName, ", <br>", student.firstName) as name'), 'studentDemographics.major', 'studentDemographics.highSchoolAvg',
                                    DB::raw('ROUND (AVG(applicationAssessment.total), 2) + ROUND ((studentDemographics.highSchoolAvg / 20 - 1), 2) AS AVGTotal'),
                                    'unmetNeed.aidStatus', 'student.studentID')
                ->where('applications.aidyear', '=', Session::get('currentAidyear'))
                ->where('applications.typeID', '=', 2)
                ->whereIn('applications.statusID', array(5, 8, 9))
                ->groupBy('applications.applicationID'))
                ->setSearchWithAlias()->searchColumns('major')
                ->showColumns('name', 'major', 'highSchoolAvg', 'AVGTotal')
                ->addColumn('graders', function($student)
                {
                    $graders = DB::table('activeUsers')
                        ->join('user', 'user.userId', '=', 'activeUsers.userId')
                        ->where('aidyear', '=', Session::get('currentAidyear'))
                        ->where('activeUsers.gradeGroup', 'LIKE', '%2%')
                        ->get(array('user.userId', 'name'));

                    $output = "";

                    foreach ($graders as $g)
                    {
                        $total = DB::table('applicationAssessment')
                            ->where('userId', '=', $g->userId)
                            ->where('applicationID', '=', $student->appID)
                            ->get(array('total'));

                        $output .= "(" . substr($g->name, strpos($g->name, " ")) . "-" . $total[0]->total . ") ";
                    }

                    return rtrim($output, " ");
                })
                ->addColumn('aid', function ($student)
                {
                    if ($student->aidStatus == 'NEED')
                    {
                        return '<strong><font color="red">*' . $student->aidStatus . '*</font></strong>';
                    }
                    elseif ($student->aidStatus == 'MERIT')
                    {
                        return '<u>' . $student->aidStatus . '</u>';
                    }
                    elseif ($student->aidStatus)
                    {
                        return '<b>*$' . $student->aidStatus . '*</b>';
                    }
                })->addColumn('awards', function ($award)
                {
                    $awards = DB::table('scholarshipAwards')->where('studentID', $award->studentID)
                        ->where('scholarshipAwards.aidyear', '=', Session::get('currentAidyear'))
                        ->whereIn('awardStatus', array(
                        '1', '2'
                    ))->get(array('awardAmount'));
                    if (count($awards) > 0)
                    {
                        $return = '';

                        foreach ($awards as $v)
                        {
                            $return .= '<strong>$' . $v->awardAmount . '</strong> ';
                        }

                        return $return;
                    }

                })
                ->setSearchWithAlias()
                ->make();
        }
        else
        {

            $students = DB::table('applications')
                ->join('student', 'student.studentID', '=', 'applications.studentID')
                ->join('studentDemographics', 'studentDemographics.studentID', '=', 'student.studentID')
                ->join('applicationAssessment', 'applicationAssessment.applicationID', '=', 'applications.applicationID')
                ->leftjoin('unmetNeed', 'unmetNeed.studentID', '=', 'student.studentID')
                ->select('applicationAssessment.applicationID AS appID', DB::raw('CONCAT(student.lastName, ", ", student.firstName) as name'), 'studentDemographics.major', 'studentDemographics.highSchoolAvg',
                    DB::raw('ROUND (AVG(applicationAssessment.total), 2) + ROUND ((studentDemographics.highSchoolAvg / 20 - 1), 2) AS AVGTotal'),
                    'unmetNeed.aidStatus', 'student.studentID')
                ->where('applications.aidyear', '=', Session::get('currentAidyear'))
                ->where('applications.typeID', '=', 2)
                ->whereIn('applications.statusID', array(5, 8, 9))
                ->groupBy('applications.applicationID')
                ->orderBy('AVGTotal', 'desc')
                ->get();

            $data['awards'] = DB::table('scholarshipAwards')
                ->where('scholarshipAwards.aidyear', '=', Session::get('currentAidyear'))
                ->whereIn('awardStatus', array('1', '2'))->get(array('awardAmount', 'studentID'));

            $graders = DB::table('activeUsers')
                ->join('user', 'user.userId', '=', 'activeUsers.userId')
                ->where('aidyear', '=', Session::get('currentAidyear'))
                ->where('activeUsers.gradeGroup', 'LIKE', '%2%')
                ->get(array('user.userId', 'name'));

            $output = array();
            $outPutString = "";

            for ($ii = 0; $ii < count($students); $ii++)
            {
                for ($i = 0; $i < count($graders); $i++)
                {
                    $total = DB::table('applicationAssessment')->where('userId', '=', $graders[$i]->userId)->where('applicationID', '=', $students[$ii]->appID)->get(array('total'));
                    $outPutString .= "(" . substr($graders[$i]->name, strpos($graders[$i]->name, " ")) . "-" . $total[0]->total . ") ";
                }

                $output[]     = rtrim($outPutString, " ");
                $outPutString = "";
            }

            for ($i=0; $i < count($students); ++$i) 
            { 
                if ($i == 0)
                {
                    if ($students[$i]->AVGTotal == $students[$i + 1]->AVGTotal)
                    {
                        $students[$i]->equal = TRUE;
                    }
                }
                elseif (($i + 1 )< count($students))
                {
                    if ($students[$i]->AVGTotal == $students[$i + 1]->AVGTotal)
                    {
                        $students[$i]->equal = TRUE;
                    }

                    if ($students[$i]->AVGTotal == $students[$i - 1]->AVGTotal)
                    {
                        $students[$i]->equal = TRUE;
                    }
                }
            }        

            $data['results'] = $students;
            $data['table'] = TRUE;
            $data['graders'] = $output;
            $pdf = PDF::loadView('Content.Admin.Reports.PrintedReports.enteringRankPrint', $data);
            return $pdf->setPaper('a4')->setOrientation('landscape')->download(Session::get('currentAidyear') . ' - Entering Rank');
        }
    }

    public function enteringAssessmentsJSON($userId)
    {
        return Datatable::query(DB::table('applicationAssessment')
                    ->join('applications', 'applications.applicationID', '=', 'applicationAssessment.applicationID')
                    ->join('student', 'student.studentID', '=', 'applications.studentID')
                    ->select(DB::raw('CONCAT(student.lastName, ", ", student.firstName) as name'), 'applicationAssessment.essay', 'applicationAssessment.extra', 'applicationAssessment.faculty', 'Total', 'assessorNotes', 'assessmentDate')
                    ->where('applicationAssessment.status', '!=', 'Deactivated')
                    ->whereIn('applications.statusID', array(3, 5, 8, 9))
                    ->where('applications.typeID', '=', 2)
                    ->where('applications.aidyear', '=', Session::get('currentAidyear'))
                    ->where('applicationAssessment.userId', '=', $userId))
                    ->showColumns('name', 'essay', 'extra', 'faculty', 'Total', 'assessorNotes', 'assessmentDate')
                    ->setSearchWithAlias()
            ->make();
    }

    public function graduatingStudentsAddress()
    {
        return Datatable::query(DB::table('student')
                    ->join('studentAddress', 'studentAddress.studentID', '=', 'student.studentID')
                    ->join('scholarshipAwards', 'scholarshipAwards.studentID', '=', 'student.studentID')
                    ->join('applications', 'applications.studentID', '=', 'student.studentID')
                    ->join('scholarships', 'scholarships.fundCode', '=', 'scholarshipAwards.fundCode')
                    ->select('student.studentID', 'firstName', 'lastName', DB::raw('SUBSTRING(address, 1, LOCATE("||", address) -1) as address1'),
                        DB::raw('SUBSTRING(address, LOCATE("||", address) +2) as address2'), 'city', 'state', 'zipCode', 'scholarshipName', 'scholarshipAwards.awardAmount')
                    ->where('scholarshipAwards.aidyear', '=', Session::get('currentAidyear'))
                    ->where('department', '=', NULL)
                    ->orWhere('department', '=', "")
                    ->where('applications.typeID', '=', 4)
                    ->whereIn('applications.statusID', array(9))
                    ->groupBy('student.studentID')
                )
            ->showColumns('studentID', 'firstName', 'lastName', 'address1', 'address2', 'city', 'state', 'zipCode')
            ->addColumn('scholarshipName', function($name)
            {
                $awards = DB::table('scholarships')->leftJoin('scholarshipAwards', 'scholarshipAwards.fundCode', '=', 'scholarships.fundCode')
                    ->where('studentID', $name->studentID)->where('scholarshipAwards.aidyear', '=', Session::get('currentAidyear'))->whereIn('awardStatus', array(
                        '1', '2'
                    ))->get(array('scholarships.scholarshipName'));

                $return = '';

                foreach ($awards as $k => $v)
                {
                    if (count($awards) > 1)
                    {
                        $return .= $v->scholarshipName . '<br><br>';
                    }
                    else
                    {
                        $return .= $v->scholarshipName;
                    }
                }
                return $return;
            })
            ->addColumn('awardAmount', function($award)
            {
                $awards = DB::table('scholarshipAwards')->where('scholarshipAwards.aidyear', '=', Session::get('currentAidyear'))
                    ->where('studentID', $award->studentID)->whereIn('awardStatus', array(
                    '1', '2'
                ))->get(array('awardAmount'));

                $return = '';

                foreach ($awards as $k => $v)
                {
                    $return .= ' $'.$v->awardAmount;
                }
                return $return;
            })
            ->setSearchWithAlias()
            ->make();
    }

    public function gradutingStudentsRegret()
    {
        return Datatable::query(DB::table('student')
                    ->join('studentAddress', 'studentAddress.studentID', '=', 'student.studentID')
                    ->join('applications', 'applications.studentID', '=', 'student.studentID')
                    ->select('student.studentID', 'firstName', 'lastName', DB::raw('SUBSTRING(address, 1, LOCATE("||", address) -1) as address1'),
                        DB::raw('SUBSTRING(address, LOCATE("||", address) +2) as address2'), 'city', 'state', 'zipCode')
                    ->where('applications.typeID', '=', 4)
                    ->where('applications.aidyear', '=', Session::get('currentAidyear'))
                    ->whereIn('statusID', array(5,8))
                )
        ->showColumns('studentID', 'firstName', 'lastName', 'address1', 'address2', 'city', 'state', 'zipCode')
        ->setSearchWithAlias()
        ->make();
    }

    public function graduatingFacultyAddress()
    {
        return Datatable::query(DB::table('student')
                    ->join('studentAddress', 'studentAddress.studentID', '=', 'student.studentID')
                    ->join('scholarshipAwards', 'scholarshipAwards.studentID', '=', 'student.studentID')
                    ->join('applications', 'applications.studentID', '=', 'student.studentID')
                    ->join('scholarships', 'scholarships.fundCode', '=', 'scholarshipAwards.fundCode')
                    ->select('student.studentID', 'firstName', 'lastName', DB::raw('SUBSTRING(address, 1, LOCATE("||", address) -1) as address1'),
                        DB::raw('SUBSTRING(address, LOCATE("||", address) +2) as address2'), 'city', 'state', 'zipCode', 'awardAmount', 'department', 'scholarshipName')
                    ->where('applications.statusID', '=', '9')
                    ->where('scholarshipAwards.department', '!=', 'NULL')
                    ->where('scholarshipAwards.department', '!=', '')
                    ->where('scholarshipAwards.aidyear', '=', Session::get('currentAidyear'))
                    ->where('applications.typeID', '=', '5')
                )
            ->showColumns('studentID', 'firstName', 'lastName', 'address1', 'address2', 'city', 'state', 'zipCode', 'awardAmount', 'department', 'scholarshipName')
            ->addColumn('amount', function($award)
                {
                    return '$' . $award->awardAmount;
                })
            ->showColumns('department')
            ->setSearchWithAlias()
            ->make();
    }

    public function returningRankJSON()
    {
       if (Request::ajax())
        {
            return Datatable::query(DB::table('applications')
                        ->join('student', 'student.studentID', '=', 'applications.studentID')
                        ->join('studentDemographics', 'studentDemographics.studentID', '=', 'student.studentID')
                        ->join('applicationAssessment', 'applicationAssessment.applicationID', '=', 'applications.applicationID')
                        ->leftjoin('unmetNeed', 'unmetNeed.studentID', '=', 'student.studentID')
                        ->select('applicationAssessment.applicationID AS appID', 'creditHourFA',
                            DB::raw('CONCAT(student.lastName, ", <br>", student.firstName) as name'),
                            'studentDemographics.major', 'studentDemographics.GPA',
                            DB::raw('ROUND (AVG(applicationAssessment.total), 2) + studentDemographics.GPA AS Total'),
                            'unmetNeed.aidStatus',
                            'student.studentID')
                        ->where('applications.aidyear', '=', Session::get('currentAidyear'))
                        ->where('applications.typeID', '=', 6)
                        ->whereIn('applications.statusID', array(5, 8, 9))
                        ->groupBy('applicationAssessment.applicationID')
                    )
                    ->showColumns('name', 'Total', 'major', 'GPA', 'creditHourFA')
                    ->addColumn('graders', function($student)
                    {
                        $graders = DB::table('activeUsers')
                            ->join('user', 'user.userId', '=', 'activeUsers.userId')
                            ->where('aidyear', '=', Session::get('currentAidyear'))
                            ->where('activeUsers.gradeGroup', 'LIKE', '%6%')
                            ->get(array('user.userId', 'name'));

                        $output = "";

                        foreach ($graders as $g)
                        {
                            $total = DB::table('applicationAssessment')
                                ->where('userId', '=', $g->userId)
                                ->where('applicationID', '=', $student->appID)
                                ->get(array('total'));

                            $output .= "(" . substr($g->name, strpos($g->name, " ")) . "-" . $total[0]->total . ") ";
                        }

                        return rtrim($output, " ");
                    })
                    ->addColumn('aid', function($student)
                    {
                        if ($student->aidStatus == 'NEED')
                        {
                            return '<strong><font color="red">*' . $student->aidStatus . '*</font></strong>';
                        }
                        elseif ($student->aidStatus == 'MERIT')
                        {
                            return '<u>' . $student->aidStatus . '</u>';
                        }
                        elseif ($student->aidStatus)
                        {
                            return '<b>*$' . $student->aidStatus . '*</b>';
                        }
                    })->addColumn('awards', function($award)
                    {
                        $awards = DB::table('scholarshipAwards')->where('studentID', $award->studentID)
                            ->where('scholarshipAwards.aidyear', '=', Session::get('currentAidyear'))
                            ->whereIn('awardStatus', array(
                            '1', '2'
                        ))->get(array('awardAmount'));
                        if (count($awards) > 0)
                        {
                            $return = '';

                            foreach ($awards as $v)
                            {
                                $return .= '<strong>$' . $v->awardAmount . '</strong> ';
                            }

                            return $return;
                        }
                    })
                    ->setSearchWithAlias()
                    ->make();
        }
        else
        {   
            $students = DB::table('applications')
                        ->join('student', 'student.studentID', '=', 'applications.studentID')
                        ->join('studentDemographics', 'studentDemographics.studentID', '=', 'student.studentID')
                        ->join('applicationAssessment', 'applicationAssessment.applicationID', '=', 'applications.applicationID')
                        ->leftjoin('unmetNeed', 'unmetNeed.studentID', '=', 'student.studentID')
                        ->select('student.studentID', 'applicationAssessment.applicationID AS appID', 'creditHourFA', DB::raw('CONCAT(student.lastName, ", ", student.firstName) as name'), 'studentDemographics.major', 'studentDemographics.GPA',
                            DB::raw('ROUND (AVG(applicationAssessment.total), 2) + studentDemographics.GPA AS Total'),
                            'unmetNeed.aidStatus', 'student.studentID')
                        ->where('applications.aidyear', '=', Session::get('currentAidyear'))
                        ->where('applications.typeID', '=', 6)
                        ->whereIn('applications.statusID', array(5, 8, 9))
                        ->groupBy('applications.applicationID')
                        ->orderBy('Total', 'desc')
                        ->get();

            $data['awards'] = DB::table('scholarshipAwards')->whereIn('awardStatus', array('1', '2'))->get(array('awardAmount', 'studentID'));

            $graders = DB::table('activeUsers')
                ->join('user', 'user.userId', '=', 'activeUsers.userId')
                ->where('aidyear', '=', Session::get('currentAidyear'))
                ->where('activeUsers.gradeGroup', 'LIKE', '%6%')
                ->get(array('user.userId', 'name'));

            $output = array();
            $outPutString = "";

            for ($ii = 0; $ii < count($students); $ii++)
            {
                for ($i = 0; $i < count($graders); $i++)
                {
                    $total = DB::table('applicationAssessment')->where('userId', '=', $graders[$i]->userId)->where('applicationID', '=', $students[$ii]->appID)->get(array('total'));
                    $outPutString .= "(" . substr($graders[$i]->name, strpos($graders[$i]->name, " ")) . "-" . $total[0]->total . ") ";
                }

                $output[]     = rtrim($outPutString, " ");
                $outPutString = "";
            }


            for ($i=0; $i < count($students); ++$i) 
            { 
                if ($i == 0)
                {
                    if ($students[$i]->Total == $students[$i + 1]->Total)
                    {
                        $students[$i]->equal = TRUE;
                    }
                }
                elseif (($i + 1 )< count($students))
                {
                    if ($students[$i]->Total == $students[$i + 1]->Total)
                    {
                        $students[$i]->equal = TRUE;
                    }

                    if ($students[$i]->Total == $students[$i - 1]->Total)
                    {
                        $students[$i]->equal = TRUE;
                    }
                }
            }
            
            $data['results'] = $students;
            $data['table'] = TRUE;
            $data['graders'] = $output;
            $pdf = PDF::loadView('Content.Admin.Reports.PrintedReports.returningRankPrint', $data);
            return $pdf->setPaper('a4')->setOrientation('landscape')->download(Session::get('currentAidyear') . ' - Returning Rank');
        } 
    }

    public function returningStudentsCriteria()
    {
        if (Request::ajax())
        {
            return Datatable::query(DB::table('student')
                ->join('applications', 'applications.studentID', '=', 'student.studentID')
                ->join('studentDemographics', 'studentDemographics.studentID', '=', 'student.studentID')
                ->join('applicationAssessment', 'applicationAssessment.applicationID', '=', 'applications.applicationID')
                ->select(DB::raw('CONCAT(student.lastName, ", ", student.firstName) as name'),
                    DB::raw('ROUND (AVG(applicationAssessment.total), 2) + studentDemographics.GPA AS Total'),
                    'student.criteria', 'student.minority')
                ->where('applications.aidyear', '=', Session::get('currentAidyear'))
                ->where('applications.typeID', '=', 6)
                ->whereIn('applications.statusID', array(5, 8, 9))
                ->groupBy('applicationAssessment.applicationID'))
                ->showColumns('name', 'Total')
                ->addColumn('criteria', function($student)
                {
                    $criteria = explode(",", ($student->criteria));
                    $return = "";

                    foreach($criteria as $k)
                    {
                        $crit = DB::table('applicationCriteria')->where('criteriaID', '=', $k)->get(array('description'));
                        $return .= $crit[0]->description . "<br>";
                    }

                    return $return;
                })
                ->addColumn('minority', function($student)
                {
                    $minority = explode(",", ($student->minority));
                    $return = "";

                    foreach($minority as $m)
                    {
                        $min = DB::table('minority')->where('minorityID', '=', $m)->get(array('description'));
                        $return .= $min[0]->description . "<br>";
                    }

                    return $return;
                })

                ->setSearchWithAlias()
                ->make();
        }
        else
        {
            $students = DB::table('student')
                ->join('applications', 'applications.studentID', '=', 'student.studentID')
                ->join('studentDemographics', 'studentDemographics.studentID', '=', 'student.studentID')
                ->join('applicationAssessment', 'applicationAssessment.applicationID', '=', 'applications.applicationID')
                ->select(DB::raw('CONCAT(student.lastName, ", ", student.firstName) as name'),
                    DB::raw('ROUND (AVG(applicationAssessment.total), 2) + studentDemographics.GPA AS Total'),
                    'student.criteria', 'student.minority')
                ->where('applications.aidyear', '=', Session::get('currentAidyear'))
                ->where('applications.typeID', '=', 6)
                ->whereIn('applications.statusID', array(5, 8, 9))
                ->groupBy('applicationAssessment.applicationID')
                ->orderBy('Total', 'desc')
                ->get();

            $outputCrit = array();
            $crits = "";

            foreach ($students as $s)
            {
                foreach (explode(",", $s->criteria) as $crits1)
                {
                    $crit         = DB::table('applicationCriteria')->where('criteriaID', '=', $crits1)->get(array('description'));
                    $crits .= $crit[0]->description . "<br>";
                }

                $outputCrit[] = $crits;
                $crits = "";

            }

            $outputMin = array();
            $mins = "";

            foreach ($students as $s)
            {
                foreach (explode(",", $s->minority) as $min)
                {
                    $min         = DB::table('minority')->where('minorityID', '=', $min)->get(array('description'));
                    $mins .= $min[0]->description . "<br>";
                }

                $outputMin[] = $mins;
                $mins = "";
            }

            for ($i=0; $i < count($students); ++$i)
            {
                if ($i == 0)
                {
                    if ($students[$i]->Total == $students[$i + 1]->Total)
                    {
                        $students[$i]->equal = TRUE;
                    }
                }
                elseif (($i + 1 )< count($students))
                {
                    if ($students[$i]->Total == $students[$i + 1]->Total)
                    {
                        $students[$i]->equal = TRUE;
                    }

                    if ($students[$i]->Total == $students[$i - 1]->Total)
                    {
                        $students[$i]->equal = TRUE;
                    }
                }
            }

            $data['minority'] = $outputMin;
            $data['criteria'] = $outputCrit;
            $data['students'] = $students;
            $data['table'] = TRUE;
            $pdf = PDF::loadView('Content.Admin.Reports.PrintedReports.criteriaMinority', $data);
            return $pdf->setPaper('a4')->setOrientation('landscape')->download(Session::get('currentAidyear') . ' - Criteria & Minority Report');



        }
    }

    public function returningAssessmentsJSON($userId)
    {
        return Datatable::query(DB::table('applicationAssessment')
            ->join('applications', 'applications.applicationID', '=', 'applicationAssessment.applicationID')
            ->join('student', 'student.studentID', '=', 'applications.studentID')
            ->select(DB::raw('CONCAT(student.lastName, ", ", student.firstName) as name'), 'applicationAssessment.essay', 'applicationAssessment.extra', 'applicationAssessment.faculty', 'Total', 'assessorNotes', 'assessmentDate')
            ->where('applicationAssessment.status', '!=', 'Deactivated')
            ->whereIn('applications.statusID', array(3, 5, 8, 9))
            ->where('applications.typeID', '=', 6)
            ->where('applications.aidyear', '=', Session::get('currentAidyear'))
            ->where('applicationAssessment.userId', '=', $userId))
            ->showColumns('name', 'essay', 'extra', 'faculty', 'Total', 'assessorNotes', 'assessmentDate')
            ->setSearchWithAlias()
        ->make();
    }

    public function returningStudentsAddress()
    {
        return Datatable::query(DB::table('student')
                    ->join('studentAddress', 'studentAddress.studentID', '=', 'student.studentID')
                    ->join('scholarshipAwards', 'scholarshipAwards.studentID', '=', 'student.studentID')
                    ->join('applications', 'applications.studentID', '=', 'student.studentID')
                    ->join('scholarships', 'scholarships.fundCode', '=', 'scholarshipAwards.fundCode')
                    ->select('student.studentID', 'firstName', 'lastName', DB::raw('SUBSTRING(address, 1, LOCATE("||", address) -1) as address1'),
                        DB::raw('SUBSTRING(address, LOCATE("||", address) +2) as address2'), 'city', 'state', 'zipCode', 'scholarshipName', 'scholarshipAwards.awardAmount')
                    ->where('scholarshipAwards.aidyear', '=', Session::get('currentAidyear'))
                    ->where('department', '=', '')
                    ->orWhere('department', '=', 'NULL')
                    ->where('applications.typeID', '=', 6)
                    ->whereIn('applications.statusID', array(9))
                    ->groupBy('student.studentID')
                )
            ->showColumns('studentID', 'firstName', 'lastName', 'address1', 'address2', 'city', 'state', 'zipCode')
            ->addColumn('scholarshipName', function($name)
            {
                $awards = DB::table('scholarships')->leftJoin('scholarshipAwards', 'scholarshipAwards.fundCode', '=', 'scholarships.fundCode')
                         ->where('scholarshipAwards.aidyear', '=', Session::get('currentAidyear'))->where('studentID', $name->studentID)->whereIn('awardStatus', array(
                            '1', '2'
                        ))->get(array('scholarships.scholarshipName'));

                $return = '';

                foreach ($awards as $k => $v) 
                {
                    if (count($awards) > 1)
                    {
                        $return .= $v->scholarshipName . '<br><br>';
                    }
                    else
                    {
                        $return .= $v->scholarshipName;
                    }
                }
                return $return;
            })
            ->addColumn('awardAmount', function($award)
            {
                $awards = DB::table('scholarshipAwards')->where('studentID', $award->studentID)
                    ->where('scholarshipAwards.aidyear', '=', Session::get('currentAidyear'))
                    ->whereIn('awardStatus', array(
                            '1', '2'
                        ))->get(array('awardAmount'));

                $return = '';

                foreach ($awards as $k => $v) 
                {
                    $return .= ' $'.$v->awardAmount;
                }
                return $return;
            })
            ->setSearchWithAlias()
            ->make();
    }

    public function returningStudentsRegret()
    {
        return Datatable::query(DB::table('student')
                    ->join('studentAddress', 'studentAddress.studentID', '=', 'student.studentID')
                    ->join('applications', 'applications.studentID', '=', 'student.studentID')
                    ->select('student.studentID', 'firstName', 'lastName', DB::raw('SUBSTRING(address, 1, LOCATE("||", address) -1) as address1'),
                        DB::raw('SUBSTRING(address, LOCATE("||", address) +2) as address2'), 'city', 'state', 'zipCode')
                    ->where('applications.typeID', '=', 6)
                    ->where('applications.aidyear', '=', Session::get('currentAidyear'))
                    ->whereIn('statusID', array(5,8))
                )
        ->showColumns('studentID', 'firstName', 'lastName', 'address1', 'address2', 'city', 'state', 'zipCode')
        ->setSearchWithAlias()
        ->make();
    }

    public function returningFacultyAddress()
    {
        return Datatable::query(DB::table('student')
                    ->join('studentAddress', 'studentAddress.studentID', '=', 'student.studentID')
                    ->join('scholarshipAwards', 'scholarshipAwards.studentID', '=', 'student.studentID')
                    ->join('applications', 'applications.studentID', '=', 'student.studentID')
                    ->join('scholarships', 'scholarships.fundCode', '=', 'scholarshipAwards.fundCode')
                    ->select('student.studentID', 'firstName', 'lastName', DB::raw('SUBSTRING(address, 1, LOCATE("||", address) -1) as address1'),
                        DB::raw('SUBSTRING(address, LOCATE("||", address) +2) as address2'), 'city', 'state', 'zipCode', 'awardAmount', 'department', 'scholarshipName')
                    ->where('applications.statusID', '=', '9')
                    ->where('scholarshipAwards.aidyear', '=', Session::get('currentAidyear'))
                    ->where('scholarshipAwards.department', '!=', 'NULL')
                    ->where('scholarshipAwards.department', '!=', '')
                    ->where('applications.typeID', '=', '7')
                )
            ->showColumns('studentID', 'firstName', 'lastName', 'address1', 'address2', 'city', 'state', 'zipCode', 'awardAmount', 'department', 'scholarshipName')
            ->addColumn('amount', function($award)
                {
                    return '$' . $award->awardAmount;
                })
            ->showColumns('department')
            ->setSearchWithAlias()
            ->make();
    }

    public function enteringStudentAddress()
    {
        return Datatable::query(DB::table('student')
                ->join('studentAddress', 'studentAddress.studentID', '=', 'student.studentID')
                ->join('studentDemographics', 'studentDemographics.studentID', '=', 'student.studentID')
                ->join('scholarshipAwards', 'scholarshipAwards.studentID', '=', 'student.studentID')
                ->join('applications', 'applications.studentID', '=', 'student.studentID')
                ->join('scholarships', 'scholarships.fundCode', '=', 'scholarshipAwards.fundCode')
                ->select('student.studentID', 'firstName', 'lastName', DB::raw('SUBSTRING(address, 1, LOCATE("||", address) -1) as address1'),
                    DB::raw('SUBSTRING(address, LOCATE("||", address) +2) as address2'), 'city', 'state', 'zipCode', 'studentDemographics.highSchoolName',  'awardAmount', 'department', 'scholarshipName')
                ->where('scholarshipAwards.aidyear', '=', Session::get('currentAidyear'))
                ->where('applications.statusID', '=', '9')
                ->where('scholarshipAwards.department', '=', '')
                ->where('applications.typeID', '=', '2')
                ->groupBy('student.studentID')

        )
            ->showColumns('studentID', 'firstName', 'lastName', 'address1', 'address2', 'city', 'state', 'zipCode', 'highSchoolName')
            ->addColumn('scholarshipName', function($name)
            {
                $awards = DB::table('scholarships')->leftJoin('scholarshipAwards', 'scholarshipAwards.fundCode', '=', 'scholarships.fundCode')
                    ->where('scholarshipAwards.aidyear', '=', Session::get('currentAidyear'))->where('studentID', $name->studentID)->whereIn('awardStatus', array(
                        '1', '2'
                    ))->get(array('scholarships.scholarshipName'));

                $return = '';

                foreach ($awards as $k => $v)
                {
                    if (count($awards) > 1)
                    {
                        $return .= $v->scholarshipName . '<br><br>';
                    }
                    else
                    {
                        $return .= $v->scholarshipName;
                    }
                }
                return $return;
            })
            ->addColumn('awardAmount', function($award)
            {
                $awards = DB::table('scholarshipAwards')->where('studentID', $award->studentID)
                        ->where('scholarshipAwards.aidyear', '=', Session::get('currentAidyear'))
                        ->whereIn('awardStatus', array(
                    '1', '2'
                ))->get(array('awardAmount'));

                $return = '';

                foreach ($awards as $k => $v)
                {
                    $return .= ' $'.$v->awardAmount;
                }
                return $return;
            })
            ->setSearchWithAlias()
            ->make();
    }

    public function enteringStudentRegret()
    {
        return Datatable::query(DB::table('student')
                ->join('studentAddress', 'studentAddress.studentID', '=', 'student.studentID')
                ->join('applications', 'applications.studentID', '=', 'student.studentID')
                ->select('student.studentID', 'firstName', 'lastName', DB::raw('SUBSTRING(address, 1, LOCATE("||", address) -1) as address1'),
                    DB::raw('SUBSTRING(address, LOCATE("||", address) +2) as address2'), 'city', 'state', 'zipCode')
                ->where('applications.typeID', '=', 2)
                ->where('applications.aidyear', '=', Session::get('currentAidyear'))
                ->whereIn('statusID', array(5,8))
        )
            ->showColumns('studentID', 'firstName', 'lastName', 'address1', 'address2', 'city', 'state', 'zipCode')
            ->setSearchWithAlias()
            ->make();
    }

    public function enteringFacultyAddress()
    {
        return Datatable::query(DB::table('student')
                ->join('studentAddress', 'studentAddress.studentID', '=', 'student.studentID')
                ->join('studentDemographics', 'studentDemographics.studentID', '=', 'student.studentID')
                ->join('scholarshipAwards', 'scholarshipAwards.studentID', '=', 'student.studentID')
                ->join('applications', 'applications.studentID', '=', 'student.studentID')
                ->join('scholarships', 'scholarships.fundCode', '=', 'scholarshipAwards.fundCode')
                ->select('student.studentID', 'firstName', 'lastName', DB::raw('SUBSTRING(address, 1, LOCATE("||", address) -1) as address1'),
                    DB::raw('SUBSTRING(address, LOCATE("||", address) +2) as address2'), 'city', 'state', 'zipCode', 'studentDemographics.highSchoolName', 'awardAmount', 'department', 'scholarshipName')
                ->where('applications.statusID', '=', '9')
                ->where('scholarshipAwards.department', '!=', 'NULL')
                ->where('scholarshipAwards.aidyear', '=', Session::get('currentAidyear'))
                ->where('scholarshipAwards.department', '!=', '')
                ->where('applications.typeID', '=', '3')
        )
            ->showColumns('studentID', 'firstName', 'lastName', 'address1', 'address2', 'city', 'state', 'zipCode', 'highSchoolName', 'awardAmount', 'department', 'scholarshipName')
            ->addColumn('amount', function($award)
            {
                return '$' . $award->awardAmount;
            })
            ->showColumns('department')
            ->setSearchWithAlias()
            ->make();
    }

    public function all_awards()
    {
        return Datatable::query(DB::table('student')
                ->join('studentAddress', 'studentAddress.studentID', '=', 'student.studentID')
                ->join('scholarshipAwards', 'scholarshipAwards.studentID', '=', 'student.studentID')
                ->join('applications', 'applications.studentID', '=', 'student.studentID')
                ->join('scholarships', 'scholarships.fundCode', '=', 'scholarshipAwards.fundCode')
                ->select('student.studentID', 'firstName', 'lastName', DB::raw('SUBSTRING(address, 1, LOCATE("||", address) -1) as address1'),
                    DB::raw('SUBSTRING(address, LOCATE("||", address) +2) as address2'), 'city', 'state', 'zipCode')
                ->where('applications.statusID', '=', '9')
                ->where('applications.aidyear', '=', Session::get('currentAidyear'))
                ->whereNotIn('applications.typeID', array(4,5))
                ->groupBy('student.studentID')
            )
            ->showColumns('studentID', 'firstName', 'lastName', 'address1', 'address2', 'city', 'state', 'zipCode')
            ->addColumn('scholarshipFundCode', function($name)
            {
                $awards = DB::table('scholarshipAwards')->where('studentID', '=', $name->studentID)->where('scholarshipAwards.aidyear', '=', Session::get('currentAidyear'))->where('awardStatus', '=', 2)
                    ->get(array('scholarshipAwards.fundCode'));

                $return = '';

                foreach ($awards as $k => $v)
                {
                    if (count($awards) > 1)
                    {
                        $return .= $v->fundCode . '<br><br>';
                    }
                    else
                    {
                        $return .= $v->fundCode;
                    }
                }
                return $return;
            })
            ->addColumn('scholarshipName', function($name)
            {
                $awards = DB::table('scholarships')->leftJoin('scholarshipAwards', 'scholarshipAwards.fundCode', '=', 'scholarships.fundCode')
                    ->where('studentID', $name->studentID)->where('scholarshipAwards.aidyear', '=', Session::get('currentAidyear'))->whereIn('awardStatus', array(
                        '1', '2'
                    ))->get(array('scholarships.scholarshipName'));

                $return = '';

                foreach ($awards as $k => $v)
                {
                    if (count($awards) > 1)
                    {
                        $return .= $v->scholarshipName . '<br><br>';
                    }
                    else
                    {
                        $return .= $v->scholarshipName;
                    }
                }
                return $return;
            })
            ->addColumn('awardAmount', function($award)
            {
                $awards = DB::table('scholarshipAwards')->where('studentID', $award->studentID)
                    ->where('scholarshipAwards.aidyear', '=', Session::get('currentAidyear'))
                    ->whereIn('awardStatus', array(
                    '1', '2'
                ))->get(array('awardAmount'));

                $return = '';

                foreach ($awards as $k => $v)
                {
                    $return .= ' $'.$v->awardAmount;
                }
                return $return;
            })
            ->setSearchWithAlias()
            ->make();
    }

    public function all_students()
    {
        return Datatable::query(DB::table('student')
                ->join('studentAddress', 'studentAddress.studentID', '=', 'student.studentID')
                ->join('studentDemographics', 'studentDemographics.studentID', '=', 'student.studentID')
                ->join('applications', 'applications.studentID', '=', 'student.studentID')
                ->join('applicationType', 'applicationType.typeID', '=', 'applications.typeID')
                ->join('applicationStatus', 'applicationStatus.statusID', '=', 'applications.statusID')
                ->select('applicationStatus.statusName', 'student.studentID', 'firstName', 'lastName', DB::raw('SUBSTRING(address, 1, LOCATE("||", address) -1) as address1'),
                    DB::raw('SUBSTRING(address, LOCATE("||", address) +2) as address2'), 'city', 'state', 'zipCode', 'studentDemographics.highSchoolName', 'applicationType.typeDescription')
                ->where('applications.aidyear', '=', Session::get('currentAidyear'))
                ->groupBy('student.studentID')
                )
                ->showColumns('statusName', 'studentID', 'firstName', 'lastName', 'address1', 'address2', 'city', 'state', 'zipCode', 'highSchoolName')
                ->addColumn('status', function($application)
                {
                    return $application->typeDescription;
                })
            ->setSearchWithAlias()
            ->make();
    }

}
