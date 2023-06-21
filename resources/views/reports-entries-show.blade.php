@extends('layouts.app')

@section('content')
    <div class="container-fluid">

        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header bg-light">{{ __('لیست ورود ها') }}
                        <button id="return-printable" class="btn btn-sm btn-outline-secondary float-left">مشاهده نسخه چاپی</button>
                    </div>

                    <div class="card-body">
                        @if(count($entries) != 0)
                            <table class="table text-center" id="unitsTable">
                                <thead>
                                <tr>
                                    <th scope="col">انتخاب</th>
                                    <th scope="col"><input class="column" type="checkbox" checked id="product_id"> نام کالا</th>
                                    <th scope="col"><input class="column" type="checkbox" checked id="date"> تاریخ ورود</th>
                                    <th scope="col"><input class="column" type="checkbox" checked id="worker_id"> وارد کننده</th>
                                    <th scope="col"><input class="column" type="checkbox" checked id="value"> مقدار</th>
                                    <th scope="col"><input class="column" type="checkbox" checked id="price"> قیمت (ریال)</th>
                            
                                    <th scope="col"><input class="column" type="checkbox" checked id="invoice_id"> شماره رسید</th>
                                </tr>
                                </thead>
                                <tbody id="report-body">
                                @foreach($entries as $entry)
                                    <tr>
                                        <td><input type="checkbox" checked id="entry{{ $entry->id }}" class="custom-checkbox checkbox"></td>
                                        @php $date = Verta::createTimestamp($entry->date) @endphp
                                        <td><a style="color: #00f" href="{{ route('entries.edit' , ['entry' => $entry->invoice_id]) }}">{{ $entry->product->title }}</a></td>
                                        <td>{{ $date->year }}/{{ $date->month }}/{{ $date->day }}</td>
                                        <td>{{ $entry->worker->title }}</td>
                                        <td>{{ $entry->value }} {{ $entry->unit->title }}</td>
                                        <td class="price">{{ $entry->price }}</td>
                                        <td>{{ $entry->code }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            <form id="to_print_items" target="_blank" action="{{ route('reports.entries.print') }}" method="post"></form>
                        @else
                            <small>یافت نشد!</small>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $.fn.digits = function(){
            return this.each(function(){
                $(this).text( $(this).text().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,") );
            })
        }

        $(".price").digits();

        $('#return-printable').click(function(){
            let items = [];
            let columns = [];
            $('#report-body .checkbox').each(function (i, el) {
                if (el.checked)
                    items.push((el.id).slice(5));
            });
            $('.column').each(function (i, el) {
                if (el.checked)
                    columns.push(el.id);
            });
            $('#to_print_items').empty();
            $('#to_print_items').append('<input type="hidden" value="' + $('meta[name="csrf-token"]').attr('content') + '" name="_token">');
            $('#to_print_items').append('<input type="hidden" value="' + items + '" name="items">');
            $('#to_print_items').append('<input type="hidden" value="' + columns + '" name="columns">');

            $('#to_print_items').submit();
        });

    </script>
@endsection
