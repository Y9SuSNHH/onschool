@extends('layout.admin.master')
@push('css')
    <style>
        .error {
            color: red;
        }
    </style>
@endpush
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title" id="username"></h4>
                    <div id="div-error" class="alert alert-danger d-none"></div>
                    <div class="tab-content">
                        <div class="tab-pane show active" id="input-types-preview">
                            <form method="POST" id="form-edit">
                                @csrf
                                @method('PUT')
                                <div class="form-row">
                                    <div class="form-group col-md-3">
                                        <label for="firstname">Firstname</label>
                                        <input type="text" id="firstname" name="firstname" class="form-control">
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label for="lastname">Lastname</label>
                                        <input type="text" id="lastname" name="lastname" class="form-control">
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label for="address">Address</label>
                                        <input type="text" id="address" name="address" class="form-control">
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label for="gender">Gender</label>
                                        <br>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" id="gender-male" name="gender"
                                                   class="custom-control-input" value="1" checked>
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
                                        <input type="number" id="phone" name="phone" class="form-control">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="email">Email</label>
                                        <input type="email" id="email" name="email" class="form-control">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="identification">Identification</label>
                                        <textarea class="form-control" name="identification"
                                                  id="identification"></textarea>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-12">
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
    <script src="{{ asset('js/jquery.validate.js')}}"></script>
    <script type="text/javascript">
        function showError(errors) {
            let string = '<ul>';
            console.log(errors);
            if (Array.isArray(errors)) {
                errors.forEach(function (each) {
                    each.forEach(function (error) {
                        string += `<li>${error}</li>`;
                    });
                });
            } else {
                string += `<li>${errors}</li>`;
            }
            string += '</ul>';
            $("#div-error").html(string);
            $("#div-error").removeClass("d-none").show();
        }

        function crawlData() {
            const urlEdit = window.location.href;
            const arrUrlEdit = urlEdit.split("/");
            const id = arrUrlEdit[6];
            $.ajax({
                url: `{{route("api.$role.$table.show")}}/${id}`,
                type: 'GET',
                dataType: 'JSON',
                headers: {Authorization: `${getJWT().token_type} ` + getJWT().access_token},
                success: function (response) {
                    let each = response.data;
                    let action = `{{route("api.$role.$table.update")}}/${id}`;
                    $("#form-edit").attr('action', action);
                    $("#username").html(each.username);
                    $("input[name=firstname]").val(each.firstname);
                    $("input[name=lastname]").val(each.lastname);
                    $("input[name=address]").val(each.address);
                    each.gender ? $("#gender-male").prop("checked", true) : $("#gender-female").prop("checked", true);
                    $("input[name=phone]").val(each.phone);
                    $("input[name=email]").val(each.email);
                    $("textarea[name=identification]").val(each.identification);

                    $(`#select-role option[value=${each.role}]`).attr("selected", "selected");
                },
                error: function () {
                    notifyError("some error");
                },
            });
        }

        function submitForm(form, type) {
            const formData = new FormData(form[0]);
            $.ajax({
                url: form.attr('action'),
                type: type,
                dataType: 'JSON',
                data: formData,
                headers: {Authorization: `${getJWT().token_type} ` + getJWT().access_token},
                processData: false,
                contentType: false,
                success: function (response) {
                    console.log(response);
                    $("#div-error").addClass("d-none")
                    notifySuccess(response.message);
                },
                error: function (response) {
                    console.log(response);
                    notifyError(response.statusText);
                    let errors;
                    if (response.responseJSON.errors) {
                        errors = Object.values(response.responseJSON.errors);
                        showError(errors);
                    } else {
                        errors = response.responseJSON.message.errorInfo[2].split("for");
                        showError(errors[0]);
                    }
                },
            });
        }

        function submitFormEdit() {
            $("#form-edit").validate({
                rules: {
                    firstname: {
                        required: true,
                    },
                    lastname: {
                        required: true,
                    },
                    address: {
                        required: true,
                    },
                    gender: {
                        required: true,
                        range: [0, 1],
                    },
                    phone: {
                        required: true,
                        minlength: 9,
                        digits: true,
                    },
                    email: {
                        required: true,
                        email: true,
                    },
                    identification: {
                        required: true,
                    },
                },
                submitHandler: function (form, event) {
                    event.preventDefault();
                    submitForm($("#form-edit"), "POST");
                },
            });
        }

        $(document).ready(function () {
            crawlData();
            submitFormEdit()
        });
    </script>
@endpush
