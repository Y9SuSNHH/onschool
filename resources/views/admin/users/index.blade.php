@extends('layout.admin.master')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="tab-content">
                        <div class="tab-pane show active" id="responsive-preview">
                            <div class="table-responsive">
                                <table class="table table-centered mb-0" id="table-data">
                                    <thead class="thead-dark">
                                    <tr>
                                        <th>#</th>
                                        <th>Username</th>
                                        <th>First Name</th>
                                        <th>Last Name</th>
                                        <th>Gender</th>
                                        <th>Phone</th>
                                        <th>Email</th>
                                        <th>Active</th>
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
@endsection
@push('js')
    <script type="text/javascript">
        function crawlData() {
            $.ajax({
                url: `{{route('api.admin.users.list')}}`,
                type: 'GET',
                dataType: 'JSON',
                data: {page: {{ request()->get('page') ?? 1 }}},
                success: function (response) {
                    console.log(response.data.data);
                    response.data.data.forEach(function (each) {
                        let email = each.email + '<br>' + formatToDate(each.email_verified_at);
                        let active = each.active ? '<i class="mdi mdi-check-circle-outline text-success"></i>' : '<i class=" mdi mdi-close-circle-outline text-danger"></i>';
                        let edit_route = `{{ route("$role.$table.edit")}}/` + each.id;
                        let edit = `<a href="${edit_route}" class="action-icon"><i class="mdi mdi-pencil"></i></a>`;
                        let destroy_route = `{{ route("api.$role.$table.destroy")}}/` + each.id;
                        let destroy = `<a href="${destroy_route}" class="action-icon"><i class="mdi mdi-delete"></i></a>`;
                        let action = edit + destroy;
                        // action += '<i class="mdi mdi-delete"></i>';
                        $('#table-data').append($('<tr>')
                            .append($('<td>').append(each.id))
                            .append($('<td>').append(each.username))
                            .append($('<td>').append(each.firstname))
                            .append($('<td>').append(each.lastname))
                            .append($('<td>').append(each.gender ? 'Male' : 'Female'))
                            .append($('<td>').append(each.phone))
                            .append($('<td>').append(email))
                            .append($('<td class="text-center">').append(active))
                            .append($('<td class="table-action">').append(action))
                        );
                    });
                    renderPagination(response.data.pagination);
                },
                error: function () {
                    notifyError("some error");
                },
            });
        }

        function changePage(page) {
            let urlParams = new URLSearchParams(window.location.search);
            urlParams.set('page', page);
            window.location.search = urlParams;
        }

        $(document).ready(function () {
            crawlData();
            submitForm();
        });
    </script>
@endpush
