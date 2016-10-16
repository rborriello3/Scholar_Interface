@extends('OutGoingMessages.Emails.masterEmail')

@section('emailContent')

	{{nl2br($body)}}

@stop