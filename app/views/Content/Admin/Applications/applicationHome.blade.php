@extends('Layouts.dashboards')

@section('head')
<title>Scholarship Interface Applications</title>
@parent
<script type="text/javascript" src="{{asset('DataTables-1.9.4/media/js/jquery.dataTables.min.js')}}"></script>
<link rel="stylesheet" type="text/css" href="{{asset('css/Admin/Application/applicationHome.css') }}">
<link rel="stylesheet" type="text/css" href="{{asset('DataTables-1.9.4/media/css/demo_table_jui.css') }}">
@stop

@section('dashBoardContent')
<br>
{{ link_to_route('showType', 'New Application') }} &nbsp;&nbsp;

<div class="btn-group" style="float: right;">
    <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">Notifications <span
            class="glyphicon glyphicon-envelope"></span></button>
    <ul class="dropdown-menu" role="menu" id="menu">
        <li>
            {{ link_to_route('showEmailIncompleteApplications', 'Incomplete', array(), array())}}
        </li>
    </ul>
</div>
<br>
<br>

<div id="lower">
    <h4 class='reportName'>Application Overview</h4>
    {{ Datatable::table()
    ->setURL(route('allApplicationsJson'))
    ->addColumn('Actions', 'Received', 'First Name', 'Last Name', 'Student ID', 'Aid Year', 'Type')
    ->setOptions('bProcessing', true)
    ->setOptions('bSort', false)
    ->setOptions('iDisplayLength', 15)
    ->setOptions('aLengthMenu', [5, 10, 15, 20, 25, 30, 35, 40, 45, 50])
    ->setOptions('bAutoWidth', false)
    ->setOptions('aoColumns', array(
        array('sWidth' => '1%'),
        array('sWidth' => '1%'),
        array('sWidth' => '5%'),
        array('sWidth' => '5%'),
        array('sWidth' => '1%'),
        array('sWidth' => '2%'),
        array('sWidth' => '1%'),
    ))
    ->render() }}

</div>

@stop
