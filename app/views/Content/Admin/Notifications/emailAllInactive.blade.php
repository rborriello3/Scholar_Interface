@extends('Layouts.dashboards')

@section('head')
	<title>Scholarship Interface Notifications</title>
	@parent
	<link rel="stylesheet" type="text/css" href="{{asset('css/Admin/') }}">
@stop

@section('dashBoardContent')
<br>
{{ Form::open(array('url' => route('doEmailIncompleteApplications', array()), 'method' => 'POST', 'accept-charset' => 'UTF-8', 'id' => 'myForm')) }}

	{{ Form::label('password', 'Verify Your Password:')}}
	<br>
	{{ Form::password('password', array('placeholder' => '••••••••')) }}
	<br>
	<font color="red">{{ $errors -> first('password')}}</font>
	<br><br>
	<b>Select Group:</b> <br>
	Entering:&nbsp;{{ Form::radio('group', 2)}}<br> Graduating:&nbsp;{{ Form::radio('group', 4)}}<br>Returning:&nbsp;{{ Form::radio('group', 6)}}
	<br> <font color="red">{{ $errors -> first('group')}}</font>
	<br><br>
	
	{{ Form::label('subject', 'Subject:') }}
	<br>
	{{ Form::text('subject', '', array('placeholder' => 'Subject', 'autocomplete' => 'off')) }}
	<br>
	<font color="red">{{ $errors -> first('subject')}}</font>
	<br>

	{{ Form::label('messageBody', 'Message Body:')}}
	<br>
	{{ Form::textarea('messageBody')}}
	<br>
	<font color="red">{{ $errors -> first('messageBody')}}</font>
	<br>
	<br>
	<img src="{{asset('images/Global/loader.gif')}}" style="display: none;" id="loading_image">
	{{ Form::submit('Send Email', array('class' => 'btn btn-primary'))}}
{{ Form::close() }}

<script type="text/javascript">
	$('#myForm').submit(function() 
	{
	   	$('#loading_image').show(); // show animation
	   	$(':submit',this).attr('disabled','disabled'); // disables form submission
	   	return true; // allow regular form submission
	});
</script>
@stop