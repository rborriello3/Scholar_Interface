@extends('Layouts.dashboards')

@section('head')
<title>Scholarship Interface Reports</title>
@parent
<link rel="stylesheet" type="text/css" href="{{asset('css/Admin/Reports/reports.css') }}">
@stop

@section('dashBoardContent')
	{{ Form::open(array('url' => route('doReportsSelection', array()), 'method' => 'POST', 'accept-charset' => 'UTF-8')) }}
		{{Form::select('report', $reports, '')}}

		{{ Form::submit('Process Request', array('class' => 'btn btn-primary'))}}
	{{ Form::close()}}
@stop
