@extends('layout.admin.master')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card" id="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-12">
                            <form class="form-inline" id="form-filter">
                                <div class="form-group mr-1">
                                    <label for="filter-username" class="sr-only">Search username</label>
                                    <input type="search" class="form-control filter-change" id="filter-username"
                                           name="username" placeholder="Search username...">
                                </div>
                                <div class="form-group mr-1">
                                    <select class="custom-select filter-change" name="deleted_type"
                                            id="filter-deleted-type">
                                        <option value="0" selected>Without deleted records</option>
                                        <option value="All">With deleted records</option>
                                        <option value="1">Only deleted records</option>
                                    </select>
                                </div>
                                <div class="form-group mr-1">
                                    <select class="custom-select filter-change" name="active"
                                            id="filter-active">
                                        <option value="1" selected>With active records</option>
                                        <option value="0">With disable records</option>
                                        <option value="All">All status records</option>
                                    </select>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-4">
                            <a href="{{route("$role.$table.create")}}" class="btn btn-danger mb-2"><i
                                    class="mdi mdi-plus-circle mr-2"></i> Add User</a>
                        </div>
                        <div class="col-sm-8">
                            <div class="text-sm-right">
                                <button type="button" class="btn btn-success mb-2 mr-1"><i
                                        class="mdi mdi-settings"></i></button>
                                <button type="button" class="btn btn-light mb-2 mr-1">Import</button>
                                <button type="button" class="btn btn-light mb-2">Export</button>
                            </div>
                        </div>
                    </div>
                    <div id="crawl-data">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="tab-content">
                                    <div class="table-responsive">
                                        <table class="table mb-0" id="table-list">
                                            <thead class="thead-dark">
                                            <tr>
                                                <th>#</th>
                                                <th>Information</th>
                                                <th>User</th>
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
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="dataTables_paginate paging_simple_numbers"
                                     id="products-datatable_paginate">
                                    <ul class="pagination pagination-rounded mb-0" id="pagination"></ul>
                                </div>
                            </div>
                        </div>
                    </div>
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
                            <button type="submit" class="btn btn-warning my-2 check-permission">DELETE</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('js')
    <script type="text/javascript">
        function createActionFormDelete(id) {
            $("#form-delete").attr('action', `{{route("api.$role.$table.destroy")}}/${id}`)
        }

        function crawlData(page = 1) {
            $.ajax({
                url: `{{route("api.$role.$table.list")}}`,
                type: 'GET',
                dataType: 'JSON',
                data: {
                    page,
                    username: $("input[name=username]").val(),
                    deleted_type: $("select[name=deleted_type]").val(),
                    active: $("select[name=active]").val(),
                },
                headers: {Authorization: `${getJWT().token_type} ` + getJWT().access_token},
                success: function (response) {
                    response.data.data.forEach(function (each) {
                        let gender = each.gender ? 'Male' : 'Female';
                        let phone = `<a href="tel:${each.phone}">${each.phone}</a>`;
                        // let email = `<a href="mailto:${each.email}">${each.email}</a>` + '<br>' + formatToDate(each.email_verified_at);
                        let email = `<a href="mailto:${each.email}">${each.email}</a>`;
                        let information = each.firstname + `.${each.lastname} - ` + gender + `<br>` + phone + `<br>` + email;
                        let role = each.role === {{\App\Enums\UserRoleEnum::ADMIN}} ? 'ADMIN' : 'USER';
                        let user = each.username + ' - ' + role + `<br><a href="#">Forgotten password?</a>`;
                        let active = `<input type="checkbox" class="check-permission" id="active-${each.id}" ${each.active ? 'checked' : ''}  data-switch="success" onclick="userUpdateActive(${each.id})"/>
                        <label for="active-${each.id}" data-on-label="Yes" data-off-label="No" class="mb-0 d-block"></label>`;
                        let edit_route = `{{ route("$role.$table.edit")}}/` + each.id;
                        let edit = `<a href="${edit_route}" class="action-icon"><i class="mdi mdi-pencil"></i></a>`;
                        let destroy = `<a class="action-icon check-permission" data-toggle="modal" data-target="#warning-alert-delete" onclick="createActionFormDelete(${each.id})"><i class="mdi mdi-delete"></i></a>`;
                        let action = edit + destroy;
                        $('#table-list').append($('<tr>')
                            .append($('<td>').append(each.id))
                            .append($('<td>').append(information))
                            .append($('<td>').append(user))
                            .append($('<td class="text-center">').append(active))
                            .append($('<td class="table-action">').append(action))
                        );
                    });
                    let payloadJwt = getJwtPayloadLocalStorage();
                    if(payloadJwt.role === 2){
                        $(".check-permission").prop('disabled',true);
                    }
                    renderPagination(response.data.pagination, page);
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
                    headers: {
                        'X-CSRF-TOKEN': $("input[name=_token]").val(),
                        Authorization: `${getJWT().token_type} ` + getJWT().access_token
                    },
                    processData: false,
                    contentType: false,
                    success: function (response) {
                        notifySuccess(response.message);
                        $('#crawl-data').load(location.href + " #crawl-data");
                        crawlData();
                        $("#warning-alert-delete").modal('hide');
                    },
                    error: function (response) {
                        notifyError(response.responseJSON.message)
                    },
                });
            });
        }


        function userUpdateActive(id) {
            $.ajax({
                url: `{{route("api.admin.users.update.active")}}/${id}`,
                type: "GET",
                dataType: "JSON",
                headers: {Authorization: `${getJWT().token_type} ` + getJWT().access_token},
                success: function (response) {
                    notifySuccess(response.message);
                },
                error: function (response) {
                    notifyError(response.responseJSON.message)
                }
            });
        }

        function filter() {
            $(".filter-change").on('change', function () {
                $("#form-filter").submit();
            });
            submitFormFilter();
        }

        function submitFormFilter() {
            $('#form-filter').on('submit', function (e) {
                e.preventDefault();
                $('#crawl-data').load(location.href + " #crawl-data");
                crawlData();
            });
        }


        $(document).ready(function () {
            crawlData();
            submitForm($("#form-delete"), "DELETE");
            filter();
        });
    </script>
@endpush
