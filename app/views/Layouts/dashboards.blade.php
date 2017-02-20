<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    @section('head')
    <link rel="stylesheet" type="text/css" href="{{asset('/css/bootstrap.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{asset('/css/Global/global.css') }}">
    <link rel="stylesheet" href="/fontAwesome/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="{{asset('/css/Global/Layouts/dashboardLayout.css') }}">
    <script type="text/javascript" src="{{asset('/javascript/jquery.js')}}"></script>
    <script type="text/javascript" src="{{asset('/javascript/bootstrap.min.js')}}"></script>
    @show
</head>
<body>
@include('_partials.TopNavigation.NavBar')

<div id="messages">
    @include('_partials.FlashMessages.flashMessages')
</div>

@include('_partials.SideNavigation.sideNavigation' . Session::get('role') . '.' . Request::segment(1))

<div id="authenticatedContainer">
    @yield('dashBoardContent')
</div>

<footer style="margin: 0; text-align: center; color: #464646;">
    <p>Scholarship Interface Created By <a href="https://www.linkedin.com/in/rixhersajazi" target="_blank">Rixhers
            Ajazi</a> and <a href="https://www.linkedin.com/in/raymond-borriello-508637107" target="blank">Ray Borriello</a></p><p>Special thanks to the <a href="http://www.sunyorange.edu/its/" target="_blank">SUNY Orange ITS Department</a> for all of their
                            great work and the <a href="https://www.sunyorange.edu/financialaid/" target="_blank">SUNY Orange Financial Aid Office</a> for giving me the opportunity to grow my portfolio and person skills.</p>
</footer>
</body>
</html>
