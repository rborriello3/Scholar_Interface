@extends('Layouts.dashboards')

@section('head')
	<title>Scholarship Interface New Application</title>
	@parent
	<link rel="stylesheet" type="text/css" href="{{asset('css/Admin/Application/recomms.css') }}">
	<script type="text/javascript" src="{{asset('/javascript/Admin/Applications/recommendations.js')}}"></script>
@stop

@section('dashBoardContent')

<br>
{{ link_to_route('endApplication', 'Cancel Application', array($appKey)) }}

@if (Session::get('recommendationComplete') ==  1)
	{{ link_to_route('showCompleteApp', 'Overview >>', array($appKey), array('class' => 'appNav')) }} &nbsp;&nbsp;
@endif
{{ link_to_route('showEssays', '<< Requirements', array($appKey), array('class' => 'appNav')) }}
<br>

<?php $none = false; $one = false; $two = false; ?>
@if ($recommendation->recommender1 != '' && $recommendation->recommender2 != '')
	<?php $two = true; ?>
@elseif($recommendation->recommender1 != '' && $recommendation->recommender2 == '')
	<?php $one = true; ?>
@else
	<?php $none = true; ?>
@endif

<?php $numScale = array('1', '2', '3', '4', '5'); ?>
<?php $rank     = array('' => 'Rank', 'Top 10' => 'Top 10%', 'Top 20' => 'Top 20%', 'Top 30' => 'Top 30%', 'Top 40' => 'Top 40%', 'Top 50' => 'Top 50%', 'Bottom' => 'Bottom'); ?>

