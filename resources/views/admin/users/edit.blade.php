@extends('layout.admin.master')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title" id="username"></h4>
                    <div class="tab-content">
                        <div class="tab-pane show active" id="input-types-preview">
                            <form method="POST" class="needs-validation" novalidate id="form-edit">
                                @csrf
                                @method('PUT')
                                <div class="form-row">
                                    <div class="form-group col-md-5">
                                        <label for="firstname">First name</label>
                                        <input type="text" id="firstname" name="firstname" class="form-control"
                                               required>
                                    </div>
                                    <div class="form-group col-md-5">
                                        <label for="lastname">Last name</label>
                                        <input type="text" id="lastname" name="lastname" class="form-control" required>
                                    </div>
                                    <div class="form-group col-md-2">
                                        <label for="gender">Gender</label>
                                        <br>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" id="gender-male" name="gender"
                                                   class="custom-control-input" value="1">
                                            <label class="custom-control-label" for="gender-male">Male</label>
                                        </div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" id="gender-female" name="gender"
                                                   class="custom-control-input" value="0">
                                            <label class="custom-control-label" for="gender-female">Female</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-4">
                                        <label for="phone">Phone</label>
                                        <input type="text" id="phone" name="phone" class="form-control" required>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="email">Email</label>
                                        <input type="text" id="email" name="email" class="form-control" required>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="select-role">Role</label>
                                        <select class="form-control" id="select-role" name="role">
                                            @foreach(\App\Enums\UserRoleEnum::asArray() as $key => $value)
                                                <option value="{{ $value }}">{{ $key}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary">Submit edit</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('js')
    <script type="text/javascript">
        function crawlData() {
            const urlEdit = window.location.href;
            const arrUrlEdit = urlEdit.split("/");
            const id = arrUrlEdit[6];
            $.ajax({
                url: `{{route('api.admin.users.profile')}}/${id}`,
                type: 'GET',
                dataType: 'JSON',
                success: function (response) {
                    let each = response.data;
                    let action = `{{route('api.admin.users.update')}}/${id}`;
                    $("#form-edit").attr('action', action);
                    $("#username").html(each.username);
                    $("input[name=firstname]").val(each.firstname);
                    $("input[name=lastname]").val(each.lastname);
                    each.gender ? $("#gender-male").prop("checked", true) : $("#gender-female").prop("checked", true);
                    $("input[name=phone]").val(each.phone);
                    $("input[name=email]").val(each.email);
                    $("#select-role").val(each.role);
                },
                error: function () {
                    notifyError("some error");
                },
            });
        }

        function submitForm(form, type) {
            form.on('submit', function (event) {
                event.preventDefault();
                const formData = new FormData(form[0]);
                $.ajax({
                    url: form.attr('action'),
                    type: type,
                    dataType: 'JSON',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function (response) {
                        notifySuccess('Update user')
                    },
                    error: function (response) {
                        notifyError(response.responseJSON.message)
                    },
                });
            });
        }

        $(document).ready(function () {
            crawlData();
            submitForm($("#form-edit"), "POST");
        });
    </script>
@endpush
