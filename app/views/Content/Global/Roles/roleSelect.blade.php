@extends('Layouts.master')

@section('head')
<title>Scholarship Interface Roles</title>
<link rel="stylesheet" type="text/css" href="{{asset('/css/Global/Roles/roleSelect.css')}}">
@parent
@stop

@section('content')
<div id="selectForm">
	{{ Form::open(array('route' => 'doRoleSelect', 'method' => 'POST', 'accept-charset' => 'UTF-8')) }}
		<div id="select">
			<font color="red">{{ $errors -> first('roleSelect')}}</font>
			<br>
			{{ Form::select('roleSelect', $roles, '') }}
		</div>

		<div id="submit">
			{{ Form::submit('Select Group', array('class' => 'btn btn-primary'))}} {{link_to_route('session.logout', 'Logout')}}
		</div>
	{{ Form::close() }}
</div>
@stop