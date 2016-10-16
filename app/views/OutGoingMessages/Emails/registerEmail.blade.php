@extends('OutGoingMessages.Emails.masterEmail')

@section('emailContent')

<h3>Message to {{{ $name }}},</h3>
<p>Thank you for registering for an account to ScholarInterface. You are currently unable to login because :</p>
<ul>
	<li>Your account hasn't been activated yet</li>
	<li>You have not been given a user role</li>
	<li>You have not been given permission to view yearly information</li>
</ul>
<p>Administrator(s) have been notified of this action and will take further action. Once you have been granted rights :</p>
<ul>
	<li>You will be emailed a temporary password</li>
	<li>You will have to update your account information on first login attempt only</li>
	<li>And you will also have to set up notification preferences</li>
</ul>

@stop
