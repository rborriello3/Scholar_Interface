@extends('Layouts.dashboards')

@section('head')
<title>Scholarship Interface New Meeting</title>
<link rel="stylesheet" type="text/css" href="{{asset('/css/Admin/Meeting/newMeeting.css')}}">
@parent
<script type="text/javascript" src="{{asset('/javascript/Admin/Meeting/newMeeting.js')}}"></script>
@stop

@section('dashBoardContent')
<h3>Create New Meeting</h3>

<div id="newMeetingForm">
    {{ Form::open(array('route' => array('doCreateMeeting'), 'method' => 'POST', 'accept-charset' => 'UTF-8', 'id' => 'myForm')) }}
    
    <ul>
        <li>
	    {{ Form::label('name', 'Meeting Name') }}
	</li>
	<li>
	    {{ Form::text('name', '', array('placeholder' => 'Meeting Name', 'autocomplete' => 'off')) }}
	</li>

	<li>
	    {{ Form::label('place', 'Meeting Place (Optional)') }}
	</li>
	<li>
	    {{ Form::text('place', '', array('placeholder' => 'Meeting Place (Optional)', 'autocomplete' => 'off')) }}
	</li>

	<li>
	    {{ Form::label('time', 'Time') }}
	</li>
	<li>
	    {{ Form::text('time', '', array('placeholder' => 'Time', 'autocomplete' => 'off')) }}
	</li>
    </ul>	

    <br>


