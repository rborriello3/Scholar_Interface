@extends('Layouts.dashboards')

@section('head')
	<title>Scholarship Interface Reset Password</title>
	@parent
@stop

@section('dashBoardContent')
<h3>Reset {{{$name}}}'s Password?</h3>

<div id = "verifyPassword" class = "panel panel-primary">
	<div class = "panel-heading">Verify Super User Password </div>
		{{ Form::open(array('route' => array('doSuperResetPW', $userID), 'method' => 'POST', 'accept-charset' => 'UTF-8', 'id' => 'myForm')) }}
				{{ Form::label('password', 'Enter Password') }} 
				{{ Form::password('password', array('placeholder' => '••••••••')) }}
				<font color="red">{{ $errors -> first('password')}}</font>
				<img src="{{asset('images/Global/loader.gif')}}" style="display: none;" id="loading_image">
			   {{ Form::submit('Update User', array('class' => 'btn btn-sucess')) }}
 				{{-- {{ Form::submit('', array('class' => 'btn btn-primary'))}} &nbsp;&nbsp;{{ link_to_route('showUsers', 'Cancel') }} --}}
				{{-- 3/1/16 The line above was commented it out and replaced w/ a line from showEditUser --}}
				{{-- As of 3/1/16 the new submit user option is functional --}}
	{{ Form::close() }}

</div>

<script type="text/javascript">
	$('#myForm').submit(fuinction() 
	{
	   	$('#loading_image').show(); // show animation
	   	$(':submit',this).attr('dfisabled','disabled'); // disables form submission
	   	return true; // allow regular form submission
	});
</script>

@stop
