@extends('Layouts.dashboards')

@section('head')
    <title>Scholarship Interface Create Meeting</title>
    @parent
    <link rel="stylesheet" type="text/css" href="{{asset('css/Admin/Meeting/newMeeting.css')}}">
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
    <form class="form-inline" role="form" autocomplete="off" action="{{route('doCreateMeeting')}}" method="POST"><br>
    {{Form::token()}}
        <div class="entry input-group">
	    <br>
	    <div class="form-group">
		<input class="form-control" name="name" type="text" placeholder="Meeting Title" />
	    </div>

	    <div class="form-group">
		<input class="form-control" name="date" type="text" id="datepicker" placeholder="Date (MM/DD/YYYY)" />
	    </div>

	    <div class="form-group">
		<input class="form-control" name="time" type="text" id="timepicker" placeholder="Time (HH:MM)" />
	    </div>

	    <div class="form-group">
		<input class="form-control" name="place" type="text" placeholder="Place (Optional)" />
	    </div>
	    <br><br>
	    
	    <div class="form-group" style="width:45%;">
		
		<select name="participants[]" multiple="multiple" id="participants">
		@foreach($participants as $part)    
			<option value="{{ $part[0] }}"> {{ $part[1] }} </option>
		@endforeach    
		</select>
		
	    </div>
	    
	    <!--<div class="form-group">
		<span class="input-group-btn">
		    <button class="btn btn-success btn-add" type="button">
			<span class="glyphicon glyphicon-plus"></span>
		    </button>
		</span>
	    </div>-->
	    <br><br>
	    <input type="submit" value="Save Meeting" class='btn btn-primary' />
 	    <img src={{asset('images/Global/loader.gif')}}" style="display:none;" id="loading_image"> {{ link_to_route('showDashboard', ' Cancel') }} 
	</div>
    </form>
    </div>

<script type="text/javascript">
    $('.form-inline').submit(function() {
	$'#loading_image').show();
	$(':submit', this).attr('disabled', 'disabled');
	return true;
    });
</script>

@stop
