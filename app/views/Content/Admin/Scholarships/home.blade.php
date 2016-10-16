@extends('Layouts.dashboards')

@section('head')
	<title>Scholarship Interface Scholarships</title>
@parent
	<link rel="stylesheet" type="text/css" href="{{asset('css/Admin/Scholarships/showAllScholarships.css') }}">
	<script type="text/javascript" src="{{asset('DataTables-1.9.4/media/js/jquery.dataTables.min.js')}}"></script>
	<link rel="stylesheet" type="text/css" href="{{asset('css/Admin/Application/applicationHome.css') }}">
	<link rel="stylesheet" type="text/css" href="{{asset('DataTables-1.9.4/media/css/demo_table_jui.css') }}">
@stop

@section('dashBoardContent')
<br>
{{link_to_route('showCreateSchol', 'Create Scholarship')}}
<br><br>
<h4 class='reportName'>Scholarships Overview</h4>
	{{ Datatable::table()
		->setURL(route('allScholarshipsJSON'))
		->addColumn('Actions', 'Name', 'Amount', 'Requirements','Description', 'Years', 'Basis', 'Recipients', 'Equal?', 'Donor Name', 'Donor Number', 'Donor Email', 'Donor Address', 'Application Types')
		->setOptions('bProcessing', true)
		->setOptions('bSort', false)
		->setOptions('iDisplayLength', 50)
		->setOptions('aLengthMenu', [50, 100, 125, 150, 200, 300, 400])
		->setOptions('bAutoWidth', false)	
		->setOptions('aoColumns', array(
			array('sWidth' => '1%'),
			array('sWidth' => '8%'),
			array('sWidth' => '2%'),
			array('sWidth' => '1%'),
			array('sWidth' => '40%'),
			array('sWidth' => '1%'),
			array('sWidth' => '1%'),
			array('sWidth' => '1%'),
			array('sWidth' => '1%'),
                        array('sWidth' => '1%'),
			array('sWidth' => '1%'),
			array('sWidth' => '1%'),
			array('sWidth' => '1%'),
			array('sWidth' => '1%'),

		))	
->render() }}
@stop
