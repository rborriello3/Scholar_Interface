@extends('Layouts.dashboards')

@section('head')
        <title>Scholarship Interface Edit User</title>
        <link rel="stylesheet" type="text/css" href="{{asset('css/SuperUser/AccountManagement/showEditUser.css') }}">
@parent
	<script type="text/javascript" src="{{asset('/javascript/SuperUser/AccountManagement/showEditUser.js')}}"></script>
@stop

@section('dashBoardContent')
{{Form::open(array('route' => array('doEditUser', $userID),
		   'method' => 'POST',
		   'accept-charset' => 'UTF-8',
		   'id' => 'myForm'))}}
<br></br>
   <div id ="generalInfo" class ="panel panel-primary">
        <div class="panel-heading">User Information </div>  
	  <li>
		{{Form::label('name','Name:');}}
        	{{Form::text('name', $name);}}
	  </li>
	  <li>
       		{{Form::label('email','Email:');}}
        	{{Form::text('email', $mail);}}
        </li>
	 <li>
                {{Form::label('yearTo','Assign Year:');}}
                {{ Form::select('monthTo', $month, '8') }} / {{ Form::selectRange('yearTo', date('Y'), date('Y')+10) }}
        </li>
<font color="red">{{ $errors ->first('name')}}</font><font color="red">{{ $errors ->first('email')}}</font><font color="red">{{ $errors ->first('yearTo')}}</font> <font color="red">{{ $errors -> first('monthTo')}}</font>

   </div>
   <div id="roles" class = "panel panel-primary">
	<div class = "panel-heading"> User Roles</div>
	{{Form::label('roles', 'Roles:');}}<br></br>
	@foreach($availableRoles as $c => $option)
		@if($option==array_pull($roles,$c))
		    @if($c==4)
			{{Form::checkbox('availableRoles[]', $c, true, array('id' =>'committee')). ' '. $option;}}<br></br>
		    @elseif($c!=4)	
		        {{Form::checkbox('availableRoles[]', $c, true, array()). ' '. $option;}}<br></br>	
		    @endif
		@else
		    @if($c==4)
                        {{Form::checkbox('availableRoles[]', $c,'' , array('id' =>'committee')). ' '. $option;}}<br></br>
		    @elseif($c!=4)	
		   	{{Form::checkbox('availableRoles[]', $c, '', array()). ' '. $option;}}<br></br>
		    @endif
		@endif
	@endforeach
<font color="red">{{ $errors -> first('roles')}}</font> <font color="red">{{ $errors -> first('availableRoles')}}</font> 
   </div>	
   <div id= "gradingGroup" class = "panel panel-primary">
	<div class = "panel-heading"> User Grading Groups</div> 
	{{Form::label('gradeGroup', 'Grade Groups:');}}<br></br>
	@foreach($availableGroups as $g => $option)
                @if($option==array_pull($group,$g))
                   {{Form::checkbox('availableGroups[]', $g, true). ' '. $option;}}<br></br>
                @else
                   {{Form::checkbox('availableGroups[]', $g). ' '. $option;}}<br></br>
                @endif
        @endforeach
<font color="red">{{ $errors -> first('gradeGroup')}}</font> <font color="red">{{ $errors -> first('availableGroups')}}</font>
    </div>
    <div id = "submits" class = "panel panel=primary">
    	{{Form::submit('Update User', array('class'=> 'btn btn-primary'))}}
    	{{Form::close()}}  
   </div>


<script type="text/javascript">
    $('#myForm').submit(function () {
        $('#loading_image').show(); // show animation
        $(':submit', this).attr('disabled', 'disabled'); // disables form submission
        return true; // allow regular form submission
    });
</script>

@stop                                                                       
