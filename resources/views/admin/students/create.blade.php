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
                    <div id="div-error" class="alert alert-danger d-none"></div>
                    <div class="tab-content">
                        <div class="tab-pane show active" id="input-types-preview">
                            <form action="{{route("api.$table.store")}}" method="POST" id="form-create">
                                @csrf
                                <div class="form-row">
                                    <div class="form-group col-md-3">
                                        <label for="username">Username</label>
                                        <input type="text" id="username" name="username" class="form-control">
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label for="firstname">Firstname</label>
                                        <input type="text" id="firstname" name="firstname" class="form-control">
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label for="lastname">Lastname</label>
                                        <input type="text" id="lastname" name="lastname" class="form-control">
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
                                        <label for="address">Address</label>
                                        <input type="text" id="address" name="address" class="form-control">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="email">Email</label>
                                        <input type="email" id="email" name="email" class="form-control">
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-4">
                                        <label for="password">Password</label>
                                        <input type="password" id="password" name="password" class="form-control">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="confirm_password">Confirm password</label>
                                        <input type="password" id="confirm_password" name="confirm_password"
                                               class="form-control">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="identification">Identification</label>
                                        <textarea class="form-control" name="identification"
                                                  id="identification"></textarea>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-success">Create</button>
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
                    notifySuccess(response.message);
                },
                error: function (response) {
                    notifyError(response.responseJSON.message);
                    let errors;
                    if (response.responseJSON.errors) {
                        errors = Object.values(response.responseJSON.errors);
                        showError(errors);
                    } else {
                        errors = response.responseJSON.message;
                        showError(errors);
                    }
                },
            });
        }

        function submitFormCreate() {
            $("#form-create").validate({
                rules: {
                    username: {
                        required: true,
                    },
                    firstname: {
                        required: true,
                    },
                    lastname: {
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
                    address: {
                        required: true,
                    },
                    email: {
                        required: true,
                        email: true,
                    },
                    password: {
                        required: true,
                        minlength: 6,
                    },
                    confirm_password: {
                        required: true,
                        minlength: 6,
                        equalTo: "#password",
                    },
                    identification: {
                        required: true,
                    },
                },
                submitHandler: function (form, event) {
                    event.preventDefault();
                    submitForm($("#form-create"), "POST");
                },
            });
        }

        $(document).ready(function () {
            submitFormCreate()
        })
    </script>
@endpush
