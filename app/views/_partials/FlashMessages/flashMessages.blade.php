@if(Session::has('success'))
<div class="alert alert-success alert-dismissable fade in" style="text-align:center">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true"><i
            class="glyphicon glyphicon-remove-circle"></i></button>
    <font color="black">{{ Session::get('success') }}</font>
</div>
@endif
@if(Session::has('error'))
<div class="alert alert-danger alert-dismissable fade in" style="text-align:center">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true"><i
            class="glyphicon glyphicon-remove-circle"></i></span></button>
    <font color="black">{{ Session::get('error') }}</font>
</div>
@endif
@if(Session::has('message'))
<div class="alert alert-info alert-dismissable fade in" style="text-align:center">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true"><i
            class="glyphicon glyphicon-remove-circle"></i></span></button>
    <font color="black">{{ Session::get('message') }}</font>
</div>
@endif
@if(Session::has('formMessage'))
<div class="alert alert-warning alert-dismissable fade in" style="text-align:center">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true"><i
            class="glyphicon glyphicon-remove-circle"></i></span></button>
    <font color="black">{{ Session::get('formMessage') }}</font>
</div>
@endif
