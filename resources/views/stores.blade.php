@extends('layouts.app')

@section('content')
    <div class="container">

        <!-- Insert Store Modal -->
        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
             aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">افزودن انبار</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form method="post" id="insertForm">
                            <div class="form-group">
                                <label for="store-title">عنوان انبار</label>
                                <input type="text" class="form-control" id="store-title"
                                       placeholder="به عنوان مثال: انبار مرکزی، سوله شرقی و ...">
                            </div>
                            <div class="form-group">
                                <label for="product-name">انباردارها</label>
                                <div id="product-categories-wrapper" style="height: 80px;" class="form-control">

                                </div>
                            </div>
                            <label for="product-category"> انباردار:</label>
                            <select class="custom-select" style="width: 250px" id="product-category">
                                <option id="initial" selected>انباردار را انتخاب کنید</option>
                                @foreach($operators as $operator)
                                    <option value="{{ $operator->id }}">{{ $operator->name }}</option>
                                @endforeach
                            </select>
                            <button id="add-category" class="btn custom-plus-btn"><span
                                    class="fa fa-plus custom-plus"></span></button>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">بستن</button>
                        <button id="add-store" type="button" class="btn btn-primary">افزودن</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Update Modal -->
        <div class="modal fade" id="storeUpdateModal" tabindex="-1" role="dialog"
             aria-labelledby="storeUpdateModalLabel"
             aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="storeUpdateLabel">تغییر عنوان انبار</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="store-title-old">عنوان فعلی</label>
                            <input type="text" class="form-control" id="store-title-old"
                                   value="" disabled>
                        </div>
                        <div class="form-group">
                            <label for="store-title-new">عنوان جدید</label>
                            <input type="text" class="form-control" id="store-title-new"
                                   placeholder="به عنوان مثال: انبار مرکزی، سوله جنوبی و ...">
                        </div>
                        <div class="form-group">
                            <label for="product-name">انباردارها</label>
                            <div id="product-categories-wrapper" style="height: 80px;" class="form-control">

                            </div>
                        </div>
                        <label for="product-category"> انباردار:</label>
                        <select class="custom-select" style="width: 250px" id="product-category">
                            <option id="initial" selected>انباردار را انتخاب کنید</option>
                            @foreach($operators as $operator)
                                <option value="{{ $operator->id }}">{{ $operator->name }}</option>
                            @endforeach
                        </select>
                        <button id="add-category" class="btn custom-plus-btn"><span
                                class="fa fa-plus custom-plus"></span></button>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">بستن</button>
                        <button id="update-store" type="button" class="btn btn-primary">اعمال</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header bg-light">{{ __('مدیریت انبارها') }}
                    <!-- Button trigger modal -->
                        @if(Auth::user()->role != 'reporter')
                        <button type="button" class="btn btn-outline-dark btn-sm float-left" data-toggle="modal"
                                data-target="#exampleModal">
                            افزودن انبار جدید
                        </button>
                            @endif
                    </div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        @if(count($stores) != 0)
                            <table class="table text-center" id="storesTable">
                                <thead>
                                <tr>
                                    <th scope="col">نام انبار</th>
                                    <th scope="col">انباردارها</th>
                                    @if(Auth::user()->role != 'reporter')
                                    <th scope="col">ویرایش</th>
                                    <th scope="col">حذف</th>
                                        @endif
                                </tr>
                                </thead>
                                <tbody id="stores-table-body">
                                @foreach($stores as $store)
                                    <tr>
                                        <td>{{ $store->title }}</td>
                                        <td>
                                            @foreach($store->users as $single_user)
                                                <span class="product-category-tiny">{{ $single_user->name }}</span>
                                            @endforeach
                                        </td>
                                        @if(Auth::user()->role != 'reporter')
                                        <td>
                                            <span class="table-link edit-record fas fa-edit" data-id="{{ $store->id }}"
                                                  data-title="{{ $store->title }}" data-toggle="modal"
                                                  data-target="#storeUpdateModal"></span>
                                        </td>
                                        <td>
                                            <span class="table-link delete-record fas fa-trash"
                                                  data-id="{{ $store->id }}"></span>
                                        </td>
                                            @endif
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        @else
                            <small>تا به حال هیچ انباری ذخیره نشده است!</small>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>

        $('#add-store').on('click', function () {
            $.ajax({
                url: "{{ route('stores.store') }}",
                method: "post",
                data: {
                    'title': $('#store-title').val(),
                    'operators': newProductCategories,
                    '_token': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (response) {
                    const data = response;
                    console.log(data)
                    $("#stores-table-body").prepend("<tr><td>" + data.title + "</td><td id='tempID'></td><td><span class='table-link edit-record fas fa-edit' data-id='" + data.id + "' data-title='" + data.title + "' data-toggle='modal' data-target='#storeUpdateModal'></span></td><td><span class='table-link delete-record fas fa-trash' data-id='" + data.id + "'></span></td></tr>"),
                        data.userNames.forEach(element => $('#tempID').append('<span  class="product-category-tiny ml-1">' + element + '<span>'));
                    $('#tempID').removeAttr('id');

                },
                error: function (err) {
                    swal("متاسفیم", "انبار مورد نظر ثبت نشد!", "warning", {
                        button: "باشه",
                    })
                }
            });
            $('#exampleModal').modal('hide');
        });

        let toUpdate;
        $("#storesTable").on("click", '.edit-record', function () {
            let id = $(this).data('id');
            toUpdate = id;
            $("#storeUpdateModal #store-title-old").val($(this).data('title'));
            $("#storeUpdateModal #store-title-new").val("");
            $("#storeUpdateModal #store-id").val($(this).data('id'));

            $.ajax({
                url: "{{ url('/') }}/stores/users/" + id,
                method: "get",
                success: function (response) {
                    const categories = response;
                    $('#storeUpdateModal #product-category #initial').attr('selected', 'selected');
                    $('#storeUpdateModal #product-categories-wrapper').children().remove();
                    $('#storeUpdateModal input[type=hidden]').remove();
                    newProductCategories = [];
                    categories.forEach(category => newProductCategories.push(String(category.id)));
                    categories.forEach(category => $('#storeUpdateModal #product-categories-wrapper').append('<span class="category-single ml-2 mb-2 d-inline-block" data-id="' + category.id + '">' + category.name + ' <span data-id="' + category.id + '" class="fa fa-remove mr-3 cat-del"></span> </span>'));
                    categories.forEach(category => $('#storeUpdateModal').append('<input type="hidden" id="cat' + category.id + '" name="categories[]" value="' + category.id + '">'));
                }
            });

        });

        $('#update-store').on('click', function () {
            const storeOldTitle = $('#storeUpdateModal #store-title-old').val();
            $.ajax({
                url: "{{ url('/') }}/stores/" + toUpdate,
                method: "put",
                data: {
                    'title': $('#store-title-new').val(),
                    'operators': newProductCategories,
                    '_token': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (response) {
                    const data = response;
                    jQuery("#storesTable td").filter(function() {
                        return $(this).text() === storeOldTitle;
                    }).parent().attr('id', 'toDelete');
                    jQuery("#storesTable #toDelete").after("<tr><td>" + data.title + "</td><td id='tempID'></td><td><span class='table-link edit-record fas fa-edit' data-id='" + data.id + "' data-title='" + data.title + "' data-toggle='modal' data-target='#storeUpdateModal'></span></td><td><span class='table-link delete-record fas fa-trash' data-id='" + data.id + "'></span></td></tr>"),
                        data.userNames.forEach(element => $('#tempID').append('<span  class="product-category-tiny ml-1">' + element + '<span>'));
                    $('#tempID').removeAttr('id');
                        jQuery("#storesTable #toDelete").remove(),
                        swal("تبریک", "نام انبار " + storeOldTitle + " به  " + data.title + " تغییر یافت!", "success", {
                            button: "باشه",
                        })
                    toUpdate = null;
                },
                error: function (err) {
                    swal("متاسفیم", "نام جدید برای انبار مورد نظر ثبت نشد!", "warning", {
                        button: "باشه",
                    })
                }
            });
            $('#storeUpdateModal').modal('hide');
        });

        $("#storesTable").on("click", '.delete-record', function () {
            const storeId = $(this).data("id");

            swal({
                title: "آیا می خواهید انبار حذف شود؟",
                icon: "warning",
                buttons: [
                    'خیر',
                    'بله'
                ],
                dangerMode: true,
            }).then(function (isConfirm) {
                if (isConfirm) {
                    $.ajax({
                        url: "{{ url("/") }}/stores/" + storeId,
                        method: "delete",
                        data: {
                            'id': $(this).data("id"),
                            '_token': $('meta[name="csrf-token"]').attr('content'),
                            '_method': "delete"
                        },
                        success: function (response) {
                            const data = response;
                            $("#storesTable span[data-id=" + storeId + "]").parent().parent().remove(),
                                swal("تبریک", "انبار حذف شد!", "success", {
                                    button: "باشه",
                                })
                        },
                        error: function (err) {
                            swal("متاسفیم", "انبار مورد نظر حذف نشد!", "warning", {
                                button: "باشه",
                            })
                        }
                    });
                }
            })

        });

        let newProductCategories =[];
        $('#insertForm #add-category').click(function (e) {
            e.preventDefault();
            let id = $('#insertForm #product-category').val();

            if (newProductCategories.includes(id)) {
                return false;
            }

            let title = $('#insertForm #product-category option[value=' + id + ']').text();
            $('#insertForm #product-category #initial').attr('selected', 'selected');
            $('#insertForm #product-categories-wrapper').append('<span class="category-single ml-2 mb-3" data-id="' + id + '">' + title + ' <span data-id="' + id + '" class="fa fa-remove mr-3 cat-del"></span> </span>');
            $('#insertForm').append('<input type="hidden" id="cat' + id + '" name="operators[]" value="' + id + '">');
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

        $('#storeUpdateModal #add-category').click(function (e) {
            e.preventDefault();
            let id = $('#storeUpdateModal #product-category').val();

            if (newProductCategories.includes(id)) {
                return false;
            }

            let title = $('#storeUpdateModal #product-category option[value=' + id + ']').text();
            $('#storeUpdateModal #product-category #initial').attr('selected', 'selected');
            $('#storeUpdateModal #product-categories-wrapper').append('<span class="category-single ml-2 mb-3" data-id="' + id + '">' + title + ' <span data-id="' + id + '" class="fa fa-remove mr-3 cat-del"></span> </span>');
            $('#storeUpdateModal').append('<input type="hidden" id="cat' + id + '" name="operators[]" value="' + id + '">');
            newProductCategories.push(id);
        });
        $("#storeUpdateModal").on("click", '.cat-del', function () {
            let id = $(this).data('id');
            $('#storeUpdateModal #product-categories-wrapper span[data-id=' + id + ']').remove();
            $('#storeUpdateModal input[id=cat' + id + ']').remove();

            newProductCategories = jQuery.grep(newProductCategories, function (value) {
                return value != id;
            });
        });

    </script>
@endsection
