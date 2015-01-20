@extends('OutGoingMessages.Emails.masterEmail')

@section('emailContent')
<h3> Hello {{{$name}}},</h3>
    Below are your account activation steps:
    <h4>Step 1</h4>
    Navigate to the following link depending on where you are:<br>
    &nbsp;&nbsp;&nbsp;<a href="https://schol.occc">SUNY Orange - Using Campus Infrastucture</a><br>
    &nbsp;&nbsp;&nbsp;<a href="https://schol.sunyorange.edu">At Home - Using Personal Network</a><br>
    {{--&nbsp;&nbsp;&nbsp;<a href="https://192.168.2.2">Test Bench</a><br>--}}

    <h4>Step 2</h4>
    &nbsp;&nbsp;&nbsp;Copy and paste your email on file into the email field.<br>
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b><font color="red">{{{$email}}}</font></b><br>
    
    <h4>Step 3</h4>
	&nbsp;&nbsp;&nbsp;Carefully copy and paste your temporary password without any spaces into the password field.<br>
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b><font color="red">{{{$password}}}</font></b><br> 

	<h4>Step 4</h4>
	&nbsp;&nbsp;&nbsp;Once you login you will be prompted to update your account which includes:<br>
	<ul>
   	 	<li>Password update</li>
    	<li>Security qestions and answers for password recovery</li>
   	 	<li>Enable mobile notification <b>(This is optional)</b></li>
	</ul>

	<h4>Important Account Information:</h4>
	&nbsp;&nbsp;&nbsp;You are able to access <b>Scholarship Interface</b> until <b>{{{$yearTo}}}</b><br>
	&nbsp;&nbsp;&nbsp;<b><u>If you lose access before this date please notify the Super User(s) of the system</u></b>
@stop