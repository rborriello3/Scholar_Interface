@extends('Layouts.dashboards')

@section('head')
	<title>Scholarship Interface Deactivate User</title>
	@parent
@stop

@section('dashBoardContent')
<h3>Deactivate {{{$name}}}?</h3>

<div id="activateForm">
	{{ Form::open(array('route' => array('doDeactivateUser', $userID), 'method' => 'POST', 'accept-charset' => 'UTF-8', 'id' => 'myForm')) }}
		{{ Form::label('password', 'Verify Your Password') }}
		<br>
		{{ Form::password('password', array('placeholder' => '••••••••')) }}
		<br>
		<font color="red">{{ $errors -> first('password')}}</font>
		<br><br>
		<img src="{{asset('images/Global/loader.gif')}}" style="display: none;" id="loading_image">
		{{ Form::submit('Deactivate User', array('class' => 'btn btn-primary'))}} &nbsp;&nbsp;{{ link_to_route('showUsers', 'Cancel') }}
	{{ Form::close() }}
</div>

<script type="text/javascript">
	$('#myForm').submit(function() 
	{
	   	$('#loading_image').show(); // show animation
	   	$(':submit',this).attr('disabled','disabled'); // disables form submission
	   	return true; // allow regular form submission
	});
</script>

@stop