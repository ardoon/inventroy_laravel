@extends('layouts.app')

@section('content')
    <div class="container">

        <!-- Insert Unit Modal -->
        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
             aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">افزودن یکای اندازه گیری</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="unit-title">نام یکای اندازه گیری</label>
                            <input type="text" class="form-control" id="unit-title"
                                   placeholder="به عنوان مثال: کیلوگرم، متر و ...">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">بستن</button>
                        <button id="add-unit" type="button" class="btn btn-primary">افزودن</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Update Modal -->
        <div class="modal fade" id="unitUpdateModal" tabindex="-1" role="dialog" aria-labelledby="unitUpdateModalLabel"
             aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="unitUpdateLabel">تغییر نام یکای اندازه گیری</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="unit-title-old">نام یکای اندازه گیری</label>
                            <input type="text" class="form-control" id="unit-title-old"
                                   value="" disabled>
                        </div>
                        <div class="form-group">
                            <label for="unit-title-new">نام جدید یکای اندازه گیری</label>
                            <input type="text" class="form-control" id="unit-title-new"
                                   placeholder="به عنوان مثال: کیلوگرم، متر و ...">
                        </div>
                        <input type="hidden" id="unit-id" value="">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">بستن</button>
                        <button id="update-unit" type="button" class="btn btn-primary">اعمال</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-light">{{ __('مدیریت یکاهای اندازه گیری') }}
                    <!-- Button trigger modal -->
                        @if(Auth::user()->role != 'reporter')
                        <button type="button" class="btn btn-outline-dark btn-sm float-left" data-toggle="modal"
                                data-target="#exampleModal">
                            افزودن یکای جدید
                        </button>
                        @endif
                    </div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        @if(count($units) != 0)
                            <table class="table text-center" id="unitsTable">
                                <thead>
                                <tr>
                                    <th scope="col">نام یکا</th>
                                    @if(Auth::user()->role != 'reporter')
                                    <th scope="col">ویرایش</th>
                                    <th scope="col">حذف</th>
                                    @endif
                                </tr>
                                </thead>
                                <tbody id="units-table-body">
                                @foreach($units as $unit)
                                    <tr>
                                        <td>{{ $unit->title }}</td>
                                        @if(Auth::user()->role != 'reporter')
                                        <td>
                                            <span class="table-link edit-record fas fa-edit" data-id="{{ $unit->id }}"
                                                  data-title="{{ $unit->title }}" data-toggle="modal"
                                                  data-target="#unitUpdateModal"></span>
                                        </td>
                                        <td>
                                            <span class="table-link delete-record fas fa-trash"
                                                  data-id="{{ $unit->id }}"></span>
                                        </td>
                                        @endif
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        @else
                            <small>تا به حال هیچ یکای اندازه گیری ذخیره نشده است!</small>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>

        $('.card-header button').click(function (){
           $('#exampleModal input[type=text]').val('');
        });

        $('#add-unit').on('click', function () {
            $.ajax({
                url: "{{ route('units.store') }}",
                method: "post",
                data: {
                    'title': $('#unit-title').val(),
                    '_token': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (response) {
                    const data = response;
                    $("#units-table-body").prepend("<tr><td>" + data.title + "</td><td><span class='table-link edit-record fas fa-edit' data-id='" + data.id + "' data-title='" + data.title + "' data-toggle='modal' data-target='#unitUpdateModal'></span></td><td><span class='table-link delete-record fas fa-trash' data-id='" + data.id + "'></span></td></tr>")
                },
                error: function (err) {
                    swal("متاسفیم", "یکای مورد نظر ثبت نشد!", "warning", {
                        button: "باشه",
                    })
                }
            });
            $('#exampleModal').modal('hide');
        });

        $("#unitsTable").on("click", '.edit-record', function () {
            $("#unitUpdateModal #unit-title-old").val($(this).data('title'));
            $("#unitUpdateModal #unit-id").val($(this).data('id'));
        });

        $('#update-unit').on('click', function () {
            const unitOldTitle = $('#unitUpdateModal #unit-title-old').val();
            $.ajax({
                url: "{{ url('/') }}/units/" + $("#unitUpdateModal #unit-id").val(),
                method: "put",
                data: {
                    'id': $('#unitUpdateModal #unit-id').val(),
                    'title': $('#unitUpdateModal #unit-title-new').val(),
                    '_token': $('meta[name="csrf-token"]').attr('content'),
                    '_method': "PUT"
                },
                success: function (title) {
                    jQuery("#unitsTable td:contains(" + unitOldTitle + ")").next().children('.edit-record').data("title", title),
                        jQuery("#unitsTable td:contains(" + unitOldTitle + ")").text(title),
                        swal("تبریک", "تام یکای " + unitOldTitle + " به  " + title + " تغییر یافت!", "success", {
                            button: "باشه",
                        })
                },
                error: function (err) {
                    swal("متاسفیم", "نام جدید برای یکای مورد نظر ثبت نشد!", "warning", {
                        button: "باشه",
                    })
                }
            });
            $('#unitUpdateModal').modal('hide');
        });

        $("#unitsTable").on("click", '.delete-record', function () {
            const unitId = $(this).data("id");

            swal({
                title: "آیا می خواهید یکا حذف شود؟",
                text: "کالاهایی با این یکا، یکای پیش فرض را می گیرند.",
                icon: "warning",
                buttons: [
                    'خیر',
                    'بله'
                ],
                dangerMode: true,
            }).then(function (isConfirm) {
                if (isConfirm) {
                    $.ajax({
                        url: "{{ url("/") }}/units/" + unitId,
                        method: "delete",
                        data: {
                            'id': $(this).data("id"),
                            '_token': $('meta[name="csrf-token"]').attr('content'),
                            '_method': "delete"
                        },
                        success: function (response) {
                            const data = response;
                            $("#unitsTable span[data-id=" + unitId + "]").parent().parent().remove(),
                                swal("تبریک", "یکا حذف شد!", "success", {
                                    button: "باشه",
                                })
                        },
                        error: function (err) {
                            swal("متاسفیم", "یکای مورد نظر حذف نشد!", "warning", {
                                button: "باشه",
                            })
                        }
                    });
                }
            })

        });

    </script>
@endsection
