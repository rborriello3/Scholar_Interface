@extends('OutGoingMessages.Emails.masterEmail')

@section('emailContent')
<h3> Hello {{{$name}}},</h3>
<p>Your first login attempt was successful! This means that you will not have to update your account at the beginning of
    any other sessions. Hopefully you understand that for your security we had to create that account update
    feature.</p>
<p>We would like you to note that, for added security we encrypt all of your account information such as: </p>
<ul>
    <li>Cell phone number (if provided)</li>
    <li>Secret questions and answers <strong><u>Treat these with care! They are like passwords!</u></strong></li>
    <li>Passwords - they are always hashed. We will never store your passwords in plain text.</li>
</ul>
<p>If you have any questions pertaining to your account and why we had to go through this process please feel free to
    contact the help desk.</p>
<p>Please understand the following: </p>
<ul>
    <li>Never share your password - help desk will never ask for it</li>
    <li>If you accidently share your password please update in the accounts management page</li>
    <li>Once you have logged out of <b>Scholarship Interface</b>, please close your browser for added security</li>
    <li>We highly recommend you allow <b>Scholarship Interface</b> to use two step authentication (Cell phone and Email)
    </li>
    <li>We also highly recommend you allow <b>Scholarship Interface</b> use two step authorization as well</li>
</ul>
<p>If you chose to enable mobile notifications we will first have to verify your cell phone number. That can be done
    while using <b>Scholarship Interface</b>.</p>
@stop