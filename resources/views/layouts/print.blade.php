<!doctype html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>انبارداری سام سیتی</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset('js/sweetalert.min.js') }}"></script>
{{--    <script src="https://kit.fontawesome.com/dba609104a.js" crossorigin="anonymous"></script>--}}

<!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <link href="{{ asset('fontawesome/css/all.css') }}" rel="stylesheet"> <!--load all styles -->

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/print.css') }}" rel="stylesheet">


    <link type="text/css" rel="stylesheet" href="{{ asset('css/jalalidatepicker.min.css') }}"/>
    <script type="text/javascript" src="{{ asset('js/jalalidatepicker.min.js') }}"></script>

</head>
<body>
    <div class="container-fluid">
        @yield('content')
    </div>
</body>
</html>
