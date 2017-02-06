@extends('Layouts.dashboards')

@section('head')
<title>Scholarship Interface Committee Dashboard</title>
@parent
<script type="text/javascript" src="{{asset('/jqueryUI/js/jquery-ui-1.10.3.custom.min.js')}}"></script>
<script type="text/javascript" src="{{asset('/javascript/Admin/Meeting/viewMeetings.js')}}"></script>
<script type="text/javascript" src="{{asset('DataTables-1.9.4/media/js/jquery.dataTables.min.js')}}"></script>
<link rel="stylesheet" type="text/css" href="{{asset('/jqueryUI/css/ui-darkness/jquery-ui-1.10.3.custom.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('DataTables-1.9.4/media/css/demo_table_jui.css') }}">
<link rel="stylesheet" type="text/css" href="{{asset('/css/Admin/Meeting/viewMeetings.css')}}">

@stop

@section('dashBoardContent')
    <div>
	<h1>Assessment Count (Completed Assessments/Total Assessements): {{{$countGraded}}}/{{{$countTotal}}}</h1>
        <br>
    </div>
<div class="dashboardMenu">
    <h3>Assessments</h3>

    <div class="container">
    	{{ Datatable::table()
    		->setURL(route('showSingleCommMemberAssessments', $userId->userId))
    		->addColumn('Student', 'Essay', 'Extra', 'Faculty', 'Total', 'Notes From ' . $userId->name, 'Date')
    		->setOptions('bProcessing', true)
    		->setOptions('bSort', true)
    		->setOptions('aaSorting', array(
            	array(0, 'asc')
        	))
        	->setOptions('iDisplayLength', 10)
    		->setOptions('aLengthMenu', [10, 20, 30, 40, 50, 100, 150, 200])
    		->setOptions('bAutoWidth', false)
    		->setOptions('sDom', 'T<"clear">lrtip')
    		->setOptions('oTableTools', array(
        		'sSwfPath' => '/DataTables-1.9.4/extras/TableTools/media/swf/copy_csv_xls_pdf.swf',
        		'aButtons' => array(
            			array('sExtends' => 'csv', 'sButtonText' => 'CSV Export'),
                        array('sExtends' => 'print', 'sButtonText' => 'Print'),
            			array('sExtends' => 'copy', 'sButtonText' => 'Copy Current Set')
            		)
        		)
    		) 	
    		->setOptions('aoColumns', array(
    				array('sWidth' => '15%'),
    				array('sWidth' => '1%'),
    				array('sWidth' => '1%'),
    				array('sWidth' => '1%'),
    				array('sWidth' => '1%'),
    				array('sWidth' => '40%'),
    				array('sWidth' => '1%')

    			)  
    		)
    		->render() }}
    </div>

</div>
<div class="dashboardMenu">
    <h3>Meetings</h3>
    <div class="container">
   	{{ Datatable::table()
	->setURL(route('showCommMemberMeetings'))
	->addColumn('Actions', 'Date', 'Time', 'Place', 'Grade Group/Person')
	->setOptions('bProcessing', true)
	->setOptions('bSort', false)
	->setOptions('iDisplayLength', 5)
	->setOptions('aLengthMenu', [5, 10, 15, 20, 25, 30, 35, 40, 45, 50])
	->setOptions('bAutoWidth', false)
	->setOptions('aoColumns', array(
	    array('sWidth' => '1%'),
	    array('sWidth' => '1%'),
	    array('sWidth' => '1%'),
	    array('sWidth' => '5%'),
  	    array('sWidth' => '20%')
	))
	->render() }}	        
    </div>
</div>

<div class="dashboardMenu">
    <h3>Deadlines</h3>
    <div class="container">
        {{ Datatable::table()
	->setURL(route('showCommMemberDeadlines'))
	->addColumn('Actions', 'Date', 'Description', 'Grade Group')
	->setOptions('bProcessing', true)
	->setOptions('bSort', false)
	->setOptions('iDisplayLength', 5)
	->setOptions('aLengthMenu', [5, 10, 15, 20, 25, 30, 35, 40, 45, 50])
	->setOptions('bAutoWidth', false)
	->setOptions('aoColumns', array(
	    array('sWidth' => '1%'),
	    array('sWidth' => '1%'),
	    array('sWidth' => '1%'),
	    array('sWidth' => '20%')
	))
	->render() }}
    </div>
</div>
@stop
