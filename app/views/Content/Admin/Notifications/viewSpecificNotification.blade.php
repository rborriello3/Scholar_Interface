@extends('Layouts.dashboards')

@section('head')
    <title>Viewing Message</title>
    @parent
    <link rel="stylesheet" type="text/css" href="{{asset('css/Admin/Notifications/singleMessage.css') }}">
@stop

@section('dashBoardContent')

    <br/>{{link_to_route('homeNotifications', 'Return')}}

    @if (strpos($info['to'], 'Student-') !== false)
        <br/>
        {{link_to_route('showMessageStudent', 'Message ' . substr($info['to'], strpos($info['to'], '-') + 1), array($info['aNum']))}}
    @endif
    <br/><br/>

    <div id="sendingInfo" class="panel panel-primary">
        <div class="panel-heading">Sending Info</div>
        <ul class="panel-body">
            <li>
                <b>Sent Time:</b>
                <br>
                {{{$info['time']}}}
            </li>

            <li>
                <b>Sender:</b>
                <br>
                {{{$info['from']}}}
            </li>
        </ul>
    </div>

    <div id="recInfo" class="panel panel-primary">
        <div class="panel-heading">Receiving Info</div>
        <ul class="panel-body">
            <li>
                <b>Receiving Party:</b>
                <br/>
                {{{substr($info['to'], strpos($info['to'], '-') + 1)}}}

                @if (strpos($info['to'], 'Student-') !== false)
                    {{{' - ' . $info['aNum']}}}
                @endif

            </li>
            <li>
                @if (strpos($info['to'], 'Student-') !== false)
                    <b>Student History:</b>
                    <br/>
                    {{link_to_route('showStudentMessageHistory', 'View History', array($info['aNum']))}}
                @else
                    <b>User History:</b>
                    <br/>
                    {{link_to_route('showUserMessageHistory', 'View History', array($info['toUserID']))}}
                @endif
            </li>
        </ul>
    </div>

    <div id="messInfo" class="panel panel-primary">
        <div class="panel-heading">Message</div>
        <ul class="panel-body">
            <li>
                <b>Subject:</b>
                <br/>
                {{$info['subject']}}
            </li>
            <br/>
            <li>
                <b>Message:</b>
                <br/>
                {{html_entity_decode(nl2br($info['message']))}}
            </li>
        </ul>
    </div>
@stop