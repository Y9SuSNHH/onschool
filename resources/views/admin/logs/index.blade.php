@extends('layout.admin.master')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div id="crawl-data">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="tab-content">
                                    <div class="table-responsive">
                                        <table class="table mb-0" id="table-list">
                                            <thead class="thead-dark">
                                            <tr>
                                                <th>Datetime</th>
                                                <th>Type</th>
                                                <th>Message</th>
                                            </tr>
                                            </thead>
                                            <tbody></tbody>
                                        </table>
                                    </div>
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
            $.ajax({
                url: `{{route("api.$table.list")}}`,
                type: 'GET',
                dataType: 'JSON',
                headers: {Authorization: `${getJWT().token_type} ` + getJWT().access_token},
                success: function (response) {
                    let logs = response.data.file.split('\n');
                    for (let i = 0; i < logs.length - 1; i++) {
                        //split time
                        let time = logs[i].split('local.')[0];
                        let status_message = logs[i].split('local.')[1];
                        //split status
                        let status = status_message.split(':')[0];
                        //split message
                        let message = status_message.split(':')[1];

                        let btn_status = `<button type="button" class="btn btn-${status.toLowerCase()}">${status}</button>`;

                        $('#table-list').append($(`<tr>`)
                            .append($('<td>').append(time))
                            .append($('<td>').append(btn_status))
                            .append($('<td>').append(message.substring(0, 120)))
                        );
                    }
                },
                error: function (response) {
                    console.log(response);
                },
            });
        }

        $(document).ready(function () {
            crawlData();
        });
    </script>
@endpush
