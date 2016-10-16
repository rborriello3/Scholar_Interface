@extends('Layouts.dashboards')

@section('head')
	<title>Scholarship Interface Edit Application</title>
    @parent
    <link rel="stylesheet" type="text/css" href="{{asset('css/Admin/Application/global.css') }}">
@stop

@section('dashBoardContent')
	{{ Form::model($app, array('url' => route('doEditApplication', array($guid)), 'method' => 'POST', 'accept-charset' => 'UTF-8'))}}
		<br>{{ Form::submit('Save', array('class' => 'btn btn-success'))}} {{link_to_route('allApplicationsJson', 'Cancel') }} <br><br>

	<div id="essays" class="panel panel-primary">
		<div class="panel-heading">Essays <font color="orange">{{ $errors->first('essay')}}</font> <font color="orange"> {{$errors->first('extra')}} </font> <font color="orange"> {{ $errors->first('essaySelf')}} </font> <font color="orange"> {{ $errors->first('essayWhy')}} </font>
		</div>

			<ul>
				<li>
					{{ Form::label('essay', 'Essay')}}
					<br>	
					<textarea name="essay" rows="10" cols="95">{{{$application->essay or Input::old('essay')}}}</textarea>
				</li>
				<li>
					{{ Form::label('extra', 'Extracurriculur')}}
					<br>
					<textarea name="extra" rows="10" cols="95">{{{$application->extra or Input::old('extra')}}}</textarea>
				</li>
				<li>
					{{ Form::label('essaySelf', 'Tell us about yourself:')}}
					<br>
					<textarea name="essaySelf" rows="10" cols="95">{{{$application->essaySelf or Input::old('essaySelf')}}}</textarea>
				</li>
				<li>
					{{ Form::label('essayWhy', 'Explain how a SUNY Orange scholarship will help you in your education:')}}
					<br>
					<textarea name="essayWhy" rows="10" cols="95">{{{$application->essayWhy or Input::old('essayWhy')}}}</textarea>
				</li>
			</ul>
		</div>

	<div id="recommender1" class="panel panel-primary">
		<div class="panel-heading">Recommendation 1 <font color="orange"> {{$errors->first('name1')}} </font> <font color="orange"> {{$errors->first('email1')}} </font> <font color="orange"> {{$errors->first('department1')}}</font> <font color="orange"> {{$errors->first('course1')}} </font> <font color="orange"> {{$errors->first('academicPotential1')}} </font> <font color="orange"> {{$errors->first('character1')}} </font>
	</div>
		<ul>
			<li>
				{{Form::label('name1','Name')}}
				{{Form::text('name1')}}
			</li>
			<li>
                                {{Form::label('email1','Email')}}
                                {{Form::text('email1')}}
                        </li>
			<li>
                                {{Form::label('department1','Department')}}
                                {{Form::text('department1')}}
                        </li>
			 <li>
                                {{Form::label('course1','Course')}}
                                {{Form::text('course1')}}
                        </li>
                        <li>
                                {{Form::label('academicPotential1','Academic Potential')}}
                                {{Form::text('academicPotential1')}}
                        </li>
                        <li>
                                {{Form::label('character1','Character')}}
                                {{Form::text('character1')}}
                        </li>
		</ul>
	</div>
<div id="recommender2" class="panel panel-primary">
                <div class="panel-heading">Recommendation 2 <font color="orange"> {{$errors->first('name2')}} </font> <font color="orange"> {{$errors->first('email2')}} </font> <font color="orange"> {{$errors->first('department2')}}</font> <font color="orange"> {{$errors->first('course2')}} </font> <font color="orange"> {{$errors->first('academicPotential2')}} </font> <font color="orange"> {{$errors->first('character2')}} </font>
        </div>
                <ul>
                        <li>
                                {{Form::label('name2','Name')}}
                                {{Form::text('name2')}}
                        </li>
                        <li>
                                {{Form::label('email2','Email')}}
                                {{Form::text('email2')}}
                        </li>
                        <li>
                                {{Form::label('department2','Department')}}
                                {{Form::text('department2')}}
                        </li>
                         <li>
                                {{Form::label('course2','Course')}}
                                {{Form::text('course2')}}
                        </li>
                        <li>
                                {{Form::label('academicPotential2','Academic Potential')}}
                                {{Form::text('academicPotential2')}}
                        </li>
                        <li>
                                {{Form::label('character2','Character')}}
                                {{Form::text('character2')}}
                        </li>
                </ul>
        </div>

@stop
