@extends('Layouts.master')

@section('head')
<title>Scholarship Interface Reset Password</title>
<link rel="stylesheet" type="text/css" href="{{asset('/css/Global/ResetPassword/validation.css') }}">
@parent
@stop

@section('content')
<div id="userValidate">

    <script type="text/javascript">
        var RecaptchaOptions = {
            theme: 'blackglass'
        };
    </script>
    {{ Form::open(array('route' => 'password.doEmail', 'method' => 'POST', 'accept-charset' => 'UTF-8')) }}

    <div id="email">
        {{ Form::label('email', 'Email Address') }}
        <br>
        {{ Form::text('email', '', array('placeholder' => 'Email Address', 'autocomplete' => 'off')) }}
        <br>
        <font color="red">{{ $errors -> first('email')}}</font>
    </div>

    <br>

    <div id="captcha">
        {{ $captcha }}
    </div>
    <br>

    {{ Form::submit('Validate Identity', array('id' => 'userValidateSubmit', 'class' => 'btn btn-primary')) }} {{
    link_to_route('home.index', 'Home Page')}}
    {{ Form::close() }}


</div>

@stop