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

    <div id="graduating" class="collegeInfo panel panel-primary">
        <div class="panel-heading">College Information - Graduating Students <font color="orange">{{ $errors ->
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

            <li>
                <label for="college">College Graduation:</label>
                <br>
                @if ($edu != null && $edu->collegeGrad != null)
                <?php
                $dbUserFriendlyDate = Date('F Y', strtotime($edu->collegeGrad));
                ?>
                @endif

                <input id="college" placeholder="College Graduation" autocomplete="off" name="coll" type="text"
                       value="{{{$dbUserFriendlyDate or Input::old('coll')}}}">
                <input name="collegeGrad" type="hidden" value="{{{$edu->collegeGrad or Input::old('collegeGrad')}}}"
                       id="realCollGrad">
            </li>
        </ul>
    </div>

    <div id="optionalGradInfo" class="collegeInfo panel panel-primary">
        <div class="panel-heading">Transfer Information - Optional <font color="orange">{{ $errors ->
                first('transferMaj')}}</font> <font color="orange">{{ $errors -> first('transferInsti')}}</font></div>
        <ul class="panel-body">
            <li>
                <label for="transferMaj">Transfering Major:</label>
                <br>
                <input placeholder="Transfer Major" autocomplete="off" name="transferMaj" type="text"
                       value="{{{$edu->transferMaj or Input::old('transferMaj')}}}" id="transferMaj">
                <br>
            </li>

            <li>
                <label for="transferInsti">Transfering Institution:</label>
                <br>
                <input placeholder="Institution" autocomplete="off" name="transferInsti" type="text"
                       value="{{{$edu->transferInsti or Input::old('transferInsti')}}}" id="transferInsti">
                <br>
            </li>
        </ul>
    </div>

    {{ Form::submit('Enter School Information', array('class' => 'btn btn-primary'))}}
    {{Form::close()}}

</div>

@stop
