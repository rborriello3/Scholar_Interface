<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    @section('head')
    <link rel="stylesheet" type="text/css" href="{{asset('/css/bootstrap.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{asset('/css/Global/global.css') }}">
    <link rel="stylesheet" type="text/css" href="{{asset('/css/Global/Layouts/noneDashboardLayout.css') }}">
    <script type="text/javascript" src="{{asset('/javascript/jquery.js')}}"></script>
    <script type="text/javascript" src="{{asset('/javascript/bootstrap.min.js')}}"></script>
    @show
</head>
<body>
<div id="flashMessages">
    @section('messages')
    @show
    @include('_partials.FlashMessages.flashMessages')
</div>

<div id="container">
    @yield('content')
</div>

<footer class="navbar navbar-fixed-bottom">
    <p>Scholarship Interface Created By <a href="https://www.linkedin.com/in/rixhersajazi" target="_blank">Rixhers
            Ajazi</a></p>
</footer>

</body>
</html>