@extends('Layouts.master')

@section('head')
<title>Scholarship Interface Account Update</title>
@parent
<link rel="stylesheet" type="text/css" href="{{asset('/css/Global/AccountManagement/firstLogin.css')}}">
<script type="text/javascript" src="{{asset('/javascript/Global/AccountManagement/firstLogin.js')}}"></script>
@stop

@section('content')
<br>
{{ Form::open(array('route' => 'doFirstLogin', 'method' => 'POST', 'accept-charset' => 'UTF-8', 'id' => 'doFirstUpdate')) }}

<div id="submit">
    <img src="{{asset('images/Global/loader.gif')}}" style="display: none;" id="loading_image">
    {{ Form::submit('Update Account', array('class' => 'btn btn-primary'))}} {{ link_to_route('session.logout',
    'Logout')}}
</div>

<ul id="cellnotify">
    <br>
    <li>
        {{ Form::label('cellnotify', 'Mobile Notification?')}}
    </li>

    <li>
        No:{{ Form::radio('cellnotify', '0', true, array('id' => 'noCell')) }} Yes:{{ Form::radio('cellnotify', '1',
        false, array('id' => 'yesCell')) }} <font color="red">{{ $errors -> first('cellPhone')}}</font> <font
            color="red">{{ $errors -> first('cellCarrier') }}</font>
    </li>

    <div id="cellPhoneInfo">
        <li>
            {{ Form::text('cellPhone', '', array('placeholder' => '5555555555', 'autocomplete' => 'off', ' maxlength' =>
            10)) }}
        </li>

        <li>
            {{ Form::select('cellCarrier', $carrier, '', array('id' => 'carrierList')) }}
        </li>
    </div>
</ul>


<div id="passwordDiv" class="panel panel-primary">
    <div class="panel-heading">Password Update</div>
    <ul class="panel-body">
        <li>
            {{ Form::label('password', 'Password') }}
            <br>
            {{ Form::password('password', array('placeholder' => '••••••••')) }}
            <br>
            <font color="red">{{ $errors -> first('password')}}</font>
            <br>
        </li>

        <li>
            {{ Form::label('password_confirmation', 'Confirm') }}
            <br>
            {{ Form::password('password_confirmation', array('placeholder' => '••••••••')) }}
            <br>
            <font color="red">{{ $errors -> first('password_confirmation')}}</font>
            <br>
        </li>
        <li>
            Password can be between 4 and 14 characters. Can contain numbers, lower and upper case letters and special
            characters like <font color="black"><b>!@#$%()\-_=+:,.</b></font>
        </li>
    </ul>
</div>

<div id="questions" class="panel panel-primary">
    <div class="panel-heading">Question and Answer</div>
    <ul class="panel-body">
        <li>
            <font color="red">{{ $errors -> first('ques1')}}</font>
            <br>
            {{ Form::select('ques1', $quesGroup1, '') }}
        </li>
        <li>
            <font color="red">{{ $errors -> first('answ1')}}</font>
            <br>
            {{ Form::text('answ1', '', array('placeholder' => 'Answer One', 'autocomplete' => 'off', 'maxlength' => '4')) }}
        </li>
        <li>
            <font color="red">{{ $errors -> first('ques2')}}</font>
            <br>
            {{ Form::select('ques2', $quesGroup2, '', array('id' => 'secondQuestion')) }}
        </li>
        <li>
            <font color="red">{{ $errors -> first('answ2')}}</font>
            <br>
            {{ Form::text('answ2', '', array('placeholder' => 'Answer Two', 'autocomplete' => 'off')) }}
        </li>
    </ul>
</div>
{{ Form::close() }}
</div>


<script type="text/javascript">
    $('#doFirstUpdate').submit(function () {
        $('#loading_image').show(); // show animation
        $(':submit', this).attr('disabled', 'disabled'); // disables form submission
        return true; // allow regular form submission
    });
</script>

@stop
