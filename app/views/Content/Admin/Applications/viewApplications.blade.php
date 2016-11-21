@extends('Layouts.dashboards')

@section('head')
<title>Scholarship Interface View Applications</title>
@parent
<script type="text/javascript" src="{{asset('/jqueryUI/js/jquery-ui-1.10.3.custom.min.js')}}"></script>
<link rel="stylesheet" type="text/css" href="{{asset('/jqueryUI/css/ui-darkness/jquery-ui-1.10.3.custom.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{asset('css/Admin/Application/viewApplications.css') }}">
<script type="text/javascript" src="{{asset('/javascript/Admin/Applications/viewApplications.js')}}"></script>
@stop

@section('dashBoardContent')
<h4 style="float: right;">{{ link_to_route('showApplications', 'Return') }}</h4>
<h4>{{ link_to_route('showEditStudent', $history[0]->firstName . ' ' . $history[0]->lastName, array($history[0]->studentID)) }}</h4>
<h5>{{{$history[0]->studentID}}}</h5>

<!--@foreach($history as $v)
	{{ link_to_route('showEditApplication', '', array($v->GUID), array('class' => 'applicationActions glyphicon glyphicon-edit', 'title' => 'Edit Application', 'alt' => 'editApplication')) }}-->

@if (strpos($v->overallRank1, 'Top') !== FALSE)
    <?php
        $v->overallRank1 = $v->overallRank1 . '%';
    ?>
@endif

@if (strpos($v->overallRank2, 'Top') !== FALSE)
    <?php
        $v->overallRank2 = $v->overallRank2 . '%';
    ?>
@endif


<div class="applications">
    <h3>{{'Aid Year: ' . $v->aidyear . ' - Received Date: ' . $v->received . ' - Type: ' . $v->typeName . ' - Status: ' .
        $v->statusName}} </h3>

    <div>
        <div class="longText">
            <b>Essay:</b>

            <p>{{{$v->essay}}}</p>
        </div>
        <div class="longText">
            <b>Extra Curricular:</b><br>

            <p>{{{$v->extraCurricular}}}</p>
        </div>
	<div class="longText">
	    <b>Tell us a little about yourself:</b>

	    <p>{{{$v->essaySelf}}}</p>
	</div>
	<div class="longText">
	    <b>In essay form, please explain how a SUNY Orange scholarship will help you in your education:</b>

	    <p>{{{$v->essayWhy}}}</p>
	</div>
        <br><br>


        <div class="recommendations">
            Name: <b>{{{$v->recommender1}}}</b><br>
            Email: <b>{{{$v->email1}}}</b><br>
            Department: <b>{{{$v->department1}}}</b><br>
            Course(s): <b>{{{$v->courseName1}}}</b><br>
            Academic Potential: <b>{{{$v->academicPotential1}}}</b><br>
            Character: <b>{{{$v->character1}}}</b><br>
            Maturity: <b>{{{$v->emotionalMaturity1}}}</b><br>
            Rank: <b>{{{$v->overallRank1}}}</b><br>
            Comments: <p><b>{{{$v->comments1}}}</b></p>
        </div>
        <div class="recommendations">
            Name: <b>{{{$v->recommender2}}}</b><br>
            Email: <b>{{{$v->email2}}}</b><br>
            Department: <b>{{{$v->department2}}}</b><br>
            Course(s): <b>{{{$v->courseName2}}}</b><br>
            Academic Potential: <b>{{{$v->academicPotential2}}}</b><br>
            Character: <b>{{{$v->character2}}}</b><br>
            Maturity: <b>{{{$v->emotionalMaturity2}}}</b><br>
            Rank: <b>{{{$v->overallRank2}}}</b><br>
            Comments: <p><b>{{{$v->comments2}}}</b></p>
        </div>
    </div>
</div>
<br>
@endforeach

@stop
