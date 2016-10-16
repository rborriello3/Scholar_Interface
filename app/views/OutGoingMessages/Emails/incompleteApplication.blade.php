@extends('OutGoingMessages.Emails.masterEmail')

@section('emailContent')
<h3>Hello {{{ $name }}},</h3>
<p>Your scholarship application has been received by the Financial Aid
    Office.</p> However please be aware your application is <strong>incomplete due to <font color="red">missing
        recommendations</font></strong>.

<hr>
@if ($recomm[0]->recommender1 == null && $recomm[0]->recommender2 == null)
<ul>
    <li>
        <font color="red">We are missing <strong>both</strong> recommendations from you.</font>
    </li>
</ul>
@elseif ($recomm[0]->recommender1 != null && $recomm[0]->recommender2 == null)
<ul>
    <li>
        <font color="red">We are missing only <strong>one</strong> recommendation from you.</font>
    </li>
</ul>
@endif
<hr>

<p>For you application to move forward we must have received two recommendations. It is the student responsibility to
    ask their professors for a recommendation.</p>
<p>If any questions arise about your application or how the process works please do not hesitate to call the financial
    aid office at: <b>845-341-4190</b></p>
@stop