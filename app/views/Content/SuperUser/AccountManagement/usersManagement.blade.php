@extends('Layouts.dashboards')

@section('head')
<title>Scholarship Interface User Managment</title>
@parent

<script type="text/javascript" src="{{asset('DataTables-1.9.4/media/js/jquery.dataTables.min.js')}}"></script>
<link rel="stylesheet" type="text/css" href="{{asset('css/SuperUser/AccountManagement/showUsers.css') }}">
<link rel="stylesheet" type="text/css" href="{{asset('DataTables-1.9.4/media/css/demo_table_jui.css') }}">

@stop

@section('dashBoardContent')
<br>
{{link_to_route('showCreateUser', 'Create New User')}}

<br>
<br>

<div id="usersTable">
    {{ Datatable::table()
    ->setURL(route('allUsersJson'))
    ->addColumn('Actions', 'Name', 'Email', 'To')
    ->setOptions('bProcessing', true)
    ->setOptions('bSort', false)
    ->setOptions('iDisplayLength', 20)
    ->setOptions('aLengthMenu', [5,10,15,20])
    ->setOptions('bAutoWidth', false)
    ->setOptions('aoColumns', array(
    array('sWidth' => '5%'),
    array('sWidth' => '20%'),
    array('sWidth' => '20%'),
    array('sWidth' => '1%')
    ))
    ->render() }}
</div>
@stop
