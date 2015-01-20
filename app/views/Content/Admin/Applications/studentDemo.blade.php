@extends('Layouts.dashboards')

@section('head')
<title>Scholarship Interface Student Demographics</title>
@parent
<link rel="stylesheet" type="text/css" href="{{asset('css/Admin/Application/studentDemo.css') }}">
<script type="text/javascript" src="{{asset('/javascript/Admin/Applications/studentDemo.js')}}"></script>
@stop

@section('dashBoardContent')
<div class="appProgress">
    @include('_partials.ApplicationCompletion.' . Request::segment(3))
</div>

<br>
{{ link_to_route('endApplication', 'Cancel Application', array($appKey)) }}
@if (Session::has('studentComplete') == 1)
{{ link_to_route('showSchoolInfo', 'School Information >>', array($appKey), array('class' => 'appNav')) }} &nbsp;&nbsp;
@endif
<br>
<div id="demographics">
    {{ Form::open(array('url' => route('doStudentDemo', array($appKey)), 'method' => 'POST', 'accept-charset' =>
    'UTF-8')) }}

    <div id="generalInfo" class="panel panel-primary">
        <div class="panel-heading">General Information <font color="orange">{{ $errors -> first('firstName')}}</font>
            <font color="orange">{{ $errors ->
                first('lastName')}}</font> <font color="orange">{{ $errors -> first('studentID')}}</font></div>
        <ul class="panel-body">
            <li>
                {{ Form::label('studentID', 'Student ID:') }}
                <br>
                {{ Form::text('studentID', $student->studentID, array('placeholder' => 'Student ID', 'autocomplete' =>
                'off', 'maxlength' => 9)) }}
            </li>

            <li>
                {{ Form::label('firstName', 'First Name:') }}
                <br>
                {{ Form::text('firstName', $student->firstName, array('placeholder' => 'First Name', 'autocomplete' =>
                'off')) }}
            </li>

            <li>
                {{ Form::label('lastName', 'Last Name:') }}
                <br>
                {{ Form::text('lastName', $student->lastName, array('placeholder' => 'Last Name', 'autocomplete' =>
                'off')) }}
            </li>
        </ul>
    </div>

    <div id="notfications" class="panel panel-primary">
        <div class="panel-heading">Notification Information <font color="orange">{{ $errors -> first('personalEmail')}}</font>
            <font color="orange">{{ $errors -> first('homephone')}}</font> <font color="orange">{{ $errors ->
                first('cellPhone')}}</font> <font color="orange">{{ $errors -> first('cellCarrier')}}</font></div>
        <ul class="panel-body">
            <font color="red">If <u>"No"</u> to Mobile Notifications set Carrier to <u>"Not Applicable"</u></font>
            <br><br>
            <li>
                {{ Form::label('personalEmail', 'Email:') }}
                <br>
                {{ Form::text('personalEmail', $student->personalEmail, array('placeholder' => 'Email', 'autocomplete' => 'off')) }}
            </li>

            <li>
                {{ Form::label('homephone', 'Home Phone:') }}
                <br>
                {{ Form::text('homephone', $student->homephone, array('placeholder' => 'Home Phone', 'autocomplete' =>
                'off', 'maxlength' => 10)) }}
            </li>

            <li>
                {{ Form::label('cellPhone', 'Cell Phone:') }}
                <br>
                {{ Form::text('cellPhone', $student->cellPhone, array('placeholder' => 'Cell Phone', 'autocomplete' =>
                'off', 'maxlength' => 10)) }}
            </li>

            <li>
                {{ Form::label('cellCarrier', 'Carrier:')}}
                <br>
                {{ Form::select('cellCarrier', $carriers, $student->cellCarrier)}}
            </li>
        </ul>
    </div>

    <div id="address" class="panel panel-primary">
        <div class="panel-heading">Address <font color="orange">{{ $errors -> first('address')}}</font> <font
                color="orange">{{ $errors -> first('city')}}</font> <font color="orange">{{ $errors ->
                first('state')}}</font> <font color="orange">{{ $errors -> first('zipCode')}}</font> <font
                color="orange">{{ $errors -> first('county')}}</font></div>
        <ul class="panel-body">
            <font color="red">If <u>Address Line 2</u> is filled out please add "||" to delimit the address lines.</font>
            <br/>
            <li>
                {{ Form::label('address', 'Street Address:')}}
                <br>
                {{ Form::text('address', $address->address, array('placeholder' => 'Address', 'autocomplete' => 'off',
                'id' => 'addressLine')) }}
            </li>

            <li>
                {{ Form::label('city', 'City:')}}
                <br>
                {{ Form::text('city', $address->city, array('placeholder' => 'City', 'autocomplete' => 'off')) }}
            </li>

            <li>
                {{ Form::label('state', 'State:')}}
                <br>
                {{ Form::select('state', $state, $address->state)}}
            </li>

            <li>
                {{ Form::label('zipCode', 'Zip Code:')}}
                <br>
                {{ Form::text('zipCode', $address->zipCode, array('placeholder' => 'Zip Code', 'autocomplete' => 'off',
                'id' => 'zip', 'maxlength' => 5)) }}
            </li>

            <li>
                {{ Form::label('county', 'County:')}}
                <br>
                {{ Form::text('county', $address->county, array('placeholder' => 'County', 'autocomplete' => 'off')) }}
            </li>
        </ul>
    </div>

    <div id="extraInfo" class="panel panel-primary">
        <div class="panel-heading">Extra Information <font color="orange">{{ $errors -> first('goal')}}</font> <font
                color="orange">{{ $errors -> first('criteria')}}</font> <font color="orange">{{ $errors ->
                first('minority')}}</font></div>
        <ul class="panel-body">
            <li>
                {{ Form::label('goal', 'Goal:')}}
                <br>
                {{ Form::text('goal', $student->goal, array('placeholder' => 'Goal', 'autocomplete' => 'off')) }}
            </li>

            <li>
                <div class="dropdown">
                    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                        Application Criteria <span class="caret"></span>
                    </button>
                    <ul id="criteria" class="dropdown-menu dropdown-menu-form" role="menu">
                        @foreach($criteria as $k => $v)
                        <?php $checked = false; ?>
                        <li>
                            <?php $crit = explode(',', $student->criteria); ?>
                            @foreach($crit as $k1 => $v1)
                            @if ($v1 == $k)
                            <?php $checked = true; ?>
                            @endif
                            @endforeach

                            {{ Form::checkbox('criteria[]', $k, $checked) }} {{{$v}}}
                        </li>
                        @endforeach
                    </ul>
                </div>
            </li>

            <li>
                <div class="dropdown">
                    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                        Minority Information <span class="caret"></span>
                    </button>

                    <ul id="minority" class="dropdown-menu dropdown-menu-form" role="menu">
                        @foreach($minority as $k => $v)
                        <?php $checked = false; ?>
                        <li>
                            <?php $min = explode(',', $student->minority); ?>
                            @foreach ($min as $k1 => $v1)
                            @if ($v1 == $k)
                            <?php $checked = true; ?>
                            @endif
                            @endforeach

                            {{ Form::checkbox('minority[]', $k, $checked) }} {{{$v}}}
                        </li>
                        @endforeach
                    </ul>
                </div>
            </li>
        </ul>
    </div>

    {{ Form::submit('Process Student Information', array('class' => 'btn btn-primary'))}}
    {{Form::close()}}

</div>

@stop
