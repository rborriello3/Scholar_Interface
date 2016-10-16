@extends('OutGoingMessages.Emails.masterEmail')

@section('emailContent')
<h3>Hello {{{$name}}},</h3>
<p>
	Please copy and paste the following password: <font color="red">{{$password}}</font> into the password field located on either of these pages depending on where you are located.<br>
	<a href="https://schol.occc">SUNY Orange - Using Campus Infrastucture<a><br>
	<a href="https://schol.sunyorange.edu">At Home - Using Personal Network</a>
</p>

<p>Once you login you will be prompted to update your account which includes:</p>
<ul>
    <li>Password</li>
    <li>Security qestions and answers</li>
    <li>If you want mobile notification (text messaging) (This is optional)</li>
</ul>

<p>This is the same step you first completed when you were first given access. Any questions please contact the Super
    User(s)</p>
@stop