@extends('OutGoingMessages.Cron.Emails.emailCronMaster')

@section('queueContent')

<h3>Hello {{{ $name }}},</h3>

{{{ $body }}}

@if ($notFoundCount > 0)
<hr/>
While the automatic process was running, <b>Scholarship Interface</b> encountered <font color="red">{{{$notFoundCount}}}</font> application(s) that do not match a students A-Number.
<br/>
Below is a list of all the applications that do not match an A-Number provided by the Banner CSV file. Please keep in mind these errors were triggered because the A-Number does not match.
<ul>
    @foreach($notFound as $error)
    <li>{{{$error}}}</li>
    @endforeach
</ul>
@endif

@if ($multiAppCount > 0)
<hr/>
<b>Scholarship Interface</b> encountered <font color="red">{{{$multiAppCount}}}</font> duplicate application(s).
<br/>
Below is a list of all the students who have duplicated applications. Please keep in mind these errors were triggered because for the current active aidyear, has an application
already exists for the student.
<ul>
    @foreach($multiApp as $error1)
    <li>{{{$error1}}}</li>
    @endforeach
</ul>
@endif

@if ($noAppCount > 0)
<hr/>
<b>Scholarship Interface</b> encountered <font color="red">{{{$noAppCount}}}</font> recommendation(s) with no corresponding applications.
<br/>
Below is a list a of all students with recommendations that have yet submit an application.
    <ul>
    @foreach ($appNotFound as $error2)
        <li>{{{$error2}}}</li>
    @endforeach
    </ul>
@endif

@stop