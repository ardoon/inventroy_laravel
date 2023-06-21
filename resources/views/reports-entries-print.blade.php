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
<div class="container-fluid pt-3">
    <table class="table text-center customTable">
        @php $row_counter = 1; @endphp
        <thead>
        <tr>
            <th scope="col">ردیف</th>
            @foreach($column_titles as $title)
                <th scope="col">{{ $title }}</th>
            @endforeach
        </tr>
        </thead>
        <tbody>
        @foreach($entries as $entry)
            <tr>
                <td>{{ $row_counter }}</td>
                @php $date = Verta::createTimestamp($entry->date); $date = $date->year . '/' . $date->month . '/' . $date->day; @endphp
                @foreach($column_titles as $title)
                    @if($title == 'نام کالا')
                        <td class="text-right">{{ $entry->product->title }}</td> @endif
                    @if($title == 'تاریخ ورود')
                        <td>{{ $date }}</td> @endif
                    @if($title == 'وارد کننده')
                        <td>{{ $entry->worker->title }}</td> @endif
                    @if($title == 'مقدار')
                        <td>{{ $entry->value }} {{ $entry->unit->title }}</td> @endif
                    @if($title == 'قیمت (ریال)')
                        <td>{{ $entry->price }}</td> @endif
                    @if($title == 'انبار')
                        <td>{{ $entry->store->title }}</td> @endif
                    @if($title == 'شماره رسید')
                        <td>{{ $entry->code }}</td> @endif
                @endforeach
            </tr>
            @php $row_counter++; @endphp
        @endforeach


        </tbody>
    </table>
</div>
</body>
</html>
