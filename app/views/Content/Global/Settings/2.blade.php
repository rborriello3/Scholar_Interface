@extends('Layouts.dashboards')

@section('head')
<title>Scholarship Interface Roles</title>
<link rel="stylesheet" type="text/css" href="{{asset('/css/Global/Roles/roleSelect.css')}}">
@parent
@stop

@section('dashBoardContent')
    {{ Form::open(['route' => array('doUpdate', Session::get('role')), 'method' => 'POST']) }}

        <b>Enter Password:</b><br/>
        {{ Form::password('password', array('placeholder' => '••••••••')) }}
        <br/>
        <font color="red">{{ $errors -> first('password')}}</font>
        <br/><br/>

        {{ Form::label('aidyear', 'Update Current Aidyear') }} <br/>
        {{ Form::select('aidyear', $aidyears) }}
        <br/><br/>


        <img src="{{asset('images/Global/loader.gif')}}" style="display: none;" id="loading_image">
        {{ Form::submit('Update Settings', array('class' => 'btn btn-primary'))}}
    {{ Form::close() }}

@stop