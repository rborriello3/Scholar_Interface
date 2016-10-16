@extends('Layouts.dashboards')

@section('head')
	<title>Scholarship Interface New Application</title>
	@parent
	<link rel="stylesheet" type="text/css" href="{{asset('css/Admin/Application/essays.css') }}">
@stop

@section('dashBoardContent')

<br>
{{ link_to_route('endApplication', 'Cancel Application', array($appKey)) }}
@if(Session::get('requirementsComplete') == 1)
	{{ link_to_route('showRecomms', '<< Recommendations', array($appKey), array('class' => 'appNav')) }}
@elseif(Session::get('requirementsComplete') == 0 && Session::get('educationComplete') == 1)
		{{ link_to_route('showSchoolInfo', '<< School Information', array($appKey), array('class' => 'appNav')) }}
@endif
<br>


{{ Form::open(array('url' => route('doCompleteApp', array($appKey)), 'method' => 'POST', 'accept-charset' => 'UTF-8')) }}
{{ Form::submit('Submit Application', array('class' => 'btn btn-primary', 'id' => 'submit'))}}
{{ Form::close()}}

@stop