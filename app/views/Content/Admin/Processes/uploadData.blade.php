@extends('Layouts.dashboards')

@section('head')
<title>Scholarship Interface Data Upload</title>
@parent
@stop

@section('dashBoardContent')
<br/>
{{ Form::open(array('url' => route('doDataUpload', array()), 'method' => 'POST', 'accept-charset' => 'UTF-8', 'files' => true, 'id' => 'myForm')) }}
{{ Form::file('dataFiles[]', array('multiple'=>true)) }}
<br/>
<img src="{{asset('images/Global/loader.gif')}}" style="display: none;" id="loading_image">
{{ Form::submit('Upload Data', array('class' => 'btn btn-primary'))}} {{ Form::button('Reset', array('class' => 'btn btn-danger', 'onClick' => 'window.location.reload()'))}}
&nbsp;&nbsp;{{ link_to_route('showProcesses', 'Cancel') }}
{{ Form::close() }}

<script type="text/javascript">
    $('#myForm').submit(function () {
        $('#loading_image').show(); // show animation
        $(':submit', this).attr('disabled', 'disabled'); // disables form submission
        return true; // allow regular form submission
    });
</script>

@stop