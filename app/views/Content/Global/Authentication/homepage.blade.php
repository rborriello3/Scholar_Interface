@extends('Layouts.master')

@section('head')
<title>Scholarship Interface Login</title>
<link rel="stylesheet" type="text/css" href="{{asset('/css/Global/Authentication/login.css') }}">
@parent
@stop

@section('messages')
<!--[if IE]>
<div class="alert" style="text-align:center; background: yellow;">
    <font color="black">
        Some of the user interface features of this site might not work as expected by the developer while using <b>Internet
        Explorer</b>. Please consider using <a href="http://www.mozilla.org/en-US/firefox/new/" target="_blank"><font
        color="blue">Mozilla FireFox</font></a> or <a href="https://www.google.com/intl/en/chrome/browser/"
                                                      target="_blank"><font color="blue">Google Chrome</font></a>.
    </font>
</div>
<![endif]-->
@parent
@stop


@section('content')
<div id="homepage">
    <br>
    <img src="{{asset('images/Global/sunyOrangeBanner.png')}}" id="banner">

    <div id="loginFormExterior">
        <h3 id="programIntro">Scholarship Interface</h3>

        <div id="form" class="form-horizontal" role="form">
            {{ Form::open(array('route' => 'session.create', 'method' => 'POST', 'accept-charset' => 'UTF-8', 'id' =>
            'myForm')) }}
            <ul>
                <li>&nbsp;</li>
                <li>
                    {{ Form::label('user', 'Email Address') }}
                </li>
                <li>
                    {{ Form::text('user', '', array('placeholder' => 'Email Address', 'autocomplete' => 'off')) }}
                </li>
                <li>
                    <font color="red">{{ $errors -> first('user')}}</font>
                </li>
                <li>&nbsp;</li>
                <li>
                    {{ Form::label('password', 'Password') }}
                </li>
                <li>
                    {{ Form::password('password', array('placeholder' => '••••••••')) }}
                    <br>
                   {{ link_to_route('password.EmailStep', 'Forgot Password?')}} 
                </li>
                <li>
                    <font color="red">{{ $errors -> first('password')}}</font>
                </li>
                <li>&nbsp;</li>
                <li>
                    <img src="{{asset('images/Global/loader.gif')}}" style="display: none;" id="loading_image">
                    {{ Form::submit('Login', array('class' => 'btn btn-primary'))}}
                    {{link_to_route('account.showCreate', 'Register')}}
                </li>
                <li>&nbsp;</li>
            </ul>
            {{ Form::close() }}
        </div>
    </div>
</div>

<script type="text/javascript">
    $('#myForm').submit(function () {
        $('#loading_image').show(); // show animation
        $(':submit', this).attr('disabled', 'disabled'); // disables form submission
        return true; // allow regular form submission
    });
</script>

@stop