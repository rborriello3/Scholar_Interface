@extends('OutGoingMessages.Emails.masterEmail')

@section('emailContent')
Hello <strong>{{{$name}}},</strong>
<p>
    {{{$body}}}
</p>

<hr>
<p>Bellow is your current recommendation status: </p>

@if ($recomm[0]->recommender1 == null && $recomm[0]->recommender2 == null)
<br>
<font color="red">We are missing <strong>both</strong> recommendations from you.</font>
<br>
@elseif ($recomm[0]->recommender1 != null && $recomm[0]->recommender2 == null)
<br>
<font color="red">We are missing only <strong>one</strong> recommendation from you.</font>
<br>
@endif

@stop