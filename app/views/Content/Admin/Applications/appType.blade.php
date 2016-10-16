@extends('Layouts.dashboards')

@section('head')
	<title>Scholarship Interface New Application</title>
	@parent
	<link rel="stylesheet" type="text/css" href="{{asset('css/Admin/Application/applicationType.css') }}">
@stop

@section('dashBoardContent')
<div class="appProgress">
@include('_partials.ApplicationCompletion.' . Request::segment(3))
</div>

<div id="appTypes">
	<br>	
	{{ link_to_route('showApplications', 'Cancel Application') }}
		{{ Form::open(array('route' => 'doType', 'method' => 'POST', 'accept-charset' => 'UTF-8'))}}
		<div id="inputs">
			<ul>
				<li>
					{{ Form::label('types', 'Application Type')}}
					<br>
					{{ Form::select('types', $types, '') }}
					<br>
					<font color="red">{{ $errors -> first('types')}}</font>
					<br>
				</li>

				<li>
					{{ Form::label('aidyear', 'Aid Year')}}
					<br>
					{{ Form::select('aidyear', $aidyear, '')}}
					<br>
					<font color="red">{{ $errors -> first('aidyear')}}</font>
					<br>
				</li>

				<li>
					{{ Form::label('studentID', 'Student ID') }}
					<br>
					{{ Form::text('studentID', '', array('placeholder' => 'Student ID', 'autocomplete' => 'off', 'maxlength' => 9)) }}
					<br>
					<font color="red">{{ $errors -> first('studentID')}}</font>		
				</li>
			</ul>
		</div>
		<br><br>

		<div id="submit">
			{{ Form::submit('Process Application Type', array('class' => 'btn btn-primary'))}} &nbsp;&nbsp;
		</div>

	{{ Form::close() }}

</div>

@stop