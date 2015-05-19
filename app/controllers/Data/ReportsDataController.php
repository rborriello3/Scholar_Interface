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
                        ->join('unmetNeed', 'unmetNeed.studentID', '=', 'student.studentID')
                        ->select('applicationAssessment.applicationID AS appID', DB::raw('CONCAT(student.lastName, ", <br>", student.firstName) as name'),
                            'studentDemographics.major', 'studentDemographics.GPA',
                            DB::raw('ROUND (AVG(applicationAssessment.total), 2) + studentDemographics.GPA AS Total'),
                            'unmetNeed.aidStatus', 'student.studentID')
                        ->where('applications.aidyear', '=', Session::get('currentAidyear'))
                        ->where('applications.typeID', '=', 4)
                        ->whereIn('applications.statusID', array(5, 8, 9))
                        ->groupBy('applicationAssessment.applicationID'))
                    ->showColumns('name', 'major', 'GPA', 'Total')
                    ->addColumn('grader', function($student)
                    {
                        
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
                        ->join('unmetNeed', 'unmetNeed.studentID', '=', 'student.studentID')
                        ->select('applicationAssessment.applicationID AS appID', DB::raw('CONCAT(student.lastName, ", ", student.firstName) as name'), 'studentDemographics.major', 'studentDemographics.GPA', DB::raw('ROUND (AVG(applicationAssessment.total), 2) + studentDemographics.GPA AS Total,
                                                    (SELECT total FROM applicationAssessment WHERE userId = 11 and applicationAssessment.applicationID = appID) as `Shultz`,
                                                    (SELECT total FROM applicationAssessment WHERE userId = 12 and applicationAssessment.applicationID = appID) as `Fiorello`,
                                                    (SELECT total FROM applicationAssessment WHERE userId = 15 and applicationAssessment.applicationID = appID) as `McGraw`,
                                                    (SELECT total FROM applicationAssessment WHERE userId = 16 and applicationAssessment.applicationID = appID) as `Sarbak`,
                                                    (SELECT total FROM applicationAssessment WHERE userId = 17 and applicationAssessment.applicationID = appID) as `Easton`,
                                                    (SELECT total FROM applicationAssessment WHERE userId = 18 and applicationAssessment.applicationID = appID) as `Sheridan`,
                                                    (SELECT total FROM applicationAssessment WHERE userId = 19 and applicationAssessment.applicationID = appID) as `Kelly`'), 'unmetNeed.aidStatus', 'student.studentID')
                        ->where('applications.aidyear', '=', Session::get('currentAidyear'))
                        ->where('applications.typeID', '=', 4)
                        ->whereIn('applications.statusID', array(5, 8, 9))
                        ->groupBy('applicationAssessment.applicationID')
                        ->orderBy('Total', 'desc')
                        ->get();

            $data['awards'] = DB::table('scholarshipAwards')->whereIn('awardStatus', array('1', '2'))
                ->where('scholarshipAwards.aidyear', '=', Session::get('currentAidyear'))
                ->get(array('awardAmount', 'studentID'));

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
            $pdf = PDF::loadView('Content.Admin.Reports.PrintedReports.graduatingRankPrint', $data);
            return $pdf->setPaper('a4')->setOrientation('landscape')->download();
            
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
                ->join('unmetNeed', 'unmetNeed.studentID', '=', 'student.studentID')
                                ->select('applicationAssessment.applicationID AS appID', DB::raw('CONCAT(student.lastName, ", <br>", student.firstName) as name'), 'studentDemographics.major', 'studentDemographics.highSchoolAvg',
                                                    DB::raw('ROUND (AVG(applicationAssessment.total), 2) + ROUND ((studentDemographics.highSchoolAvg / 20 - 1), 2) AS AVGTotal,
                                                    (SELECT total FROM applicationAssessment WHERE userId = 12 and applicationAssessment.applicationID = appID) as `Fiorello`,
                                                    (SELECT total FROM applicationAssessment WHERE userId = 16 and applicationAssessment.applicationID = appID) as `Sarbak`,
                                                    (SELECT total FROM applicationAssessment WHERE userId = 18 and applicationAssessment.applicationID = appID) as `Sheridan`,
                                                    (SELECT total FROM applicationAssessment WHERE userId = 24 and applicationAssessment.applicationID = appID) as `Schmidt`,
                                                    (SELECT total FROM applicationAssessment WHERE userId = 25 and applicationAssessment.applicationID = appID) as `Devitt`,
                                                    (SELECT total FROM applicationAssessment WHERE userId = 26 and applicationAssessment.applicationID = appID) as `Frommer`,
                                                    (SELECT total FROM applicationAssessment WHERE userId = 27 and applicationAssessment.applicationID = appID) as `Illobre`,
                                                    (SELECT total FROM applicationAssessment WHERE userId = 28 and applicationAssessment.applicationID = appID) as `McCarty`,
                                                    (SELECT total FROM applicationAssessment WHERE userId = 29 and applicationAssessment.applicationID = appID) as `Peverely`,
                                                    (SELECT total FROM applicationAssessment WHERE userId = 30 and applicationAssessment.applicationID = appID) as `Yankanin`'), 'unmetNeed.aidStatus', 'student.studentID')
                ->where('applications.aidyear', '=', Session::get('currentAidyear'))
                ->where('applications.typeID', '=', 2)
                ->whereIn('applications.statusID', array(5, 8, 9))
                ->groupBy('applicationAssessment.applicationID'))
                ->setSearchWithAlias()->searchColumns('major')->showColumns('name', 'major', 'highSchoolAvg', 'AVGTotal', 'Fiorello', 'Sarbak', 'Sheridan', 'Schmidt', 'Devitt', 'Frommer', 'Illobre', 'McCarty', 'Peverely', 'Yankanin')
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
                    else
                    {
                        return '<strong><font color="red">$' . $student->aidStatus . '</font>';
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
                ->join('unmetNeed', 'unmetNeed.studentID', '=', 'student.studentID')
                ->select('applicationAssessment.applicationID AS appID', DB::raw('CONCAT(student.lastName, ", ", student.firstName) as name'), 'studentDemographics.major', 'studentDemographics.highSchoolAvg',
                                                    DB::raw('ROUND (AVG(applicationAssessment.total), 2) + ROUND ((studentDemographics.highSchoolAvg / 20 - 1), 2) AS AVGTotal,
                                                    (SELECT total FROM applicationAssessment WHERE userId = 12 and applicationAssessment.applicationID = appID) as `Fiorello`,
                                                    (SELECT total FROM applicationAssessment WHERE userId = 16 and applicationAssessment.applicationID = appID) as `Sarbak`,
                                                    (SELECT total FROM applicationAssessment WHERE userId = 18 and applicationAssessment.applicationID = appID) as `Sheridan`,
                                                    (SELECT total FROM applicationAssessment WHERE userId = 24 and applicationAssessment.applicationID = appID) as `Schmidt`,
                                                    (SELECT total FROM applicationAssessment WHERE userId = 25 and applicationAssessment.applicationID = appID) as `Devitt`,
                                                    (SELECT total FROM applicationAssessment WHERE userId = 26 and applicationAssessment.applicationID = appID) as `Frommer`,
                                                    (SELECT total FROM applicationAssessment WHERE userId = 27 and applicationAssessment.applicationID = appID) as `Illobre`,
                                                    (SELECT total FROM applicationAssessment WHERE userId = 28 and applicationAssessment.applicationID = appID) as `McCarty`,
                                                    (SELECT total FROM applicationAssessment WHERE userId = 29 and applicationAssessment.applicationID = appID) as `Peverely`,
                                                    (SELECT total FROM applicationAssessment WHERE userId = 30 and applicationAssessment.applicationID = appID) as `Yankanin`'), 'unmetNeed.aidStatus', 'student.studentID')
                ->where('applications.aidyear', '=', Session::get('currentAidyear'))
                ->where('applications.typeID', '=', 2)
                ->whereIn('applications.statusID', array(5, 8, 9))
                ->groupBy('applicationAssessment.applicationID')
                ->orderBy('AVGTotal', 'desc')
                ->get();

            $data['awards'] = DB::table('scholarshipAwards')
                ->where('scholarshipAwards.aidyear', '=', Session::get('currentAidyear'))
                ->whereIn('awardStatus', array('1', '2'))->get(array('awardAmount', 'studentID'));

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
            $pdf = PDF::loadView('Content.Admin.Reports.PrintedReports.enteringRankPrint', $data);
            return $pdf->setPaper('a4')->setOrientation('landscape')->download();
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
                        ->join('unmetNeed', 'unmetNeed.studentID', '=', 'student.studentID')
                        ->select('applicationAssessment.applicationID AS appID', 'creditHourFA', DB::raw('CONCAT(student.lastName, ", <br>", student.firstName) as name'), 'studentDemographics.major', 'studentDemographics.GPA', DB::raw('ROUND (AVG(applicationAssessment.total), 2) + studentDemographics.GPA AS Total,
                                                    (SELECT total FROM applicationAssessment WHERE userId = 11 and applicationAssessment.applicationID = appID) as `Shultz`,
                                                    (SELECT total FROM applicationAssessment WHERE userId = 12 and applicationAssessment.applicationID = appID) as `Fiorello`,
                                                    (SELECT total FROM applicationAssessment WHERE userId = 15 and applicationAssessment.applicationID = appID) as `McGraw`,
                                                    (SELECT total FROM applicationAssessment WHERE userId = 16 and applicationAssessment.applicationID = appID) as `Sarbak`,
                                                    (SELECT total FROM applicationAssessment WHERE userId = 17 and applicationAssessment.applicationID = appID) as `Easton`,
                                                    (SELECT total FROM applicationAssessment WHERE userId = 18 and applicationAssessment.applicationID = appID) as `Sheridan`,
                                                    (SELECT total FROM applicationAssessment WHERE userId = 19 and applicationAssessment.applicationID = appID) as `Kelly`'), 'unmetNeed.aidStatus', 'student.studentID')
                        ->where('applications.aidyear', '=', Session::get('currentAidyear'))
                        ->where('applications.typeID', '=', 6)
                        ->whereIn('applications.statusID', array(5, 8, 9))
                        ->groupBy('applicationAssessment.applicationID')
                    )
                    ->showColumns('name', 'Total', 'major', 'GPA', 'creditHourFA', 'Shultz', 'Fiorello', 'McGraw', 'Sarbak', 'Easton', 'Sheridan', 'Kelly')
                    ->addColumn('aid', function($student)
                    {
                        if ($student->aidStatus > 0)
                        {
                            return '<strong><font color="red">$' . $student->aidStatus . '</font></strong>';
                        }
                        elseif ($student->aidStatus == 0)
                        {
                            return '<b>$' . $student->aidStatus . '</b>';
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
                        ->join('unmetNeed', 'unmetNeed.studentID', '=', 'student.studentID')
                        ->select('applicationAssessment.applicationID AS appID', 'creditHourFA', DB::raw('CONCAT(student.lastName, ", ", student.firstName) as name'), 'studentDemographics.major', 'studentDemographics.GPA', DB::raw('ROUND (AVG(applicationAssessment.total), 2) + studentDemographics.GPA AS Total,
                                                    (SELECT total FROM applicationAssessment WHERE userId = 11 and applicationAssessment.applicationID = appID) as `Shultz`,
                                                    (SELECT total FROM applicationAssessment WHERE userId = 12 and applicationAssessment.applicationID = appID) as `Fiorello`,
                                                    (SELECT total FROM applicationAssessment WHERE userId = 15 and applicationAssessment.applicationID = appID) as `McGraw`,
                                                    (SELECT total FROM applicationAssessment WHERE userId = 16 and applicationAssessment.applicationID = appID) as `Sarbak`,
                                                    (SELECT total FROM applicationAssessment WHERE userId = 17 and applicationAssessment.applicationID = appID) as `Easton`,
                                                    (SELECT total FROM applicationAssessment WHERE userId = 18 and applicationAssessment.applicationID = appID) as `Sheridan`,
                                                    (SELECT total FROM applicationAssessment WHERE userId = 19 and applicationAssessment.applicationID = appID) as `Kelly`'), 'unmetNeed.aidStatus', 'student.studentID')
                        ->where('applications.aidyear', '=', Session::get('currentAidyear'))
                        ->where('applications.typeID', '=', 6)
                        ->whereIn('applications.statusID', array(5, 8, 9))
                        ->groupBy('applicationAssessment.applicationID')
                        ->orderBy('Total', 'desc')
                        ->get();

            $data['awards'] = DB::table('scholarshipAwards')->whereIn('awardStatus', array('1', '2'))->get(array('awardAmount', 'studentID'));

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
            $pdf = PDF::loadView('Content.Admin.Reports.PrintedReports.returningRankPrint', $data);
            return $pdf->setPaper('a4')->setOrientation('landscape')->download();
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
