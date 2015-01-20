@extends('Layouts.dashboards')

@section('head')
<title>Scholarship Interface Entering Graduating Students</title>
@parent
<script type="text/javascript" src="{{asset('DataTables-1.9.4/media/js/jquery.dataTables.min.js')}}"></script>
<link rel="stylesheet" type="text/css" href="{{asset('DataTables-1.9.4/media/css/demo_table_jui.css') }}">
<script type="text/javascript" src="{{asset('DataTables-1.9.4/extras/TableTools/media/js/ZeroClipboard.js')}}"></script>
<script type="text/javascript" src="{{asset('DataTables-1.9.4/extras/TableTools/media/js/TableTools.js')}}"></script>
<link rel="stylesheet" type="text/css"
      href="{{asset('DataTables-1.9.4/extras/TableTools/media/css/TableTools_JUI.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('DataTables-1.9.4/extras/TableTools/media/css/TableTools.css')}}">
@stop

@section('dashBoardContent')
<br>
{{ link_to_route('showReportsHome', 'Return') }}
<br>
<h4 class='reportName'>All Awarded Entering Faculty Recommended Students</h4>
{{ Datatable::table()
->setURL(route('enteringFacultyAddress'))
->addColumn('Student ID', 'First', 'Last', 'Address 1', 'Address 2', 'City', 'State', 'Zip','HS', 'Amount', 'Department', 'Scholarship')
->setOptions('bProcessing', true)
->setOptions('bSort', true)
->setOptions('aaSorting', array(
array(2, 'asc')
))
->setOptions('iDisplayLength', 150)
->setOptions('aLengthMenu', [50, 100, 150, 200, 250, 300, 350, 400, 450])
->setOptions('bAutoWidth', false)
->setOptions('sDom', 'T<"clear">lrtip')
->setOptions('oTableTools', array(
'sSwfPath' => '/DataTables-1.9.4/extras/TableTools/media/swf/copy_csv_xls_pdf.swf',
'aButtons' => array(
array('sExtends' => 'csv', 'sButtonText' => 'CSV Export'),
array('sExtends' => 'copy', 'sButtonText' => 'Copy Current Set')
)
)
)
->setOptions('aoColumns', array(
array('sWidth' => '1%'),
array('sWidth' => '10%'),
array('sWidth' => '10%'),
array('sWidth' => '20%'),
array('sWidth' => '10%'),
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