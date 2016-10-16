@extends('Layouts.dashboards')

@section('head')
    <title>Viewing Messages</title>
    @parent
    <script type="text/javascript" src="{{asset('/jqueryUI/js/jquery-ui-1.10.3.custom.min.js')}}"></script>
    <link rel="stylesheet" type="text/css" href="{{asset('/jqueryUI/css/ui-darkness/jquery-ui-1.10.3.custom.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{asset('css/Admin/Notifications/viewHistory.css') }}">
    <script type="text/javascript" src="{{asset('/javascript/Admin/Notifications/viewHistory.js')}}"></script>
@stop

@section('dashBoardContent')

    <br/>{{link_to_route('homeNotifications', 'Return')}}

        {{link_to_route('showMessageStudent', 'Message ' . substr($info[0]['to'], strpos($info[0]['to'], '-') + 1), array($info[0]['aNum']))}}
    <br/><br/>

    @foreach($info as $i)
        <div class="history">
            <h3>{{'<font color="orange">Subject:</font> ' . $i['subject'] . ' <font color="orange">Date:</font> ' . $i['time']}} </h3>
            <div>
                <div id="messInfo">
                    <ul>
                        <li>
                            <b>From: </b>
                            <br/>
                            {{$i['from']}}
                        </li>
                        <br/> <br/>
                        <li>
                            <b>Message:</b>
                            <br/>
                            {{html_entity_decode(nl2br($i['message']))}}
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <br/>
    @endforeach
@stop