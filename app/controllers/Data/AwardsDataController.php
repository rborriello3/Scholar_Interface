<?php

class AwardsDataController extends BaseController
{
	public function awardsJSON()
	{
	    return Datatable::query(DB::table('scholarshipAwards')
	    		->join('student', 'student.studentID', '=', 'scholarshipAwards.studentID')
	    		->join('awardStatus', 'awardStatus.awardStatusID', '=', 'scholarshipAwards.awardStatus')
	    		->leftjoin('applications', 'applications.studentID', '=', 'student.studentID')
	    		->join('scholarships', 'scholarships.fundCode', '=', 'scholarshipAwards.fundCode')
	 			->select('student.studentID', 'firstName', 'lastName', 'awardAmount', 'awardStatus.description', 'aidyear', 'scholarshipName', 'scholarships.fundCode')
                ->where('applications.aidyear', '=', Session::get('currentAidyear'))
	 			->orderBy('lastName', 'asc')
	 			)
	    ->addColumn('actions', function($award)
	    {
	    	$crudLinks = '<div class="btn-group">';
 	           
 	           	if ($award->description == 'Awarded')
 	           	{
	 	           	$crudLinks .= '<button type="button" class="btn btn-warning dropdown-toggle" data-toggle="dropdown">' . $award->studentID . '<span class="glyphicon glyphicon-arrow-down"></span></button>';
					$statusLinks = '<li>' . link_to_route('doDeactivateAward', 'Deactivate Award', $parameters = array($award->fundCode, $award->studentID), $attributes = array('alt' => 'deactivateAward')) . '</li>';
					$statusLinks .= '<li>' . link_to_route('doAcceptAward', 'Accept Award', $parameters = array($award->fundCode, $award->studentID), $attributes = array('alt' => 'acceptAward')) . '</li>';            
 	           	}
 	           	elseif ($award->description == 'Accepted')
 	           	{
	 	           	$crudLinks .= '<button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">' . $award->studentID . '<span class="glyphicon glyphicon-arrow-down"></span></button>';
					$statusLinks = '<li>' . link_to_route('doDeactivateAward', 'Deactivate Award', $parameters = array($award->fundCode, $award->studentID), $attributes = array('alt' => 'deactivateAward')) . '</li>';
					$statusLinks .= '<li>' . link_to_route('doRevokeAward', 'Revoke Award', $parameters = array($award->fundCode, $award->studentID), $attributes = array('alt' => 'revokeAward')) . '</li>';
	           	}
	           	elseif ($award->description == 'Deactivated')
	           	{
	 	           	$crudLinks .= '<button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown">' . $award->studentID . '<span class="glyphicon glyphicon-arrow-down"></span></button>';
					$statusLinks = '<li>' . link_to_route('doActivateAward', 'Activate Award', $parameters = array($award->fundCode, $award->studentID), $attributes = array('alt' => 'ativateAward')) . '</li>';            
					$statusLinks .= '<li>' . link_to_route('doAcceptAward', 'Accept Award', $parameters = array($award->fundCode, $award->studentID), $attributes = array('alt' => 'acceptAward')) . '</li>';            
	           	}

            	$crudLinks .= '<ul class="dropdown-menu" role="menu">';
            		$crudLinks .= $statusLinks;
            		$crudLinks .= '<li>' . link_to_route('showMessageStudent', 'Message Student', $parameters = array($award->studentID), $attributes = array('alt' => 'messageStudent',)) . '</li>';            
            		$crudLinks .= '<li>' . link_to_route('showEditStudent', 'View/Edit Student', $parameters = array($award->studentID), $attributes = array('alt' => 'editStudent')) . '</li>';
   		            $crudLinks .= '<li>' . link_to_route('showStudentApplications', 'View Application(s)', $parameters = array($award->studentID), $attributes = array('alt' => 'viewApplication')) . '</li>';
                    $crudLinks .= '<li>' . link_to_route('showAwardHistory', 'Award History', $parameters = array($award->studentID), $attributes = array('alt' => 'viewAwards')) . '</li>';
            	$crudLinks .= '</ul>';
            
            $crudLinks .= '</div>';

            return $crudLinks;
	    })
	    ->showColumns('description', 'firstName', 'lastName', 'fundCode', 'scholarshipName')
	    ->addColumn('amount', function($award)
	    {
	    	return '$'.$award->awardAmount;
	    })
	    ->showColumns('aidyear')
	    ->searchColumns('student.studentID', 'firstName', 'lastName', 'description', 'aidyear', 'scholarshipName', 'scholarships.fundCode')
	    ->make();
	}
}