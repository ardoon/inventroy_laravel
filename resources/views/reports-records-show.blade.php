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

        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header bg-light">کاردکس <span style="font-weight: bold;">{{ $product->title }}</span></div>

                    <div class="card-body">
                        @if(count($records) != 0)
                            <table class="table text-center" id="unitsTable">
                                <thead>
                                <tr>
                                    <th scope="col">ردیف</th>
{{--                                    <th scope="col">نام کالا</th>--}}
                                    <th scope="col">تاریخ</th>
                                    <th scope="col">واردکننده/خارج کننده</th>
                                    <th scope="col">مقدار</th>
                                    <th scope="col">موجودی</th>
                                </tr>
                                </thead>
                                <tbody id="units-table-body">
                                @php $aran_counter = 1; @endphp

                                @foreach($records as $record)
                                    <tr>

                                        <td>{{ $aran_counter }}</td>
                                        @php $aran_counter ++; @endphp
                                        {{--                                        <td>{{ $record->product->title }}</td>--}}

                                        @php $date = Verta::createTimestamp($record->date) @endphp
                                        <td>{{ $date->year }}/{{ $date->month }}/{{ $date->day }}</td>
                                        <td>{{ $record->worker->title }}</td>

                                        @if($record->getTable() == 'entries')
                                            <td>{{ $record->value }}+ {{ $record->unit->title }}</td>
                                        @else
                                            <td>{{ $record->value }}- {{ $record->unit->title }}</td>
                                        @endif
                                        @php $stock_value = $record->stock_of_time; @endphp
                                        @if(!(is_numeric($stock_value) && floor( $stock_value ) != $stock_value))
                                            <td>{{ $stock_value }} {{ $record->product->unit->title }}</td>
                                        @elseif($record->product->proportion != null)
                                            <td>{{ floor($record->stock_of_time) }} {{ $record->product->unit->title }} و {{ ($stock_value - floor($record->stock_of_time)) / $record->product->proportion }} {{ $record->product->subunit->title }}</td>
                                        @else
                                            <td>{{ $record->stock_of_time }} {{ $record->product->unit->title }} </td>
                                        @endif
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        @else
                            <small>یافت نشد!</small>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
<script>
    $.fn.digits = function(){
        return this.each(function(){
            $(this).text( $(this).text().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,") );
        })
    }

    $(".price").digits();
</script>
</html>
