@extends('Layouts.dashboards')

@section('head')
<title>Scholarship Interface Scoring Interface</title>
@parent
<link rel="stylesheet" type="text/css" href="{{asset('css/Committee/Scoring/specificGrading.css') }}">
@stop

@section('dashBoardContent')

<br>
{{ Form::open(array('url' => route('doPaginateRequest'), 'method' => 'POST', 'accept-charset' => 'UTF-8')) }}
{{ Form::submit('Begin Mass Grading', array('class' => 'btn btn-primary'))}}

{{ Form::radio('massGradeType', 0)}} All({{'<font color="green">' . $applicationCounts['allGraded'] . '</font> / <font
    color="red">' . $applicationCounts['all'] . '</font>'}}) &nbsp;

{{ Form::radio('massGradeType', 2)}} Entering({{'<font color="green">' . $applicationCounts['enteringGraded'] .
    '</font> / <font color="red">'. $applicationCounts['entering'] . '</font>'}}) &nbsp;

{{ Form::radio('massGradeType', 4)}} Graduating({{'<font color="green">' . $applicationCounts['graduatingGraded'] .
    '</font> / <font color="red">' . $applicationCounts['graduating'] . '</font>'}}) &nbsp;

{{ Form::radio('massGradeType', 6)}} Returning({{'<font color="green">' . $applicationCounts['returningGraded'] .
    '</font> / <font color="red">' . $applicationCounts['returning'] . '</font>'}}) &nbsp;

(<font color="green">Graded</font> / <font color="red">Total</font>)
{{ Form::close() }}
<font color="red">{{ $errors -> first('massGradeType')}}</font>
<br>

{{ Form::open(array('route' => array('processGrade', $guid, $page), 'method' => 'POST', 'accept-charset' => 'UTF-8')) }}
<div class="actions">
    <input class="btn btn-danger" type="submit" name='action' value="Finish Assessment">
    <input class="btn btn-warning" type="submit" name='action' value="Save Progress">
    {{ link_to_route('showCommitteeApps', 'Cancel Review') }}
</div>

<div id="notes">
    Assessment Notes: <br>
    <textarea name="assessorNotes" rows="5" cols="47">{{{$insertedValues[0]['assessorNotes'] or
        Input::old('assessorNotes')}}}</textarea>
</div>

<br><br>
<div id="information">
    <div id="generalInfo" class="panel panel-primary">
        <div class="panel-heading">General Information</div>
        <ul class="panel-body">
            <li>
                Student Name:
                <b>{{{$scoringInfo['studentName']}}}</b>
            </li>

            <li>
                Student ID:
                <b>{{{$scoringInfo['studentID']}}}</b>
            </li>

            <li>
                County:
                <b>{{{$scoringInfo['county']}}}</b>
            </li>

            <li>
                City:
                <b>{{{$scoringInfo['city']}}}</b>
            </li>

            <li>
                Application Type:
                <b>{{{$scoringInfo['type']}}}</b>
            </li>

            <li>
                Criteria:
                <b>{{{$scoringInfo['criteria']}}}</b>
            </li>

            <li>
                Minority:
                <b>{{{$scoringInfo['minority']}}}</b>
            </li>
        </ul>
    </div>

    <div id="educationalInfo" class="panel panel-primary">
        <div class="panel-heading">Educational Information</div>
        <ul class="panel-body">
            <li>
                Earned Credits:
                <b>{{{$scoringInfo['education']['creditsEarned']}}}</b>
            </li>

            <li>
                College Major:
                <b>{{{$scoringInfo['education']['major']}}}</b>
            </li>

            <li>
                College Graduation:
                <b>{{{$scoringInfo['education']['collegeGrad']}}}</b>
            </li>

            <li>
                Fall:
                <b>{{{$scoringInfo['education']['creditHourFA']}}}</b>
            </li>

            <li>
                Spring:
                <b>{{{$scoringInfo['education']['creditHourSP']}}}</b>
            </li>
        </ul>
    </div>
    <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>

    <div id="essay" class="panel panel-primary">
        <div class="panel-heading">Application Essay {{Form::select('essay', $grades, $insertedValues[0]['essay'])}}
        </div>
        <p>
            <b>{{{$scoringInfo['essay']}}}</b>
        </p>
    </div>
    <br>

    <div id="extra" class="panel panel-primary">
        <div class="panel-heading">Extra Curricular {{Form::select('extra', $grades, $insertedValues[0]['extra'])}}
        </div>
        <p>
            <b>{{{$scoringInfo['extraCurricular']}}}</b>
        </p>
    </div>

    <div id="recomm" class="recommendations panel panel-primary">
        <div class="panel-heading">
            <center>Recommendations {{Form::select('faculty', $grades, $insertedValues[0]['faculty'])}}</center>
        </div>
        <ul class="panel-body" id="firstRecom">
            <li>
                Recomender Name:
                <b>{{{$scoringInfo['recommendations']['recommender1']}}}</b>
            </li>

            <li>
                Department:
                <b>{{{$scoringInfo['recommendations']['department1']}}}</b>
            </li>

            <li>
                Course:
                <b>{{{$scoringInfo['recommendations']['courseName1']}}}</b>
            </li>

            <li>
                Academic Potential:
                <b>{{{$scoringInfo['recommendations']['academicPotential1']}}}</b>
            </li>

            <li>
                Emotional Maturity:
                <b>{{{$scoringInfo['recommendations']['emotionalMaturity1']}}}</b>
            </li>

            <li>
                Character:
                <b>{{{$scoringInfo['recommendations']['character1']}}}</b>
            </li>

            <li>
                Overall Rank:
                <b>{{{$scoringInfo['recommendations']['overallRank1']}}}</b><?php echo ($scoringInfo['recommendations']['overallRank1'] != 'Bottom') ? '%' : '' ?>
            </li>

            <li class="recommenderNotes">

                Comments:<br>

                <p>
                    <b>{{{$scoringInfo['recommendations']['comments1']}}}</b>
                </p>
            </li>

        </ul>

        <ul class="panel-body" id="secondRecom">
            <li>
                Recomender Name:
                <b>{{{$scoringInfo['recommendations']['recommender2']}}}</b>
            </li>

            <li>
                Department:
                <b>{{{$scoringInfo['recommendations']['department2']}}}</b>
            </li>

            <li>
                Course:
                <b>{{{$scoringInfo['recommendations']['courseName2']}}}</b>
            </li>

            <li>
                Academic Potential:
                <b>{{{$scoringInfo['recommendations']['academicPotential2']}}}</b>
            </li>

            <li>
                Emotional Maturity:
                <b>{{{$scoringInfo['recommendations']['emotionalMaturity2']}}}</b>
            </li>

            <li>
                Character:
                <b>{{{$scoringInfo['recommendations']['character2']}}}</b>
            </li>

            <li>
                Overall Rank:
                <b>{{{$scoringInfo['recommendations']['overallRank2']}}}</b><?php echo ($scoringInfo['recommendations']['overallRank2'] != 'Bottom') ? '%' : '' ?>
            </li>

            <li class="recommenderNotes">
                Comments:<br>

                <p>
                    <b>{{{$scoringInfo['recommendations']['comments2']}}}</b>
                </p>
            </li>
        </ul>
    </div>
</div>

{{ Form::close()}}

@stop