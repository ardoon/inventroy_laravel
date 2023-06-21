@extends('layouts.app')

@section('content')
    <div class="container">

        <!-- Insert Category Modal -->
        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
             aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">افزودن دسته بندی</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="category-title">نام دسته جدید</label>
                            <input type="text" class="form-control" id="category-title"
                                   placeholder="به عنوان مثال: آهن آلات، تاسیسات و ...">
                        </div>
                        <input type="hidden" id="category-parent-id" value="">
                        <div class="form-group">
                            <label for="category-parent">دسته اصلی</label>
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
                        <h5 class="modal-title" id="updateCategoryLabel">ویرایش دسته بندی</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="category-new-title">نام دسته</label>
                            <input type="text" class="form-control" id="category-new-title"
                                   placeholder="به عنوان مثال: آهن آلات، تاسیسات و ...">
                        </div>
                        <input type="hidden" id="category-id" value="">
                        <input type="hidden" id="category-parent-id" value="">
                        <input type="hidden" id="category-old-title" value="">
                        <div class="form-group">
                            <label for="category-parent">دسته اصلی</label>
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
                    <div class="card-header bg-light">{{ __('مدیریت دسته بندی ها') }}
                    <!-- Button trigger modal -->
                        @if(Auth::user()->role != 'reporter')
                        <button id="show-add-modal" type="button" class="btn btn-outline-dark btn-sm float-left"
                                data-toggle="modal"
                                data-target="#exampleModal">
                            افزودن دسته جدید
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
                                    <th scope="col" class="text-right">نام دسته</th>
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

                                                @endif

                                            @endforeach

                                        @endif

                                    @endforeach

                                @endforeach
                                </tbody>
                            </table>
                        @else
                            <small>تا به حال هیچ دسته ایی ذخیره نشده است!</small>
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
                title: "آیا می خواهید دسته بندی حذف شود؟",
                text: "کالاهای با این دسته بندی، دسته بندی پیش فرض را می گیرند.",
                icon: "warning",
                buttons: [
                    'خیر',
                    'بله'
                ],
                dangerMode: true,
            }).then(function (isConfirm) {
                if (isConfirm) {
                    $.ajax({
                        url: "{{ url("/") }}/categories/" + categoryId,
                        method: "delete",
                        data: {
                            'id': $(this).data("id"),
                            '_token': $('meta[name="csrf-token"]').attr('content'),
                            '_method': "delete"
                        },
                        success: function (response) {
                            const data = response;
                            $("#categoriesTable span[data-id=" + categoryId + "]").parent().parent().remove(),
                                swal("تبریک", "دسته بندی حذف شد!", "success", {
                                    button: "باشه",
                                })
                        },
                        error: function (err) {
                            swal("متاسفیم", "دسته بندی مورد نظر حذف نشد!", "warning", {
                                button: "باشه",
                            })
                        }
                    });
                }
            })

        });

        $('#add-category').on('click', function () {
            $.ajax({
                url: "{{ route('categories.store') }}",
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
                        if (parentName.includes("----")) {
                            $("#categories-table-body #cat" + $('#category-parent-id').val() + "").after("<tr id='cat" + data.id + "'><td class='text-right'>-------- " + data.title + "</td><td><span class='table-link edit-record fas fa-edit' data-id='" + data.id + "' data-title='" + data.title + "' data-toggle='modal' data-target='#categoryUpdateModal'></span></td><td><span class='table-link delete-record fas fa-trash' data-id='" + data.id + "'></span></td></tr>")
                        } else {
                            $("#categories-table-body #cat" + $('#category-parent-id').val() + "").after("<tr id='cat" + data.id + "'><td class='text-right'>---- " + data.title + "</td><td><span class='table-link edit-record fas fa-edit' data-id='" + data.id + "' data-title='" + data.title + "' data-toggle='modal' data-target='#categoryUpdateModal'></span></td><td><span class='table-link delete-record fas fa-trash' data-id='" + data.id + "'></span></td></tr>")
                        }
                    }
                    // swal("تبریک", "دسته بندی " + data.title + " ثبت شد!", "success", {
                    //     button: "باشه",
                    // }),
                        $('#category-parent-id').val("")
                },
                error: function (err) {
                    swal("متاسفیم", "دسته بندی مورد نظر ثبت نشد!", "warning", {
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
            $('#exampleModal .category-parent').empty().append('<option class="reset-parent-id" selected>دسته مورد نظر را انتخاب کنید</option>');
            $.ajax({
                url: "{{ route('categories.parents') }}",
                method: "get",
                success: function (response) {
                    const data = response;
                    data.forEach(element => $("#category-parent").append("<option class='parent-option' value='" + element.id + "'>" + element.title + "</option>"));
                },
            });
        });

        $("#exampleModal").on("change", '.category-parent', function () {
            const parentId = $('#exampleModal .category-parent option:selected').val();
            $('#category-parent-id').val(parentId);
            if (parentId == 'دسته مورد نظر را انتخاب کنید'){
                $('#exampleModal .modal-body .children-selector').remove();
                $("#exampleModal #category-parent-id").val("");
            } else {
                $.ajax({
                    url: "{{ url('/') }}/categories/children/" + parentId,
                    method: "get",
                    success: function (response) {
                        const data = response;
                        if (data.length > 0) {
                            $('#exampleModal .modal-body .children-selector').remove();
                            $('#exampleModal .modal-body').append('<div class="form-group children-selector"><label for="category-children">زیر دسته</label><select class="custom-select category-children" id="category-children"><option class="reset-child-id" selected>دسته مورد نظر را انتخاب کنید</option></select></div>');
                            data.forEach(element => $("#category-children").append("<option class='children-option' value='" + element.id + "'>" + element.title + "</option>"));
                        } else {
                            $('#exampleModal .modal-body .children-selector').remove();
                        }
                    },
                });
            }

        });

        $("#exampleModal").on("change", '.category-children', function () {
            const parentId = $('#exampleModal .category-children  option:selected').val();
            if (parentId == 'دسته مورد نظر را انتخاب کنید'){
                const parentID = $("#exampleModal #category-parent").val();
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
            let lastParentId;
            let parentId;

            $("#updateCategoryModal #category-parent-id").val("");
            $('#updateCategoryModal .modal-body .children-selector').remove();
            $('#updateCategoryModal .category-parent').empty().append('<option class="reset-parent-id">دسته مورد نظر را انتخاب کنید</option>');

            $('#updateCategoryModal #category-id').val(categoryId);
            $('#updateCategoryModal #category-old-title').val($(this).parent().prev().text());
            $.ajax({
                url: "{{ url("/") }}/categories/" + categoryId,
                method: "get",
                success: function (response) {
                    $('#updateCategoryModal #category-new-title').val(response.title);
                    if (response.parent != null) {
                        lastParentId = response.parent.id;
                        $("#updateCategoryModal #category-parent-id").val(lastParentId);
                        parentId = response.parent.id;
                        $.ajax({
                            url: "{{ url('/') }}/categories/children/" + parentId,
                            method: "get",
                            success: function (response) {
                                const data = response;
                                if (data.length > 0) {
                                    $('#updateCategoryModal .modal-body .children-selector').remove();
                                    $('#updateCategoryModal .modal-body').append('<div class="form-group children-selector"><label for="category-children">زیر دسته</label><select class="custom-select category-children" id="category-children"><option class="reset-child-id" selected>دسته مورد نظر را انتخاب کنید</option></select></div>');
                                    data.forEach(element => $("#category-children").append("<option class='children-option' value='" + element.id + "'>" + element.title + "</option>"));
                                    $("#updateCategoryModal option[value=" + lastParentId + "]").attr('selected', 'selected');
                                    $('#updateCategoryModal option[value=' + $("#updateCategoryModal #category-id").val() + ']').css('display', 'none');
                                } else {
                                    $('#updateCategoryModal .modal-body .children-selector').remove();
                                }
                            },
                        });
                        if (response.parent.parent_id != null) {
                            parentId = response.parent.parent_id;
                        }
                    } else {
                        parentId = "NONE";
                    }
                    $.ajax({
                        url: "{{ route('categories.parents') }}",
                        method: "get",
                        success: function (response) {
                            const data = response;
                            data.forEach(element => $("#updateCategoryModal #category-parent").append("<option class='parent-option' value='" + element.id + "'>" + element.title + "</option>"));
                            $("#updateCategoryModal option[value=" + parentId + "]").attr('selected', 'selected');
                            $('#updateCategoryModal option[value=' + categoryId + ']').css('display', 'none');

                            if (parentId != lastParentId) {
                                $.ajax({
                                    url: "{{ url('/') }}/categories/children/" + parentId,
                                    method: "get",
                                    success: function (response) {
                                        const data = response;
                                        if (data.length > 0) {
                                            $('#updateCategoryModal .modal-body .children-selector').remove();
                                            $('#updateCategoryModal .modal-body').append('<div class="form-group children-selector"><label for="category-children">زیر دسته</label><select class="custom-select category-children" id="category-children"><option class="reset-child-id" selected>دسته مورد نظر را انتخاب کنید</option></select></div>');
                                            data.forEach(element => $("#category-children").append("<option class='children-option' value='" + element.id + "'>" + element.title + "</option>"));
                                            $("#updateCategoryModal option[value=" + lastParentId + "]").attr('selected', 'selected');
                                            $('#updateCategoryModal option[value=' + categoryId + ']').css('display', 'none');
                                            $('#updateCategoryModal option[value=' + $("#updateCategoryModal #category-id").val() + ']').css('display', 'none');
                                        } else {
                                            $('#updateCategoryModal .modal-body .children-selector').remove();
                                        }
                                    },
                                });
                            }
                        },
                    });
                }
            });
            $('#updateCategoryModal').modal('show');
        });

        $("#updateCategoryModal").on("change", '.category-parent', function () {
            const parentId = $('#updateCategoryModal .category-parent option:selected').val();
            $('#updateCategoryModal #category-parent-id').val(parentId);
            if (parentId == 'دسته مورد نظر را انتخاب کنید'){
                $('#updateCategoryModal .modal-body .children-selector').remove();
                $("#updateCategoryModal #category-parent-id").val("");
            } else {
                $.ajax({
                    url: "{{ url('/') }}/categories/children/" + parentId,
                    method: "get",
                    success: function (response) {
                        const data = response;
                        if (data.length > 0) {
                            $('#updateCategoryModal .modal-body .children-selector').remove();
                            $('#updateCategoryModal .modal-body').append('<div class="form-group children-selector"><label for="category-children">زیر دسته</label><select class="custom-select category-children" id="category-children"><option class="reset-child-id" selected>دسته مورد نظر را انتخاب کنید</option></select></div>');
                            data.forEach(element => $("#updateCategoryModal #category-children").append("<option class='children-option' value='" + element.id + "'>" + element.title + "</option>"));
                        } else {
                            $('#updateCategoryModal .modal-body .children-selector').remove();
                        }
                    },
                });
            }
        });

        $("#updateCategoryModal").on("change", '.category-children', function () {
            const parentId = $('#updateCategoryModal .category-children  option:selected').val();
            if (parentId == 'دسته مورد نظر را انتخاب کنید'){
                const parentID = $("#updateCategoryModal #category-parent").val();
                $("#updateCategoryModal #category-parent-id").val(parentID);
            } else {
                $('#updateCategoryModal #category-parent-id').val(parentId);
            }
        });

        $('#update-category').on('click', function () {
            let id = $("#updateCategoryModal #category-id").val();
            const categoryOldTitle = $('#updateCategoryModal #category-old-title').val();
            $.ajax({
                url: "{{ url("/") }}/categories/" + $("#updateCategoryModal #category-id").val(),
                method: "post",
                data: {
                    'title': $('#updateCategoryModal #category-new-title').val(),
                    'parent_id': $('#updateCategoryModal #category-parent-id').val(),
                    '_token': $('meta[name="csrf-token"]').attr('content'),
                    '_method': 'PUT'
                },
                success: function (updatedCategory) {
                    const data = updatedCategory;

                    if (data == false){
                        swal("متاسفیم", "این دسته بندی نمی تواند زیر مجموعه دسته انتخابی باشد.", "warning", {
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
                        if (parentName.includes("----")) {
                            $("#categories-table-body #cat" + $('#updateCategoryModal #category-parent-id').val() + "").after("<tr id='cat" + data.id + "'><td class='text-right'>-------- " + data.title + "</td><td><span class='table-link edit-record fas fa-edit' data-id='" + data.id + "' data-title='" + data.title + "' data-toggle='modal' data-target='#categoryUpdateModal'></span></td><td><span class='table-link delete-record fas fa-trash' data-id='" + data.id + "'></span></td></tr>")
                        } else {
                            $("#categories-table-body #cat" + $('#updateCategoryModal #category-parent-id').val() + "").after("<tr id='cat" + data.id + "'><td class='text-right'>---- " + data.title + "</td><td><span class='table-link edit-record fas fa-edit' data-id='" + data.id + "' data-title='" + data.title + "' data-toggle='modal' data-target='#categoryUpdateModal'></span></td><td><span class='table-link delete-record fas fa-trash' data-id='" + data.id + "'></span></td></tr>")
                        }
                    }

                    subCats.slice().reverse().forEach(element => $("#categories-table-body #cat" + data.id + "").after(element));
                    let categoryNewTitle = data.title;

                    if (!categoryOldTitle.includes("----")){
                        if ($("#categories-table-body #cat" + data.id + " td.text-right").text().includes("----")){
                            subCategories.forEach(element => $("#categories-table-body #cat" + element + " td.text-right").text('----' + $("#categories-table-body #cat" + element + " td.text-right").text()))
                        }
                    }

                    if (categoryOldTitle.includes("----")){
                        if (!$("#categories-table-body #cat" + data.id + " td.text-right").text().includes("----")){
                            subCategories.forEach(element => $("#categories-table-body #cat" + element + " td.text-right").text($("#categories-table-body #cat" + element + " td.text-right").text().substring(4)))
                        }
                    }

                    swal("تبریک", "تغییرات دسته بندی " + data.title + " اعمال گردید!", "success", {
                        button: "باشه",
                    }),
                        $('#updateCategoryModal #category-parent-id').val("")
                },
                error: function (err) {
                    swal("متاسفیم", "تغییرات برای دسته بندی مورد نظر ثبت نشد!", "warning", {
                        button: "باشه",
                    })
                }
            });
            $('#updateCategoryModal').modal('hide');
        });

    </script>
@endsection
