@extends('layout.admin.master')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title" id="username"></h4>
                    <div class="tab-content">
                        <div class="tab-pane show active" id="input-types-preview">
                            <div class="row">
                                <div class="col-lg-6">
                                    <form method="POST">
                                        @csrf
                                        @method('PUT')
                                    </form>
                                </div>
                            </div>
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
                url: `{{route('api.admin.users.each')}}/${id}`,
                type: 'GET',
                dataType: 'JSON',
                success: function (response) {
                    console.log(response.data);
                    let each = response.data;
                    $("#username").html(each.username);
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
                    type: `${type}`,
                    dataType: 'JSON',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function (response) {
                        console.log(response);
                    },
                    error: function (response) {
                        console.log(response);
                    },
                });
            });
        }

        $(document).ready(function () {
            crawlData();
            let form = $("#form-edit");
            submitForm(form, "POST");
        });
    </script>
@endpush
