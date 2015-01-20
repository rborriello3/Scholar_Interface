@extends('Layouts.printing')

@section('head')
    <title>Returning Students Grid</title>
    @parent
@stop


@section('content')
<strong><center>Entering Students Grid 2014-2015</center></strong><br>
<table cellspacing="0" cellpadding="1" width="100%"> 
    <thead>
        <tr class="Even">
            <th width="1%" align="center" valign="middle">Rank</th>
            <th width="15%" align="center" valign="middle">Student</th>
            <th align="center" valign="middle">Score</th>
            <th align="center" valign="middle">Major</th>
            <th align="center" valign="middle">AVG</th>
            <th align="center" valign="middle">Fiorello</th>
            <th align="center" valign="middle">Sarbak</th>
            <th align="center" valign="middle">Sheridan</th>
            <th align="center" valign="middle">Schmidt</th>
            <th align="center" valign="middle">Devitt</th>
            <th align="center" valign="middle">Frommer</th>
            <th align="center" valign="middle">Illobre</th>
            <th align="center" valign="middle">McCarty</th>
            <th align="center" valign="middle">Peverely</th>
            <th align="center" valign="middle">Yankanin</th>
            <th align="center" valign="middle">Need</th>
            <th width="25%" align="center" valign="middle">Award(s)</th>
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
                <td class="{{{$even}}}">{{{$r->AVGTotal}}}</td>
                <td class="{{{$even}}}">{{{$r->major}}}</td>
                <td class="{{{$even}}}">{{{$r->highSchoolAvg}}}</td>
                <td class="{{{$even}}}">{{{$r->Fiorello}}}</td>
                <td class="{{{$even}}}">{{{$r->Sarbak}}}</td>
                <td class="{{{$even}}}">{{{$r->Sheridan}}}</td>
                <td class="{{{$even}}}">{{{$r->Schmidt}}}</td>
                <td class="{{{$even}}}">{{{$r->Devitt}}}</td>
                <td class="{{{$even}}}">{{{$r->Frommer}}}</td>
                <td class="{{{$even}}}">{{{$r->Illobre}}}</td>
                <td class="{{{$even}}}">{{{$r->McCarty}}}</td>
                <td class="{{{$even}}}">{{{$r->Peverely}}}</td>
                <td class="{{{$even}}}">{{{$r->Yankanin}}}</td>
                <td class="{{{$even}}}">
                    @if($r->aidStatus != 0 )
                        <font color="red">${{{$r->aidStatus}}}</font>
                    @else
                        <b>*${{{$r->aidStatus}}}*</b>
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