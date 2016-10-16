@extends('Layouts.dashboards')

@section('head')
	<title>Scholarship Interface Super User Dashboard</title>
	<link rel="stylesheet" type="text/css" href="{{asset('css/Global/Layouts/dashboardLayout.css')}}">
@parent
	<script type="text/javascript" src="{{asset('/javascript/Global/Dashboards/showDashboard.js')}}"></script>
@stop

@section('dashBoardContent')
<br>
<div class"btn-group" style="float: right;".
	<button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">Notifications <span class="glyphicon glyphicon-envelope"></span></button>
</div>
<br>
<br>
@stop
