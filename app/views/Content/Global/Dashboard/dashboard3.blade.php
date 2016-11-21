@extends('Layouts.dashboards')

@section('head')
<title>Scholarship Interface Admin Dashboard</title>
@parent
<script type="text/javascript" src="{{asset('/jqueryUI/js/jquery-ui-1.10.3.custom.min.js')}}"></script>
<link rel="stylesheet" type="text/css" href="{{asset('/jqueryUI/css/ui-darkness/jquery-ui-1.10.3.custom.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('/css/Admin/Events/viewEvents.css')}}">
<script type="text/javascript" src="{{asset('/javascript/Admin/Events/viewEvents.js')}}"></script>

<link rel="stylesheet" type="text/css" href="{{asset('css/Admin/Application/viewApplications.css')}}">
<script type="text/javascript" src="{{asset('/javascript/Admin/Applications/viewApplications.js')}}"></script>
@stop

@section('dashBoardContent')
<div class="eventsMenu">
    <h3>Events</h3>

    <div>
	<div class = "col-xs-4">
	    <b>Event Name:</b>
	    <b>Event Date:</b>
	    <b>Event Time:</b>
	    <b>Event Place:</b>
	</div>
    </div>
</div>
@stop
