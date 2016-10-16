@extends('Layouts.dashboards')

@section('head')
    <title>Scholarship Interface Response Dashboard</title>
    @parent
    <script type="text/javascript" src="{{asset('DataTables-1.9.4/media/js/jquery.dataTables.min.js')}}"></script>
    <link rel="stylesheet" type="text/css" href="{{asset('css/Admin/Awards/showAwardsTable.css') }}">
    <link rel="stylesheet" type="text/css" href="{{asset('DataTables-1.9.4/media/css/demo_table_jui.css') }}">
@stop

@section('dashBoardContent')

    {{ Form::open(array('url' => route('doResponseProcess', array()), 'method' => 'POST', 'accept-charset' => 'UTF-8')) }}
        <br/>
        {{ Form::submit('Update', array('class' => 'btn btn-primary'))}}

        <br/><br/>

        {{ Datatable::table()
            ->setURL(route('allResponses'))
            ->addColumn('Actions', 'First Name', 'Last Name', 'ID', 'Scholarship', 'Aid Year', 'TY', 'ACCPT', 'CV', 'App Date')
            ->setOptions('bProcessing', true)
            ->setOptions('bSort', false)
            ->setOptions('iDisplayLength', 50)
            ->setOptions('aLengthMenu', [25, 50, 75, 100, 150, 200])
            ->setOptions('bAutoWidth', false)
            ->setOptions('aoColumns', array(
                array('sWidth' => '1%'),
                array('sWidth' => '5%'),
                array('sWidth' => '5%'),
                array('sWidth' => '1%'),
		array('sWidth' => '5%'),
                array('sWidth' => '2%'),
                array('sWidth' => '1%'),
                array('sWidth' => '1%'),
                array('sWidth' => '1%'),
                array('sWidth' => '1%')
            ))
        ->render() }}

        <br/><br/>
        {{ Form::submit('Update', array('class' => 'btn btn-primary'))}}
        <br/><br/>
    {{ Form::close() }}

@stop
