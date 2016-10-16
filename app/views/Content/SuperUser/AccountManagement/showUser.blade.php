@extends('Layouts.dashboards')

@section('head')
<title>Scholarship Interface Show User</title>

@parent
	<link rel="stylesheet" type="text/css" href="{{asset('css/SuperUser/AccountManagement/showEditUser.css') }}">
@stop

@section('dashBoardContent')

<br></br>
   <div id ="generalInfo" align = "left" class ="panel panel-primary">
	<div class="panel-heading">User Information </div>
	<li>
		{{Form::label('name','Name:');}}
        	{{$name}}
	</li>
	<li>
		{{Form::label('email','Email:');}}
		{{$mail}}
	</li>
    </div>
   <div id ="roles" class ="panel panel-primary">
        <div class="panel-heading">User Roles </div>

	<li>
		{{Form::label('role','Roles:')}}<br></br>
		@foreach($roles as $r=>$role)
		{{$role}}<br></br>
		@endforeach
	</li>
   </div>

   <div id ="gradingGroup" class ="panel panel-primary">
        <div class="panel-heading">Grade Groups </div>

        <li>
                {{Form::label('gradeGroup','Grade Groups:');}} <br></br>
                @foreach($group as $g=>$gradeGroup)
                {{$gradeGroup}}<br></br>
                @endforeach
        </li>
   </div>

@stop

