@extends('layouts.app')

@section('content')
    <div class="container">

        <!-- Insert Product Modal -->
        <div id="insertModal" class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog"
             aria-labelledby="insertModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="insertModalLabel">افزودن کاربر جدید</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form autocomplete="off" id="insertForm" action="{{ route('users.store') }}" method="post">
                            <div class="form-row">
                                <div class="form-group col-12">
                                    <label for="name">نام کاربر</label>
                                    <input type="text" class="form-control" id="name" name="name"
                                           placeholder="نام کاربر را وارد کنید..">
                                </div>
                                <div class="form-group col-12">
                                    <label for="email">ایمیل</label>
                                    <input id="email" type="email" class="form-control" name="email" required autocomplete="email">
                                </div>
                                <div class="form-group col-12">
                                    <label for="role">دسترسی</label>
                                    <select class="custom-select" id="role" name="role">
                                        <option value="admin">مدیر</option>
                                        <option value="clerk">سرپرست</option>
                                        <option value="operator">انباردار</option>
                                        <option value="reporter">ناظر</option>
                                    </select>
                                </div>
                                <div class="form-group col-6">
                                    <label for="password">گذرواژه</label>
                                    <input id="password" type="password" class="form-control" name="password" required autocomplete="new-password">
                                </div>
                                <div class="form-group col-6">
                                    <label for="password-confirm">تکرار گذرواژه</label>
                                    <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
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
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="updateLabel">افزودن کالای جدید</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form autocomplete="off" id="updateForm" action="" method="post">
                            <div class="form-group col-12">
                                <label for="name">نام کاربر</label>
                                <input type="text" class="form-control" id="name" name="name"
                                       placeholder="نام کاربر را وارد کنید..">
                            </div>
                            <div class="form-group col-12">
                                <label for="update-role">دسترسی</label>
                                <select class="custom-select" id="update-role" name="role">
                                    <option value="admin">مدیر</option>
                                    <option value="clerk">سرپرست</option>
                                    <option value="operator">انباردار</option>
                                    <option value="reporter">ناظر</option>
                                </select>
                            </div>
                            <div class="form-group col-12">
                                <label for="password">گذرواژه</label>
                                <input id="password" type="password" class="form-control" name="password" autocomplete="new-password">
                            </div>
                            <div class="form-group col-12">
                                <label for="password-confirm">تکرار گذرواژه</label>
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" autocomplete="new-password">
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">بستن</button>
                        <button id="delete-button" type="button" class="btn btn-danger">حذف کاربر</button>
                        <button id="update-button" type="button" class="btn btn-primary">اعمال</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header bg-light">{{ __('مدیریت کاربران') }}
                    <!-- Button trigger modal -->
                        <button id="show-insert-modal" type="button" class="btn btn-outline-dark btn-sm float-left"
                                data-toggle="modal"
                                data-target="#insertModal">
                            افزودن کاربر جدید
                        </button>
                    </div>

                    <div class="card-body">

                            <table class="table text-center" id="productsTable">
                                <thead>
                                <tr>
                                    <th scope="col">نام کاربر</th>
                                    <th scope="col">دسترسی</th>
                                    <th scope="col">ایمیل</th>
                                    <th scope="col" class="text-center">مشاهده</th>
                                </tr>
                                </thead>
                                <tbody id="products-table-body">
                                @foreach($users as $user)
                                    <tr id="user{{ $user->id }}">
                                        <td>{{ $user->name }}</td>
                                        @if($user->role == 'admin')<td>مدیر</td>@endif @if($user->role == 'clerk')<td>سرپرست</td>@endif @if($user->role == 'operator')<td>انباردار</td>@endif @if($user->role == 'reporter')<td>ناظر</td>@endif</td>
                                        <td>{{ $user->email }}</td>
                                        <td class="text-center">
                                            <span class="table-link show-record fas fa-eye"
                                                  data-id="{{ $user->id }}"></span>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>

        $('#insert-button').click(function () {
            let csrf = {'_token': $('meta[name="csrf-token"]').attr('content')}

            if ($('#insertForm #password').val().length < 8){
                swal("متاسفیم", "پسورد حداقل باید 8 رقم باشد!", "warning", {
                    button: "باشه",
                })
                return false;
            }

            if ($('#insertForm #password').val() != $('#insertForm #password-confirm').val()){
                swal("متاسفیم", "تکرار پسورد با پسورد مطابقت ندارد!", "warning", {
                    button: "باشه",
                })
                return false;
            }

            $.ajax({
                url: "{{ route('users.store') }}",
                method: "post",
                data: $('#insertForm').serialize() + '&' + $.param(csrf),
                success: function (response) {
                    const data = response;
                    let newRole;

                    if (data.role == 'admin'){ newRole = 'مدیر' };
                    if (data.role == 'clerk'){ newRole = 'سرپرست' };
                    if (data.role == 'operator'){ newRole = 'انباردار' };
                    if (data.role == 'reporter'){ newRole = 'ناظر' };

                    $("#products-table-body").append('<tr id="user' + data.id + '"> <td>' + data.name + '</td> <td>' + newRole + '</td><td>' + data.email + '</td><td class="text-center"> <span class="table-link show-record fas fa-eye" data-id="' + data.id + '"></span> </td></tr>'),

                    swal("تبریک", "کاربر " + data.name + " ثبت شد!", "success", {
                        button: "باشه",
                    })
                },
                error: function (err) {
                    swal("متاسفیم", "کاربر مورد نظر ثبت نشد!", "warning", {
                        button: "باشه",
                    })
                }
            });
            $('#insertModal').modal('hide');
            $('#insertForm #name').val("");
        });

        //    Update
        let toUpdateID;

        $("#delete-button").on("click", function () {
            const categoryId = $(this).data("id");
            swal({
                title: "آیا می خواهید کاربر حذف شود؟",
                icon: "warning",
                buttons: [
                    'خیر',
                    'بله'
                ],
                dangerMode: true,
            }).then(function (isConfirm) {
                if (isConfirm) {
                    $.ajax({
                        url: "{{ url("/") }}/users/" + toUpdateID,
                        method: "delete",
                        data: {
                            '_token': $('meta[name="csrf-token"]').attr('content'),
                            '_method': "delete"
                        },
                        success: function (response) {
                            const data = response;
                            $("#products-table-body #user" + toUpdateID + "").remove(),
                                swal("تبریک", "کاربر حذف شد!", "success", {
                                    button: "باشه",
                                })
                            toUpdateID = null;
                        },
                        error: function (err) {
                            swal("متاسفیم", "کاربر حذف نشد!", "warning", {
                                button: "باشه",
                            })
                        }
                    });
                    $('#updateModal').modal('hide');
                }
            })

        });

        $("#productsTable").on("click", '.show-record', function () {
            let id = $(this).data('id');
            toUpdateID = id;
            let oldTitle = $("#productsTable tr[id=user" + id + "] td:first-child").text();
            let oldRole = $("#productsTable tr[id=user" + id + "] td:nth-child(2)").text();
            $('#updateModal').modal('show');
            $('#updateForm #name').val(oldTitle);
            $('#update-role option:contains(' + oldRole + ')').attr('selected', 'selected');
        });

        $('#update-button').click(function () {
            let csrf = {'_token': $('meta[name="csrf-token"]').attr('content')}
            let method = {'_method': 'put'}

            let passwordLength = $('#updateForm #password').val().length;

            if (passwordLength < 8 && passwordLength > 0){
                swal("متاسفیم", "پسورد حداقل باید 8 رقم باشد!", "warning", {
                    button: "باشه",
                })
                return false;
            }

            if ($('#updateForm #password').val() != $('#updateForm #password-confirm').val()){
                swal("متاسفیم", "تکرار پسورد با پسورد مطابقت ندارد!", "warning", {
                    button: "باشه",
                })
                return false;
            }

            $.ajax({
                url: "{{ url('/') }}/users/" + toUpdateID,
                method: "post",
                data: $('#updateForm').serialize() + '&' + $.param(csrf) + '&' + $.param(method),
                success: function (response) {
                    const data = response;
                    let newRole;

                    if (data.role == 'admin'){ newRole = 'مدیر' };
                    if (data.role == 'clerk'){ newRole = 'سرپرست' };
                    if (data.role == 'operator'){ newRole = 'انباردار' };
                    if (data.role == 'reporter'){ newRole = 'ناظر' };

                    $("#products-table-body #user" + data.id + " td:first-child").text(data.name);
                    $("#products-table-body #user" + data.id + " td:nth-child(2)").text(newRole);
                    swal("تبریک", "تغییرات برای کاربر " + data.name + " ثبت شد!", "success", {
                        button: "باشه",
                    })
                },
                error: function (err) {
                    swal("متاسفیم", "تغییرات برای کاربر مورد نظر ثبت نشد!", "warning", {
                        button: "باشه",
                    })
                }
            });
            $('#updateModal').modal('hide');
            $('#updateForm #name').val("");
            toUpdateID = null;
        });

    </script>
@endsection
