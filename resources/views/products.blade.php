@extends('layouts.app')

@section('content')
    <div class="container">

        <!-- Insert Product Modal -->
        <div id="insertModal" class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog"
             aria-labelledby="insertModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="unitUpdateLabel">افزودن کالای جدید</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" class="position-relative">
                        <div id="category-picker" class="category-picker-wrapper position-absolute">
                            <div class="form-group col-6">
                                <label for="select-role">دسته اصلی</label>
                                <select class="custom-select" id="select-role">
                                    <option selected="selected">لطفا انتخاب کنید</option>
                                    @foreach($categories as $category)
                                        <option class="role-level-one"
                                                value="{{ $category->id }}">{{ $category->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <button id="pickCategory" type="button" class="btn btn-primary btn-abs">تایید</button>
                        </div>
                        <form autocomplete="off" id="insertForm" action="{{ route('products.store') }}" method="post">
                            <div class="form-row">
                                <div class="form-group col-6">
                                    <label for="product-title">نام کالا</label>
                                    <input type="text" class="form-control" id="product-title" name="title"
                                           placeholder="به عنوان مثال: سیمان، کاشی و ...">

                                    <label for="unit" class="mt-3">واحد</label>
                                    <select class="custom-select" id="unit" name="product_unit">
                                        @foreach($units as $unit)
                                            <option value="{{ $unit->id }}">{{ $unit->title }}</option>
                                        @endforeach
                                    </select>

                                    <label for="unit-sub" class="mt-3">واحد دوم</label>
                                    <select class="custom-select" id="unit-sub" name="product_unit_sub">
                                        <option selected value>اختیاری </option>
                                        @foreach($units as $unit)
                                            <option value="{{ $unit->id }}">{{ $unit->title }}</option>
                                        @endforeach
                                    </select>

                                    <label for="product-proportion" class="mt-3">نسبت</label>
                                    <input type="text" class="form-control" id="product-proportion" name="proportion"
                                           placeholder="تا شش رقم اعشار">

                                    <label for="product-category" class="mt-3">افزودن دسته بندی</label>
                                    <div>
                                        <input type="hidden" id="product-category-id">
                                        <input type="text" id="product-category" class="form-control col-10 float-right ml-2" value="دسته بندی را انتخاب کنید">
                                        <button id="add-category" class="btn custom-plus-btn"><span
                                                class="fa fa-plus custom-plus"></span></button>
                                    </div>
                                </div>
                                <div class="form-group col-6">
                                    <label for="product-name">دسته بندی ها</label>
                                    <div id="product-categories-wrapper" class="form-control">

                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">بستن</button>
                        <button id="insert-button" type="button" class="btn btn-primary">افزودن</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Update Product Modal -->
        <div id="updateModal" class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog"
             aria-labelledby="updateModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="updateLabel">ویرایش کالا</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div id="category-picker" class="category-picker-wrapper position-absolute">
                            <div class="form-group col-6">
                                <label for="select-role">دسته اصلی</label>
                                <select class="custom-select" id="select-role">
                                    <option selected="selected">لطفا انتخاب کنید</option>
                                    @foreach($categories as $category)
                                        <option class="role-level-one"
                                                value="{{ $category->id }}">{{ $category->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <button id="pickCategory" type="button" class="btn btn-primary btn-abs">تایید</button>
                        </div>
                        <form autocomplete="off" id="updateForm" action="" method="post">
                            <div class="form-row">
                                <div class="form-group col-6">
                                    <label for="product-title">نام کالا</label>
                                    <input type="text" class="form-control" id="product-title" name="title"
                                           placeholder="به عنوان مثال: سیمان، کاشی و ...">

                                    <label for="unit" class="mt-3">واحد</label>
                                    <select class="custom-select" id="unit" name="product_unit">
                                        @foreach($units as $unit)
                                            <option value="{{ $unit->id }}">{{ $unit->title }}</option>
                                        @endforeach
                                    </select>

                                    <label for="unit-sub" class="mt-3">واحد دوم</label>
                                    <select class="custom-select" id="unit-sub" name="product_unit_sub">
                                        <option selected value>اختیاری </option>
                                        @foreach($units as $unit)
                                            <option value="{{ $unit->id }}">{{ $unit->title }}</option>
                                        @endforeach
                                    </select>

                                    <label for="product-proportion" class="mt-3">نسبت</label>
                                    <input type="text" class="form-control" id="product-proportion" name="proportion"
                                           placeholder="تا شش رقم اعشار">

                                    <label for="product-category" class="mt-3">افزودن دسته بندی</label>
                                    <div>
                                        <input type="hidden" id="product-category-id">
                                        <input type="text" id="product-category" class="form-control col-10 float-right ml-2" value="دسته بندی را انتخاب کنید">
                                        <button id="add-category" class="btn custom-plus-btn"><span
                                                class="fa fa-plus custom-plus"></span></button>
                                    </div>
                                </div>
                                <div class="form-group col-6">
                                    <label for="product-name">دسته بندی ها</label>
                                    <div id="product-categories-wrapper" class="form-control">

                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">بستن</button>
                        <button id="delete-button" type="button" class="btn btn-danger">حذف کالا</button>
                        <button id="update-button" type="button" class="btn btn-primary">اعمال</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header bg-light">{{ __('مدیریت کالا') }}
                    <input id="search-product-name" class="mr-5 form-control form-control-sm" style="width:200px; display:inline-block"
                               type="text" placeholder="نام کالا">
                        <select class="custom-select custom-select-sm" style="display:inline-block; width:200px" id="search-category-id">
                            <option value="all">همه</option>
                        @foreach($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->title }}</option>
                        @endforeach
                        </select>
                        <buttom id="search-btn" class="btn btn-sm btn-secondary">اعمال فیلتر</buttom>
                    <!-- Button trigger modal -->
                        @if(Auth::user()->role != 'reporter')
                        <button id="show-insert-modal" type="button" class="btn btn-outline-dark btn-sm float-left"
                                data-toggle="modal"
                                data-target="#insertModal">
                            افزودن کالای جدید
                        </button>
                            @endif
                    </div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        @if(count($products) != 0)
                            <table class="table text-center" id="productsTable">
                                <thead>
                                <tr>
                                    <th scope="col">نام کالا</th>
                                    <th scope="col">یکا</th>
                                    <th scope="col" class="text-center">دسته بندی ها</th>
                                    @if(Auth::user()->role != 'reporter')
                                    <th scope="col" class="text-center">مشاهده</th>
                                        @endif
                                </tr>
                                </thead>
                                <tbody id="products-table-body">
                                @foreach($products as $product)
                                    <tr id="product{{ $product->id }}" data-subunit="{{ $product->unit_sub_id }}" data-proportion="{{ $product->proportion }}">
                                        <td>{{ $product->title }}</td>
                                        <td>{{ $product->unit->title }}</td>
                                        <td class="text-center">
                                            @foreach($product->categories as $category)
                                                <span class="product-category-tiny">{{ $category->title }}</span>
                                            @endforeach
                                            @if(count($product->categories) == 0)
                                                <samll class="empty-td">فاقد دسته بندی</samll>
                                            @endif
                                        </td>
                                        @if(Auth::user()->role != 'reporter')
                                        <td class="text-center">
                                            <span class="table-link show-record fas fa-eye"
                                                  data-id="{{ $product->id }}"></span>
                                        </td>
                                            @endif
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            {{ $products->appends(Request::all())->links() }}
                        @else
                            <small>تا به حال هیچ یکای اندازه گیری ذخیره نشده است!</small>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    
        $("#search-product-name").val("");
        $("#search-category-id").prop("selectedIndex", 0);;

        $(".pagination").addClass('justify-content-center');
        $(".pagination").addClass('pagination-sm');

        $('#insert-button').click(function () {
            let csrf = {'_token': $('meta[name="csrf-token"]').attr('content')}
            $.ajax({
                url: "{{ route('products.store') }}",
                method: "post",
                data: $('#insertForm').serialize() + '&' + $.param(csrf),
                success: function (response) {
                    const data = response;
                    $("#products-table-body").prepend(' <tr id="product' + data.id + '"> <td>' + data.title + '</td> <td>' + data.unit + '</td> <td class="text-center" id="tempID"></td> <td class="text-center"> <span class="table-link show-record fas fa-eye" data-id="' + data.id + '"></span> </td></tr>'),
                        data.categoriesTitles.forEach(element => $('#products-table-body #tempID').append('<span  class="product-category-tiny ml-1">' + element + '<span>'));
                    $('#products-table-body #tempID').removeAttr('id');

                },
                error: function (err) {
                    swal("متاسفیم", "کالای مورد نظر ثبت نشد!", "warning", {
                        button: "باشه",
                    })
                }
            });
            $('#insertModal').modal('hide');
            $('#insertForm #product-title').val("");
            newProductCategories = [];
            $('#insertForm #product-categories-wrapper').children().remove();
            $('#insertForm input[type=hidden]').remove();
            $('#insertForm').append('<input type="hidden" id="product-category-id" value="">');
        });

        let newProductCategories = [];

        $('#insertForm #add-category').click(function (e) {
            e.preventDefault();
            let id = $('#insertForm #product-category-id').val();

            if (newProductCategories.includes(id)) {
                return false;
            }

            let title = $('#insertForm #product-category').val();

            if (title == 'دسته بندی را انتخاب کنید') {
                return false;
            }

            $('#insertForm #product-category').val('دسته بندی را انتحاب کنید');
            $('#insertForm #product-category-id').val('');
            $('#insertForm #product-categories-wrapper').append('<span class="category-single ml-2 mb-3" data-id="' + id + '">' + title + ' <span data-id="' + id + '" class="fa fa-trash mr-3 cat-del"></span> </span>');
            $('#insertForm').append('<input type="hidden" id="cat' + id + '" name="categories[]" value="' + id + '">');
            newProductCategories.push(id);
        });

        $("#insertForm").on("click", '.cat-del', function () {
            let id = $(this).data('id');
            $('#insertForm #product-categories-wrapper span[data-id=' + id + ']').remove();
            $('#insertForm input[id=cat' + id + ']').remove();

            newProductCategories = jQuery.grep(newProductCategories, function (value) {
                return value != id;
            });
        });

        //    Update
        let toUpdateID;

        $("#delete-button").on("click", function () {
            const categoryId = $(this).data("id");
            swal({
                title: "آیا می خواهید کالا حذف شود؟",
                icon: "warning",
                buttons: [
                    'خیر',
                    'بله'
                ],
                dangerMode: true,
            }).then(function (isConfirm) {
                if (isConfirm) {
                    $.ajax({
                        url: "{{ url("/") }}/products/" + toUpdateID,
                        method: "delete",
                        data: {
                            '_token': $('meta[name="csrf-token"]').attr('content'),
                            '_method': "delete"
                        },
                        success: function (response) {
                            const data = response;
                            $("#products-table-body #product" + toUpdateID + "").remove(),
                                $('#updateModal').modal('hide'),
                                swal("تبریک", "کالا حذف شد!", "success", {
                                    button: "باشه",
                                })
                            toUpdateID = null;
                        },
                        error: function (err) {
                            swal("متاسفیم", "کالای مورد نظر حذف نشد!", "warning", {
                                button: "باشه",
                            })
                        }
                    });
                }
            })

        });

        $("#productsTable").on("click", '.show-record', function () {
            let id = $(this).data('id');
            toUpdateID = id;
            let oldTitle = $("#productsTable tr[id=product" + id + "] td:first-child").text();
            let oldUnit = $("#productsTable tr[id=product" + id + "] td:nth-child(2)").text();
            let subUnit = $("#productsTable tr[id=product" + id + "]").data('subunit');
            let proportion = $("#productsTable tr[id=product" + id + "]").data('proportion');

            $('#updateModal').modal('show');
            $('#updateForm #product-title').val(oldTitle);
            $('#updateForm #product-proportion').val(proportion);
            $('#updateForm #unit option:contains(' + oldUnit + ')').attr('selected', 'selected');
            if (typeof subUnit == 'number'){
                $('#updateForm #unit-sub option[value=' + subUnit + ']').attr('selected', 'selected');
            } else {
                $('#updateForm #unit-sub option').removeAttr('selected');
            }

            $.ajax({
                url: "{{ url('/') }}/products/categories/" + id,
                method: "get",
                success: function (response) {
                    const categories = response;
                    $('#updateForm #product-category').val('دسته بندی را انتخاب کنید');
                    $('#updateForm #product-categories-wrapper').children().remove();
                    $('#updateForm input[type=hidden]').remove();
                    $('#updateForm #product-category').after('<input type="hidden" id="product-category-id">');
                    newProductCategories = [];
                    categories.forEach(category => newProductCategories.push(String(category.id)));
                    categories.forEach(category => $('#updateForm #product-categories-wrapper').append('<span class="category-single ml-2 mb-2 d-inline-block" data-id="' + category.id + '">' + category.title + ' <span data-id="' + category.id + '" class="fa fa-trash mr-3 cat-del"></span> </span>'));
                    categories.forEach(category => $('#updateForm').append('<input type="hidden" id="cat' + category.id + '" name="categories[]" value="' + category.id + '">'));
                }
            });


        });

        $('#updateForm #add-category').click(function (e) {
            e.preventDefault();
            let id = $('#updateForm #product-category-id').val();

            if (newProductCategories.includes(id)) {

            } else {
                let title = $('#updateForm #product-category').val();

                if (title == 'دسته بندی را انتخاب کنید') {
                    return false;
                } else {
                    $('#updateForm #product-category').val('دسته بندی را انتخاب کنید');
                    $('#updateForm #product-category-id').val('');
                    $('#updateForm #product-categories-wrapper').append('<span class="category-single ml-2 mb-2  d-inline-block" data-id="' + id + '">' + title + ' <span data-id="' + id + '" class="fa fa-trash mr-3 cat-del"></span> </span>');
                    $('#updateForm').append('<input type="hidden" id="cat' + id + '" name="categories[]" value="' + id + '">');
                    newProductCategories.push(id);
                }
            }

        });

        $("#updateForm").on("click", '.cat-del', function () {
            let id = $(this).data('id');
            $('#updateForm #product-categories-wrapper span[data-id=' + id + ']').remove();
            $('#updateForm input[id=cat' + id + ']').remove();

            newProductCategories = jQuery.grep(newProductCategories, function (value) {
                return value != id;
            });
        });

        $('#update-button').click(function () {
            let csrf = {'_token': $('meta[name="csrf-token"]').attr('content')}
            let method = {'_method': 'put'}
            $.ajax({
                url: "{{ url('/') }}/products/" + toUpdateID,
                method: "post",
                data: $('#updateForm').serialize() + '&' + $.param(csrf) + '&' + $.param(method),
                success: function (response) {
                    const data = response;
                    $("#products-table-body #product" + data.id + " td:first-child").text(data.title);
                    $("#products-table-body #product" + data.id + " td:nth-child(2)").text(data.unit);
                    $("#products-table-body #product" + data.id + " td:nth-child(3)").text("");
                    if (data.unit_sub_id != null){
                        $("#products-table-body #product" + data.id).attr("data-subunit",data.unit_sub_id);
                    }
                    if (data.proportion != null){
                        $("#products-table-body #product" + data.id).attr("data-proportion", data.proportion);
                    }
                    data.categoriesTitles.forEach(element => $('#products-table-body #product' + data.id + ' td:nth-child(3)').append('<span  class="product-category-tiny ml-1">' + element + '<span>'));

                    swal("تبریک", "تغییرات برای کالای " + data.title + " ثبت شد!", "success", {
                        button: "باشه",
                    })
                },
                error: function (err) {
                    swal("متاسفیم", "تغییرات برای کالای مورد نظر ثبت نشد!", "warning", {
                        button: "باشه",
                    })
                }
            });
            $('#updateModal').modal('hide');
            $('#updateForm #product-title').val("");
            newProductCategories = [];
            $('#updateForm #product-categories-wrapper').children().remove();
            $('#updateForm input[type=hidden]').remove();
            $('#updateForm').append('<input type="hidden" id="product-category-id" value="">');
            toUpdateID = null;
        });

        $("#insertForm").on('click', '#product-category', function (){
            $("#insertModal #category-picker").show();
        });
        $("#insertModal #category-picker").on("change", 'select', function () {
            $("#insertModal #product-category").val($('option:selected',this).text());
            $("#insertModal #product-category-id").val($('option:selected',this).val());
        });
        $('#insertModal #category-picker  #select-role').change(function () {
            let id = $('option:selected',this).val();
            $.ajax({
                url: "{{ url('/') }}/categories/children/" + id,
                method: "get",
                success: function (response) {
                    const data = response;
                    if (data.length > 0) {
                        $('#category-picker .children-selector').remove();
                        $('#category-picker .end-selector').remove();

                        $('#category-picker').append('<div class="form-group children-selector col-6"><label for="category-children">زیر دسته</label><select class="custom-select category-children" id="category-children"><option class="reset-child-id" selected>نقش مورد نظر را انتخاب کنید</option></select></div>');
                        data.forEach(element => $("#category-picker #category-children").append("<option class='role-level-two' value='" + element.id + "'>" + element.title + "</option>"));
                    } else {
                        $('#category-picker .children-selector').remove();
                        $('#category-picker .end-selector').remove();
                    }
                },
            });
        });
        $("#insertModal #category-picker").on("change", '#category-children', function () {
            let id = $('option:selected',this).val();
            if (id == 'نقش مورد نظر را انتخاب کنید'){
                $("#insertModal #product-category").val($('#insertModal #select-role option:selected').text());
                $("#insertModal #product-category-id").val($('#insertModal #select-role option:selected').val());
                $('#insertModal .modal-body .end-selector').remove();
            } else {
                $.ajax({
                    url: "{{ url('/') }}/categories/children/" + id,
                    method: "get",
                    success: function (response) {
                        const data = response;
                        if (data.length > 0) {
                            $('#category-picker .end-selector').remove();

                            $('#category-picker').append('<div class="form-group end-selector col-6"><label for="end-children">زیر دسته</label><select class="custom-select end-children" id="end-children"><option class="reset-end-child-id" selected>نقش مورد نظر را انتخاب کنید</option></select></div>');
                            data.forEach(element => $("#category-picker #end-children").append("<option class='role-level-three' value='" + element.id + "'>" + element.title + "</option>"));
                        } else {
                            $('#category-picker .end-selector').remove();
                        }
                    },
                });
            }
        });
        $("#insertModal #category-picker").click(function () {
            $("#category-picker").modal('hide');
        });
        $("#insertModal #category-picker").on("change", '#end-children', function () {
            let id = $('option:selected',this).val();
            if (id == 'نقش مورد نظر را انتخاب کنید') {
                $("#insertModal #product-category").val($('#category-picker #category-children option:selected').text());
                $("#insertModal #product-category-id").val($('#category-picker #category-children option:selected').val());
            }
        });
        $("#insertModal #category-picker #pickCategory").click(function () {
            $("#category-picker").hide();
        });

        $("#updateForm").on('click', '#product-category', function (){
            $("#updateModal #category-picker").show();
        });
        $("#updateModal #category-picker").on("change", 'select', function () {
            $("#updateModal #product-category").val($('option:selected',this).text());
            $("#updateModal #product-category-id").val($('option:selected',this).val());
        });
        $('#updateModal #category-picker #select-role').change(function () {
            let id = $(this).val();
            $.ajax({
                url: "{{ url('/') }}/categories/children/" + id,
                method: "get",
                success: function (response) {
                    const data = response;
                    if (data.length > 0) {
                        $('#updateModal #category-picker .children-selector').remove();
                        $('#updateModal #category-picker .end-selector').remove();

                        $('#updateModal #category-picker').append('<div class="form-group children-selector col-6"><label for="category-children">زیر دسته</label><select class="custom-select category-children" id="category-children"><option class="reset-child-id" selected>نقش مورد نظر را انتخاب کنید</option></select></div>');
                        data.forEach(element => $("#updateModal #category-picker #category-children").append("<option class='role-level-two' value='" + element.id + "'>" + element.title + "</option>"));
                    } else {
                        $('#updateModal #category-picker .children-selector').remove();
                        $('#updateModal #category-picker .end-selector').remove();
                    }
                },
            });
        });
        $("#updateModal #category-picker").on("change", '#category-children', function () {
            let id = $('option:selected',this).val();
            if (id == 'نقش مورد نظر را انتخاب کنید'){
                $("#updateModal #product-category").val($('#updateModal #select-role option:selected').text());
                $("#updateModal #product-category-id").val($('#updateModal #select-role option:selected').val());
                $('#updateModal .modal-body .end-selector').remove();
            } else {
                $.ajax({
                    url: "{{ url('/') }}/categories/children/" + id,
                    method: "get",
                    success: function (response) {
                        const data = response;
                        if (data.length > 0) {
                            $('#updateModal #category-picker .end-selector').remove();

                            $('#updateModal #category-picker').append('<div class="form-group end-selector col-6"><label for="end-children">زیر دسته</label><select class="custom-select end-children" id="end-children"><option class="reset-end-child-id" selected>نقش مورد نظر را انتخاب کنید</option></select></div>');
                            data.forEach(element => $("#updateModal #category-picker #end-children").append("<option class='role-level-three' value='" + element.id + "'>" + element.title + "</option>"));
                        } else {
                            $('#updateModal #category-picker .end-selector').remove();
                        }
                    },
                });
            }
        });
        $("#updateModal #category-picker").click(function () {
            $("#updateModal #category-picker").modal('hide');
        });
        $("#updateModal #category-picker").on("change", '#end-children', function () {
            let id = $('option:selected',this).val();
            if (id == 'نقش مورد نظر را انتخاب کنید') {
                $("#updateModal #product-category").val($('#updateModal #category-picker #category-children option:selected').text());
                $("#updateModal #product-category-id").val($('#updateModal #category-picker #category-children option:selected').val());
            }
        });
        $("#updateModal #category-picker #pickCategory").click(function () {
            $("#updateModal #category-picker").hide();
        });
        
        $("#search-btn").on('click', function(){
            let product = $("#search-product-name").val();
            if(product == ''){
                product = 'none';
            }
            
            let category = $("#search-category-id option:selected").val();
            window.location.href = "{{ url('/') }}" + '/products?product=' + product + '&category=' + category;
        });

    </script>
@endsection
