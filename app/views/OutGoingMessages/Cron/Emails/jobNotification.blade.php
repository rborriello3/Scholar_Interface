@extends('OutGoingMessages.Cron.Emails.emailCronMaster')

@section('queueContent')
<h3>Hello {{{ $name }}},</h3>
<br/>

{{{ $body }}}
@stop