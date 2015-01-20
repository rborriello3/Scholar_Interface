@extends('OutGoingMessages.Cron.Emails.emailCronMaster')

@section('queueContent')
<h3>Hello {{{ $name }}},</h3>

{{{ $body }}}

@if ($error > 0)
<hr/>
    While the automatic process was running, <b>Scholarship Interface</b> encountered <font color="red">{{{$error}}}</font> error(s).
<br/>
    Below is list of all the erros. Please keep in mind these errors were triggered because the A-Number does not match.
    <ul>
    @foreach($errorMessage as $error)
        <li>{{{$error}}}</li>
    @endforeach
    </ul>
@endif

@stop