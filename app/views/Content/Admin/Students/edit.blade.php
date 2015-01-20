@extends('Layouts.dashboards')

@section('head')
	<title>Scholarship Interface Edit Student</title>
@parent
	<link rel="stylesheet" type="text/css" href="{{asset('/css/Admin/Student/global.css')}}">
@stop

@section('dashBoardContent')
	
	{{ Form::open(array('url' => route('doStudentUpdate', array($ID)), 'method' => 'POST', 'accept-charset' => 'UTF-8')) }}
		<br>{{ Form::submit('Update Student', array('class' => 'btn btn-success'))}} &nbsp; {{link_to_route('showStudentHome', 'Cancel Update')}}<br><br>

		<div id="generalInfo" class="panel panel-primary">
			<div class="panel-heading">General Information <font color="orange">{{$errors->first('studentID')}}</font> <font color="orange"> {{$errors->first('firstName')}} </font> <font color="orange"> {{$errors->first('lastName')}} </font>
	        </div>
	        <ul>
		        <li>
		        	{{Form::label('studentID', 'Student ID:')}}<br>
		        	{{Form::text('studentID', $student->studentID, array('maxlength' => 9, 'size' => '8'))}}
		        </li>
		        <li>
		        	{{Form::label('firstName', 'First Name:')}}<br>
		        	{{Form::text('firstName', $student->firstName)}}
		        </li>
		        <li>
		        	{{Form::label('lastName', 'Last Name:')}}<br>
		        	{{Form::text('lastName', $student->lastName)}}
		        </li>
		    </ul>
		</div>

		<div id="contact" class="panel panel-primary">
			<div class="panel-heading">Contact Information <font color="orange">{{$errors->first('personalEmail')}}</font> <font color="orange"> {{$errors->first('sunyEmail')}} </font> <font color="orange"> {{$errors->first('cellPhone')}} </font> <font color="orange"> {{$errors->first('homephone')}} </font> <font color="orange"> {{$errors->first('cellCarrier')}} </font>
			</div>
			<ul>
				<li>
					{{Form::label('personalEmail', 'Personal Email:')}}<br>
					{{Form::text('personalEmail', $student->personalEmail, array('size' => '40'))}}
				</li>
				<li>
					{{form::label('sunyEmail', 'Official Email:')}}<br>
					{{Form::text('sunyEmail', $student->sunyEmail, array('size' => '40'))}}
				</li>
				<li>
					{{Form::label('homephone', 'Home Phone:')}}<br>
					{{Form::text('homephone', $student->homephone, array('maxlength' => 10, 'size' => '9'))}}
				</li>
				<li>
					{{Form::label('cellPhone', 'Cell Phone:')}}<br>
					{{Form::text('cellPhone', $student->cellPhone, array('maxlength' => 10, 'size' => '9'))}}
				</li>
				<li>
					{{Form::label('cellCarrier', 'Cell Carrier:')}}<br>
					{{Form::select('cellCarrier', $carrier, $student->cellCarrier)}}
				</li>
			</ul>
		</div>

		<div id="address" class="panel panel-primary">
			<div class="panel-heading">Address Information <font color="orange">{{$errors->first('address')}}</font> <font color="orange">{{$errors->first('city')}}</font> <font color="orange">{{$errors->first('state')}}</font> 
				<font color="orange">{{$errors->first('zipCode')}}</font> <font color="orange">{{$errors->first('county')}}</font>
			</div>
			<ul>
				<li>
					{{Form::label('address', 'Address:')}}<br>
					{{Form::text('address', $address->address, array('size' => '50'))}}
				</li>
				<li>
					{{Form::label('city', 'City:')}}<br>
					{{Form::text('city', $address->city)}}
				</li>
				<li>
					{{Form::label('state', 'State:')}}<br>
					{{Form::select('state', $states, $address->state)}}
				</li>
				<li>
					{{Form::label('zipCode', 'Zip:')}}<br>
					{{Form::text('zipCode', $address->zipCode, array('size' => '3'))}}
				</li>
				<li>
					{{Form::label('county', 'County:')}}<br>
					{{Form::text('county', $address->county, array('size' => '15'))}}
				</li>
			</ul>			
		</div>

		<div id="demograph1" class="panel panel-primary">
			<div class="panel-heading">College Information <font color="orange">{{ $errors->first('major')}}</font>
				<font color="orange">{{$errors->first('creditHourSP')}}</font> <font color="orange">{{$errors->first('creditHourFA')}}</font> <font color="orange">{{$errors->first('creditsEarned')}}</font>
			</div>
			<ul>
				<li>
					{{Form::label('major', 'Major:')}}<br>
					{{Form::text('major', $demo->major)}}
				</li>

				<li>
					{{Form::label('creditHourSP', 'Spring Credits:')}}<br>
					{{Form::text('creditHourSP', $demo->creditHourSP, array('size' => '2', 'placeholder' => '##.##'))}}
				</li>

				<li>
					{{Form::label('creditHourFA', 'Fall Credits:')}}<br>
					{{Form::text('creditHourFA', $demo->creditHourFA, array('size' => '2', 'placeholder' => '##.##'))}}
				</li>

				<li>
					{{Form::label('creditsEarned', 'Earned Credits:')}}<br>
					{{Form::text('creditsEarned', $demo->creditsEarned, array('size' => '2', 'placeholder' => '##.##'))}}
				</li>
				<li>
					{{Form::label('GPA', 'GPA:')}}<br>
					{{Form::text('GPA', $demo->GPA, array('size' => '1', 'placeholder' => '#.##'))}}
				</li>
				<li>
					{{Form::label('collegeGrad', 'College Graduation:')}}<br>
					{{Form::text('collegeGrad', $demo->collegeGrad, array('size' => '4', 'placeholder' => 'MM/YY'))}}
				</li>
			</ul>
		</div>

		<div id="demograph2" class="panel panel-primary">
			<div class="panel-heading">High School Information <font color="orange">{{ $errors->first('GPA')}}</font>
				<font color="orange">{{ $errors->first('highSchoolName')}}</font> <font color="orange">{{$errors->first('highSchoolAVG')}}</font> <font color="orange">{{$errors->first('highGrad')}}</font> 
			</div>
			<ul>	
				<li>
					{{Form::label('highSchoolName', 'High School:')}}<br>
					{{Form::text('highSchoolName', $demo->highSchoolName, array('size' => '50'))}}
				</li>
				<li>
					{{Form::label('highSchoolAvg', 'High School Avg:')}}<br>
					{{Form::text('highSchoolAvg', $demo->highSchoolAvg, array('size' => '3', 'placeholder' => '##.##'))}}
				</li>

				<li>
					{{Form::label('highGrad', 'High School Graduation:')}}<br>
					{{Form::text('highGrad', $demo->highGrad, array('size' => '4', 'placeholder' => 'MM/YY'))}}
				</li>
			</ul>
		</div>

		<div id="demograph3" class="panel panel-primary">
			<div class="panel-heading">Extra Information <font color="orange">{{$errors->first('transferInsti')}}</font>
				<font color="orange">{{$errors->first('transferMaj')}}</font>
			</div>

			<ul>
				<li>
					{{Form::label('transferMaj', 'Transfering Major:')}}<br>
					{{Form::text('transferMaj', $demo->transferMaj, array('size' => '65'))}}
				</li>

				<li>
					{{Form::label('transferInsti', 'Transfering Institute:')}}<br>
					{{Form::text('transferInsti', $demo->transferInsti, array('size' => '65'))}}
				</li>
			</ul>
		</div>

		<br>{{ Form::submit('Update Student', array('class' => 'btn btn-success'))}}<br><br>
	{{ Form::close()}}
@stop

