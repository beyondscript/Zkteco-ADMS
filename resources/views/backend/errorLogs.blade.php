@extends('layouts.app')

@section('style')
    <style>
        .dataTables_length,
        .dataTables_filter{
            margin-top: 0.5rem !important;
        }

        .dataTables_length select{
            width: 65px !important;
        }

        .dataTables_processing.card{
            color: #0D6EFD;
            border-color: red;
        }
    </style>
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    @include('backend.partials.headMenu')
                </div>

                <div class="card-body">
                    @include('backend.partials.navMenu')

                    <hr>

                    <div class="manage_supervisors">
                        <h4 class="header" style="margin-bottom: 0;">Error logs</h4>

                        <div>
                            <table id="error-logs-table" class="table table-striped table-bordered" style="margin-top: 0 !important; margin-bottom: 0 !important;" width="100%">
                                <thead>
                                    <tr>
                                        <th style="min-width: 50px;">#</th>
                                        <th style="min-width: 200px;">Log</th>
                                        <th style="min-width: 150px;">IP address</th>
                                        <th style="min-width: 150px;">Connection time</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>

                    <form method="post" action="{{route('resetErrorLogs')}}" style="margin-top: 1rem;">
                        @csrf
                        @method('delete')

                        <button type="submit" class="btn btn-danger">Reset</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
    <script type="text/javascript">
        $(function(){
            var table = $('#error-logs-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('errorLogs') }}",
                scrollX: true,
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', searchable: false },
                    {data: 'log', name: 'log'},
                    {data: 'ip_address', name: 'ip_address'},
                    {data: 'connection_time', name: 'connection_time', searchable: false },
                ],
                "drawCallback": function( settings ) {
                    document.getElementById('error-logs-table_previous').querySelector('a').innerHTML = '<i class="fa fa-angle-left"></i>';
                    document.getElementById('error-logs-table_next').querySelector('a').innerHTML = '<i class="fa fa-angle-right"></i>';

                    let previous = document.getElementById('error-logs-table_previous');
                    let next = document.getElementById('error-logs-table_next');

                    if(previous.classList.contains('disabled')){
                        previous.style.cursor = 'not-allowed';
                    }
                    else{
                        previous.style.cursor = 'pointer';
                    }

                    if(next.classList.contains('disabled')){
                        next.style.cursor = 'not-allowed';
                    }
                    else{
                        next.style.cursor = 'pointer';
                    }
                }
            });
        });
    </script>
@endsection
