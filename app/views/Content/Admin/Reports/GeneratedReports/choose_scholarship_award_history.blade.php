@extends('Layouts.dashboards')

@section('head')
	<title>Choose Scholarship (Scholarship Award History Report)</title>
	@parent
	<link rel="stylesheet" type="text/css" href="{{ asset('css/Admin/Reports/reports.css') }}">
@stop

@section('dashBoardContent')
	{{ Form::open(array('url' => route('show_scholarship_award_history', array()), 'method' => 'POST', 'accept-charset' => 'UTF-8')) }}
		{{Form::select('fundCode[]', $scholarships, '')}}
		<!--<input type="submit" href="{{ link_to_route('show_scholarship_award_history') }}" value="Run Report" class='btn btn-primary'>-->
		<!--<img src="{{asset('images/Global/loader.gif')}}" style="display: none;" id="loading_image"> {{ link_to_route('show_scholarship_award_history', 'Run Report') }}-->	

	{{ Form::submit('Run Report', array('class' => 'btn btn-primary'), link_to_route('show_scholarship_award_history'))}} 
		<!--<img src="{{asset('images/Global/loader.gif')}}" style="dislay: none;" id="loading_image"> {{ link_to_route('show_scholarship_award_history', 'Run Report')}}<br><br>-->
	{{Form::close()}}
@stop	
