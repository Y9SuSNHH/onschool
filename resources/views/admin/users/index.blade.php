@extends('layout.admin.master')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card" id="card">
                <div class="card-header">
                    <a href="{{route("$role.$table.create")}}" class="btn btn-success">Create</a>
                </div>
                <div class="card-body">
                    <div class="tab-content">
                        <div class="tab-pane show active" id="responsive-preview">
                            <div class="table-responsive">
                                <table class="table table-centered mb-0" id="table-list">
                                    <thead class="thead-dark">
                                    <tr>
                                        <th>#</th>
                                        <th>Information</th>
                                        <th>Username</th>
                                        <th>Active?</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <nav>
                        <ul class="pagination pagination-rounded mb-0" id="pagination">
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    <div id="warning-alert-delete" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-body p-4">
                    <div class="text-center">
                        <i class="dripicons-warning h1 text-warning"></i>
                        <h4 class="mt-2">ARE YOU SURE?</h4>
                        <p class="mt-3">After delete you can restore this user in trash of users</p>
                        <form method="POST" id="form-delete">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-warning my-2">DELETE</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div
@endsection
@push('js')
    <script type="text/javascript">
        function createActionFormDelete(id) {
            $("#form-delete").attr('action', `{{route("api.$role.$table.destroy")}}/${id}`)
        }

        function crawlData() {
            $.ajax({
                url: `{{route("api.$role.$table.list")}}`,
                type: 'GET',
                dataType: 'JSON',
                data: {page: {{ request()->get('page') ?? 1 }}},
                headers: {Authorization: `${getJWT().token_type} ` + getJWT().access_token},
                success: function (response) {
                    response.data.data.forEach(function (each) {
                        let gender = each.gender ? 'Male' : 'Female';
                        let phone = `<a href="tel:${each.phone}">${each.phone}</a>`;
                        // let email = `<a href="mailto:${each.email}">${each.email}</a>` + '<br>' + formatToDate(each.email_verified_at);
                        let email = `<a href="mailto:${each.email}">${each.email}</a>`;
                        let information = each.firstname + `.${each.lastname} - ` + gender + `<br>` + phone + `<br>` + email;
                        let role = each.role === {{\App\Enums\UserRoleEnum::ADMIN}} ? 'ADMIN' : 'USER';
                        let username = each.username + ' - ' + role + `<br><a href="#">Forgotten password?</a>`;
                        let active = `<input type="checkbox" id="active-${each.id}" ${each.active ? 'checked' : ''}  data-switch="success" onclick="userUpdateActive(${each.id})"/>
                        <label for="active-${each.id}" data-on-label="Yes" data-off-label="No" class="mb-0 d-block"></label>`;
                        let edit_route = `{{ route("$role.$table.edit")}}/` + each.id;
                        let edit = `<a href="${edit_route}" class="action-icon"><i class="mdi mdi-pencil"></i></a>`;
                        let destroy = `<a class="action-icon" data-toggle="modal" data-target="#warning-alert-delete" onclick="createActionFormDelete(${each.id})"><i class="mdi mdi-delete"></i></a>`;
                        let action = edit + destroy;
                        $('#table-list').append($('<tr>')
                            .append($('<td>').append(each.id))
                            .append($('<td>').append(information))
                            .append($('<td>').append(username))
                            .append($('<td class="text-center">').append(active))
                            .append($('<td class="table-action">').append(action))
                        );
                    });
                    renderPagination(response.data.pagination);
                },
                error: function (response) {
                    notifyError(response.statusText);
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
                    headers: {Authorization: `${getJWT().token_type} ` + getJWT().access_token},
                    processData: false,
                    contentType: false,
                    success: function (response) {
                        notifySuccess(response.message);
                        $('#card').load(location.href + " #card");
                        crawlData();
                        $("#warning-alert-delete").modal('hide');
                    },
                    error: function (response) {
                        notifyError(response.responseJSON.message)
                    },
                });
            });
        }

        function changePage(page) {
            let urlParams = new URLSearchParams(window.location.search);
            urlParams.set('page', page);
            window.location.search = urlParams;
        }

        function userUpdateActive(id) {
            $.ajax({
                url: `{{route("api.admin.users.update.active")}}/${id}`,
                type: "GET",
                dataType: "JSON",
                headers: {Authorization: `${getJWT().token_type} ` + getJWT().access_token},
                success: function (response) {
                    notifySuccess(response.message);
                }
            });
        }

        $(document).ready(function () {
            crawlData();
            submitForm($("#form-delete"), "DELETE");
        });
    </script>
@endpush
