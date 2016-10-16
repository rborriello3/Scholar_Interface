@extends('Layouts.printing')

@section('head')
    <title>Returning Students Grid</title>
    @parent
@stop


@section('content')

<strong><center>Returning Students Ranked Grid For {{{Session::get('currentAidyear')}}}</center></strong><br>
<table cellspacing="0" cellpadding="3" width="100%"> 
    <thead>
        <tr class="Even">
            <th width="1%" align="center" valign="middle">Rank</th>
            <th width="15%" align="center" valign="middle">Student</th>
            <th width="1%" align="center" valign="middle">Score</th>
            <th width="1%" align="center" valign="middle">Major</th>
            <th width="1%" align="center" valign="middle">GPA</th>
            <th width="1%" align="center" valign="middle">Fall</th>
            <th width="50%" align="center" valign="middle">Graders</th>
            <th width="1%" align="center" valign="middle">Need</th>
            <th width="35%" align="center" valign="middle">Award(s)</th>
        </tr>
    </thead>
    <tbody>
    <?php $count = 1; ?>
        @foreach($results as $r)
           @if(isset($r->equal))
                <tr class="Equal">
            @else       
                <tr>
           @endif

            @if($count % 2 == 0)
                <?php $even = 'Even'; ?>
            @else
                <?php $even = 'Odd'; ?>
            @endif
                <td class="{{{$even}}}">{{{$count++}}}</td>
                <td class="{{{$even}}}">{{{$r->name}}}</td>
                <td class="{{{$even}}}">{{{$r->Total}}}</td>
                <td class="{{{$even}}}">{{{$r->major}}}</td>
                <td class="{{{$even}}}">{{{$r->GPA}}}</td>
                <td class="{{{$even}}}">{{{$r->creditHourFA}}}</td>
                <td class="{{{$even}}}">
                    {{$graders[($count - 1) - 1]}}
                </td>
                <td class="{{{$even}}}">
                    @if($r->aidStatus > '0')
                        <font color="red">${{{$r->aidStatus}}}</font>
                    @else
                        <b>${{{$r->aidStatus}}}</b>
                    @endif
                </td>
                <td class="{{{$even}}}">
                    @if(count($awards) > 0)
                    <?php $award = ''; ?>
                        @foreach($awards as $a)
                            @if(strtolower($a->studentID) == strtolower($r->studentID))
                                <?php
                                    $award .= '$' . $a->awardAmount . ' ';
                                ?>
                            @endif
                        @endforeach
                    @endif

                    {{$award}}
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
@stop
