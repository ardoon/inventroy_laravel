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
    <div class="pb-3">
        <h3 class="text-center">تعاونی مسکن دانشگاه فرهنگیان</h3>
        <h5 class="text-center pt-3">صورت جلسه کارگاهی</h5>
        <div class="col-12 text-left">تاریخ: {{ $date }}</div>
        <div style="font-size: 16px" class="mt-5">
            <div class="row">
                
                <div class="col-12">
                    صورت جلسه میگردد در مورخه .................... اقلام ذیل/فاکتور پیوست توسط ............................. به انبار تعاونی وارد شد که به صورت ریز از انبار خارج میگردد.
                </div>
            </div>
        </div>


    </div>
    <table class="table text-center customTable">

            <thead>
            <tr>
                <th scope="col">ردیف</th>
                <th scope="col">نام کالا</th>
                <th scope="col">مقدار</th>
                <th scope="col">توضیحات</th>
            </tr>
            </thead>            @php $row_counter = 1; $total = 0; @endphp

    @foreach($outputs as $output)
            <tbody>
            <tr>
                <td>{{ $row_counter }}</td>
                <td>{{ $output->product->title }}</td>
                <td class="price">{{ $output->value }} {{ $output->unit->title }}</td>
                <td class="price">{{ $output->description }}</td>
            </tr>
            @php $row_counter++;  @endphp
            @endforeach
        </tbody>
        <tfoot class="page-footer">
            <tr>
                <td style="height: 90px" colspan="5">
                    <div class="row justify-content-center">
                        <div class="col-3 mt-4 mr-3">انباردار</div>
                    </div>
                </td>
            </tr>
        </tfoot>
    </table>
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
