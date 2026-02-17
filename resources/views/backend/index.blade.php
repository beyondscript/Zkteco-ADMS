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
                        <h4 class="header" style="margin-bottom: 0;">Devices</h4>

                        <div>
                            <table id="devices-table" class="table table-striped table-bordered" style="margin-top: 0 !important; margin-bottom: 0 !important;" width="100%">
                                <thead>
                                    <tr>
                                        <th style="min-width: 50px;">#</th>
                                        <th style="min-width: 200px;">Serial number</th>
                                        <th style="min-width: 150px;">IP address</th>
                                        <th style="min-width: 150px;">Last online</th>
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
@endsection

@section('script')
    <script type="text/javascript">
        $(function(){
            var table = $('#devices-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('dashboard') }}",
                scrollX: true,
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', searchable: false },
                    {data: 'serial_number', name: 'serial_number'},
                    {data: 'ip_address', name: 'ip_address'},
                    {data: 'last_online', name: 'last_online', searchable: false },
                ],
                "drawCallback": function( settings ) {
                    document.getElementById('devices-table_previous').querySelector('a').innerHTML = '<i class="fa fa-angle-left"></i>';
                    document.getElementById('devices-table_next').querySelector('a').innerHTML = '<i class="fa fa-angle-right"></i>';

                    let previous = document.getElementById('devices-table_previous');
                    let next = document.getElementById('devices-table_next');

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
