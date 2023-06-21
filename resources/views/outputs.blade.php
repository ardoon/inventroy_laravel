@extends('layouts.app')

@section('content')
    <div class="container-fluid">

        <!-- Select Product Modal -->
        <div id="select-product-modal" class="modal" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header justify-content-center">
                        <h5 class="modal-title">انتخاب کالا</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group row">
                            <div class="form-group col-6">
                                <label for="select-main-category">دسته</label>
                                <select class="custom-select catselect" id="select-main-category">
                                    <option id="reset-first" selected="selected">لطفا انتخاب کنید</option>
                                    @foreach($categories as $category)
                                        <option class="category-level-one"
                                                value="{{ $category->id }}">{{ $category->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-6">
                                <label for="product-selector">کالا</label>
                                <select class="custom-select" id="product-selector">
                                    <option selected="selected">لطفا انتخاب کنید</option>
                                    @foreach($products as $product)
                                        <option data-unit="{{ $product->unit_id }}" value="{{ $product->id }}">{{ $product->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">تایید</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Select Part Modal -->
        <div id="select-part-modal" class="modal" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-sm" role="document">
                <div class="modal-content">
                    <div class="modal-header justify-content-center">
                        <h5 class="modal-title">انتخاب محل مصرف</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group row">
                            <div class="form-group col-12">
                                <label for="select-main-category">دسته</label>
                                <select class="custom-select catselect" id="select-main-category">
                                    <option id="reset-first" selected="selected">لطفا انتخاب کنید</option>
                                    @foreach($parts as $part)
                                        <option class="category-level-one"
                                                value="{{ $part->id }}">{{ $part->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">تایید</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Select Worker Modal -->
        <div id="select-worker-modal" class="modal" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header justify-content-center">
                        <h5 class="modal-title">انتخاب خارج کننده</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group row">
                            <div class="form-group col-6">
                                <label for="select-main-category">نقش</label>
                                <select class="custom-select catselect" id="select-main-category">
                                    <option id="reset-first" selected="selected">لطفا انتخاب کنید</option>
                                    @foreach($roles as $role)
                                        <option class="category-level-one"
                                                value="{{ $role->id }}">{{ $role->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-6">
                                <label for="product-selector">شخص</label>
                                <select class="custom-select" id="product-selector">
                                    <option selected="selected">لطفا انتخاب کنید</option>
                                    @foreach($workers as $worker)
                                        <option value="{{ $worker->id }}">{{ $worker->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">تایید</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header bg-light">{{ __('ثبت خروج از انبار') }}</div>

                    <div class="card-body">
                        <form id="new-entry" method="post" action="{{ route('outputs.store') }}">
                            <div class="form-group row">
                                <div class="form-group col-2">
                                    <label for="date">تاریخ</label>
                                    <input type="text" id="date" name="date" class="form-control form-control-sm"
                                           value="{{ $date }}"
                                           data-jdp required>
                                </div>
                                <div class="form-group col-2">
                                    <label for="code">شماره رسید</label>
                                    <input type="text" id="code" name="code" class="form-control form-control-sm"
                                         readonly required>
                                </div>
                                <div class="form-group col-2">
                                    <label for="worker">خارج کننده</label>
                                    <input type="text" id="worker" name="worker"
                                           class="form-control text-dark bg-white form-control-sm" required>
                                    <input type="hidden" id="worker-id" name="worker_id" value="">
                                </div>
                                <div class="form-group col-2">
                                    <label for="part">محل مصرف</label>
                                    <input type="text" id="part" name="part"
                                           class="form-control text-dark bg-white form-control-sm" required>
                                    <input type="hidden" id="part-id" name="part_id" value="">
                                </div>
                                <div class="form-group col-4">
                                    <button id="submit" class="btn btn-outline-secondary float-left w-50 ml-3 mt-3">ثبت
                                        خروج از انبار
                                    </button>
                                </div>
                            </div>

                            <div class="form-group row custom-single-product">
                                <span class="fa fa-trash delete-single-entry"></span>
                                <div class="form-group col-3">
                                    <label for="product"><span class="rowCounter">1. </span>کالا</label>
                                    <input type="text" id="product" name="product"
                                           class="form-control text-dark bg-white form-control-sm" required>
                                    <input type="hidden" id="product-id" name="product_id[]" value="">
                                </div>
                                <div class="form-group col-1">
                                    <label for="value">مقدار</label>
                                    <input type="text" id="value" name="value[]" class="form-control form-control-sm"
                                           required>
                                </div>
                                <div class="form-group col-2">
                                    <label for="unit">یکا</label>
                                    <select class="custom-select custom-select-sm" id="unit" name="unit[]" required>
                                        <option selected="selected">لطفا انتخاب کنید</option>
                                        @foreach($units as $unit)
                                            <option class="category-level-one"
                                                    value="{{ $unit->id }}">{{ $unit->title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-2">
                                    <label for="description">توضیحات</label>
                                    <input type="text" id="description" name="description[]"
                                           class="form-control form-control-sm">
                                </div>
                            </div>
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">

                        </form>
                        <div class="w-100 text-center">
                            <p class="custom-add-row-icon">افزودن کالا <span class="fa fa-plus mr-2"></span></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>

        function sortItems(){
            let numItems = $('#new-entry .rowCounter').length
            let numCount = 1;
            $('#new-entry .rowCounter').filter(function() {
                $(this).text(numCount + '. ');
                numCount++;
            });
            numCount = 1;
        }

        $(document).ready(function () {

            sortItems();

            $("#new-entry").on('click', '.delete-single-entry', function () {
                swal({
                    title: "آیا می خواهید حذف شود؟",
                    icon: "warning",
                    buttons: [
                        'خیر',
                        'بله'
                    ],
                    dangerMode: true,
                })
                    .then((willDelete) => {
                        if (willDelete) {
                            $(this).parent().remove();
                            sortItems();
                        }
                    });
            });

            $("#submit").click(function () {
                let input = $("<input>")
                    .attr("type", "hidden")
                    .attr("name", "_token").val("{{ csrf_token() }}");
                $('#new-entry').append(input);
            });

            jalaliDatepicker.startWatch();

            const singleProductRow = $('#new-entry .custom-single-product').html();

            $("#new-entry input").val('');
            $('#select-product-modal .modal-body #select-main-category #reset-first').attr('selected', 'selected');
            $('#select-product-modal .modal-body .children-selector').remove();
            $('#select-product-modal .modal-body .end-selector').remove();
            $("#date").val("{{ $date }}");

            $("#new-entry").on('click', '#product', function () {
                $("#select-product-modal").modal('show');
                $(".current-product-active").removeClass('current-product-active');
                $(this).addClass('current-product-active');
            });

            $("#new-entry").on('click', '#part', function () {
                $(".current-part-active").removeClass('current-part-active');
                $(this).parent().parent().addClass('current-part-active');
                $('#select-part-modal .modal-body .select-main-category option').removeAttr('selected');
                $('#select-part-modal .modal-body .select-main-category option:first-child').attr('selected', 'selected');
                $('#select-part-modal .modal-body .children-selector').remove();
                $('#select-part-modal .modal-body .end-selector').remove();
                $('#select-part-modal .modal-body .end-end-selector').remove();
                $("#select-part-modal").modal('show');
            });

            $("#new-entry").on('click', '#worker', function () {
                $("#select-worker-modal").modal('show');
            });

            function getProducts(categoryID) {
                $('#select-product-modal #product-selector').children().remove();
                $.ajax({
                    url: "{{ url('/') }}/products/bycategory/" + categoryID,
                    method: "get",
                    success: function (response) {
                        const data = response;
                        if (data.length > 0) {
                            data.forEach(element => {
                                $("#select-product-modal #product-selector").append("<option data-unit='" + element.unit_id + "' value='" + element.id + "'>" + element.title + "</option>")
                            });
                        } else {
                            $("#select-product-modal #product-selector").append("<option>کالایی وجود ندارد!</option>");
                            $('#select-product-modal #product-selector option:first-child').attr('selected', 'selected');
                        }
                    },
                });
            };

            function getRoles(roleID) {
                $('#select-worker-modal #product-selector').children().remove();
                $.ajax({
                    url: "{{ url('/') }}/workers/byrole/" + roleID,
                    method: "get",
                    success: function (response) {
                        const data = response;
                        console.log(data)
                        if (data.length > 0) {
                            data.forEach(element => $("#select-worker-modal #product-selector").append("<option value='" + element.id + "'>" + element.title + "</option>"));
                        } else {
                            $("#select-worker-modal #product-selector").append("<option>شخصی وجود ندارد!</option>");
                            $('#select-worker-modal #product-selector option:first-child').attr('selected', 'selected');
                        }
                    },
                });
            };

            $('#select-product-modal').on('change', '#select-main-category', function () {
                let id = $('option:selected', this).val();
                if (id == 'لطفا انتخاب کنید') {
                    $('#select-product-modal .modal-body .children-selector').remove();
                    $('#select-product-modal .modal-body .end-selector').remove();
                    getProducts("all");
                } else {
                    $('#select-product-modal .modal-body .end-selector').remove();
                    $('#select-product-modal .modal-body #category-children').remove();
                    getProducts(id);
                    $.ajax({
                        url: "{{ url('/') }}/categories/children/" + id,
                        method: "get",
                        success: function (response) {
                            const data = response;
                            if (data.length > 0) {
                                $('#select-product-modal .modal-body .children-selector').remove();
                                $('#select-product-modal .modal-body .end-selector').remove();

                                $('#select-product-modal .modal-body .row').append('<div class="form-group col-6 children-selector"><label for="category-children">زیر دسته</label><select class="custom-select catselect category-children" id="category-children"><option class="reset-child-id" selected>دسته مورد نظر را انتخاب کنید</option></select></div>');
                                data.forEach(element => $("#select-product-modal #category-children").append("<option class='role-level-two' value='" + element.id + "'>" + element.title + "</option>"));
                            } else {
                                $('#select-product-modal .modal-body .children-selector').remove();
                                $('#select-product-modal .modal-body .end-selector').remove();
                            }
                        },
                    });
                }
            });
            $("#select-product-modal").on("change", '#category-children', function () {
                let id = $('option:selected', this).val();
                if (id == 'دسته مورد نظر را انتخاب کنید') {
                    $('#select-product-modal .modal-body .end-selector').remove();
                    getProducts($('#select-main-category option:selected').val());
                } else {
                    getProducts(id);
                    $.ajax({
                        url: "{{ url('/') }}/categories/children/" + id,
                        method: "get",
                        success: function (response) {
                            const data = response;
                            if (data.length > 0) {
                                $('#select-product-modal .modal-body .end-selector').remove();

                                $('#select-product-modal .modal-body').append('<div class="form-group row end-selector"><div class="form-group col-6 end-selector"><label for="end-children">زیر دسته</label><select class="custom-select end-children catselect" id="end-children"><option class="reset-end-child-id" selected>دسته مورد نظر را انتخاب کنید</option></select></div></div>');
                                data.forEach(element => $("#select-product-modal #end-children").append("<option class='role-level-three' value='" + element.id + "'>" + element.title + "</option>"));
                            } else {
                                $('#select-product-modal .modal-body .end-selector').remove();
                            }
                        },
                    });
                }

            });

            $("#select-product-modal").on("change", '#end-children', function () {
                let id = $('option:selected', this).val();
                if (id == 'دسته مورد نظر را انتخاب کنید') {
                    getProducts($('#select-product-modal  .role-level-two:selected').val());
                } else {
                    getProducts(id);
                }
            });

            $("#select-product-modal #product-selector").on("change", 'select', function () {
                $(".current-product-active").val($('option:selected', this).text());
                $(".current-product-active").next().val($('option:selected', this).val());
                let unitID = $('option:selected', this).data('unit');
                let thisRow = $(".current-product-active").parent().parent();
                $("#unit option[value=" + unitID + "]", thisRow).attr('selected', 'selected');
            });
            $("#select-product-modal .modal-footer button").click(function () {
                $(".current-product-active").val($("#select-product-modal #product-selector option:selected").text());
                $(".current-product-active").next().val($("#select-product-modal #product-selector option:selected").val());
                let unitID = $('#product-selector option:selected').data('unit');
                let thisRow = $(".current-product-active").parent().parent();
                $("#unit option[value=" + unitID + "]", thisRow).attr('selected', 'selected');
            });

            $('#select-worker-modal').on('change', '#select-main-category', function () {
                let id = $('option:selected', this).val();
                if (id == 'لطفا انتخاب کنید') {
                    $('#select-worker-modal .modal-body .children-selector').remove();
                    $('#select-worker-modal .modal-body .end-selector').remove();
                    getRoles("all");
                } else {
                    $('#select-worker-modal .modal-body .end-selector').remove();
                    $('#select-worker-modal .modal-body #category-children').remove();
                    getRoles(id);
                    $.ajax({
                        url: "{{ url('/') }}/roles/children/" + id,
                        method: "get",
                        success: function (response) {
                            const data = response;
                            if (data.length > 0) {
                                $('#select-worker-modal .modal-body .children-selector').remove();
                                $('#select-worker-modal .modal-body .end-selector').remove();

                                $('#select-worker-modal .modal-body .row').append('<div class="form-group col-6 children-selector"><label for="category-children">زیر دسته</label><select class="custom-select catselect category-children" id="category-children"><option class="reset-child-id" selected>دسته مورد نظر را انتخاب کنید</option></select></div>');
                                data.forEach(element => $("#select-worker-modal #category-children").append("<option class='role-level-two' value='" + element.id + "'>" + element.title + "</option>"));
                            } else {
                                $('#select-worker-modal .modal-body .children-selector').remove();
                                $('#select-worker-modal .modal-body .end-selector').remove();
                            }
                        },
                    });
                }
            });
            $("#select-worker-modal").on("change", '#category-children', function () {
                let id = $('option:selected', this).val();
                if (id == 'دسته مورد نظر را انتخاب کنید') {
                    $('#select-worker-modal .modal-body .end-selector').remove();
                    getRoles($('#select-worker-modal option:selected').val());
                } else {
                    getRoles(id);
                    $.ajax({
                        url: "{{ url('/') }}/roles/children/" + id,
                        method: "get",
                        success: function (response) {
                            const data = response;
                            if (data.length > 0) {
                                $('#select-worker-modal .modal-body .end-selector').remove();

                                $('#select-worker-modal .modal-body').append('<div class="form-group row end-selector"><div class="form-group col-6 end-selector"><label for="end-children">زیر دسته</label><select class="custom-select end-children catselect" id="end-children"><option class="reset-end-child-id" selected>دسته مورد نظر را انتخاب کنید</option></select></div></div>');
                                data.forEach(element => $("#select-worker-modal #end-children").append("<option class='role-level-three' value='" + element.id + "'>" + element.title + "</option>"));
                            } else {
                                $('#select-worker-modal .modal-body .end-selector').remove();
                            }
                        },
                    });
                }
            });
            $("#select-worker-modal").on("change", '#end-children', function () {
                let id = $('option:selected', this).val();
                if (id == 'دسته مورد نظر را انتخاب کنید') {
                    getRoles($('#select-worker-modal  .role-level-two:selected').val());
                } else {
                    getRoles(id);
                }
            });

            $("#select-worker-modal #product-selector").on("change", 'select', function () {
                $('#new-entry #worker').val($('option:selected', this).text());
                $('#new-entry #worker-id').val($('option:selected', this).val());
            });

            $("#select-worker-modal .modal-footer button").click(function () {
                $('#new-entry #worker').val($("#select-worker-modal #product-selector option:selected").text());
                $('#new-entry #worker-id').val($("#select-worker-modal #product-selector option:selected").val());
            });


            // _-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_- Select Part Scripts -_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-

            $("#select-part-modal").on("change", 'select', function () {
                $('#new-entry .current-part-active #part').val($('option:selected', this).text());
                $('#new-entry .current-part-active #part-id').val($('option:selected', this).val());
            });

            $('#select-part-modal').on('change', '#select-main-category', function () {
                let id = $('option:selected', this).val();
                if (id == 'لطفا انتخاب کنید') {
                    $('#select-part-modal .modal-body .children-selector').remove();
                    $('#select-part-modal .modal-body .end-selector').remove();
                    $('#select-part-modal .modal-body .end-end-selector').remove();
                    $('#new-entry .current-part-active #part').val('');
                    $('#new-entry .current-part-active #part-id').val('');
                } else {
                    $('#select-part-modal .modal-body .end-end-selector').remove();
                    $('#select-part-modal .modal-body .end-selector').remove();
                    $('#select-part-modal .modal-body #category-children').remove();
                    $.ajax({
                        url: "{{ url('/') }}/parts/children/" + id,
                        method: "get",
                        success: function (response) {
                            const data = response;
                            if (data.length > 0) {
                                $('#select-part-modal .modal-body .children-selector').remove();
                                $('#select-part-modal .modal-body .end-selector').remove();
                                $('#select-part-modal .modal-body .end-end-selector').remove();

                                $('#select-part-modal .modal-body .row').append('<div class="form-group col-12 children-selector"><label for="category-children">زیر دسته</label><select class="custom-select catselect category-children" id="category-children"><option class="reset-child-id" selected>دسته مورد نظر را انتخاب کنید</option></select></div>');
                                data.forEach(element => $("#select-part-modal #category-children").append("<option class='role-level-two' value='" + element.id + "'>" + element.title + "</option>"));
                            } else {
                                $('#select-part-modal .modal-body .children-selector').remove();
                                $('#select-part-modal .modal-body .end-selector').remove();
                                $('#select-part-modal .modal-body .end-end-selector').remove();
                            }
                        },
                    });
                }
            });
            $("#select-part-modal").on("change", '#category-children', function () {
                let id = $('option:selected', this).val();
                if (id == 'دسته مورد نظر را انتخاب کنید') {
                    $('#select-part-modal .modal-body .end-selector').remove();
                    $('#select-part-modal .modal-body .end-end-selector').remove();
                    $('#new-entry .current-part-active #part').val($('#select-part-modal #select-main-category option:selected').text());
                    $('#new-entry .current-part-active #part-id').val($('#select-part-modal #select-main-category option:selected').val());
                } else {
                    $.ajax({
                        url: "{{ url('/') }}/parts/children/" + id,
                        method: "get",
                        success: function (response) {
                            const data = response;
                            if (data.length > 0) {
                                $('#select-part-modal .modal-body .end-selector').remove();
                                $('#select-part-modal .modal-body .end-end-selector').remove();

                                $('#select-part-modal .modal-body').append('<div class="form-group row end-selector"><div class="form-group col-12 end-selector"><label for="end-children">زیر دسته</label><select class="custom-select end-children catselect" id="end-children"><option class="reset-end-child-id" selected>دسته مورد نظر را انتخاب کنید</option></select></div></div>');
                                data.forEach(element => $("#select-part-modal #end-children").append("<option class='role-level-three' value='" + element.id + "'>" + element.title + "</option>"));
                            } else {
                                $('#select-part-modal .modal-body .end-end-selector').remove();
                                $('#select-part-modal .modal-body .end-selector').remove();
                            }
                        },
                    });
                }
            });
            $("#select-part-modal").on("change", '#end-children', function () {
                let id = $('option:selected', this).val();
                if (id == 'دسته مورد نظر را انتخاب کنید') {
                    $('#select-part-modal .modal-body .end-end-selector').remove();
                    $('#new-entry .current-part-active #part').val($('#category-children option:selected').text());
                    $('#new-entry .current-part-active #part-id').val($('#category-children option:selected').val());
                } else {
                    $.ajax({
                        url: "{{ url('/') }}/parts/children/" + id,
                        method: "get",
                        success: function (response) {
                            const data = response;
                            if (data.length > 0) {
                                $('#select-part-modal .modal-body .end-end-selector').remove();

                                $('#select-part-modal .modal-body').append('<div class="form-group row end-end-selector"><div class="form-group col-12 end-end-selector"><label for="end-end-children">زیر دسته</label><select class="custom-select end-end-children catselect" id="end-end-children"><option class="reset-end-end-child-id" selected>دسته مورد نظر را انتخاب کنید</option></select></div></div>');
                                data.forEach(element => $("#select-part-modal #end-end-children").append("<option class='role-level-four' value='" + element.id + "'>" + element.title + "</option>"));
                            } else {
                                $('#select-part-modal .modal-body .end-end-selector').remove();
                            }
                        },
                    });
                }
            });
            $("#select-part-modal").on("change", '#end-end-children', function () {
                let id = $('option:selected', this).val();
                if (id == 'دسته مورد نظر را انتخاب کنید') {
                    $('#new-entry .current-part-active #part').val($('#end-children option:selected').text());
                    $('#new-entry .current-part-active #part-id').val($('#end-children option:selected').val());
                }
            });

            $(".custom-add-row-icon").click(function () {
                $("#new-entry").append('<div class="form-group row custom-single-product">' + singleProductRow + '</div>');
                sortItems();
            });
            
            $("#code").val('{{ $generated_no }}');
            
        });
    </script>
@endsection
