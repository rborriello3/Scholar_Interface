@extends('Layouts.dashboards')

@section('head')
    <title>Scholarship Interface Edit Meeting</title>
@parent
    <link rel="stylesheet" type="text/css" href="{{asset('/css/Admin/Meeting/editMeeting.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('/jqueryUI/css/ui-darkness/jquery-ui-1.10.3.custom.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('/jqueryUI/css/jquery.timepicker.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('/jqueryUI/css/jquery.multiselect.css')}}">
    <script type="text/javascript" src="{{asset('/javascript/Admin/Meeting/newMeeting.js')}}"></script>
    <script type="text/javascript" src="{{asset('/jqueryUI/js/jquery-ui-1.10.3.custom.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('/jqueryUI/js/jquery.timepicker.js')}}"></script>
    <script type="text/javascript" src="{{asset('/jqueryUI/js/jquery.multiselect.js')}}"></script>
@stop

@section('dashBoardContent')
    <h2>Schedule Meeting</h2>
    <div class="controls">
    <form class="form-inline" role="form" autocomplete="off" action="{{route('doEditMeeting')}}" method="POST"><br>
    {{Form::token()}}
	<div class="entry input-group">
	    <br>
	    <div class="form-group">
		<input name="name" type="text" value="{{{ $data['name'] or Input::old('name')}}}" id="name" placeholder="{{{ $name }}}"/>
	    </div>

	<br><br>
        <input type="submit" value="Save Meeting" class='btn btn-primary' />
	<img src={{asset('images/Global/loader.gif')}}" style="display:none;" id="loading_image"> {{ link_to_route('showDashboard', '  Cancel') }}	</div>
    </form>
    </div>

<script type="text/javascript">
    $('.form-inline').submit(function() {
	$('#loading_image').show();
	$(':submit', this).attr('disabled', 'disabled');
	return true;
    });
</script>

@stop
