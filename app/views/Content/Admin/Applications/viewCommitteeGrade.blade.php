@extends('Layouts.dashboards')

@section('head')
    <title>Scholarship Interface Score</title>
    @parent
    <script type="text/javascript" src="{{asset('DataTables-1.9.4/media/js/jquery.dataTables.min.js')}}"></script>

    <link rel="stylesheet" type="text/css" href="{{asset('DataTables-1.9.4/media/css/demo_table_jui.css') }}">
@stop

@section('dashBoardContent')
    <br>
        {{ Datatable::table()
        ->setURL(route('specificAssessmentJSON', $guid))
        ->addColumn('Name', 'Essay', 'Extra', 'Faculty', 'Total', 'Notes', 'Graded On')
        ->setOptions('bProcessing', true)
        ->setOptions('bSort', false)
        ->setOptions('iDisplayLength', 100)
        ->setOptions('aLengthMenu', [100, 200])
        ->setOptions('bAutoWidth', false)
        ->setOptions('aoColumns', array(
            array('sWidth' => '8%'),
            array('sWidth' => '1%'),
            array('sWidth' => '1%'),
            array('sWidth' => '1%'),
            array('sWidth' => '1%'),
            array('sWidth' => '20%'),
            array('sWidth' => '1%'),
        ))
        ->render() }}
@stop