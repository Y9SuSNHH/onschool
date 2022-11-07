@extends('layout.admin.master')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card" id="card">
                <div class="card-header">
                    <a href="{{route("$role.$table.create")}}" class="btn btn-success">Create</a>
                </div>
                <div class="card-body">
                    <div class="card-body">
                        <div class="tab-content">
                            <div class="tab-pane show active" id="responsive-preview">
                                <div class="table-responsive">
                                    <table class="table table-centered mb-0" id="table-list">
                                        <thead class="thead-dark">
                                        <tr>
                                            <th>#</th>
                                            <th>Information</th>
                                            <th>Identification</th>
                                            <th>Contact</th>
                                            <th>User</th>
                                            <th>Action</th>
                                        </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
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
                        let destroy = `<a class="action-icon" data-toggle="modal" data-target="#warning-alert-delete" onclick="createActionFormDelete(${each.id})"><i class="mdi mdi-delete"></i></a>`;
                        let action = edit + destroy;
                        $('#table-list').append($('<tr>')
                            .append($('<td>').append(each.id))
                            .append($('<td>').append(information))
                            .append($('<td>').append(each.identification))
                            .append($('<td>').append(contact))
                            .append($('<td>').append(user))
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

        $(document).ready(function () {
            crawlData();
        });
    </script>
@endpush
