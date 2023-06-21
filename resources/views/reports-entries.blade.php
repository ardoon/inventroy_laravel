@extends('layouts.app')

@section('content')
    <div class="container">

        <!-- Add new Filter to report -->
        <div id="add-filter-modal" class="modal" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header justify-content-center">
                        <h5 class="modal-title">افزودن فیلتر جدید</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="filter-type-selector">نوع فیلتر</label>
                            <select name="filter-type-selector" id="filter-type-selector" class="custom-select">
                                <option value="code">شماره رسید</option>
                                <option value="product">کالا</option>
                                <option value="category">دسته بندی</option>
                                <option value="store">انبار</option>
                                <option value="worker">وارد کننده</option>
                            </select>
                        </div>

                        <div id="filter-value-wrapper" class="form-group">

                        </div>

                    </div>
                    <div class="modal-footer">
                        <button id="add-filter-btn" type="button" class="btn btn-secondary" data-dismiss="modal">
                            افزودن
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('گزارش ورود') }}
                        <button id="view-report" class="btn btn-outline-secondary btn float-left">مشاهده گزارش</button>
                    </div>

                    <div class="card-body">

                        <form id="filtersForm" action="{{ route('reports.entries.show') }}" method="post">

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

                            <table class="table text-center" id="filtersTable">
                                <thead>
                                <tr>
                                    <th scope="col">نوع فیلتر</th>
                                    <th scope="col">مقدار</th>
                                    <th scope="col">حذف</th>
                                </tr>
                                </thead>
                                <tbody id="units-table-body">
                                <tr id="filters-first-row">
                                    <td colspan="3">هیچ فیلتری تا به حال اعمال نشده است!</td>
                                </tr>
                                </tbody>
                            </table>
                            <div class="w-100 text-center">
                                <p class="custom-add-row-icon">افزودن فیلتر</p>
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

            $('.custom-add-row-icon').click(function () {
                $('#add-filter-modal #filter-value-wrapper').empty();
                let filterType = $('#add-filter-modal #filter-type-selector option:selected').val();
                showFVW(filterType);
                $('#add-filter-modal').modal('show');
            });

            $('#add-filter-modal').on('click change', '#filter-type-selector', function () {
                let filterType = $('option:selected', this).val();

                $('#add-filter-modal #filter-value-wrapper').empty();

                showFVW(filterType);

            });

            function showFVW(Type) {
                if (Type == 'code') {
                    $('#add-filter-modal #filter-value-wrapper').append('<label for="filter-value">مقدار</label> <input type="text" class="form-control" id="filter-value" list="codes"><datalist id="codes"></datalist>');
                    $.ajax({
                        url: "{{ route('entries.codes') }}",
                        method: "get",
                        success: function (response) {
                            $("#add-filter-modal #filter-value-wrapper #codes").empty();
                            if (response.length > 0) {
                                response.forEach(element => {
                                    $("#add-filter-modal #filter-value-wrapper #codes").append("<option value='" + element.code + "'></option>");
                                });
                            }
                        },
                    });
                } else if (Type == 'product') {
                    $('#add-filter-modal #filter-value-wrapper').append('<label for="filter-value">مقدار</label> <input type="text" class="form-control" id="filter-value" list="products"><datalist id="products"></datalist>');
                    $.ajax({
                        url: "{{ route('products.all') }}",
                        method: "get",
                        success: function (response) {
                            $("#add-filter-modal #filter-value-wrapper #products").empty();
                            if (response.length > 0) {
                                response.forEach(element => {
                                    $("#add-filter-modal #filter-value-wrapper #products").append("<option value='" + element.title + "'></option>");
                                });
                            }
                        },
                    });
                } else if (Type == 'category') {
                    $('#add-filter-modal #filter-value-wrapper').append('<label for="filter-value">مقدار</label> <input type="text" class="form-control" id="filter-value" list="categories"><datalist id="categories"></datalist>');
                    $.ajax({
                        url: "{{ route('categories.all') }}",
                        method: "get",
                        success: function (response) {
                            $("#add-filter-modal #filter-value-wrapper #categories").empty();
                            if (response.length > 0) {
                                response.forEach(element => {
                                    $("#add-filter-modal #filter-value-wrapper #categories").append("<option value='" + element.title + "'></option>");
                                });
                            }
                        },
                    });
                } else if (Type == 'store') {
                    $('#add-filter-modal #filter-value-wrapper').append('<label for="filter-value">مقدار</label> <input type="text" class="form-control" id="filter-value" list="stores"><datalist id="stores"></datalist>');
                    $.ajax({
                        url: "{{ route('stores.all') }}",
                        method: "get",
                        success: function (response) {
                            $("#add-filter-modal #filter-value-wrapper #stores").empty();
                            if (response.length > 0) {
                                response.forEach(element => {
                                    $("#add-filter-modal #filter-value-wrapper #stores").append("<option value='" + element.title + "'></option>");
                                });
                            }
                        },
                    });
                } else if (Type == 'worker') {
                    $('#add-filter-modal #filter-value-wrapper').append('<label for="filter-value">مقدار</label> <input type="text" class="form-control" id="filter-value" list="workers"><datalist id="workers"></datalist>');
                    $.ajax({
                        url: "{{ route('workers.all') }}",
                        method: "get",
                        success: function (response) {
                            $("#add-filter-modal #filter-value-wrapper #workers").empty();
                            if (response.length > 0) {
                                response.forEach(element => {
                                    $("#add-filter-modal #filter-value-wrapper #workers").append("<option value='" + element.title + "'></option>");
                                });
                            }
                        },
                    });
                }
            }

            $('#add-filter-modal #add-filter-btn').on('click', function () {
                $('#filtersTable #filters-first-row').remove();
                let filterType = $('#add-filter-modal #filter-type-selector option:selected').val();
                let filterTitle = $('#add-filter-modal #filter-type-selector option:selected').text();
                let filterValue = $('#add-filter-modal #filter-value-wrapper input[id=filter-value]').val();
                $('#filtersTable').append('<tr><td>' + filterTitle + '</td><td>' + filterValue + '</td><td><span class="table-link delete-record fas fa-trash""></span></td><input type="hidden" name="key[]" value="' + filterType + '"><input type="hidden" name="value[]" value="' + filterValue + '"></tr>')
            });

            $('#filtersTable').on('click', '.delete-record', function () {
                $(this).parent().parent().remove();
            });

            $('#view-report').on('click', function (){
                $('#filtersForm').submit();
            });

        });
    </script>
@endsection
