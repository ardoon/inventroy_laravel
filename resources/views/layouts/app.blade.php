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
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">


    <link type="text/css" rel="stylesheet" href="{{ asset('css/jalalidatepicker.min.css') }}" />
    <script type="text/javascript" src="{{ asset('js/jalalidatepicker.min.js') }}"></script>

</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-dark bg-dark shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                انبارداری سام سیتی
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    @guest

                    @else
                    <ul class="navbar-nav ml-auto">
                        @if(Auth::user()->role == 'admin' || Auth::user()->role == 'clerk' || Auth::user()->role == 'reporter')
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="{{ route('products.index') }}" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>کالا ها </a>
                            <div class="dropdown-menu dropdown-menu-left" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="{{ route('products.index') }}">کالاها</a>
                                <a class="dropdown-item" href="{{ route('units.index') }}">یکاها</a>
                                <a class="dropdown-item" href="{{ route('categories.index') }}">دسته بندی</a>
                            </div>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('roles.index') }}">نقش ها</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('parts.index') }}">بخش ها</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('workers.index') }}">اشخاص</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('stores.index') }}">انبارها</a>
                        </li>
                        @endif
                        @if(Auth::user()->role == 'admin')
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('users.index') }}">کاربران</a>
                        </li>
                        @endif
                        @if(Auth::user()->role == 'admin' || Auth::user()->role == 'operator')
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="{{ route('entries.create') }}" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>ورود کالا </a>
                            <div class="dropdown-menu dropdown-menu-left" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="{{ route('entries.create') }}">ثبت ورود</a>
                                <a class="dropdown-item" href="{{ route('entries.index') }}">مشاهده ورود ها</a>
                            </div>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="{{ route('outputs.create') }}" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>خروج کالا </a>
                            <div class="dropdown-menu dropdown-menu-left" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="{{ route('outputs.create') }}">ثبت خروج</a>
                                <a class="dropdown-item" href="{{ route('outputs.index') }}">مشاهده خروج ها</a>
                            </div>
                        </li>
                        @endif
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="{{ route('reports.index') }}" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>گزارشات </a>
                            <div class="dropdown-menu dropdown-menu-left" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="{{ route('reports.entries') }}">گزارشات ورود</a>
                                <a class="dropdown-item" href="{{ route('reports.outputs') }}">گزارشات خروج</a>
                                <a class="dropdown-item" href="{{ route('reports.stocks') }}">گزارشات موجودی</a>
                                <a class="dropdown-item" href="{{ route('reports.records') }}">کاردکس کالا</a>
                            </div>
                        </li>
                    </ul>
                    @endguest

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">
                        <!-- Authentication Links -->
                        @guest

                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-left" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        خروج
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            @yield('content')
        </main>
    </div>
</body>
</html>
