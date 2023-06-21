@extends('layouts.app')

@section('content')
    <div class="container">

        <!-- Insert Unit Modal -->
        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
             aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">افزودن شخص</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="title">نام</label>
                            <input type="text" class="form-control" id="title"
                                   placeholder="نام را وارد کنید..">
                        </div>
                        <div class="form-group">
                            <label for="code">کدملی</label>
                            <input type="text" class="form-control" id="code"
                                   placeholder="شماره ملی را بدون خط تیره وارد کنید..">
                        </div>
                        <input type="hidden" id="part-id">
                        <input type="hidden" id="role-id">

                        <div class="form-group">
                            <label for="code">نقش</label>
                            <div class="clearfix"></div>
                            <input disabled type="text" class="form-control text-dark col-10 float-right" id="role"
                                   placeholder="یک نقش انتخاب کنید">
                            <a data-toggle="modal" data-target="#role-pick-modal" id="pick-role"
                               class="btn btn-secondary pl-3"
                               style="border-top-right-radius: 0; border-bottom-right-radius: 0;">انتخاب</a>
                        </div>

                        <div id="role-pick-modal" class="modal" tabindex="-1" role="dialog">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header justify-content-center">
                                        <h5 class="modal-title">انتخاب نقش برای شخص</h5>
                                    </div>
                                    <div class="modal-body" style="height: 284px;">
                                        <div class="form-group">
                                            <label for="select-role">نقش</label>
                                            <select class="custom-select" id="select-role">
                                                <option selected="selected">لطفا انتخاب کنید</option>
                                                @foreach($roles as $role)
                                                    <option class="role-level-one"
                                                            value="{{ $role->id }}">{{ $role->title }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="modal-footer justify-content-center">
                                        <button id="role-pick-button" type="button" class="btn btn-primary">تایید</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">بستن</button>
                        <button id="add-worker" type="button" class="btn btn-primary">افزودن</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Update Unit Modal -->
        <div class="modal fade" id="updateModal" tabindex="-1" role="dialog" aria-labelledby="updateModalLabel"
             aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="updateModalLabel">ویرایش شخص</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="title">نام</label>
                            <input type="text" class="form-control" id="title"
                                   placeholder="نام را وارد کنید..">
                        </div>
                        <div class="form-group">
                            <label for="code">کدملی</label>
                            <input type="text" class="form-control" id="code"
                                   placeholder="شماره ملی را بدون خط تیره وارد کنید..">
                        </div>
                        <input type="hidden" id="part-id">
                        <input type="hidden" id="role-id">

                        <div class="form-group">
                            <label for="code">نقش</label>
                            <div class="clearfix"></div>
                            <input disabled type="text" class="form-control text-dark col-10 float-right" id="role"
                                   placeholder="یک نقش انتخاب کنید">
                            <a data-toggle="modal" data-target="#updateModal #role-pick-modal" id="pick-role"
                               class="btn btn-secondary pl-3"
                               style="border-top-right-radius: 0; border-bottom-right-radius: 0;">انتخاب</a>
                        </div>

                        <div id="role-pick-modal" class="modal" tabindex="-3" role="dialog">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header justify-content-center">
                                        <h5 class="modal-title">انتخاب نقش برای شخص</h5>
                                    </div>
                                    <div class="modal-body" style="height: 284px;">
                                        <div class="form-group">
                                            <label for="select-role">نقش</label>
                                            <select class="custom-select" id="select-role">
                                                <option selected="selected">لطفا انتخاب کنید</option>
                                                @foreach($roles as $role)
                                                    <option class="role-level-one"
                                                            value="{{ $role->id }}">{{ $role->title }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="modal-footer justify-content-center">
                                        <button id="role-pick-button" type="button" class="btn btn-primary">تایید</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">بستن</button>
                        <button id="update-worker" type="button" class="btn btn-primary">ویرایش</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header bg-light">{{ __('مدیریت پرسنل') }}
                    <!-- Button trigger modal -->
                        @if(Auth::user()->role != 'reporter')
                        <button id="show-new-worker-modal" type="button" class="btn btn-outline-dark btn-sm float-left" data-toggle="modal"
                                data-target="#exampleModal">
                            افزودن شخص جدید
                        </button>
                            @endif
                    </div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        @if(count($workers) != 0)
                            <table class="table text-center" style="font-size: 13px" id="workersTable">
                                <thead>
                                <tr>
                                    <th scope="col">نام شخص</th>
                                    <th scope="col">کدملی</th>
                                    <th scope="col">نقش</th>
                                    @if(Auth::user()->role != 'reporter')
                                    <th scope="col">ویرایش</th>
                                    <th scope="col">حذف</th>
                                        @endif
                                </tr>
                                </thead>
                                <tbody id="workers-table-body">
                                @foreach($workers as $worker)
                                    <tr id="worker{{ $worker->id }}">
                                        <td>{{ $worker->title }}</td>
                                        <td>{{ $worker->code }}</td>
                                        <td>{{ $worker->role->title }}</td>
                                        @if(Auth::user()->role != 'reporter')
                                        <td>
                                            <span class="table-link edit-record fas fa-edit" data-id="{{ $worker->id }}"
                                                  data-role="{{ $worker->role_id }}" data-toggle="modal"
                                                  data-target="#workerUpdateModal"></span>
                                        </td>
                                        <td>
                                            <span class="table-link delete-record fas fa-trash"
                                                  data-id="{{ $worker->id }}"></span>
                                        </td>
                                            @endif
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                                {{ $workers->onEachSide(5)->links() }}
                        @else
                            <small>تا به حال هیچ شخصی ذخیره نشده است!</small>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>

        $('#exampleModal #select-role').change(function () {
            let id = $('option:selected',this).val();
            $.ajax({
                url: "{{ url('/') }}/roles/children/" + id,
                method: "get",
                success: function (response) {
                    const data = response;
                    if (data.length > 0) {
                        $('#role-pick-modal .modal-body .children-selector').remove();
                        $('#role-pick-modal .modal-body .end-selector').remove();

                        $('#role-pick-modal .modal-body').append('<div class="form-group children-selector"><label for="category-children">زیر نقش</label><select class="custom-select category-children" id="category-children"><option class="reset-child-id" selected>نقش مورد نظر را انتخاب کنید</option></select></div>');
                        data.forEach(element => $("#role-pick-modal #category-children").append("<option class='role-level-two' value='" + element.id + "'>" + element.title + "</option>"));
                    } else {
                        $('#role-pick-modal .modal-body .children-selector').remove();
                        $('#role-pick-modal .modal-body .end-selector').remove();
                    }
                },
            });
        });
        $("#exampleModal").on("change", '#category-children', function () {
            let id = $('option:selected',this).val();
            if (id == 'نقش مورد نظر را انتخاب کنید'){
                $("#exampleModal #role").val($('#exampleModal #select-role option:selected').text());
                $("#exampleModal #role-id").val($('#exampleModal #select-role option:selected').val());
                $('#role-pick-modal .modal-body .end-selector').remove();
            } else {
                $.ajax({
                    url: "{{ url('/') }}/roles/children/" + id,
                    method: "get",
                    success: function (response) {
                        const data = response;
                        if (data.length > 0) {
                            $('#role-pick-modal .modal-body .end-selector').remove();

                            $('#role-pick-modal .modal-body').append('<div class="form-group end-selector"><label for="end-children">زیر نقش</label><select class="custom-select end-children" id="end-children"><option class="reset-end-child-id" selected>نقش مورد نظر را انتخاب کنید</option></select></div>');
                            data.forEach(element => $("#role-pick-modal #end-children").append("<option class='role-level-three' value='" + element.id + "'>" + element.title + "</option>"));
                        } else {
                            $('#role-pick-modal .modal-body .end-selector').remove();
                        }
                    },
                });
            }
        });
        $("#role-pick-button").click(function () {
            $("#role-pick-modal").modal('hide');
        });
        $("#role-pick-modal").on("change", 'select', function () {
            $("#exampleModal #role").val($('option:selected',this).text());
            $("#exampleModal #role-id").val($('option:selected',this).val());
        });
        $("#role-pick-modal").on("change", '#end-children', function () {
            let id = $('option:selected',this).val();
            if (id == 'نقش مورد نظر را انتخاب کنید') {
                $("#exampleModal #role").val($('#role-pick-modal #category-children option:selected').text());
                $("#exampleModal #role-id").val($('#role-pick-modal #category-children option:selected').val());
            }
        });

        $('#add-worker').on('click', function () {
            $.ajax({
                url: "{{ route('workers.store') }}",
                method: "post",
                data: {
                    'title': $('#title').val(),
                    'code': $('#code').val(),
                    'role_id': $('#role-id').val(),
                    '_token': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (response) {
                    const data = response;
                    $("#workers-table-body").prepend('<tr id="worker' + data.id + '"> <td>' + data.title + '</td> <td>' + data.code + '</td> <td>' + data.role.title + '</td><td> <span class="table-link edit-record fas fa-edit" data-id="' + data.id + '" data-role="' + data.role_id + '" data-toggle="modal" data-target="#workerUpdateModal"></span> </td> <td> <span class="table-link delete-record fas fa-trash" data-id="' + data.id + '"></span> </td> </tr>')
                        // swal("تبریک", data.title + " با نقش " + data.role.title + " در بخش " + data.part.title + " ثبت شد!", "success", {
                        //     button: "باشه",
                        // })
                    $('#exampleModal #title').val('');
                    $('#exampleModal #code').val('');
                    $('#exampleModal #role').val('');
                    $('#exampleModal #role-id').val('');
                },
                error: function (err) {
                    swal("متاسفیم", "شخص مورد نظر ثبت نشد!", "warning", {
                        button: "باشه",
                    })
                }
            });
            $('#exampleModal').modal('hide');
        });

        $('#workers-table-body').on('click', '.delete-record', function () {
            const unitId = $(this).data("id");

            swal({
                title: "آیا می خواهید حذف شود؟",
                icon: "warning",
                buttons: [
                    'خیر',
                    'بله'
                ],
                dangerMode: true,
            }).then(function (isConfirm) {
                if (isConfirm) {
                    $.ajax({
                        url: "{{ url("/") }}/workers/" + unitId,
                        method: "delete",
                        data: {
                            'id': $(this).data("id"),
                            '_token': $('meta[name="csrf-token"]').attr('content'),
                            '_method': "delete"
                        },
                        success: function (response) {
                            const data = response;
                            $("#workersTable span[data-id=" + unitId + "]").parent().parent().remove(),
                                swal("تبریک", "شخص حذف شد!", "success", {
                                    button: "باشه",
                                })
                        },
                        error: function (err) {
                            swal("متاسفیم", "شخض مورد نظر حذف نشد!", "warning", {
                                button: "باشه",
                            })
                        }
                    });
                }
            })
        });

        let toUpdateId;
        $('#workers-table-body').on('click', '.edit-record', function () {
            $('#updateModal').modal('show');
            toUpdateId = $(this).data('id');
            $('#updateModal #title').val($(this).parent().prev().prev().prev().text());
            $('#updateModal #code').val($(this).parent().prev().prev().text());
            $('#updateModal #role').val($(this).parent().prev().text());
            $('#updateModal #role-id').val($(this).data('role'));
            $('#updateModal .end-selector').remove();
            $('#updateModal .children-selector').remove();
        });

        $('#updateModal #select-role').change(function () {
            let id = $('option:selected',this).val();
            $.ajax({
                url: "{{ url('/') }}/roles/children/" + id,
                method: "get",
                success: function (response) {
                    const data = response;
                    if (data.length > 0) {
                        $('#updateModal #role-pick-modal .modal-body .children-selector').remove();
                        $('#updateModal #role-pick-modal .modal-body .end-selector').remove();

                        $('#updateModal #role-pick-modal .modal-body').append('<div class="form-group children-selector"><label for="category-children">زیر نقش</label><select class="custom-select category-children" id="category-children"><option class="reset-child-id" selected>نقش مورد نظر را انتخاب کنید</option></select></div>');
                        data.forEach(element => $("#updateModal #role-pick-modal #category-children").append("<option class='role-level-two' value='" + element.id + "'>" + element.title + "</option>"));
                    } else {
                        $('#updateModal #role-pick-modal .modal-body .children-selector').remove();
                        $('#updateModal #role-pick-modal .modal-body .end-selector').remove();
                    }
                },
            });
        });
        $("#updateModal").on("change", '#category-children', function () {
            let id = $('option:selected',this).val();
            if (id == 'نقش مورد نظر را انتخاب کنید'){
                $("#updateModal #role").val($('#updateModal #select-role option:selected').text());
                $("#updateModal #role-id").val($('#updateModal #select-role option:selected').val());
                $('#updateModal #role-pick-modal .modal-body .end-selector').remove();
            } else {
                $.ajax({
                    url: "{{ url('/') }}/roles/children/" + id,
                    method: "get",
                    success: function (response) {
                        const data = response;
                        if (data.length > 0) {
                            $('#updateModal #role-pick-modal .modal-body .end-selector').remove();

                            $('#updateModal #role-pick-modal .modal-body').append('<div class="form-group end-selector"><label for="end-children">زیر نقش</label><select class="custom-select end-children" id="end-children"><option class="reset-end-child-id" selected>نقش مورد نظر را انتخاب کنید</option></select></div>');
                            data.forEach(element => $("#updateModal #role-pick-modal #end-children").append("<option class='role-level-three' value='" + element.id + "'>" + element.title + "</option>"));
                        } else {
                            $('#updateModal #role-pick-modal .modal-body .end-selector').remove();
                        }
                    },
                });
            }
        });
        $("#updateModal #role-pick-button").click(function () {
            $("#updateModal #role-pick-modal").modal('hide');
        });
        $("#updateModal #role-pick-modal").on("change", 'select', function () {
            $("#updateModal #role").val($('option:selected',this).text());
            $("#updateModal #role-id").val($('option:selected',this).val());
        });
        $("#updateModal #role-pick-modal").on("change", '#end-children', function () {
            let id = $('option:selected',this).val();
            if (id == 'نقش مورد نظر را انتخاب کنید') {
                $("#updateModal #role").val($('#updateModal #role-pick-modal #category-children option:selected').text());
                $("#updateModal #role-id").val($('#updateModal #role-pick-modal #category-children option:selected').val());
            }
        });

        $('#update-worker').on('click', function () {
            $.ajax({
                url: "{{ url('/') }}/workers/" + toUpdateId,
                method: "put",
                data: {
                    'title': $('#updateModal #title').val(),
                    'code': $('#updateModal #code').val(),
                    'role_id': $('#updateModal #role-id').val(),
                    '_token': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (response) {
                    const data = response;
                    $('#workers-table-body #worker' + toUpdateId + '').after('<tr> <td>' + data.title + '</td> <td>' + data.code + '</td> <td>' + data.role.title + '</td><td> <span class="table-link edit-record fas fa-edit" data-id="' + data.id + '" data-role="' + data.role_id + '" data-toggle="modal" data-target="#workerUpdateModal"></span> </td> <td> <span class="table-link delete-record fas fa-trash" data-id="' + data.id + '"></span> </td> </tr>');
                    $('#workers-table-body #worker' + toUpdateId + '').remove();
                    // swal("تبریک", " تغییرات اعمال شد!", "success", {
                    //         button: "باشه",
                    //     })
                },
                error: function (err) {
                    swal("متاسفیم", "تغییرات اعمال نشد!", "warning", {
                        button: "باشه",
                    })
                }
            });
            $('#updateModal').modal('hide');
        });

    </script>
@endsection
