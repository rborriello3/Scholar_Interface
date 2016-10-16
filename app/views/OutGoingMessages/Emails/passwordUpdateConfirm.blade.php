@extends('OutGoingMessages.Emails.masterEmail')

@section('emailContent')
<h3>Hello {{{ $name }}},</h3>
<p>Your password has been succesfully updated on {{ date('l M d, Y') }} at
{{{ date('h:i A') }}}</p>
<p>If this reset was not triggered by you please get in contact with the 
Super User</p>
@stop