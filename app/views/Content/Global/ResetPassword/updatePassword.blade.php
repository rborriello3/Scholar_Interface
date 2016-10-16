@extends('Layouts.master')

@section('head')
	<title>Scholarship Interface Reset Password</title>
		<link rel="stylesheet" type="text/css" href="{{asset('/css/Global/ResetPassword/updatePass.css') }}">

	@parent
@stop

@section('content')
	<div id="upDatePassword">
		{{ Form::open(array('url' => route('password.reset.doUpdate', array($token)), 'method' => 'POST', 'accept-charset' => 'UTF-8', 'id' => 'myForm')) }}

			{{ Form::label('password', 'Password') }}
			<br>
			{{ Form::password('password', array('placeholder' => '••••••••'))}}
			<br>
			<font color="red">{{ $errors -> first('password')}}</font>
	
		<br>

			{{ Form::label('password_confirmation', 'Password') }}
			<br>
			{{ Form::password('password_confirmation', array('placeholder' => '••••••••'))}}
			<br>
			<font color="red">{{ $errors -> first('password_confirmation')}}</font>

		<br>
			<img src="{{asset('images/Global/loader.gif')}}" style="display: none;" id="loading_image">
			{{ Form::submit('Update Password', array('class' => 'btn btn-primary')) }}
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