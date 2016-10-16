@extends('Layouts.dashboards')

@section('head')
<title>Scholarship Interface Background Processes</title>
@parent
<link rel="stylesheet" type="text/css" href="{{asset('css/Admin/Processes/processHome.css') }}">
<script type="text/javascript" src="{{asset('DataTables-1.9.4/media/js/jquery.dataTables.min.js')}}"></script>
<link rel="stylesheet" type="text/css" href="{{asset('css/Admin/Application/applicationHome.css') }}">
<link rel="stylesheet" type="text/css" href="{{asset('DataTables-1.9.4/media/css/demo_table_jui.css') }}">
@stop

@section('dashBoardContent')
<br>
{{ Link_to_route('showNewProcess', 'New Process')}}
<br>
{{ Link_to_route('showDataUpload', 'Upload Data')}}
<br>
<h4 class='reportName'>Processes Overview</h4>
{{ Datatable::table()
->setURL(route('allProcessesJSON'))
->addColumn('Actions', 'Process', 'Time', 'Day(s)', 'User', 'Description', 'Count', 'Repeats?')
->setOptions('bProcessing', true)
->setOptions('bSort', false)
->setOptions('iDisplayLength', 5)
->setOptions('aLengthMenu', [5, 10, 15, 20, 25, 30, 35, 40, 45, 50])
->setOptions('bAutoWidth', false)
->setOptions('aoColumns', array(
array('sWidth' => '1%'),
array('sWidth' => '2%'),
array('sWidth' => '1%'),
array('sWidth' => '1%'),
array('sWidth' => '1%'),
array('sWidth' => '5%'),
array('sWidth' => '1%'),
array('sWidth' => '1%')
))
->render() }}
@stop