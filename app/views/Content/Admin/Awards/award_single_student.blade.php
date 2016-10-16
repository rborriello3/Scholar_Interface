@extends('Layouts.dashboards')

@section('head')
<title>Scholarship Interface Award Student</title>
@parent
<link rel="stylesheet" type="text/css" href="{{asset('css/Admin/Awards/newAwards.css') }}">
<script type="text/javascript" src="{{asset('/javascript/Admin/Awards/newAwards.js')}}"></script>

@stop

@section('dashBoardContent')
<div class="controls">
    <form class="form-inline" role="form" autocomplete="off" action="{{route('doAwardSingleStudent', $guid)}}" method="POST"><br>
        {{Form::token()}}

        <input type="submit" value="Save" class='btn btn-primary' />
        <img src="{{asset('images/Global/loader.gif')}}" style="display: none;" id="loading_image"> {{ link_to_route('showAllAwards', 'Return')}} <br><br>

        <div class="entry input-group">
            <br>
            <font color="red">Enter A-Number (For verification): {{{$studentID}}}</font>

            <br/>
            <div class="form-group">
                {{Form::select('fundCode[]', $scholarships, '')}}
            </div>
            <br>

            <div class="form-group">
                <input class="form-control" name="studentID[]" type="text" placeholder="A-Number" maxlength="9"/>
            </div>

            <div class="form-group">
                <input class="form-control" name="awardAmount[]" type="text" placeholder="Amount" />
            </div>

            <div class="form-group">
                <input class="form-control" name="department[]" type="text" placeholder="Department" />
            </div>

            <div class="form-group">
                <input class="form-control" name="notes[]" type="text" placeholder="Notes" />
            </div>

            <div class="form-group">
            		<span class="input-group-btn">
                    		<button class="btn btn-success btn-add" type="button">
                                <span class="glyphicon glyphicon-plus"></span>
                            </button>
                	</span>
            </div>
        </div>
    </form>
</div>

<script type="text/javascript">
    $('.form-inline').submit(function () {
        $('#loading_image').show(); // show animation
        $(':submit', this).attr('disabled', 'disabled'); // disables form submission
        return true; // allow regular form submission
    });
</script>

@stop

