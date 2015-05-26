@extends('Layouts.dashboards')

@section('head')
<title>Scholarship Interface Entering Ranks {{{Session::get('currentAidyear')}}}</title>
@parent
<script type="text/javascript" src="{{asset('DataTables-1.9.4/media/js/jquery.dataTables.min.js')}}"></script>
<link rel="stylesheet" type="text/css" href="{{asset('DataTables-1.9.4/media/css/demo_table_jui.css') }}">
<script type="text/javascript" src="{{asset('DataTables-1.9.4/extras/TableTools/media/js/ZeroClipboard.js')}}"></script>
<script type="text/javascript" src="{{asset('DataTables-1.9.4/extras/TableTools/media/js/TableTools.js')}}"></script>
<link rel="stylesheet" type="text/css"
      href="{{asset('DataTables-1.9.4/extras/TableTools/media/css/TableTools_JUI.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('DataTables-1.9.4/extras/TableTools/media/css/TableTools.css')}}">
@stop

@section('dashBoardContent')
<br>
{{ link_to_route('showReportsHome', 'Return') }}
<br>
    <h4 class='reportName'>Entering Ranks</h4>
    {{ link_to_route('enteringRankJSON', 'Print', array(), array('class' => 'btn btn-primary', 'target' => '_blank'))}}
    {{ Datatable::table()
    ->setURL(route('enteringRankJSON'))
    ->addColumn('Student', 'Major', 'AVG', 'Score', 'Graders', 'Need', 'Award(s)')
    ->setOptions('bProcessing', true)
    ->setOptions('bSort', true)
    ->setOptions('aaSorting', array(
            array(3, 'desc')
        ))
    ->setOptions('iDisplayLength', 150)
    ->setOptions('aLengthMenu', [50, 100, 150, 200, 250, 300, 350, 400, 450])
    ->setOptions('bAutoWidth', false)
    ->setOptions('sDom', 'T<"clear">lrtip')
    ->setOptions('oTableTools', array(
        'sSwfPath' => '/DataTables-1.9.4/extras/TableTools/media/swf/copy_csv_xls_pdf.swf',
        'aButtons' => array(
            array('sExtends' => 'csv', 'sButtonText' => 'CSV Export'),
            array('sExtends' => 'copy', 'sButtonText' => 'Copy Current Set')
            )
        )
    )
    ->setOptions('aoColumns', array(
        array('sWidth' => '5%'),
        array('sWidth' => '1%'),
        array('sWidth' => '1%'),
        array('sWidth' => '1%'),
        array('sWidth' => '50%'),
        array('sWidth' => '1%'),
        array('sWidth' => '20%')
    ))
    ->render() }}

@stop