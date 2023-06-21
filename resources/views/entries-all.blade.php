@extends('layouts.app')

@section('content')
    <div class="container-fluid">

        <!-- Select Product Modal -->
        <div id="select-special-modal" class="modal" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header justify-content-center">
                        <h5 class="modal-title">چاپ صورت جلسه</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="specialsForm" action="{{ route('reports.specials.show') }}" method="post"
                              target="_blank">

                            @csrf
                            <div class="row">
                                <div class="form-group col-6">
                                    <label for="start-date">از تاریخ</label>
                                    <input type="text" id="start-date" name="start_date" class="form-control"
                                           value="{{ $date }}"
                                           data-jdp required>
                                </div>
                                <div class="form-group col-6">
                                    <label for="end-date">تا تاریخ</label>
                                    <input type="text" id="end-date" name="end_date" class="form-control"
                                           value="{{ $date }}"
                                           data-jdp required>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button id="specials-show-btn" type="button" class="btn btn-secondary" data-dismiss="modal">
                            تایید
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header bg-light">{{ __('لیست ورود ها') }}
                        <button id="specials-btn" class="btn btn-outline-secondary btn-sm float-left">چاپ صورت جلسه
                        </button>
                    </div>

                    <div class="card-body">
                        @if(count($invoices) != 0)
                            <table class="table text-center" id="unitsTable">
                                <thead>
                                <tr>
                                    <th scope="col">تاریخ رسید</th>
                                    <th scope="col">عنوان</th>
                                    <th scope="col">وارد کننده</th>
                                    <th scope="col">شماره رسید</th>
                                    <th scope="col">تعداد کالاها</th>
                                    <th scope="col">مشاهده</th>
                                </tr>
                                </thead>
                                <tbody id="units-table-body">
                                @foreach($invoices as $invoice)
                                    <tr>
                                        @php $date = Verta::createTimestamp($invoice->date) @endphp
                                        <td>{{ $date->year }}/{{ $date->month }}/{{ $date->day }}</td>
                                        @if($invoice->title != null)
                                            <td>{{ $invoice->title }}</td>
                                        @else
                                            <td>بدون عنوان</td>
                                        @endif
                                        <td>{{ $invoice->worker->title }}</td>
                                        <td>{{ $invoice->code }}</td>
                                        <td>{{ count($invoice->entries) }}</td>
                                        <td>
                                            <a href="{{ route('entries.edit' , ['entry' => $invoice->id]) }}"
                                               class="fas fa-eye"></a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            {{ $invoices->onEachSide(5)->links() }}
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
            $(function () {
                $('[data-toggle="tooltip"]').tooltip()
            })


            jalaliDatepicker.startWatch();

            $(function () {
                $('[data-toggle="tooltip"]').tooltip()
            })

            $("#specials-btn").click(function () {
                $("#select-special-modal").modal();
            });

            $("#specials-show-btn").click(function () {
                $("#specialsForm").submit();
                //alert('این ویژگی آماده است به زودی فعال میگردد');
            });
        });
    </script>
@endsection
