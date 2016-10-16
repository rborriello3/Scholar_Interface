@extends('Layouts.dashboards')

@section('head')
<title>Scholarship Interface Background Processes</title>
@parent
<link rel="stylesheet" type="text/css" href="{{asset('css/Admin/Processes/newProcess.css') }}">
@stop

@section('dashBoardContent')
{{ Form::open(array('url' => route('doNewProcess', array()), 'method' => 'POST', 'accept-charset' => 'UTF-8')) }}
<br>
<div id="processInfo">
    {{ Form::label('processName', 'Process Name:') }} <br>
    {{ Form::text('processName', '', array('placeholder' => 'Name', 'autocomplete' => 'off')) }}
    <br>
    <font color="red">{{ $errors -> first('processName')}}</font>
    <br>

    {{ Form::label('processDescription', 'Description:') }} <br>
    {{ Form::text('processDescription', '', array('placeholder' => 'Description', 'autocomplete' => 'off')) }}
    <br>
    <font color="red">{{ $errors -> first('processDescription')}}</font>
</div>
<br>
<div id="time">
    <b>Schedule:</b><br>
    <font color="red">{{ $errors -> first('hour')}}</font> <font color="red">{{ $errors -> first('min')}}</font> <font
        color="red">{{ $errors -> first('meridian')}}</font>
    <br>
    {{ Form::select('hour', array(
    '' => '',
    '01' => '01',
    '02' => '02',
    '03' => '03',
    '04' => '04',
    '05' => '05',
    '06' => '06',
    '07' => '07',
    '08' => '08',
    '09' => '09',
    '10' => '10',
    '11' => '11',
    '12' => '12'
    ))}}
    :
    {{ Form::select('min', array(
    '' => '',
    '15' => '15',
    '30' => '30',
    '45' => '45',
    '00' => '00'
    ))}}

    {{ Form::select('meridian', array(
    '' => '',
    'AM' => 'AM',
    'PM' => 'PM'
    ))}}
    <br>
</div>

<div id="frequency">
    <?php $days = array('Sun' => 'Sun', 'Mon' => 'Mon', 'Tue' => 'Tue', 'Wed' => 'Wed', 'Thur' => 'Thur', 'Fri' => 'Fri', 'Sat' => 'Sat') ?>

    @foreach($days as $k => $v)
    {{ Form::checkbox('days[]', $k)}} {{$v}} &nbsp;&nbsp;
    @endforeach
    <br>
    <font color="red">{{ $errors -> first('days[]')}}</font>
    <br>
    <b>Repeat?</b> <br>
    No {{ Form::radio('repeat', 0)}} Yes {{ Form::radio('repeat', 1)}}
    <br>
    <br>
</div>

<div id="job">
    {{ Form::label('scriptLocation', 'Process Name:')}}
    <br>
    {{ Form::select('scriptLocation', $jobs, '')}}
    <br>
    <font color="red">{{ $errors -> first('scriptLocation')}}</font>
    <br>
</div>
<br>
{{ Form::submit('Create Process', array('class' => 'btn btn-primary'))}}
{{ link_to_route('showProcesses', 'Cancel') }}
{{ Form::close() }}
@stop