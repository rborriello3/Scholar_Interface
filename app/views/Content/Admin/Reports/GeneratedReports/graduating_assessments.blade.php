@extends('Layouts.dashboards')

@section('head')
<title>Scholarship Interface Graduating Assessments</title>
@parent
<script type="text/javascript" src="{{asset('DataTables-1.9.4/media/js/jquery.dataTables.min.js')}}"></script>
<link rel="stylesheet" type="text/css" href="{{asset('DataTables-1.9.4/media/css/demo_table_jui.css') }}">
<script type="text/javascript" src="{{asset('DataTables-1.9.4/extras/TableTools/media/js/ZeroClipboard.js')}}"></script>
<script type="text/javascript" src="{{asset('DataTables-1.9.4/extras/TableTools/media/js/TableTools.js')}}"></script>
<link rel="stylesheet" type="text/css" href="{{asset('DataTables-1.9.4/extras/TableTools/media/css/TableTools_JUI.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('DataTables-1.9.4/extras/TableTools/media/css/TableTools.css')}}">
@stop

@section('dashBoardContent')
<br>
{{ link_to_route('showReportsHome', 'Return') }}
<br>
    <h4 class='reportName'>Graduating Assessments</h4>
    @foreach ($commMembers as $v)
    <br>
       	<h4 class='reportName'>Assessments For {{{$v->name}}}</h4>
    	{{ Datatable::table()
    		->setURL(route('graduatingAssessmentsJSON', $v->userId))
    		->addColumn('Student', 'Essay', 'Extra', 'Faculty', 'Total', 'Notes From ' . $v->name, 'Date')
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
    @endforeach
@stop
