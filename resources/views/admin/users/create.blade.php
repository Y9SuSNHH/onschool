@extends('layout.users.master')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title" id="username"></h4>
                    <div class="tab-content">
                        <div class="tab-pane show active" id="input-types-preview">
                            <form method="POST" id="form-edit">
                                @csrf
                                <div class="form-row">
                                    <div class="form-group col-md-4">
                                        <label for="firstname">Firstname</label>
                                        <input type="text" id="firstname" name="firstname" class="form-control"
                                               required>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="lastname">Lastname</label>
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
                                    <div class="form-group col-md-4">
                                        <label for="username">Username</label>
                                        <input type="text" id="username" name="username" class="form-control" required>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="password">Password</label>
                                        <input type="password" id="password" name="password" class="form-control" required>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="confirm_password">Confirm Password</label>
                                        <input type="password" id="password" name="confirm_password" class="form-control" required>
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
