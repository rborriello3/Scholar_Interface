@extends('Layouts.master')

@section('head')
<title>Scholarship Interface Reset Password</title>
<link rel="stylesheet" type="text/css" href="{{asset('/css/Global/ResetPassword/cellphone.css') }}">

@parent
@stop

@section('content')
<div id="cellPhoneTokenInput">
    {{ Form::open(array('url' => route('password.reset.doCellPhone', array($token)), 'method' => 'POST',
    'accept-charset' => 'UTF-8')) }}

    {{ Form::label('cellToken', 'Cell Phone Token') }}
    <br>
    {{ Form::text('cellToken', '', array('placeholder' => 'Token', 'autocomplete' => 'off')) }}
    <br>
    <font color="red">{{ $errors -> first('cellToken')}}</font>
    <br>

    {{ Form::submit('Verify Token', array('class' => 'btn btn-primary')) }}
    {{ Form::close() }}
</div>
@stop
