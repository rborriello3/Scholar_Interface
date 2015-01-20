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

    <div id="returning_student" class="panel panel-primary collegeInfo">
        <div class="panel-heading">College Information - Returning Students <font color="orange">{{ $errors ->
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

    {{ Form::submit('Enter School Information', array('class' => 'btn btn-primary'))}}
    {{Form::close()}}

</div>

@stop
