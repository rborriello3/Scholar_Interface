@extends('Layouts.dashboards')

@section('head')
	<title>Scholarship Interface New Scholarship</title>

@parent
	<link rel="stylesheet" type="text/css" href="{{asset('/css/Admin/Scholarships/global.css')}}">
	{{--This script below is used for the app type button--}}
	<script type="text/javascript" src="{{asset('/javascript/Admin/Applications/studentDemo.js')}}"></script>
@stop

@section('dashBoardContent')
	{{ Form::open(array('url' => route('doCreateSchol', array()), 'method' => 'POST', 'accept-charset' => 'UTF-8'))}}
		<br>{{ Form::submit('Save', array('class' => 'btn btn-success'))}} {{ link_to_route('showAllScholarships', 'Cancel') }} <br><br>
	    
	    <div id="generalInfo" class="panel panel-primary">
	        <div class="panel-heading">General Information <font color="orange">{{ $errors->first('fundCode')}}</font> <font color="orange"> {{$errors->first('scholarshipName')}} </font> <font color="orange"> {{$errors->first('scholarshipAmount')}} </font>
	        </div>
				<ul>
					<li>
						{{ Form::label('fundCode', 'Fund Code')}}
						<br>
						<input name="fundCode" type="text" value="{{{Input::old('fundCode')}}}" id="fundCode" maxlength="4" size="3px">					
					</li>
					<li>
						{{ Form::label('scholarshipName', 'Name')}}
						<br>
						<input name="scholarshipName" type="text" value="{{{Input::old('scholarshipName')}}}" id="scholarshipName" size="40px">
					</li>
					<li>
						{{ Form::label('scholarshipAmount', 'Amount')}}
						<br>
						<input name="scholarshipAmount" type="text" value="{{{Input::old('scholarshipAmount')}}}" id="scholarshipAmount" size="10px">
						</li>
				</ul>
			</div>

			<div class="panel panel-primary">
	        <div class="panel-heading">Description <font color="orange"> {{$errors->first('scholarshipDesc')}} </font>
	        </div>
				<ul>
					<li>
						{{ Form::label('scholarshipDesc', 'Description')}}
						<br>	
						<textarea name="scholarshipDesc" rows="10" cols="170">{{{Input::old('scholarshipDesc')}}}</textarea>
					</li>
        		</ul>
        	</div>

        	<div id="additional" class="panel panel-primary">
	        <div class="panel-heading">Additional Information <font color="orange">{{ $errors -> first('programRequired')}}</font> <font color="orange">{{ $errors -> first('yearFrom')}}</font> <font color="orange"> {{$errors->first('yearTo')}} </font> <font color="orange"> {{$errors->first('awardBasis')}} </font> <font color="orange"> {{$errors->first('recipients')}} </font> <font color="orange"> {{$errors->first('equalAwarding')}} </font> <font color="orange"> {{$errors->first('appType')}} </font>
	        </div>
				<ul>
					<li>
						{{ Form::label('programRequired', 'Program')}}
						<br>
						{{ Form::text('programRequired')}}
					</li>
					<li>
						{{ Form::label('yearFrom', 'From')}}
						<br>
						{{ Form::selectYear('yearFrom', 2010, date('Y')+10, Input::old('yearFrom')) }}
					</li>
					<li>
						{{ Form::label('yearTo', 'To')}}
						<br>
						{{ Form::selectYear('yearTo', 2010, date('Y')+10, Input::old('yearFrom')) }}
					</li>
					<li>
						{{ Form::label('awardBasis', 'Need')}}
						<br>
						<?php $basis = array('Merit' => 'Merit', 'Need' => 'Need', 'Merit/Need' => 'Merit/Need'); ?>
						{{ Form::select('awardBasis', $basis)}}
					</li>
					<li>
						{{ Form::label('recipients', 'Recipients')}}
						<br>
						<input name="recipients" type="text" value="{{{Input::old('recipients')}}}" id="recipients" size="1px">
					</li>
					<li>
						{{ Form::label('equalAwarding', 'Equal Awarding?')}}
						<br>
						{{ Form::radio('equalAwarding', 0)}} No {{ Form::radio('equalAwarding', 1)}} Yes
					</li>
					<li>
						{{ Form::label('appTypes', 'Application Type')}}
						<div class="dropdown">
                    		<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                    			Group <span class="caret"></span> 
                    		</button>                    		
                    		<ul id="appType" class="dropdown-menu dropdown-menu-form" role="menu">
                        		@foreach($app as $k => $v)
                        			<li>
                        				<label>
                            				{{ Form::checkbox('appTypes[]', $k)}} {{$v}}
                            			</label>
                            		</li>
                        		@endforeach
                        	</ul>
                       	</div>
					</li>
        		</ul>
        	</div>

	{{ Form::close()}}

@stop