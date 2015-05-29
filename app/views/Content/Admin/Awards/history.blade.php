@extends('Layouts.dashboards')

@section('head')
	<title>Scholarship Interface Award History</title>
	@parent
	<script type="text/javascript" src="{{asset('/jqueryUI/js/jquery-ui-1.10.3.custom.min.js')}}"></script>
	<link rel="stylesheet" type="text/css" href="{{asset('/jqueryUI/css/ui-darkness/jquery-ui-1.10.3.custom.min.css') }}">
	<link rel="stylesheet" type="text/css" href="{{asset('css/Admin/Awards/awardHistory.css') }}">
	<script type="text/javascript" src="{{asset('/javascript/Admin/Awards/awardHistory.js')}}"></script>
@stop

@section('dashBoardContent')
	<h4 style="float: right;">{{ link_to_route('showAllAwards', 'Return') }}</h4>
	<h4>{{ link_to_route('showEditStudent', $awardInfo[0]->firstName . ' ' . $awardInfo[0]->lastName, array($awardInfo[0]->studentID)) }}</h4>
	<h5>{{{$awardInfo[0]->studentID}}}</h5>

	@foreach($awardInfo as $v)

		<div class="awards">
    		<h3>{{'Aid Year: ' . $v->aidyear . ' - Status: ' . $v->description . ' - Fund Code: ' . $v->fundCode . ' - Type: ' . $v->typeDescription}} </h3>

        	<div>
            	Scholarship Name: <b>{{{$v->scholarshipName}}}</b><br>
            	Amount: <b>{{{$v->awardAmount}}}</b><br>
	            Department: <b>{{{$v->department}}}</b><br>
	            Notes: <p><b>{{{$v->notes}}}</b></p>
        	</div>
	    </div>
		<br>
	@endforeach

@stop