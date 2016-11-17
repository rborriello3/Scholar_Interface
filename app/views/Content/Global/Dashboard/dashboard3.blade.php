@extends('Layouts.dashboards')

@section('head')
<title>Scholarship Interface Admin Dashboard</title>
@parent
<script type="text/javascript" src="{{asset('/jqueryUI/js/jquery-ui-1.10.3.custom.min.js')}}"></script>
<link rel="stylesheet" type="text/css" href="{{asset('/jqueryUI/css/ui-darkness/jquery-ui-1.10.3.custom.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('css/Admin/Events/viewEvents.css')}}">
<script type="text/javascript" src="{{asset('/javascript/Admin/Events/viewEvents.js')}}"></script>
@stop

@section('dashBoardContent')
<div class="events">
    <h3>Events</h3>

    <div>
	<div class = "longText">
	    <b>Event Name:</b>
	    <p>Event Name</p>
	</div>

	<div class = "longText">
	    <b>Date</b>
	    <p>Event Date</p>
	</div>

	<div class = "longText">
	    <b>Time</b>
	    <p>Event Time</p>
	</div>

	<div class = "longText">
	    <b>Place</b>
	    <p>Event Place</p><br>
	</div>
</div>
@stop
