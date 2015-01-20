@extends('Layouts.master')

@section('head')
<title>Scholarship Interface Logout</title>
<style type="text/css">
    ul {
        margin-left: 1px;
    }

    li {
        margin-left: 1px;
    }

    #logout {
        margin-left: 35%;
        margin-top: 15%;
        width: 400px;
        height: 200px;
        padding: 10px;
        border: solid 1px;
        background: white;
    }

    #loggingOutSpinner {
        display: inline;
    }

    #loadingMessage {
        margin-top: 30%;
        float: right;
    }

    #loading_image {
        margin-left: 45%;
        margin-top: 5%;
    }

    #checkMark {
        font-size: 5em;
        color: green;
        margin-left: 80%;
    }

    #closeWindow {
        color: red;
        font-size: 1.3em;
    }

</style>

<script type="text/javascript">
    setTimeout(function () {
        $("#loggingOutSpinner").fadeOut('slow');
    }, 3000);

    setTimeout(function () {
        $("#afterSpinner").fadeIn('slow');
    }, 3500);
</script>
@parent
@stop

@section('content')
<div id="logout">
    <div id="loggingOutSpinner">
        <h3>Logging Out</h3>
        <img src="{{asset('images/Global/loader.gif')}}" id="loading_image">

        <p id="loadingMessage">Please Wait...</p>
    </div>

    <div id="afterSpinner" style="display: none;">
        <i class="glyphicon glyphicon-check" id="checkMark"></i></li><br>

        <p>Please review the following:</p>
        <ul>
            <li>Close <b>browser window</b> to finish logging out <i class="glyphicon glyphicon-remove-sign"
                                                                     id="closeWindow"></i></li>
            <li>If you need to login back in: {{link_to_route('home.index', '>>Click Here<<')}}</li>
        </ul>
    </div>
</div>
@stop