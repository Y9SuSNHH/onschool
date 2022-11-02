@extends('layout.admin.master')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title">Message</h4>
                    <p class="text-muted font-14" id="message">
                    </p>
                    <div class="tab-content">
                        <div class="tab-pane show active" id="responsive-preview">
                            <form method="POST" id="form-edit">
                                @method('PUT')
                                @csrf
                                <div class="form-group mb-3">
                                    <label for="username">Username</label>
                                    <input type="text" id="username" name="username" class="form-control" value="">
                                </div>
                                <button type="submit" class="btn btn-info" id="btn-form-edit-submit">Sửa username</button>
                            </form>
                        </div> <!-- end preview-->

                    </div> <!-- end tab-content-->

                </div> <!-- end card body-->
            </div> <!-- end card -->
        </div>
    </div>
@endsection
@push('js')
{{--    <script src="{{asset('js/jquery.validate.js')}}"></script>--}}
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.  0/jquery.min.js"></script>
    <script type="text/javascript">
        function crawlData() {
            $.ajax({
                url: "{{route('api.admin.users')}}",
                type: 'GET',
                dataType: 'JSON',
                success: function (response) {
                    $.each(response.data, function (index, each) {
                        $("#form-edit").attr("action", "{{route('api.admin.users.update')}}/" + each.id);
                        $("#message").text(each.message);
                        $("#username").attr("value", each.username);
                        // $("#username").val(each.username);
                    })
                },
                error: function (response) {

                },
            });
        }

        function submitForm() {
            const form = $("#form-edit");
            form.on('submit', function (event) {
                event.preventDefault();
                const formData = new FormData(form[0]);
                $.ajax({
                    url: form.attr('action'),
                    type: 'POST',
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
            submitForm();
        });
    </script>
@endpush
