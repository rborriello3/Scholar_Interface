<?php

class StudentDataController extends BaseController
{
    public function allStudents()
    {
        return Datatable::query(DB::table('student',/* 'applications',*/ 'scholarshipAwards')
                    ->leftjoin('studentDemographics', 'studentDemographics.studentID', '=', 'student.studentID')
                    ->leftjoin('applications', 'applications.studentID', '=', 'student.studentID')
		    ->leftjoin('scholarshipAwards', 'scholarshipAwards.studentID', '=', 'student.studentID')
                    //->leftjoin('applicationType', 'applicationType.typeID', '=', 'applications.typeID')
                    ->select('student.lastName', 'student.firstName', 'student.studentID as ID', 'sunyEmail', 
                        'studentDemographics.major', 'studentDemographics.GPA', 'studentDemographics.highSchoolAVG', 'personalEmail'
                    )
		    ->where('applications.aidyear', '=', Session::get('currentAidyear'))
		    ->orWhere('scholarshipAwards.aidyear', '=', Session::get('currentAidyear'))
		    /*->whereNull('studentDemographics.major')
		    ->orWhereNotNull('studentDemographics.major')
		    ->whereNull('studentDemographics.highSchoolAVG')
		    ->orWhereNotNull('studentDemographics.highSchoolAVG')
		    ->whereNull('studentDemographics.GPA')
		    ->orWhereNotNull('studentDemographics.GPA')*/
		    ->groupBy('student.studentID')
		    ->orderBy('student.lastName', 'asc')
		    //->where('student.lastName', '=', 'Ribaudo')
                )

/*	//Get students who have applications on file
	$applications = (DB::table('student')
                    ->leftjoin('studentDemographics', 'studentDemographics.studentID', '=', 'student.studentID')
                    ->join('applications', 'applications.studentID', '=', 'student.studentID')
                    ->leftjoin('applicationType', 'applicationType.typeID', '=', 'applications.typeID')
                    ->select('student.lastName', 'student.firstName', 'student.studentID as ID', 'sunyEmail', 
                        'studentDemographics.major', 'studentDemographics.GPA', 'studentDemographics.highSchoolAVG', 'personalEmail'
                    )
		    ->where('applications.aidyear', '=', Session::get('currentAidyear'))
		 //   ->orderBy('student.lastName', 'asc')
                );

	//Get students who dont' have an application on file (so, they must be awarded)
	$noApps = (DB::table('student')
		    ->leftjoin('studentDemographics', 'studentDemographics.studentID', '=', 'student.studentID')
		    ->join('scholarshipAwards', 'scholarshipAwards.studentID', '=', 'student.studentID')
		    ->leftjoin('applicationType', 'applicationType.typeID', '=', 'scholarshipAwards.typeID')
		    ->select('student.lastName', 'student.firstName', 'student.studentID as ID', 'sunyEmail', 'studentDemographics.major', 'studentDemographics.GPA', 'studentDemographics.highSchoolAVG', 'personalEmail')  
		    ->where('scholarshipAwards.aidyear', '=', Session::get('currentAidyear'))
//		    ->whereNull('studentDemographics.major')
//		    ->whereNull('studentDemographics.GPA')
//		    ->whereNull('studentDemographics.highSchoolAVG')
		   // ->union($applications)
		  //  ->groupBy('student.studentID')
		    ->orderBy('student.lastName', 'asc')
		    );

	$student = Datatable::query($noApps->union($applications)->get());

	return $student->get()
	//return $applications->union($noApps)->get()*/

        ->addColumn('ID', function($student)
        {
            $crudLinks = '<div class="btn-group">';
                $crudLinks .= '<button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">' . $student->ID . '<span class="glyphicon glyphicon-arrow-down"></span></button>';
                $crudLinks .= '<ul class="dropdown-menu" role="menu">';
                    
                    $crudLinks .= '<li>' . link_to_route('showEditStudent', 'View/Edit Student', $parameters = array($student->ID), $attributes = array('alt'   => 'editStudent', 'title' => 'Edit ' . $student->firstName)) . '</li>';
                    $crudLinks .= '<li>' . link_to_route('showMessageStudent', 'Message Student', $parameters = array($student->ID), $attributes = array('alt' => 'messageStudent')) . '</li>';
                    $crudLinks .= '<li>' . link_to_route('showStudentApplications', 'View Application(s)', $parameters = array($student->ID), $attributes = array('alt' => 'viewApplication')) . '</li>';
                    $crudLinks .= '<li>' . link_to_route('showAwardHistory', 'Award History', $parameters = array($student->ID), $attributes = array('alt' => 'viewAwards')) . '</li>';

                $crudLinks .= '</ul>';
            $crudLinks .= '</div>';   

            return $crudLinks; 
        })
        ->showColumns('firstName', 'lastName', 'sunyEmail', 'personalEmail', 'major')
        ->addColumn('gpa', function($student)
        {
            if ($student->highSchoolAVG != NULL && $student->GPA == NULL)
            {
                return ($student->highSchoolAVG / 20 - 1);
            }
            elseif ($student->GPA != NULL)
            {
                return $student->GPA;
            }
            elseif ($student->highSchoolAVG != NULL && $student->GPA != NULL)
            {
                return $student->GPA;
            }
        })
        ->setSearchWithAlias()
        ->searchColumns('lastName', 'firstName', 'student.studentID', 'major', 'sunyEmail', 'personalEmail')
        ->make();
    }
}
