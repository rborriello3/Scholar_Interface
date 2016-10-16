@extends('Layouts.master')

@section('head')
<title>Scholarship Interface Sign-Up</title>
<link rel="stylesheet" type="text/css" href="{{asset('/css/Global/AccountManagement/register.css') }}">
@parent
@stop

@section('content')
<div id="register">
	<div id="registerFormExterior">
	<h3 id="pageIntro">Register</h3>
		<div id="form" class="form-horizontal" role="form">
			{{ Form::open(array('route' => 'account.doCreate', 'method' => 'POST', 'accept-charset' => 'UTF-8', 'id' => 'myForm')) }}
				<ul>
					<li>&nbsp;</li>
					<li>
						{{ Form::label('name', 'Full Name') }}
					</li>	
					<li>
						{{ Form::text('name', '', array('placeholder' => 'Full Name', 'autocomplete' => 'off')) }}
					</li>
					<li>
						<font color="red">{{ $errors -> first('name')}}</font>
					</li>
					<li>&nbsp;</li>
					<li>
						{{ Form::label('email', 'Email Address') }}
					</li>	
					<li>
						{{ Form::text('email', '', array('placeholder' => 'Email Address', 'autocomplete' => 'off')) }}
					</li>
					<li>
						<font color="red">{{ $errors -> first('email')}}</font>
					</li>
					<li>&nbsp;</li>
					<li>
						<img src="{{asset('images/Global/loader.gif')}}" style="display: none;" id="loading_image">
						{{ Form::submit('Create Account', array('class' => 'btn btn-primary'))}} 
						{{link_to_route('home.index', 'Home')}}
					</li>
					<li>&nbsp;</li>					
				</ul>

			{{ Form::close()}}
		</div>
	</div>
</div>

<script type="text/javascript">
	$('#myForm').submit(function() 
	{
	   	$('#loading_image').show(); // show animation
	   	$(':submit',this).attr('disabled','disabled'); // disables form submission
	   	return true; // allow regular form submission
	});
</script>
@stop