@extends('Layouts.dashboards')

@section('head')
<title>Scholarship Interface Scoring Interface</title>
@parent
<script type="text/javascript" src="{{asset('DataTables-1.9.4/media/js/jquery.dataTables.min.js')}}"></script>
<link rel="stylesheet" type="text/css" href="{{asset('css/Committee/Scoring/scoringHome.css') }}">
<link rel="stylesheet" type="text/css" href="{{asset('DataTables-1.9.4/media/css/demo_table_jui.css') }}">
@stop

@section('dashBoardContent')
<?php 
/*
<div id="upper">
    {{ Form::open(array('url' => route('doPaginateRequest'), 'method' => 'POST', 'accept-charset' => 'UTF-8')) }}
    {{ Form::submit('Begin Mass Grading', array('class' => 'btn btn-primary'))}}

    {{ Form::radio('massGradeType', 0)}} All({{'<font color="green">' . $applicationCounts['allGraded'] . '</font> /
    <font color="red">' . $applicationCounts['all'] . '</font>'}}) &nbsp;

    {{ Form::radio('massGradeType', 2)}} Entering({{'<font color="green">' . $applicationCounts['enteringGraded'] .
        '</font> / <font color="red">'. $applicationCounts['entering'] . '</font>'}}) &nbsp;

    {{ Form::radio('massGradeType', 4)}} Graduating({{'<font color="green">' . $applicationCounts['graduatingGraded'] .
        '</font> / <font color="red">' . $applicationCounts['graduating'] . '</font>'}}) &nbsp;

    {{ Form::radio('massGradeType', 6)}} Returning({{'<font color="green">' . $applicationCounts['returningGraded'] .
        '</font> / <font color="red">' . $applicationCounts['returning'] . '</font>'}}) &nbsp;

    (<font color="green">Graded</font> / <font color="red">Total</font>)
    {{ Form::close() }}
    <font color="red">{{ $errors -> first('massGradeType')}}</font>
</div>
*/
?>
<div id="lower">
    <h4 class='reportName'>Applications Overview</h4>
    {{ Datatable::table()
    ->setURL(route('allGradingApplicationsJson'))
    ->addColumn('Actions', 'Received', 'First Name', 'Last Name', 'Student ID', 'Aid Year', 'Type')
    ->setOptions('bProcessing', true)
    ->setOptions('iDisplayLength', 15)
    ->setOptions('bSort', false)
    ->setOptions('aLengthMenu', [5, 10, 15, 20, 25, 30, 35, 40, 45, 50])
    ->setOptions('bAutoWidth', false)
    ->setOptions('aoColumns', array(
    array('sWidth' => '1%'),
    array('sWidth' => '1%'),
    array('sWidth' => '15%'),
    array('sWidth' => '15%'),
    array('sWidth' => '2%'),
    array('sWidth' => '3%'),
    array('sWidth' => '1%')
    ))
    ->render() }}

</div>

@stop
