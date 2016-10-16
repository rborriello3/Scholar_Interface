<?php

class ApplicationsDataController extends BaseController
{
    public function JsonCRUD()
    {
        return Datatable::query(DB::table('applications')
            ->join('student', 'student.studentID', '=', 'applications.studentID')
            ->join('applicationType', 'applicationType.typeID', '=', 'applications.typeID')
            ->join('applicationStatus', 'applicationStatus.statusID', '=', 'applications.statusID')
            ->select('GUID', 'applicationID', 'firstName', 'lastName', 'student.studentID', 'applications.aidyear', 'applicationType.typeName', 'applications.received', 'applicationStatus.statusName', 'applications.statusID')
            ->where('applications.statusID', '!=', 6)
            ->where('applications.aidyear', '=', Session::get('currentAidyear'))
            ->orderBy('received', 'desc'))
            ->addColumn('Actions', function ($application)
            {
            $crudLinks = '<div class="btn-group">';
            if ($application->statusName == 'Incomplete')
            {
                $crudLinks .= '<button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown">' . $application->statusName . '<span class="glyphicon glyphicon-arrow-down"></span></button>';
                $statusLinks = '<li>' . link_to_route('showFinishApplication', 'Finish Application', $parameters = array($application->GUID), $attributes = array('alt' => 'viewIncompleteApplication')) . '</li>';
            }

            elseif ($application->statusName == 'Complete')
            {
                $crudLinks .= '<button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">' . $application->statusName . '<span class="glyphicon glyphicon-arrow-down"></span></button>';
                $statusLinks = '<li>' . link_to_route('doDeleteApplication', 'Deactivate Application', $parameters = array($application->GUID), $attributes = array('alt' => 'deleteApplication')) . '</li>';

                if ($application->typeName != 'Athletes' && $application->typeName != 'Entering - FR' && $application->typeName != 'Graduating - FR' && $application->typeName != 'Returning - FR' && $application->typeName != 'Honors')
                {
                    $statusLinks .= '<li>' . link_to_route('showViewGrades', 'View Assessment', $parameters = array($application->GUID), $attributes = array('alt' => 'viewGrades')) . '</li>';
                }
            }

            elseif ($application->statusName == 'Award')
            {
                $crudLinks .= '<button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown">' . $application->statusName . '<span class="glyphicon glyphicon-arrow-down"></span></button>';
                $statusLinks = '<li>' . link_to_route('showAwardSingleStudent', 'Award Scholarship', $parameters = array($application->GUID), $attributes = array('alt' => 'awardApplication')) . '</li>';
                $statusLinks .= '<li>' . link_to_route('doDeleteApplication', 'Deactivate Application', $parameters = array($application->GUID), $attributes = array('alt' => 'deleteApplication')) . '</li>';

                if ($application->typeName != 'Athletes' && $application->typeName != 'Entering - FR' && $application->typeName != 'Graduating - FR' && $application->typeName != 'Returning - FR' && $application->typeName != 'Honors')
                {
                    $statusLinks .= '<li>' . link_to_route('showViewGrades', 'View Assessment', $parameters = array($application->GUID), $attributes = array('alt' => 'viewGrades')) . '</li>';
                }
            }
            elseif ($application->statusName == 'Awarded')
            {
                $crudLinks .= '<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">' . $application->statusName . '<span class="glyphicon glyphicon-arrow-down"></span></button>';
                $statusLinks = "";

                if ($application->typeName != 'Athletes' && $application->typeName != 'Entering - FR' && $application->typeName != 'Graduating - FR' && $application->typeName != 'Returning - FR' && $application->typeName != 'Honors')
                {
                    $statusLinks .= '<li>' . link_to_route('showViewGrades', 'View Assessment', $parameters = array($application->GUID), $attributes = array('alt' => 'viewGrades')) . '</li>';
                }
            }

            elseif ($application->statusName == 'Deactivated')
            {
                $crudLinks .= '<button type="button" class="btn btn-warning dropdown-toggle" data-toggle="dropdown">' . $application->statusName . '<span class="glyphicon glyphicon-arrow-down"></span></button>';
                $statusLinks = '<li>' . link_to_route('doActivateApplication', 'Activate Application', $parameters = array($application->GUID), $attributes = array('alt' => 'activateApplication')) . '</li>';

                if ($application->statusName != 'Athletes' && $application->statusName != 'Entering - FR' && $application->statusName != 'Graduating - FR' && $application->statusName != 'Returning - FR' && $application->statusName != 'Honors')
                {
                    $statusLinks .= '<li>' . link_to_route('showViewGrades', 'View Assessment', $parameters = array($application->GUID), $attributes = array('alt' => 'viewGrades')) . '</li>';
                }
            }

            elseif ($application->statusName == 'Ineligible')
            {
                // What makes an application ineligible?
            }

            $crudLinks .= '<ul class="dropdown-menu" role="menu">';
            $crudLinks .= '<li>' . link_to_route('showStudentApplications', 'View Application(s)', $parameters = array($application->studentID), $attributes = array('alt' => 'viewApplication')) . '</li>';
            $crudLinks .= '<li>' . link_to_route('showMessageStudent', 'Message Student', $parameters = array($application->studentID), $attributes = array('alt' => 'messageStudent',)) . '</li>';            $crudLinks .= '<li>' . link_to_route('showEditStudent', 'View/Edit Student', $parameters = array($application->studentID), $attributes = array('alt' => 'editStudent')) . '</li>';
            $crudLinks .= '<li>' . link_to_route('showEditApplication', 'Edit Application', $parameters = array($application->GUID), $attributes = array('alt' => 'editApplication')) . '</li>';
            $crudLinks .= '<li>' . link_to_route('showAwardHistory', 'Award History', $parameters = array($application->studentID), $attributes = array('alt' => 'viewAwards')) . '</li>';
            $crudLinks .= $statusLinks;

            $crudLinks .= '</ul>';
            $crudLinks .= '</div>';


            return $crudLinks;
        })->showColumns('received', 'firstName', 'lastName', 'studentID', 'aidyear', 'typeName')->searchColumns('lastName', 'student.studentID', 'typeName', 'received', 'applicationStatus.statusName')->setExactWordSearch()->make();
    }

