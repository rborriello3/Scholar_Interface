@extends('Layouts.dashboards')

@section('head')
    <title>Scholarship Interface Create Meeting</title>
    @parent
    <link rel="stylesheet" type="text/css" href="{{asset('css/Admin/Meeting/newMeeting.css')}}">
    <script type="text/javascript" src="{{asset('/javascript/Admin/Meeting/newMeeting.js')}}"></script>
@stop

@section('dashBoardContent')
    <div class="controls">
    <form class="form-inline" role="form" autocomplete="off" action="{{route('doCreateMeeting')}}" method="POST"><br>
    {{Form::token()}}
        <input type="submit" value="Save Meeting" class='btn btn-primary' />
 	<img src={{asset('images/Global/loader.gif')}}" style="display:none;" id="loading_image"> {{ link_to_route('showDashboard', 'Cancel') }} <br><br>

	<div class="entry input-group">
	    <br>
	    <div class="form-group">
		<input class="form-control" name="name[]" type="text" placeholder="Meeting Title" />
	    </div>

	    <div class="form-group">
		<input class="form-control" name="date[]" type="text" placeholder="Date (MM/DD/YYYY)" />
	    </div>

	    <div class="form-group">
		<input class="form-control" name="time[]" type="text" placeholder="Time (HH:MM)" />
	    </div>

	    <div class="form-group">
		<input class="form-control" name="place[]" type="text" placeholder="Place (Optional)" />
	    </div>

	    <div class="form-group">
		{{Form::select('participants[]', $participants, '')}}
	    </div>

	    <div class="form-group">
		<span class="input-group-btn">
		    <button class="btn btn-success btn-add" type="button">
			<span class="glyphicon glyphicon-plus"></span>
		    </button>
		</span>
	    </div>
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
