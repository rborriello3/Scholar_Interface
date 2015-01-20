@extends('Layouts.dashboards')

@section('head')
<title>Scholarship Interface Edit Application</title>
@parent
<link rel="stylesheet" type="text/css" href="{{asset('css/Admin/Application/editApplications.css') }}">
@stop

@section('dashBoardContent')
Edit {{{$guid}}} doEditApplication
@stop
