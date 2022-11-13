@extends('layout.admin.master')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card" id="card">
                {{--                <div class="card-header">--}}
                {{--                    <div class="row">--}}
                {{--                        <div class="col-lg-6">--}}
                {{--                            <form class="form-inline" id="form-filter">--}}
                {{--                                <div class="form-group mb-2">--}}
                {{--                                    <label for="username" class="sr-only">Search</label>--}}
                {{--                                    <input type="search" class="form-control filter-change" id="filter-username"--}}
                {{--                                           name="username" placeholder="Search...">--}}
                {{--                                </div>--}}
                {{--                                <div class="form-group mx-sm-3 mb-2">--}}
                {{--                                    <select class="custom-select filter-change" name="deleted_type"--}}
                {{--                                            id="filter-deleted-type">--}}
                {{--                                        <option value="0" selected>Without deleted records</option>--}}
                {{--                                        <option value="All">With deleted records</option>--}}
                {{--                                        <option value="1">Only deleted records</option>--}}
                {{--                                    </select>--}}
                {{--                                </div>--}}
                {{--                            </form>--}}
                {{--                        </div>--}}
                {{--                    </div>--}}
                {{--                </div>--}}
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-4">
                            <a href="{{route("$role.$table.create")}}" class="btn btn-danger mb-2"><i
                                    class="mdi mdi-plus-circle mr-2"></i> Add Student</a>
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
                            <div class="col-sm-12">
                                <div class="tab-content">
                                    <div class="table-responsive">
                                        <table class="table mb-0" id="table-list">
                                            <thead class="thead-dark">
                                            <tr>
                                                <th>#</th>
                                                <th>Information</th>
                                                <th>Identification</th>
                                                <th>Contact</th>
                                                <th>User</th>
                                                <th>Created by</th>
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
                                <ul class="pagination pagination-rounded mb-0" id="pagination"></ul>
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
            $("#form-delete").attr('action', `{{route("api.$table.destroy")}}/${id}`)
        }

        function crawlData(page = 1, renderPage = true) {
            $.ajax({
                url: `{{route("api.$table.list")}}`,
                type: 'GET',
                dataType: 'JSON',
                data: {page},
                headers: {Authorization: `${getJWT().token_type} ` + getJWT().access_token},
                success: function (response) {
                    console.log(response.data)
                    response.data.data.forEach(function (each) {
                        let gender = each.gender ? 'Male' : 'Female';
                        let phone = `<a href="tel:${each.phone}">${each.phone}</a>`;
                        // let email = `<a href="mailto:${each.email}">${each.email}</a>` + '<br>' + formatToDate(each.email_verified_at);
                        let email = `<a href="mailto:${each.email}">${each.email}</a>`;
                        let information = each.firstname + `.${each.lastname} - ${gender} <br>` + each.address;
                        let contact = phone + `<br>` + email;
                        let user = each.username + `<br><a href="#">Forgotten password?</a>`;
                        let edit_route = `{{ route("$role.$table.edit")}}/` + each.id;
                        let edit = `<a href="${edit_route}" class="action-icon"><i class="mdi mdi-pencil"></i></a>`;
                        let destroy = `<a class="action-icon check-permission" data-toggle="modal" data-target="#warning-alert-delete" onclick="createActionFormDelete(${each.id})"><i class="mdi mdi-delete"></i></a>`;
                        let action = edit + destroy;
                        let created_by = `<a href="{{route("$role.$table.show")}}/${each.created_by.id}">${each.created_by.username}</a>` +
                            `<br>${each.created_by.role === 1 ? 'ADMIN' : 'USER'}`;
                        $('#table-list').append($('<tr>')
                            .append($('<td>').append(each.id))
                            .append($('<td>').append(information))
                            .append($('<td>').append(each.identification))
                            .append($('<td>').append(contact))
                            .append($('<td>').append(user))
                            .append($('<td>').append(created_by))
                            .append($('<td class="table-action">').append(action))
                        );
                        let payloadJwt = getJwtPayloadLocalStorage();
                        if(payloadJwt.role === 2){
                            $(".check-permission").prop('disabled',true);
                        }
                    });
                    renderPagination(response.data.last_page, page);
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

        $(document).ready(function () {
            crawlData();
            submitForm($("#form-delete"), "DELETE");
        });
    </script>
@endpush
