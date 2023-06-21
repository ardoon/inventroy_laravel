@extends('layouts.app')

@section('content')
    <div class="container">

        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('گزارش کاردکس کالا') }}
                        <button id="view-report" class="btn btn-outline-secondary btn float-left">مشاهده گزارش </button>
                    </div>

                    <div class="card-body">

                        <form id="filtersForm" target="_blank" action="{{ route('reports.records.show') }}" method="post">

                            @csrf

                            <div class="row">
                                <div class="form-group col-6">
                                    <label for="start-date">از تاریخ</label>
                                    <input type="text" id="start-date" name="start_date" class="form-control"
                                           value=""
                                           data-jdp >
                                </div>
                                <div class="form-group col-6">
                                    <label for="end-date">تا تاریخ</label>
                                    <input type="text" id="end-date" name="end_date" class="form-control"
                                           value="{{ $date }}"
                                           data-jdp required>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="filter-type-selector">نوع فیلتر</label>
                                <select name="filter-type-selector" id="filter-type-selector" class="custom-select">
                                    <option value="product">کالا</option>
                                </select>
                            </div>
                            <input type="hidden" name="key[]" value="product">

                            <div id="filter-value-wrapper" class="form-group">

                            </div>

                        </form>

                    </div>
                </div>
            </div>
        </div>

    </div>

    <script>
        $(document).ready(function () {

            jalaliDatepicker.startWatch();

            $('#filter-value-wrapper').append('<label for="filter-value">مقدار</label> <input type="text" class="form-control" id="filter-value" name="value[]" list="products"><datalist id="products"></datalist>');
            $.ajax({
                url: "{{ route('products.all') }}",
                method: "get",
                success: function (response) {
                    $("#filter-value-wrapper #products").empty();
                    if (response.length > 0) {
                        response.forEach(element => {
                            $("#filter-value-wrapper #products").append("<option value='" + element.title + "'></option>");
                        });
                    }
                },
            });

            $('#view-report').on('click', function (){
                $('#filtersForm').submit();
            });

        });

    </script>
@endsection