<div id="recommendations">
	{{ Form::open(array('url' => route('doRecomms', array($appKey)), 'method' => 'POST', 'accept-charset' => 'UTF-8')) }}
	Number of recommendations:
	<br>
	None: {{ Form::radio('recomms', 0, $none)}} &nbsp;&nbsp;
	One: {{ Form::radio('recomms', 1, $one)}} &nbsp;&nbsp;
	Two: {{ Form::radio('recomms', 2, $two)}} &nbsp;&nbsp;
	
	@if ($one || $two)
	&nbsp;&nbsp;&nbsp;{{ Form::button('Reload Data', array('class' => 'btn btn-primary', 'onClick' => 'window.location.reload()'))}}
	<br>
	<br>
	@endif
	<div id="recomm1">
		<div class="recomm panel panel-primary">
			<div class="panel-heading">Recommendation One <font color="orange">{{ $errors -> first('recommender1')}}</font> <font color="orange">{{ $errors -> first('email1')}}</font> <font color="orange">{{ $errors -> first('department1')}}</font> <font color="orange">{{ $errors -> first('courseName1')}}</font></div>
			<ul class="panel-body">
				<li>
					{{ Form::label('recommender1', 'Recommender:') }}
					<br>
					{{ Form::text('recommender1', $recommendation->recommender1, array('placeholder' => 'Recommender', 'autocomplete' => 'off')) }}
				</li>

				<li>
					{{ Form::label('email1', 'Email:') }}
					<br>
					{{ Form::text('email1', $recommendation->email1, array('placeholder' => 'Email', 'autocomplete' => 'off')) }}
				</li>

				<li>	
					{{ Form::label('department1', 'Department:') }}
					<br>
					{{ Form::text('department1', $recommendation->department1, array('placeholder' => 'Department', 'autocomplete' => 'off')) }}
				</li>

				<li>
					{{ Form::label('courseName1', 'Course Name(s):') }}
					<br>
					{{ Form::text('courseName1', $recommendation->courseName1, array('placeholder' => 'Course Name(s)', 'autocomplete' => 'off')) }}
				</li>
			</ul>	
		</div>

		<div class="recomm1Info panel panel-primary">
			<div class="panel-heading">Recommendation One - Extra Information <font color="orange">{{ $errors -> first('academicPotential1')}}</font> <font color="orange">{{ $errors -> first('character1')}}</font> <font color="orange">{{ $errors -> first('emotionalMaturity1')}}</font> <font color="orange">{{ $errors -> first('overallRank1')}}</font> <font color="orange">{{ $errors -> first('comments1')}}</font></div>
			<ul>
				<li>
					Academic Potential: 
					@foreach($numScale as $v)
						@if($v == $recommendation->academicPotential1)
							{{$v}} {{ Form::radio('academicPotential1', $v, true)}}
						@else
							{{$v}} {{ Form::radio('academicPotential1', $v)}} 
						@endif	
					@endforeach
					<input name="academicPotential1" type="radio" value="" style="display:none">
				</li>

				<li>
					Character: 
					@foreach($numScale as $v)
						@if($v == $recommendation->character1)
							{{$v}} {{ Form::radio('character1', $v, true)}}
						@else
							{{$v}} {{ Form::radio('character1', $v)}} 
						@endif	
					@endforeach
					<input name="character1" type="radio" value="" style="display:none">
				</li>

				<li>
					Emotional Maturity: 
					@foreach($numScale as $v)
						@if($v == $recommendation->emotionalMaturity1)
							{{$v}} {{ Form::radio('emotionalMaturity1', $v, true)}}
						@else
							{{$v}} {{ Form::radio('emotionalMaturity1', $v)}} 
						@endif	
					@endforeach
					<input name="emotionalMaturity1" type="radio" value="" style="display:none">
				</li>

				<li>	
					{{ Form::label('overallRank1', 'Rank:') }}
					<br>
					{{ Form::select('overallRank1', $rank, $recommendation->overallRank1)}}
				</li>

				<li>
					{{ Form::label('comments1', 'Comments:') }}
					<br>
					{{ Form::text('comments1', $recommendation->comments1, array('placeholder' => 'Comments', 'autocomplete' => 'off')) }}
				</li>
			</ul>
		</div>
	</div>

	<div id="recomm2">
		<div class="recomm panel panel-primary">
			<div class="panel-heading">Recommendation Two  <font color="orange">{{ $errors -> first('recommender2')}}</font> <font color="orange">{{ $errors -> first('email2')}}</font> <font color="orange">{{ $errors -> first('department2')}}</font> <font color="orange">{{ $errors -> first('courseName2')}}</font></div>
			<ul class="panel-body">
				<li>
					{{ Form::label('recommender2', 'Recommender:') }}
					<br>
					{{ Form::text('recommender2', $recommendation->recommender2, array('placeholder' => 'Recommender', 'autocomplete' => 'off')) }}
				</li>

				<li>
					{{ Form::label('email2', 'Email:') }}
					<br>
					{{ Form::text('email2', $recommendation->email2, array('placeholder' => 'Email', 'autocomplete' => 'off')) }}
				</li>

				<li>	
					{{ Form::label('department2', 'Department:') }}
					<br>
					{{ Form::text('department2', $recommendation->department2, array('placeholder' => 'Department', 'autocomplete' => 'off')) }}
				</li>

				<li>
					{{ Form::label('courseName2', 'Course Name(s):') }}
					<br>
					{{ Form::text('courseName2', $recommendation->courseName2, array('placeholder' => 'Course Name(s)', 'autocomplete' => 'off')) }}
				</li>
			</ul>		
		</div>

		<div class="recomm1Info panel panel-primary">
			<div class="panel-heading">Recommendation Two - Extra Information <font color="orange">{{ $errors -> first('academicPotential2')}}</font> <font color="orange">{{ $errors -> first('character2')}}</font> <font color="orange">{{ $errors -> first('emotionalMaturity2')}}</font> <font color="orange">{{ $errors -> first('overallRank2')}}</font> <font color="orange">{{ $errors -> first('comments2')}}</font></div>
			<ul>
				<li>
					Academic Potential: 
					@foreach($numScale as $v)
						@if($v == $recommendation->academicPotential2)
							{{$v}} {{ Form::radio('academicPotential2', $v, true)}}
						@else
							{{$v}} {{ Form::radio('academicPotential2', $v)}} 
						@endif	
					@endforeach
					<input name="academicPotential2" type="radio" value="" style="display:none">
				</li>

				<li>
					Character: 
					@foreach($numScale as $v)
						@if($v == $recommendation->character2)
							{{$v}} {{ Form::radio('character2', $v, true)}}
						@else
							{{$v}} {{ Form::radio('character2', $v)}} 
						@endif	
					@endforeach
					<input name="character2" type="radio" value="" style="display:none">
				</li>

				<li>
					Emotional Maturity: 
					@foreach($numScale as $v)
						@if($v == $recommendation->emotionalMaturity2)
							{{$v}} {{ Form::radio('emotionalMaturity2', $v, true)}}
						@else
							{{$v}} {{ Form::radio('emotionalMaturity2', $v)}} 
						@endif	
					@endforeach
					<input name="emotionalMaturity2" type="radio" value="" style="display:none">
				</li>

				<li>
					{{ Form::label('overallRank2', 'Rank:') }}
					<br>
					{{ Form::select('overallRank2', $rank, $recommendation->overallRank2)}}
				</li>

				<li>
					{{ Form::label('comments2', 'Comments:') }}
					<br>
					{{ Form::text('comments2', $recommendation->comments2, array('placeholder' => 'Comments', 'autocomplete' => 'off')) }}
				</li>
			</ul>
		</div>
	</div>
	
</div>
	
	{{ Form::submit('Process Recommendations', array('class' => 'btn btn-primary', 'id' => 'submit'))}}
	{{ Form::close()}}

@stop