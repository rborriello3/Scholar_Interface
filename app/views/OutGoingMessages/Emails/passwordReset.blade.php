@extends('OutGoingMessages.Emails.masterEmail')

@section('emailContent')
<h3>Hello {{{ $name }}},</h3>
<p>
	Below are links that depending where you are located will send you to the correct update screen. There are two because one address is for use within the SUNY Orange network, the other for use at a personal/home network.
</p>

<a href="https://schol.occc/password/reset/update/{{{$token}}}">SUNY Orange - Using Campus Infrastucture<a><br>
<a href="https://schol.sunyorange.edu/password/reset/update/{{{$token}}}">At Home - Using Personal Network</a><br>
<a href="https://192.168.2.2/password/reset/update/{{{$token}}}">Test Bench</a><br><br>

<p>This link will only be active for no more than 5 minutes. Once the
    time frame has been met, the link will expire and a new email will be
    sent to you.</p>

<p>For your convenience you can add a cell phone number to our system, and when ever you forget your password a code
    will be sent via text message making the recovery process much easier.</p>

@stop