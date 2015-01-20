@extends('Layouts.dashboards')

@section('head')
	<title>Scholarship Interface Students</title>
@parent
	<script type="text/javascript" src="{{asset('DataTables-1.9.4/media/js/jquery.dataTables.min.js')}}"></script>
	<link rel="stylesheet" type="text/css" href="{{asset('css/Admin/Application/applicationHome.css') }}">
	<link rel="stylesheet" type="text/css" href="{{asset('DataTables-1.9.4/media/css/demo_table_jui.css') }}">
@stop

@section('dashBoardContent')
<br>
	{{link_to_route('showNewStudent', 'Create Student')}}
<h4 class='reportName'>Students Overview</h4>
	{{ Datatable::table()
		->setURL(route('allStudentsJSON'))
		->addColumn('Actions', 'First', 'Last', 'Email', 'Email', 'Major', 'GPA')
		->setOptions('bProcessing', true)
		->setOptions('bSort', false)
		->setOptions('iDisplayLength', 25)
		->setOptions('aLengthMenu', [25, 50, 100, 125, 150, 200, 300, 400])
		->setOptions('bAutoWidth', false)
		->setOptions('aoColumns', array(
			array('sWidth' => '1%'),
			array('sWidth' => '15%'),
			array('sWidth' => '10%'),
			array('sWidth' => '1%'),
			array('sWidth' => '1%'),
			array('sWidth' => '1%'),
			array('sWidth' => '1%')
		))	
->render() }}
@stop