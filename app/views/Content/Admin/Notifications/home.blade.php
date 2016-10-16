@extends('Layouts.dashboards')

@section('head')
	<title>Scholarship Interface Notifications</title> 
	@parent
	<script type="text/javascript" src="{{asset('DataTables-1.9.4/media/js/jquery.dataTables.min.js')}}"></script>
	<link rel="stylesheet" type="text/css" href="{{asset('css/Admin/Awards/showAwardsTable.css') }}">
	<link rel="stylesheet" type="text/css" href="{{asset('DataTables-1.9.4/media/css/demo_table_jui.css') }}">
@stop

@section('dashBoardContent')
<br>
{{--link_to_route('showNewAwards', 'Award Students')--}}
<br>
	{{ Datatable::table()
		->setURL(route('getAllNotifications'))
		->addColumn('Actions', 'Subject', 'To Student', 'To User', 'Sender', 'Message Type', 'Sent')
		->setOptions('bProcessing', true)
		->setOptions('bSort', false)
		->setOptions('iDisplayLength', 20)
		->setOptions('aLengthMenu', [25, 50, 75, 100, 150, 200])
		->setOptions('bAutoWidth', false)
		->setOptions('aoColumns', array(
			array('sWidth' => '1%'),
			array('sWidth' => '1%'),
			array('sWidth' => '1%'),
			array('sWidth' => '1%'),
			array('sWidth' => '1%'),
			array('sWidth' => '1%'),
			array('sWidth' => '1%')
		))
	->render() }}

@stop

