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
{{ link_to_route('endApplication', 'Cancel Application', array($appKey)) }}
@if (Session::get('educationComplete') == 1)
{{ link_to_route('showCompleteApp', 'Overview >>', array($appKey), array('class' => 'appNav')) }} &nbsp;&nbsp;
@endif
{{ link_to_route('showStudentDemo', '<< Demographics', array($appKey), array('class' => 'appNav')) }}
<br>
<div id="educationalInfo">
    {{ Form::open(array('url' => route('doSchoolInfo', array($appKey)), 'method' => 'POST', 'accept-charset' =>
    'UTF-8')) }}
    {{ Form::submit('Skip', array('class' => 'btn btn-primary', 'id' => 'skip'))}}
    <br>

    @if (Session::get('selecttype') == 2)
    <?php $highschool = true; ?>
    @else
    <?php $highschool = false; ?>
    @endif

    @if (Session::get('selecttype') == 6)
    <?php $college = true; ?>
    @else
    <?php $college = false; ?>
    @endif

    @if (Session::get('selecttype') == 0)
    <?php $none = true; ?>
    @else
    <?php $none = false; ?>
    @endif


    @if ($college|| $highschool)
    {{ Form::button('Reload Data', array('class' => 'btn btn-primary', 'onClick' => 'window.location.reload()'))}}
    @endif
    <br>

    Athlete is a:<br>No Answer: {{ Form::radio('selecttype', '', $none)}} <br>Freshmen Student: {{
    Form::radio('selecttype', 2, $highschool)}}<br>Returning Student: {{ Form::radio('selecttype', 6, $college)}}

    <div id="ath_fresh" class="collegeInfo panel panel-primary">
        <div class="panel-heading">College Information - Freshmen Athletes <font color="orange">{{ $errors ->
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
                <input id="highschool" placeholder="High School Graduation" autocomplete="off" name="high" type="text"
                       value="{{{Session::get('high')}}}">
                <input name="highGrad" type="hidden" value="{{{$edu->highGrad or Input::old('highGrad')}}}"
                       id="realHighGrad">
            </li>
        </ul>
    </div>

    <div id="returning" class="collegeInfo panel panel-primary">
        <div class="panel-heading">College Information - Returning Athletes <font color="orange">{{ $errors ->
                first('major')}}</font> <font color="orange">{{ $errors -> first('creditsEarned')}}</font> <font
                color="orange">{{ $errors -> first('GPA')}}</font> <font color="orange">{{ $errors ->
                first('collegeGrad')}}</font></div>
        <ul class="panel-body">
            <li>
                <label for="major">Major:</label>
                <br>
                <input placeholder="Major" autocomplete="off" name="major" type="text"
                       value="{{{$edu->major or Input::old('major')}}}" id="major">
                <br>
            </li>

            <li>
                <label for="creditsEarned">Earned:</label>
                <br>
                <input placeholder="Credits" autocomplete="off" name="creditsEarned" type="text"
                       value="{{{$edu->creditsEarned or Input::old('creditsEarned')}}}" id="creditsEarned">
                <br>
            </li>

            <li>
                <label for="GPA">GPA:</label>
                <br>
                <input placeholder="GPA" autocomplete="off" name="GPA" type="text"
                       value="{{{$edu->GPA or Input::old('GPA')}}}" id="GPA">
                <br>
            </li>
        </ul>
    </div>

    {{ Form::submit('Enter School Information', array('class' => 'btn btn-primary', 'id' => 'submit'))}}

    {{Form::close()}}
</div>

@stop
