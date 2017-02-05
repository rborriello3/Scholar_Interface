@extends('Layouts.dashboards')

@section('head')
    <title>Scholarship Interface Create Deadline</title>
    @parent
    <link rel="stylesheet" type="text/css" href="{{asset('css/Admin/Deadline/newDeadline.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('/jqueryUI/css/ui-darkness/jquery-ui-1.10.3.custom.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('/jqueryUI/css/jquery.multiselect.css')}}">
    <script type="text/javascript" src="{{asset('/javascript/Admin/Deadline/newDeadline.js')}}"></script>
    <script type="text/javascript" src="{{asset('/jqueryUI/js/jquery-ui-1.10.3.custom.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('/jqueryUI/js/jquery.multiselect.js')}}"></script>
@stop

@section('dashBoardContent')
    <h2>Schedule Deadline</h2>
    <div class="controls">
    <form class="form-inline" role="form" autocomplete="off" action="{{route('doCreateDeadline')}}" method="POST"><br>
    {{Form::token()}}
	<div class="entry input-group">
	    <br>
	    <div class="form-group">
		<input class="form-control" name="name" type="text" placeholder="Deadline Name"/>
	    </div>

	    <div class="form-group">
		<input class="form-control" name="date" type="text" id="datepicker" placeholder="Date (MM/DD/YYYY)"/>
	    </div>

	    <div class="form-group">
		<input class="form-control" name="description" type="text" placeholder="Deadline Description (Optional)"/>
	    </div>

	    <div class="form-group" style="width:45%;">
		<select name="gradeGroup[]" multiple="multiple" id="gradeGroup">
		@foreach($gradeGroup as $g)
		    <option value="{{ $g[0] }}"> {{ $g[1] }} </option>
	 	@endforeach
		</select>
	    </div>
	    <br><br>
	    <input type="submit" value="Save Deadline" class='btn btn-primary'/>
	    <img src="{{asset('images/Global/loader.gif')}}" style="display:none;" id="loading_image"> {{ link_to_route('showDashboard', ' Cancel') }}
	</div>
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
