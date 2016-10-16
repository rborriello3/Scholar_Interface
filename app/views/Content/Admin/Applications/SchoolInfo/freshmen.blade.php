@extends('Layouts.dashboards')

@section('head')
<title>Scholarship Interface Student Demographics</title>
@parent
<link rel="stylesheet" type="text/css" href="{{asset('css/Admin/Application/schoolInfo.css') }}">
<script type="text/javascript" src="{{asset('/jqueryUI/js/jquery-ui-1.10.3.custom.min.js')}}"></script>
<link rel="stylesheet" type="text/css" href="{{asset('/jqueryUI/css/ui-darkness/jquery-ui-1.10.3.custom.min.css') }}">
<script type="text/javascript" src="{{asset('/javascript/Admin/Applications/schoolInfo.js')}}"></script>
@stop

@section('dashBoardContent')
<div class="appProgress">
    @include('_partials.ApplicationCompletion.' . Request::segment(3))
</div>

<br>
{{ link_to_route('endApplication', 'Cancel Application', array($appKey))}}
@if (Session::get('educationComplete') == 1)
{{ link_to_route('showEssays', 'Requirements >>', array($appKey), array('class' => 'appNav')) }} &nbsp;&nbsp;
@endif
{{ link_to_route('showStudentDemo', '<< Demographics', array($appKey), array('class' => 'appNav')) }}
<br>
<div id="educationalInfo">
    {{ Form::open(array('url' => route('doSchoolInfo', array($appKey)), 'method' => 'POST', 'accept-charset' =>
    'UTF-8')) }}
    {{ Form::hidden('type', $type)}}

    <div id="freshmen" class="collegeInfo panel panel-primary">
        <div class="panel-heading">College Information - Freshmen Students <font color="orange">{{ $errors ->
                first('highSchoolName')}}</font> <font color="orange">{{ $errors -> first('expectedCredits')}}</font>
            <font color="orange">{{ $errors -> first('highSchoolAvg')}}</font> <font color="orange">{{ $errors ->
                first('highGrad')}}</font></div>
        <ul class="panel-body">
            <li>
                <label for="highSchoolName">High School Name:</label>
                <br>
                <input placeholder="High School Name" autocomplete="off" name="highSchoolName" type="text"
                       value="{{{$edu->highSchoolName or Input::old('highSchoolName')}}}" id="highSchoolName">
            </li>

            <li>
                <label for="highSchoolAvg">Avg:</label>
                <br>
                <input placeholder="Avg" autocomplete="off" name="highSchoolAvg" type="text"
                       value="{{{$edu->highSchoolAvg or Input::old('highSchoolAvg')}}}" id="highSchoolAvg">
            </li>

            <li>
                <label for="highschool">High School Graduation:</label>
                <br>
                @if ($edu != null && $edu->highGrad != null)
                <?php
                $dbUserFriendlyDate = Date('F Y', strtotime($edu->highGrad));
                ?>
                @endif

                <input id="highschool" placeholder="High School Graduation" autocomplete="off" name="high" type="text"
                       value="{{{$dbUserFriendlyDate or Input::old('high')}}}">
                <input name="highGrad" type="hidden" value="{{{$edu->highGrad or Input::old('highGrad')}}}"
                       id="realHighGrad">
            </li>
        </ul>
    </div>

    {{ Form::submit('Enter School Information', array('class' => 'btn btn-primary'))}}
    {{Form::close()}}

</div>

@stop