    public function gradingApplications()
    {
        return Datatable::query(DB::table('applications')
            ->join('student', 'student.studentID', '=', 'applications.studentID')
            ->join('applicationType', 'applicationType.typeID', '=', 'applications.typeID')
            ->leftjoin('applicationAssessment', 'applicationAssessment.applicationID', '=', 'applications.applicationID')
            ->select('GUID', 'firstName', 'lastName', 'student.studentID', 'applications.aidyear', 'typeName', 'applications.received', 'applicationAssessment.status')
            ->whereRaw('statusID IN (3,5)')
            ->whereRaw('applicationType.typeID NOT IN (1,3,5,7,8)')
            ->where('applicationAssessment.userID', '=', Auth::user()->userId)
            ->where('applicationAssessment.status', '!=', 'Deactivated')
            ->where('applications.aidyear', '=', Session::get('currentAidyear'))
	->orderBy('applicationType.typeID', 'asc')
	->orderBy('applicationAssessment.status', 'desc'))
        ->addColumn('Actions', function ($student)
        {
            if ($student->status == 'Waiting')
            {
                $crudLinks = link_to_route('showGrading', 'Start Application Assessment', $parameters = array($student->GUID), $attributes = array('class' => 'btn btn-success', 'alt' => 'showApplication','title' => 'Score ' . $student->firstName . '\'s Application'));
            }

            elseif ($student->status == 'Graded')
            {
                $crudLinks = link_to_route('showGrading', 'Application Assessment Complete', $parameters = array($student->GUID), $attributes = array('class' => 'btn btn-danger', 'alt' => 'showApplication', 'title' => 'Score ' . $student->firstName . '\'s Application'));
            }

            elseif ($student->status == 'Incomplete')
            {
                $crudLinks = link_to_route('showGrading','Continue Application Assessment', $parameters = array($student->GUID), $attributes = array('class' => 'btn btn-warning', 'alt' => 'showApplication', 'title' => 'Score ' . $student->firstName . '\'s Application'));
            }

            return $crudLinks;
        })->showColumns('received', 'firstName', 'lastName', 'studentID', 'aidyear', 'typeName')->searchColumns('lastName', 'student.studentID', 'typeName', 'received')->make();
    }

    public function getSpecificAssessment($guid)
    {
        return Datatable::query(DB::table('applicationAssessment')
        ->join('applications', 'applications.applicationID', '=', 'applicationAssessment.applicationID')
        ->join('user', 'user.userId', '=', 'applicationAssessment.userId')
        ->select('user.name', 'user.userId', 'applicationAssessment.essay', 'applicationAssessment.extra', 'applicationAssessment.faculty', 'applicationAssessment.total', 'applicationAssessment.assessorNotes', 'applicationAssessment.assessmentDate')
        ->where('applications.GUID', '=', $guid))
        ->showColumns('name', 'essay', 'extra', 'faculty', 'total', 'assessorNotes', 'assessmentDate')
        ->searchColumns('name')
        ->make();
    }
}
