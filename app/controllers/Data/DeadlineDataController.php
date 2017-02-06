<?php

class DeadlineDataController extends BaseController
{
    public function showAllDeadlinesJsonCRUD()
    {
	return Datatable::query(DB::table('deadline')
	    ->select('deadlineID', 'name', 'date', 'description', 'gradeGroup', 'status')
	    ->where('date', '>=', date('Y/m/d', strtotime('today')))
	    ->orderBy('date', 'asc'))
	    ->addColumn('Actions', function($deadline)
	    {
		$crudLinks = '<div class="btn-group">';
		if($deadline->status == '0')
		{
		    $crudLinks .= '<button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown">' . $deadline->name . '<span class="glyphicon glyphicon-arrow-down"></span></button>';
		    $statusLinks = '<li>' . link_to_route('activateDeadline', 'Activate Deadline', $parameters = array($deadline->deadlineID), $attributes = array('alt' => 'reactivateDeadline')) . '</li>';
		}
		else
		{
		    $crudLinks .= '<button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">' . $deadline->name . '<span class="glyphicon glyphicon-arrow-down"></span></button>';
		    $statusLinks = '<li>' . link_to_route('deactivateDeadline', 'Deactivate Deadline', $parameters = array($deadline->deadlineID), $attributes = array('alt' => 'cancelDeadline')) . '</li>';
		}

		$crudLinks .= '<ul class="dropdown-menu" role="menu">';
		//$crudLinks .= '<li>' . link_to_route('showEditDeadline', 'Edit Deadline', $parameters = array($deadline->deadlineID), $attributes = array('alt' => 'editDeadline')) . '</li>';
		$crudLinks .= $statusLinks;
		$crudLinks .= '</ul>';
		$crudLinks .= '</div>';
		return $crudLinks;
	    })
	    //Date is stored in 'YYYY/MM/DD' format so it's easier to compare dates in queries
	    //This function will change the display format to 'MM/DD/YYYY'
	    ->addColumn('date', function($deadline)
	    {
		$deadline->date = date('m/d/Y', strtotime($deadline->date));
		return $deadline->date;
	    })
	    ->showColumns('description')
	    ->addColumn('gradeGroup', function($deadline)
	    {
		$gradeGroup = explode(',', $deadline->gradeGroup);
		foreach($gradeGroup as $k => $v)
		{
		    if($v == 2)
		    {
			$gradeGroup[$k] = "All Entering Student Committee Members";
		    }
		    else if ($v == 4)
		    {
			$gradeGroup[$k] = "All Graduating Student Committee Members";
		    }
		    else if($v == 6)
		    {
			$gradeGroup[$k] = "All Returning Student Committee Members";
		    }
		}
		   
		$gradeGroup = implode(', ', $gradeGroup);
		return $gradeGroup;
	    })
	    ->make();
    }

    public function showCommMemberDeadlines()
    {
	$gradeGroup = User::find(Auth::user()->gradeGroup);
	return Datatable::query(DB::table('deadline')
	    ->select('deadlineID', 'name', 'date', 'description', 'gradeGroup', 'status')
	    ->where('date', '>=', date('Y/m/d', strtotime('today')))
	    ->where('gradeGroup', 'LIKE', '%' . $gradeGroup . '%')
	    ->orderBy('date', 'asc'))
	    ->addColumn('Actions', function($deadline)
	    {
		$crudLinks = '<div class="btn-group">';
		if($deadline->status == '0')
		{
		    $crudLinks .= '<button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown">' . $deadline->name . '<span class="glyphicon glyphicon-arrow-down"></span></button>';
		    //$statusLinks = '<li>' . link_to_route('activateDeadline', 'Activate Deadline', $parameters = array($deadline->deadlineID), $attributes = array('alt' => 'reactivateDeadline')) . '</li>';
		}
		else
		{
		    $crudLinks .= '<button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">' . $deadline->name . '<span class="glyphicon glyphicon-arrow-down"></span></button>';
		    //$statusLinks = '<li>' . link_to_route('deactivateDeadline', 'Deactivate Deadline', $parameters = array($deadline->deadlineID), $attributes = array('alt' => 'cancelDeadline')) . '</li>';
		}

		$crudLinks .= '<ul class="dropdown-menu" role="menu">';
		//$crudLinks .= '<li>' . link_to_route('showEditDeadline', 'Edit Deadline', $parameters = array($deadline->deadlineID), $attributes = array('alt' => 'editDeadline')) . '</li>';
		//$crudLinks .= $statusLinks;
		$crudLinks .= '</ul>';
		$crudLinks .= '</div>';
		return $crudLinks;
	    })
	    //Date is stored in 'YYYY/MM/DD' format so it's easier to compare dates in queries
	    //This function will change the display format to 'MM/DD/YYYY'
	    ->addColumn('date', function($deadline)
	    {
		$deadline->date = date('m/d/Y', strtotime($deadline->date));
		return $deadline->date;
	    })
	    ->showColumns('description') 
	    ->addColumn('gradeGroup', function($deadline)
	    {
		$gradeGroup = explode(',', $deadline->gradeGroup);
		foreach($gradeGroup as $k => $v)
		{
		    if($v == 2)
		    {
			$gradeGroup[$k] = "All Entering Student Committee Members";
		    }
		    else if ($v == 4)
		    {
			$gradeGroup[$k] = "All Graduating Student Committee Members";
		    }
		    else if($v == 6)
		    {
			$gradeGroup[$k] = "All Returning Student Committee Members";
		    }
		}
		   
		$gradeGroup = implode(', ', $gradeGroup);
		return $gradeGroup;
	    })
	    ->make();
    }
}
