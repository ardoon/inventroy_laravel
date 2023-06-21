@extends('layouts.app')

@section('content')
    <div class="container-fluid">

        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header bg-light">{{ __('لیست خروج ها') }}</div>

                    <div class="card-body">
                        @if(count($receipts) != 0)
                            <table class="table text-center" id="unitsTable">
                                <thead>
                                <tr>
                                    <th scope="col">تاریخ رسید</th>
                                    <th scope="col">خارج کننده</th>
                                    <th scope="col">شماره رسید</th>
                                    <th scope="col">تعداد کالاها</th>
                                    <th scope="col">مشاهده</th>
                                </tr>
                                </thead>
                                <tbody id="units-table-body">
                                @foreach($receipts as $receipt)
                                    <tr>
                                        @php $date = Verta::createTimestamp($receipt->date) @endphp
                                        <td>{{ $date->year }}/{{ $date->month }}/{{ $date->day }}</td>
                                        <td>{{ $receipt->worker->title }}</td>
                                        <td>{{ $receipt->code }}</td>
                                        <td>{{ count($receipt->outputs) }}</td>
                                        <td>
                                            <a href="{{ route('outputs.edit' , ['output' => $receipt->id]) }}" class="fas fa-eye"></a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            {{ $receipts->onEachSide(5)->links() }}
                        @else
                            <small>تا به حال هیچ ورودی ذخیره نشده است!</small>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <style>
        jdp-container{
            z-index:999999999 !important;
        }
    </style>
    <script>
        $(document).ready(function () {
            
            jalaliDatepicker.startWatch();
            
            $(function () {
                $('[data-toggle="tooltip"]').tooltip()
            })
        });
        
        $("#specials-btn").click(function () {
            $("#select-special-modal").modal();
        });
        
        $("#specials-show-btn").click(function () {
             $("#specialsForm").submit();
            //alert('این ویژگی آماده است به زودی فعال میگردد');
        });
            
    </script>
@endsection
