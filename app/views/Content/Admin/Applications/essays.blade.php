@extends('Layouts.dashboards')

@section('head')
<title>Scholarship Interface New Application</title>
@parent
<link rel="stylesheet" type="text/css" href="{{asset('css/Admin/Application/essays.css') }}">
@stop

@section('dashBoardContent')

<br>
{{ link_to_route('endApplication', 'Cancel Application', array($appKey)) }}
@if (Session::get('requirementsComplete') == 1)
{{ link_to_route('showRecomms', 'Recommendations >>', array($appKey), array('class' => 'appNav')) }} &nbsp;&nbsp;
@endif
{{ link_to_route('showSchoolInfo', '<< School Information', array($appKey), array('class' => 'appNav')) }}
<br>

<div id="essays">
    {{ Form::open(array('url' => route('doEssays', array($appKey)), 'method' => 'POST', 'accept-charset' => 'UTF-8')) }}
    <div class="panel panel-primary">
        <div class="panel-heading">Mandatory Essay <font color="orange">{{ $errors -> first('essay')}}</font></div>
        <ul class="panel-body">
            <li>
                <textarea name="essay" class="panel-body" rows="5" cols="60">{{{$essay or
                    Input::old('essay')}}}</textarea>
            </li>
        </ul>
    </div>

    <div class="panel panel-primary">
        <div class="panel-heading">Extra Curricular <font color="orange">{{ $errors -> first('extraCurricular')}}</font>
        </div>
        <ul class="panel-body">
            <li>
                <textarea name="extraCurricular" class="panel-body" rows="5" cols="60">{{{$extraCurricular or
                    Input::old('extraCurricular')}}}</textarea>
            </li>
        </ul>
    </div>
</div>

{{ Form::submit('Process Mandatory Essays', array('class' => 'btn btn-primary'))}}
{{ Form::close()}}

@stop