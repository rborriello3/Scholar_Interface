@extends('Layouts.printing')

@section('head')
    <title>Returning Students Grid</title>
    @parent
@stop


@section('content')

    <strong><center>Returning Students Criteria and Minority Report {{{Session::get('currentAidyear')}}}</center></strong><br>
    <table cellspacing="0" cellpadding="3" width="100%">
        <thead>
        <tr class="Even">
            <th width="1%" align="center" valign="middle">Rank</th>
            <th width="15%" align="center" valign="middle">Student</th>
            <th width="1%" align="center" valign="middle">Score</th>
            <th width="40%" align="center" valign="middle">Criteria</th>
            <th width="40%" align="center" valign="middle">Minority</th>
        </tr>
        </thead>
        <tbody>
        <?php $count = 1; ?>
        @foreach($students as $r)
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
                    <td class="{{{$even}}}">
                        {{$criteria[($count - 1) - 1]}}
                    </td>
                    <td class="{{{$even}}}">
                        {{$minority[($count - 1) - 1]}}
                    </td>
                </tr>
                @endforeach
        </tbody>
    </table>
@stop
