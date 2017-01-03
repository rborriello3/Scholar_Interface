@extends('Layouts.dashboards')

@section('head')
<title>Scholarship Interface Admin Dashboard</title>
@parent
<script type="text/javascript" src="{{asset('/jqueryUI/js/jquery-ui-1.10.3.custom.min.js')}}"></script>
<script type="text/javascript" src="{{asset('/javascript/Admin/Meeting/viewMeetings.js')}}"></script>
<script type="text/javascript" src="{{asset('DataTables-1.9.4/media/js/jquery.dataTables.min.js')}}"></script>
<link rel="stylesheet" type="text/css" href="{{asset('/jqueryUI/css/ui-darkness/jquery-ui-1.10.3.custom.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('DataTables-1.9.4/media/css/demo_table_jui.css') }}">
<link rel="stylesheet" type="text/css" href="{{asset('/css/Admin/Meeting/viewMeetings.css')}}">

@stop

@section('dashBoardContent')
<br>
{{ link_to_route('showCreateMeeting', 'Create Meeting') }}
<div class="meetingsMenu">
    <h3>Meetings</h3>
    <div class="container">
	{{ Datatable::table()
	->setURL(route('showAllMeetingsJsonCRUD'))
	->addColumn('Actions', 'Name', 'Date', 'Time', 'Place', 'Grade Group/Person')
	->setOptions('bProcessing', true)
	->setOptions('bSort', false)
	->setOptions('iDisplayLength', 5)
	->setOptions('aLengthMenu', [5, 10, 15, 20, 25, 30, 35, 40, 45, 50])
	->setOptions('bAutoWidth', false)
	->setOptions('aoColumns', array(
	    array('sWidth' => '1%'),
	    array('sWidth' => '1%'),
	    array('sWidth' => '1%'),
	    array('sWidth' => '1%'),
	    array('sWidth' => '1%'),
  	    array('sWidth' => '1%')
	))
	->render() }}	        
    </div>
</div>
@stop
