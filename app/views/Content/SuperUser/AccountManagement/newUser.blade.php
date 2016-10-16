@extends('Layouts.dashboards')

@section('head')
<title>Scholarship Interface New User</title>
<link rel="stylesheet" type="text/css" href="{{asset('/css/SuperUser/AccountManagement/newUser.css')}}">
@parent
<script type="text/javascript" src="{{asset('/javascript/SuperUser/AccountManagement/newUser.js')}}"></script>
@stop

@section('dashBoardContent')
<h3>Create new user</h3>

<div id="newUserForm">
    {{ Form::open(array('route' => array('doCreateUser'), 'method' => 'POST', 'accept-charset' => 'UTF-8', 'id' =>
    'myForm')) }}
    <font color="red">{{ $errors -> first('name')}}</font> <font color="red">{{ $errors -> first('email')}}</font>
    <ul>
        <li>
            {{ Form::label('name', 'Full Name') }}
        </li>
        <li>
            {{ Form::text('name', '', array('placeholder' => 'Full Name', 'autocomplete' => 'off')) }}
        </li>
        <li>
            {{ Form::label('email', 'Email') }}
        </li>
        <li>
            {{ Form::text('email', '', array('placeholder' => 'Email', 'autocomplete' => 'off')) }}
        </li>
    </ul>

    <br>

    <div id="assignRole">
        <h5>Assign Role(s):</h5>
        @foreach($roles as $k => $v)        
        @if ($k == 4)
            {{ Form::checkbox('roles[]', $k, '', array('id' => 'committee')) . ' ' . $v}} <br>
        @elseif ($k != 4)
            {{ Form::checkbox('roles[]', $k, '', array()) . ' ' . $v}} <br>
        @endif
        
        @endforeach
        <font color="red">{{ $errors -> first('roles')}}</font>
        <br>
    </div>

    <div id="years">
        <div id="toYears">
            <h4>Assign Access Date:</h4>
            {{ Form::select('monthTo', $month, '') }} / {{ Form::selectRange('yearTo', date('Y'), date('Y')+10) }}
            <br>
            <font color="red">{{ $errors -> first('monthTo')}}</font> <font color="red">{{ $errors ->
                first('yearTo')}}</font>
        </div>
    </div>

    <div id="gradeGroup">
        <h4>Assign Grade Group To User:</h4>
        <?php $group = array('2' => 'Entering', '4' => 'Graduating', '6' => 'Returning'); ?>

        @foreach ($group as $k => $v)
        {{ Form::checkbox('group[]', $k, '', array()) . ' ' . $v}}
        @endforeach
        <br><font color="red">{{ $errors -> first('group')}}</font>
    </div>
    <br/>

    <br><br><br><br><br><br>
    <img src="{{asset('images/Global/loader.gif')}}" style="display: none;" id="loading_image">
    {{ Form::submit('Create User', array('class' => 'btn btn-primary'))}} &nbsp;&nbsp;{{ link_to_route('showUsers',
    'Cancel') }}

    {{ Form::close() }}

</div>

<script type="text/javascript">
    $('#myForm').submit(function () {
        $('#loading_image').show(); // show animation
        $(':submit', this).attr('disabled', 'disabled'); // disables form submission
        return true; // allow regular form submission
    });
</script>

@stop