@extends('OutGoingMessages.Emails.masterEmail')

@section('emailContent')
<h3>Hello {{{ $adminName }}},</h3>

<p>This is a triggered event caused by a users registration. User information bellow:</p>
<ul>
	<li>Name: {{{ $name }}} </li>
	<li>Email: {{{ $email }}} </li>
	<li>Creation Date: {{ date('m/d/y - h:i A') }}</li>
</ul>
<p>Please be advised that without your permission the user will not be able to login. The steps that must be taken are as follows:</p>
<ul>
	<li>Assign the user a role</li>
	<li>Activate the users account</li>
	<li>Assign the user a starting and ending aid year</li>
</ul>

<p>Once you have completed the above the user will recieve an email notificaiton with a temporary password and instructions for 
logging in and updating their account information.
</p>

@stop