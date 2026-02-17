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
                        <h4 class="header" style="margin-bottom: 0;">Attendances</h4>

                        <div>
                            <table id="attendances-table" class="table table-striped table-bordered" style="margin-top: 0 !important; margin-bottom: 0 !important;" width="100%">
                                <thead>
                                    <tr>
                                        <th style="min-width: 50px;">#</th>
                                        <th style="min-width: 200px;">Device SN</th>
                                        <th style="min-width: 100px;">User ID</th>
                                        <th style="min-width: 150px;">IP address</th>
                                        <th style="min-width: 150px;">Attendance time</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>

                    <form method="post" action="{{route('resetAttendances')}}" style="margin-top: 1rem;">
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
            var table = $('#attendances-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('attendances') }}",
                scrollX: true,
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', searchable: false },
                    {data: 'device_sn', name: 'device_sn'},
                    {data: 'user_id', name: 'user_id'},
                    {data: 'ip_address', name: 'ip_address'},
                    {data: 'attendance_time', name: 'attendance_time', searchable: false },
                ],
                "drawCallback": function( settings ) {
                    document.getElementById('attendances-table_previous').querySelector('a').innerHTML = '<i class="fa fa-angle-left"></i>';
                    document.getElementById('attendances-table_next').querySelector('a').innerHTML = '<i class="fa fa-angle-right"></i>';

                    let previous = document.getElementById('attendances-table_previous');
                    let next = document.getElementById('attendances-table_next');

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
