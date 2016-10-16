@extends('Layouts.dashboards')

@section('head')
<title>Scholarship Interface Send Message</title>
	@parent
	<link rel="stylesheet" type="text/css" href="{{asset('css/Admin/Notifications/messageStudent.css') }}">
	<script type="text/javascript" src="{{asset('javascript/Admin/Notifications/messageStudent.js')}}"></script>
@stop

@section('dashBoardContent')
<br>
	{{ Form::open(array('url' => route('doMessageStudent', array($student->studentID)), 'method' => 'POST', 'accept-charset' => 'UTF-8', 'id' => 'myForm')) }}
		Send <strong>{{$student->firstName}} {{$student->lastName}}</strong> a message
<br>	
<br>
		{{ Form::label('subject', 'Subject:') }}
<br>
		{{ Form::text('subject', '', array('placeholder' => 'Subject', 'autocomplete' => 'off', 'size' => '49')) }}
<br>
		<font color="red">{{ $errors -> first('subject')}}</font>
<br>

		{{ Form::label('messageBody', 'Message Body:')}}
<br>
		{{ Form::textarea('messageBody', 'Dear ' . $student->firstName . ' ' . $student->lastName . ',')}}
<br>
		<font color="red">{{ $errors -> first('messageBody')}}</font>
<br>
<br>
		<img src="{{asset('images/Global/loader.gif')}}" style="display: none;" id="loading_image">
		{{ Form::submit('Send Email', array('class' => 'btn btn-primary'))}} {{link_to_route('showStudentHome', 'Back to students')}}
	{{ Form::close() }}

<script type="text/javascript">
    $('#myForm').submit(function () {
        $('#loading_image').show(); // show animation
        $(':submit', this).attr('disabled', 'disabled'); // disables form submission
        return true; // allow regular form submission
    });
</script>
@stop