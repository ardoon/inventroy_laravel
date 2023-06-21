@extends('layouts.app')

@section('content')
    <div class="container">

        <!-- Insert Category Modal -->
        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
             aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">افزودن بخش</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="category-title">نام بخش جدید</label>
                            <input type="text" class="form-control" id="category-title"
                                   placeholder="به عنوان مثال: اتاق نظارت، بلوک ب و ...">
                        </div>
                        <input type="hidden" id="category-parent-id" value="">
                        <div class="form-group">
                            <label for="category-parent">بخش اصلی</label>
                            <select class="custom-select category-parent" id="category-parent">
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">بستن</button>
                        <button id="add-category" type="button" class="btn btn-primary">افزودن</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Update Category Modal -->
        <div class="modal fade" id="updateCategoryModal" tabindex="-1" role="dialog"
             aria-labelledby="updateCategoryLabel"
             aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="updateCategoryLabel">ویرایش بخش</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="category-new-title">نام بخش</label>
                            <input type="text" class="form-control" id="category-new-title"
                                   placeholder="به عنوان مثال: اتاق نظارت، بلوک ب و ...">
                        </div>
                        <input type="hidden" id="category-id" value="">
                        <input type="hidden" id="category-parent-id" value="">
                        <input type="hidden" id="category-old-title" value="">
                        <div class="form-group">
                            <label for="category-parent">بخش اصلی</label>
                            <select class="custom-select category-parent" id="category-parent">
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">بستن</button>
                        <button id="update-category" type="button" class="btn btn-primary">اعمال</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header bg-light">{{ __('مدیریت بخش ها') }}
                    <!-- Button trigger modal -->
                        @if(Auth::user()->role != 'reporter')
                        <button id="show-add-modal" type="button" class="btn btn-outline-dark btn-sm float-left"
                                data-toggle="modal"
                                data-target="#exampleModal">
                            افزودن بخش جدید
                        </button>
                            @endif
                    </div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        @if(count($categories) != 0)
                            <table class="table text-center" id="categoriesTable">
                                <thead>
                                <tr>
                                    <th scope="col" class="text-right">نام بخش</th>
                                    @if(Auth::user()->role != 'reporter')
                                    <th scope="col">ویرایش</th>
                                    <th scope="col">حذف</th>
                                        @endif
                                </tr>
                                </thead>
                                <tbody id="categories-table-body">
                                @foreach($categories as $category)
                                    <tr class="head-row" id="cat{{ $category->id }}">
                                        <td class="text-right">{{ $category->title }}</td>
                                        @if(Auth::user()->role != 'reporter')
                                        <td>
                                            <span class="table-link edit-record fas fa-edit"
                                                  data-id="{{ $category->id }}"
                                                  data-toggle="modal"
                                                  data-target="#updateCategoryModal"></span>
                                        </td>
                                        <td>
                                            <span class="table-link delete-record fas fa-trash"
                                                  data-id="{{ $category->id }}"></span>
                                        </td>
                                            @endif
                                    </tr>
                                    @foreach($subcategories as $subcategory)

                                        @if($subcategory->parent_id == $category->id)

                                            <h3></h3>
                                            <tr id="cat{{ $subcategory->id }}">
                                                <td class="text-right">---- {{ $subcategory->title }}</td>
                                                @if(Auth::user()->role != 'reporter')
                                                <td>
                                            <span class="table-link edit-record fas fa-edit"
                                                  data-id="{{ $subcategory->id }}"
                                                  data-toggle="modal"
                                                  data-target="#updateCategoryModal"></span>
                                                </td>
                                                <td>
                                            <span class="table-link delete-record fas fa-trash"
                                                  data-id="{{ $subcategory->id }}"></span>
                                                </td>
                                                    @endif
                                            </tr>

                                            @foreach($subcategories as $subsubcategory)

                                                @if($subsubcategory->parent_id == $subcategory->id)

                                                    <h3></h3>
                                                    <tr id="cat{{ $subsubcategory->id }}">
                                                        <td class="text-right">
                                                            -------- {{ $subsubcategory->title }}</td>
                                                        @if(Auth::user()->role != 'reporter')
                                                        <td>
                                            <span class="table-link edit-record fas fa-edit"
                                                  data-id="{{ $subsubcategory->id }}"
                                                  data-toggle="modal"
                                                  data-target="#updateCategoryModal"></span>
                                                        </td>
                                                        <td>
                                            <span class="table-link delete-record fas fa-trash"
                                                  data-id="{{ $subsubcategory->id }}"></span>
                                                        </td>
                                                            @endif
                                                    </tr>

                                                    @foreach($subcategories as $subsubsubcategory)

                                                        @if($subsubsubcategory->parent_id == $subsubcategory->id)

                                                            <h3></h3>
                                                            <tr id="cat{{ $subsubsubcategory->id }}">
                                                                <td class="text-right">
                                                                    ---------------- {{ $subsubsubcategory->title }}</td>
                                                                @if(Auth::user()->role != 'reporter')
                                                                <td>
                                            <span class="table-link edit-record fas fa-edit"
                                                  data-id="{{ $subsubsubcategory->id }}"
                                                  data-toggle="modal"
                                                  data-target="#updateCategoryModal"></span>
                                                                </td>
                                                                <td>
                                            <span class="table-link delete-record fas fa-trash"
                                                  data-id="{{ $subsubsubcategory->id }}"></span>
                                                                </td>
                                                                    @endif
                                                            </tr>

                                                        @endif

                                                    @endforeach

                                                @endif

                                            @endforeach

                                        @endif

                                    @endforeach

                                @endforeach
                                </tbody>
                            </table>
                        @else
                            <small>تا به حال هیچ بخشی ذخیره نشده است!</small>
                        @endif

                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>

        let updateCategoryLevel = 0;

        $("#categoriesTable").on("click", '.delete-record', function () {
            const categoryId = $(this).data("id");

            swal({
                title: "آیا می خواهید بخش حذف شود؟",
                text: "اشخاص با این بخش، بخش پیش فرض را می گیرند.",
                icon: "warning",
                buttons: [
                    'خیر',
                    'بله'
                ],
                dangerMode: true,
            }).then(function (isConfirm) {
                if (isConfirm) {
                    $.ajax({
                        url: "{{ url("/") }}/parts/" + categoryId,
                        method: "delete",
                        data: {
                            'id': $(this).data("id"),
                            '_token': $('meta[name="csrf-token"]').attr('content'),
                            '_method': "delete"
                        },
                        success: function (response) {
                            const data = response;
                            $("#categoriesTable span[data-id=" + categoryId + "]").parent().parent().remove(),
                                swal("تبریک", "بخش حذف شد!", "success", {
                                    button: "باشه",
                                })
                        },
                        error: function (err) {
                            swal("متاسفیم", "بخش مورد نظر حذف نشد!", "warning", {
                                button: "باشه",
                            })
                        }
                    });
                }
            })

        });
        $('#add-category').on('click', function () {
            $.ajax({
                url: "{{ route('parts.store') }}",
                method: "post",
                data: {
                    'title': $('#category-title').val(),
                    'parent_id': $('#category-parent-id').val(),
                    '_token': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (response) {
                    const data = response;
                    if ($('#category-parent-id').val() == "") {
                        $("#categories-table-body").prepend("<tr id='cat" + data.id + "' class='head-row'><td class='text-right'>" + data.title + "</td><td><span class='table-link edit-record fas fa-edit' data-id='" + data.id + "' data-title='" + data.title + "' data-toggle='modal' data-target='#categoryUpdateModal'></span></td><td><span class='table-link delete-record fas fa-trash' data-id='" + data.id + "'></span></td></tr>")
                    } else {
                        const parentName = $("#categories-table-body #cat" + $('#category-parent-id').val() + "").text();
                        if (parentName.includes("--------")) {
                            $("#categories-table-body #cat" + $('#category-parent-id').val() + "").after("<tr id='cat" + data.id + "'><td class='text-right'>---------------- " + data.title + "</td><td><span class='table-link edit-record fas fa-edit' data-id='" + data.id + "' data-title='" + data.title + "' data-toggle='modal' data-target='#categoryUpdateModal'></span></td><td><span class='table-link delete-record fas fa-trash' data-id='" + data.id + "'></span></td></tr>")
                        } else if (parentName.includes("--------")) {
                            $("#categories-table-body #cat" + $('#category-parent-id').val() + "").after("<tr id='cat" + data.id + "'><td class='text-right'>-------- " + data.title + "</td><td><span class='table-link edit-record fas fa-edit' data-id='" + data.id + "' data-title='" + data.title + "' data-toggle='modal' data-target='#categoryUpdateModal'></span></td><td><span class='table-link delete-record fas fa-trash' data-id='" + data.id + "'></span></td></tr>")
                        } else {
                            $("#categories-table-body #cat" + $('#category-parent-id').val() + "").after("<tr id='cat" + data.id + "'><td class='text-right'>---- " + data.title + "</td><td><span class='table-link edit-record fas fa-edit' data-id='" + data.id + "' data-title='" + data.title + "' data-toggle='modal' data-target='#categoryUpdateModal'></span></td><td><span class='table-link delete-record fas fa-trash' data-id='" + data.id + "'></span></td></tr>")
                        }
                    }
                    // swal("تبریک", "بخش " + data.title + " ثبت شد!", "success", {
                    //     button: "باشه",
                    // }),
                    $('#category-parent-id').val("")
                },
                error: function (err) {
                    swal("متاسفیم", "بخش مورد نظر ثبت نشد!", "warning", {
                        button: "باشه",
                    })
                }
            });
            $('#exampleModal').modal('hide');
        });
        $('#show-add-modal').on('click', function () {
            $("#exampleModal #category-parent-id").val("");
            $("#exampleModal #category-title").val("");
            $('#exampleModal .modal-body .children-selector').remove();
            $('#exampleModal .modal-body .grandchild-selector').remove();
            $('#exampleModal .category-parent').empty().append('<option class="reset-parent-id" selected>بخش مورد نظر را انتخاب کنید</option>');
            $.ajax({
                url: "{{ route('parts.parents') }}",
                method: "get",
                success: function (response) {
                    const data = response;
                    data.forEach(element => $("#category-parent").append("<option class='parent-option' value='" + element.id + "'>" + element.title + "</option>"));
                },
            });
        });
        $("#exampleModal").on("change", '#category-parent', function () {
            const parentId = $('#exampleModal .category-parent option:selected').val();
            $('#exampleModal .modal-body .grandchild-selector').remove();
            $('#exampleModal .modal-body .children-selector').remove();
            $('#category-parent-id').val(parentId);
            if (parentId == 'بخش مورد نظر را انتخاب کنید'){
                $('#exampleModal .modal-body .grandchild-selector').remove();
                $('#exampleModal .modal-body .children-selector').remove();
                $("#exampleModal #category-parent-id").val("");
            } else {
                $.ajax({
                    url: "{{ url('/') }}/parts/children/" + parentId,
                    method: "get",
                    success: function (response) {
                        const data = response;
                        if (data.length > 0) {
                            $('#exampleModal .modal-body .children-selector').remove();
                            $('#exampleModal .modal-body .grandchild-selector').remove();
                            $('#exampleModal .modal-body').append('<div class="form-group children-selector"><label for="category-children">زیر بخش</label><select class="custom-select category-children" id="category-children"><option class="reset-child-id" selected>بخش مورد نظر را انتخاب کنید</option></select></div>');
                            data.forEach(element => $("#category-children").append("<option class='children-option' value='" + element.id + "'>" + element.title + "</option>"));
                        } else {
                            $('#exampleModal .modal-body .children-selector').remove();
                            $('#exampleModal .modal-body .grandchild-selector').remove();
                        }
                    },
                });
            }
        });
        $("#exampleModal").on("change", '#category-children', function () {
            const parentId = $('#exampleModal .category-children option:selected').val();
            $('#exampleModal .modal-body .grandchild-selector').remove();
            $('#category-parent-id').val(parentId);
            if (parentId == 'بخش مورد نظر را انتخاب کنید'){
                $('#exampleModal .modal-body .grandchild-selector').remove();
                const parentID = $("#exampleModal #category-parent").val();
                $("#exampleModal #category-parent-id").val(parentID);
            } else {
                $.ajax({
                    url: "{{ url('/') }}/parts/children/" + parentId,
                    method: "get",
                    success: function (response) {
                        const data = response;
                        if (data.length > 0) {
                            $('#exampleModal .modal-body .grandchild-selector').remove();
                            $('#exampleModal .modal-body').append('<div class="form-group grandchild-selector"><label for="category-grandchild">زیر بخش</label><select class="custom-select grandchild" id="category-grandchild"><option class="reset-grandchild-id" selected>بخش مورد نظر را انتخاب کنید</option></select></div>');
                            data.forEach(element => $("#category-grandchild").append("<option class='grandchild-option' value='" + element.id + "'>" + element.title + "</option>"));
                        } else {
                            $('#exampleModal .modal-body .grandchild-selector').remove();
                        }
                    },
                });
            }
        });
        $("#exampleModal").on("change", '#category-grandchild', function () {
            const parentId = $('#exampleModal #category-grandchild option:selected').val();
            if (parentId == 'بخش مورد نظر را انتخاب کنید'){
                const parentID = $("#exampleModal #category-children").val();
                $("#exampleModal #category-parent-id").val(parentID);
            } else {
                $('#exampleModal #category-parent-id').val(parentId);
            }
        });

        $('document').ready(function () {
            $("#exampleModal #category-parent-id").val("");
            $("#updateCategoryModal #category-parent-id").val("");
        });

        $("#categoriesTable").on("click", '.edit-record', function () {
            let categoryId = $(this).data('id');
            let parentId;
            let lastParentId;

            $("#updateCategoryModal #category-parent-id").val("");
            $('#updateCategoryModal .modal-body .children-selector').remove();
            $('#updateCategoryModal .modal-body .grandchild-selector').remove();
            $('#updateCategoryModal .category-parent').empty().append('<option class="reset-parent-id">بخش مورد نظر را انتخاب کنید</option>');

            $('#updateCategoryModal #category-id').val(categoryId);
            $('#updateCategoryModal #category-old-title').val($(this).parent().prev().text());
            $.ajax({
                url: "{{ url("/") }}/parts/" + categoryId,
                method: "get",
                success: function (response) {
                    $('#updateCategoryModal #category-new-title').val(response.title);
                    $('#updateCategoryModal #category-parent-id').val(response.parent_id);
                    if (response.parents.length == 0) {
                        parentId = "NONE";
                        $.ajax({
                            url: "{{ route('parts.parents') }}",
                            method: "get",
                            success: function (response) {
                                const data = response;
                                data.forEach(element => $("#updateCategoryModal #category-parent").append("<option class='parent-option' value='" + element.id + "'>" + element.title + "</option>"));
                                $('#updateCategoryModal option[value=' + categoryId + ']').css('display', 'none');
                            }
                        });
                    } else {

                        parentId = response.parent_id;

                        if (Object.keys(response.parents).length == 1) {
                            let level1 = response.parents.level1;
                            $.ajax({
                                url: "{{ route('parts.parents') }}",
                                method: "get",
                                success: function (response) {
                                    const data = response;
                                    data.forEach(element => $("#updateCategoryModal #category-parent").append("<option class='parent-option' value='" + element.id + "'>" + element.title + "</option>"));
                                    $('#updateCategoryModal option[value=' + categoryId + ']').css('display', 'none');
                                    $("#updateCategoryModal option[value=" + level1 + "]").attr('selected', 'selected');
                                }
                            });
                            $.ajax({
                                url: "{{ url('/') }}/parts/children/" + level1,
                                method: "get",
                                success: function (response) {
                                    const data = response;
                                    if (data.length > 0) {
                                        $('#updateCategoryModal .modal-body').append('<div class="form-group children-selector"><label for="category-children">زیر دسته</label><select class="custom-select category-children" id="category-children"><option class="reset-child-id" selected>دسته مورد نظر را انتخاب کنید</option></select></div>');
                                        data.forEach(element => $("#updateCategoryModal #category-children").append("<option class='children-option' value='" + element.id + "'>" + element.title + "</option>"));
                                        $('#updateCategoryModal option[value=' + categoryId + ']').css('display', 'none');
                                        if ($('#category-children option').length == 2 && $('#category-children option:last-child').val() == categoryId) {
                                            $('#updateCategoryModal .modal-body .children-selector').remove();
                                        }
                                    }
                                },
                            });

                        } else if (Object.keys(response.parents).length == 2) {
                            let level1 = response.parents.level1;
                            let level2 = response.parents.level2;
                            $.ajax({
                                url: "{{ route('parts.parents') }}",
                                method: "get",
                                success: function (response) {
                                    const data = response;
                                    data.forEach(element => $("#updateCategoryModal #category-parent").append("<option class='parent-option' value='" + element.id + "'>" + element.title + "</option>"));
                                    $("#updateCategoryModal #category-parent option[value=" + level2 + "]").attr('selected', 'selected');
                                    $.ajax({
                                        url: "{{ url('/') }}/parts/children/" + level2,
                                        method: "get",
                                        success: function (response) {
                                            const data = response;
                                            if (data.length > 0) {
                                                $('#updateCategoryModal .modal-body').append('<div class="form-group children-selector"><label for="category-children">زیر دسته</label><select class="custom-select category-children" id="category-children"><option class="reset-child-id" selected>دسته مورد نظر را انتخاب کنید</option></select></div>');
                                                data.forEach(element => $("#updateCategoryModal #category-children").append("<option class='children-option' value='" + element.id + "'>" + element.title + "</option>"));
                                                $("#updateCategoryModal #category-children option[value=" + level1 + "]").attr('selected', 'selected');
                                                $.ajax({
                                                    url: "{{ url('/') }}/parts/children/" + level1,
                                                    method: "get",
                                                    success: function (response) {
                                                        const data = response;
                                                        if (data.length > 0) {
                                                            $('#updateCategoryModal .modal-body').append('<div class="form-group grandchild-selector"><label for="category-grandchild">زیر دسته</label><select class="custom-select category-grandchild" id="category-grandchild"><option class="reset-grandchild-id" selected>دسته مورد نظر را انتخاب کنید</option></select></div>');
                                                            data.forEach(element => $("#updateCategoryModal #category-grandchild").append("<option class='grandchild-option' value='" + element.id + "'>" + element.title + "</option>"));
                                                            $('#updateCategoryModal #category-grandchild option[value=' + categoryId + ']').css('display', 'none');
                                                        }
                                                    },
                                                });
                                            }
                                        },
                                    });
                                }
                            });

                        } else if (Object.keys(response.parents).length == 3) {
                            let level1 = response.parents.level1;
                            let level2 = response.parents.level2;
                            let level3 = response.parents.level3;
                            $.ajax({
                                url: "{{ route('parts.parents') }}",
                                method: "get",
                                success: function (response) {
                                    const data = response;
                                    data.forEach(element => $("#updateCategoryModal #category-parent").append("<option class='parent-option' value='" + element.id + "'>" + element.title + "</option>"));
                                    $("#updateCategoryModal #category-parent option[value=" + level3 + "]").attr('selected', 'selected');
                                    $.ajax({
                                        url: "{{ url('/') }}/parts/children/" + level3,
                                        method: "get",
                                        success: function (response) {
                                            const data = response;
                                            if (data.length > 0) {
                                                $('#updateCategoryModal .modal-body').append('<div class="form-group children-selector"><label for="category-children">زیر دسته</label><select class="custom-select category-children" id="category-children"><option class="reset-child-id" selected>دسته مورد نظر را انتخاب کنید</option></select></div>');
                                                data.forEach(element => $("#updateCategoryModal #category-children").append("<option class='children-option' value='" + element.id + "'>" + element.title + "</option>"));
                                                $("#updateCategoryModal #category-children option[value=" + level2 + "]").attr('selected', 'selected');
                                                $.ajax({
                                                    url: "{{ url('/') }}/parts/children/" + level2,
                                                    method: "get",
                                                    success: function (response) {
                                                        const data = response;
                                                        if (data.length > 0) {
                                                            $('#updateCategoryModal .modal-body').append('<div class="form-group grandchild-selector"><label for="category-grandchild">زیر دسته</label><select class="custom-select category-grandchild" id="category-grandchild"><option class="reset-grandchild-id" selected>دسته مورد نظر را انتخاب کنید</option></select></div>');
                                                            data.forEach(element => $("#updateCategoryModal #category-grandchild").append("<option class='grandchild-option' value='" + element.id + "'>" + element.title + "</option>"));
                                                            $('#updateCategoryModal #category-grandchild option[value=' + categoryId + ']').css('display', 'none');
                                                            $("#updateCategoryModal #category-grandchild option[value=" + level1 + "]").attr('selected', 'selected');
                                                        }
                                                    },
                                                });
                                            }
                                        },
                                    });
                                }
                            });

                        } else {
                            lastParentId = "DontHave"
                        }
                    }
                }
            });

            $('#updateCategoryModal').modal('show');
        });

        $("#updateCategoryModal").on("change", '#category-parent', function () {
            const parentId = $('#updateCategoryModal .category-parent option:selected').val();
            $('#updateCategoryModal .modal-body .grandchild-selector').remove();
            $('#updateCategoryModal .modal-body .children-selector').remove();
            $('#updateCategoryModal #category-parent-id').val(parentId);
            if (parentId == 'بخش مورد نظر را انتخاب کنید'){
                $('#updateCategoryModal .modal-body .grandchild-selector').remove();
                $('#updateCategoryModal .modal-body .children-selector').remove();
                $("#updateCategoryModal #category-parent-id").val("");
            } else {
                $.ajax({
                    url: "{{ url('/') }}/parts/children/" + parentId,
                    method: "get",
                    success: function (response) {
                        const data = response;
                        if (data.length > 0) {
                            $('#updateCategoryModal .modal-body .children-selector').remove();
                            $('#updateCategoryModal .modal-body').append('<div class="form-group children-selector"><label for="category-children">زیر بخش</label><select class="custom-select category-children" id="category-children"><option class="reset-child-id" selected>بخش مورد نظر را انتخاب کنید</option></select></div>');
                            data.forEach(element => $("#updateCategoryModal #category-children").append("<option class='children-option' value='" + element.id + "'>" + element.title + "</option>"));
                        } else {
                            $('#updateCategoryModal .modal-body .children-selector').remove();
                        }
                    },
                });
            }
        });

        $("#updateCategoryModal").on("change", '#category-children', function () {
            const parentId = $('#updateCategoryModal #category-children option:selected').val();
            $('#updateCategoryModal .modal-body .grandchild-selector').remove();
            $('#updateCategoryModal #category-parent-id').val(parentId);
            if (parentId == 'دسته مورد نظر را انتخاب کنید'){
                $('#updateCategoryModal .modal-body .grandchild-selector').remove();
                const parentID = $("#updateCategoryModal #category-parent").val();
                $("#updateCategoryModal #category-parent-id").val(parentID);
            } else {
                $.ajax({
                    url: "{{ url('/') }}/parts/children/" + parentId,
                    method: "get",
                    success: function (response) {
                        const data = response;
                        if (data.length > 0) {
                            $('#updateCategoryModal .modal-body .grandchild-selector').remove();
                            $('#updateCategoryModal .modal-body').append('<div class="form-group grandchild-selector"><label for="category-grandchild">زیر بخش</label><select class="custom-select grandchild" id="category-grandchild"><option class="reset-grandchild-id" selected>بخش مورد نظر را انتخاب کنید</option></select></div>');
                            data.forEach(element => $("#category-grandchild").append("<option class='grandchild-option' value='" + element.id + "'>" + element.title + "</option>"));
                        } else {
                            $('#updateCategoryModal .modal-body .grandchild-selector').remove();
                        }
                    },
                });
            }
        });

        $("#updateCategoryModal").on("change", '#category-grandchild', function () {
            const parentId = $('#updateCategoryModal #category-grandchild option:selected').val();
            if (parentId == 'دسته مورد نظر را انتخاب کنید'){
                const parentID = $("#updateCategoryModal #category-children").val();
                $("#updateCategoryModal #category-parent-id").val(parentID);
            } else {
                $('#updateCategoryModal #category-parent-id').val(parentId);
            }
        });

        $('#update-category').on('click', function () {
            let id = $("#updateCategoryModal #category-id").val();
            const categoryOldTitle = $('#updateCategoryModal #category-old-title').val();
            $.ajax({
                url: "{{ url("/") }}/parts/" + $("#updateCategoryModal #category-id").val(),
                method: "post",
                data: {
                    'title': $('#updateCategoryModal #category-new-title').val(),
                    'parent_id': $('#updateCategoryModal #category-parent-id').val(),
                    '_token': $('meta[name="csrf-token"]').attr('content'),
                    '_method': 'PUT'
                },
                success: function (updatedCategory) {
                    const data = updatedCategory;

                    if (data == false) {
                        swal("متاسفیم", "این بخش نمی تواند زیر مجموعه بخش انتخابی باشد.", "warning", {
                            button: "باشه",
                        });
                        return false;
                    }

                    let subCats = [];
                    let subCategories = updatedCategory.sub_categories;

                    subCategories.forEach(element => subCats.push($("#categories-table-body #cat" + element)))
                    subCategories.forEach(element => $("#categories-table-body #cat" + element).remove())

                    $("#categories-table-body #cat" + id).remove();

                    if ($('#updateCategoryModal #category-parent-id').val() == "") {
                        $("#categories-table-body").prepend("<tr id='cat" + data.id + "' class='head-row'><td class='text-right'>" + data.title + "</td><td><span class='table-link edit-record fas fa-edit' data-id='" + data.id + "' data-title='" + data.title + "' data-toggle='modal' data-target='#categoryUpdateModal'></span></td><td><span class='table-link delete-record fas fa-trash' data-id='" + data.id + "'></span></td></tr>")
                    } else {
                        const parentName = $("#categories-table-body #cat" + $('#updateCategoryModal #category-parent-id').val() + "").text();
                        if (parentName.includes("--------")){
                            $("#categories-table-body #cat" + $('#updateCategoryModal #category-parent-id').val() + "").after("<tr id='cat" + data.id + "'><td class='text-right'>---------------- " + data.title + "</td><td><span class='table-link edit-record fas fa-edit' data-id='" + data.id + "' data-title='" + data.title + "' data-toggle='modal' data-target='#categoryUpdateModal'></span></td><td><span class='table-link delete-record fas fa-trash' data-id='" + data.id + "'></span></td></tr>")
                        } else if (parentName.includes("----")) {
                            $("#categories-table-body #cat" + $('#updateCategoryModal #category-parent-id').val() + "").after("<tr id='cat" + data.id + "'><td class='text-right'>-------- " + data.title + "</td><td><span class='table-link edit-record fas fa-edit' data-id='" + data.id + "' data-title='" + data.title + "' data-toggle='modal' data-target='#categoryUpdateModal'></span></td><td><span class='table-link delete-record fas fa-trash' data-id='" + data.id + "'></span></td></tr>")
                        } else {
                            $("#categories-table-body #cat" + $('#updateCategoryModal #category-parent-id').val() + "").after("<tr id='cat" + data.id + "'><td class='text-right'>---- " + data.title + "</td><td><span class='table-link edit-record fas fa-edit' data-id='" + data.id + "' data-title='" + data.title + "' data-toggle='modal' data-target='#categoryUpdateModal'></span></td><td><span class='table-link delete-record fas fa-trash' data-id='" + data.id + "'></span></td></tr>")
                        }
                    }

                    subCats.slice().reverse().forEach(element => $("#categories-table-body #cat" + data.id + "").after(element));
                    let categoryNewTitle = data.title;

                    if (!categoryOldTitle.includes("----")) {
                        if ($("#categories-table-body #cat" + data.id + " td.text-right").text().includes("----")) {
                            subCategories.forEach(element => $("#categories-table-body #cat" + element + " td.text-right").text('----' + $("#categories-table-body #cat" + element + " td.text-right").text()))
                        }
                    }

                    if (categoryOldTitle.includes("----")) {
                        if (!$("#categories-table-body #cat" + data.id + " td.text-right").text().includes("----")) {
                            subCategories.forEach(element => $("#categories-table-body #cat" + element + " td.text-right").text($("#categories-table-body #cat" + element + " td.text-right").text().substring(4)))
                        }
                    }

                    swal("تبریک", "تغییرات بخش " + data.title + " اعمال گردید!", "success", {
                        button: "باشه",
                    }),
                        $('#updateCategoryModal #category-parent-id').val("")
                },
                error: function (err) {
                    swal("متاسفیم", "تغییرات برای بخش مورد نظر ثبت نشد!", "warning", {
                        button: "باشه",
                    })
                }
            });
            $('#updateCategoryModal').modal('hide');
        });

    </script>
@endsection
