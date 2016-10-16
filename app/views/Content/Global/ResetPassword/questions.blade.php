@extends('Layouts.master')

@section('head')
	<title>Scholarship Interface Reset Password</title>
		<link rel="stylesheet" type="text/css" href="{{asset('/css/Global/ResetPassword/questions.css') }}">

	@parent
	<script type="text/javascript" src="{{asset('/javascript/Global/ResetPassword/passResetCellNum.js')}}"></script>
@stop

@section('content')
	<div id="questions">
		{{ Form::open(array('route' => 'password.reset.doQuestions', 'method' => 'POST', 'accept-charset' => 'UTF-8', 'id' => 'myForm')) }}

			<div id="question1">
				{{$questions[0]->ques}}
				<br>
				{{ Form::label('answ1', 'Answer') }}
				<br>
				{{ Form::password('answ1', array('placeholder' => 'Answer')) }}
				<br>
				<font color="red">{{ $errors -> first('answ1')}}</font>
			</div>
			
			<div id="question2">
				{{$questions[1]->ques}}
				<br>
				{{ Form::label('answ2', 'Answer') }}
				<br>
				{{ Form::password('answ2', array('placeholder' => 'Answer')) }}
				<br>
				<font color="red">{{ $errors -> first('answ2')}}</font>
			</div>
			<br><br>
		</div>

@if(isset($number))
	<div id="cellPhoneCode">
		{{ Form::label('cellCode', 'Send code to cell phone?')}}
		<br>
		No:{{ Form::radio('cellCode', '0', true, array('id' => 'noCell')) }} 
		Yes:{{ Form::radio('cellCode', '1', false, array('id' => 'yesCell'))}}
		<br>
		<p id="hashedNumber">6 digit code will be sent to: <font color="red">{{{ $number }}}</font> </p>
		<br>
	</div>
@endif
	<div id="verifyAnswer">
		<img src="{{asset('images/Global/loader.gif')}}" style="display: none;" id="loading_image">
		{{ Form::submit('Verify Answers', array('class' => 'btn btn-primary')) }} {{ link_to_route('password.EmailStep', 'Return')}} or go to the {{ link_to_route('home.index', 'Home Page')}}
	</div>

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