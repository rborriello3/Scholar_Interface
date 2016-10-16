@extends('Layouts.dashboards')

@section('head')
    <title>Scholarship Interface Edit Award</title>
    @parent
    <link rel="stylesheet" type="text/css" href="{{asset('css/Admin/Awards/newAwards.css') }}">
    <script type="text/javascript" src="{{asset('/javascript/Admin/Awards/newAwards.js')}}"></script>

@stop

@section('dashBoardContent')
    <div class="controls">
        <form class="form-inline" role="form" autocomplete="off" action="{{route('doEditAward', array($fundCode, $studentID))}}" method="POST"><br>
            {{Form::token()}}

            <input type="submit" value="Update" class='btn btn-primary' />
            <img src="{{asset('images/Global/loader.gif')}}" style="display: none;" id="loading_image"> {{ link_to_route('showAllAwards', 'Return')}} <br><br>

            <div class="entry input-group">
                <br/>
                <div class="form-group">
                    {{Form::select('fundCode[]', $scholarships, '')}}
                </div>

                <br/>
                <div class="form-group">
                    <input class="form-control" value="{{{$studentID}}}" name="studentID[]" type="text" placeholder="A-Number" maxlength="9" readonly/>
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
			{{Form::select('typeID[]', $typeID, '')}}
	<!--		<input class="form-control" name="typeID[]" type="text" placeholder="Student Type (Graduating = 4, Returning = 6, Entering = 2)" />-->
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

