@section('title')
{{ 'Báo cáo nghỉ việc theo khoảng thời gian' }}
@endsection

@push('styles')
  <link rel="stylesheet" href="{{ asset('plugins/daterangepicker/daterangepicker.css') }}">
@endpush

@extends('layouts.base')
@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Báo cáo nghỉ việc theo khoảng thời gian</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('off_work_reports.index') }}">Tất cả</a></li>
            <li class="breadcrumb-item active">Khoảng thời gian</li>
          </ol>
        </div><!-- /.col -->
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>
  <!-- /.content-header -->

  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-4">
                <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text">
                        <i class="far fa-calendar-alt"></i>
                      </span>
                    </div>
                    <input type="text" class="form-control float-right daterange" id="daterange"  name="daterange">
                </div>
            </div>
        </div>
      <!-- Small boxes (Stat box) -->
      <div class="row mt-4">
        <div class="col-12">
            <div class="card">
              <!-- /.card-header -->
              <div class="card-body">
                <table id="reports-table" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>STT</th>
                    <th>Mã NV</th>
                    <th>Tên</th>
                    <th>Chức danh</th>
                    <th>Bộ phận</th>
                    <th>Phòng/ban</th>
                    <th>Ngày thử việc</th>
                    <th>Ngày chính thức</th>
                    <th>Ngày nghỉ việc</th>
                    <th>Lý do nghỉ</th>
                  </tr>
                  </thead>
                </table>
              </div>
            </div>
        </div>
      </div>
      <!-- /.row (main row) -->
    </div><!-- /.container-fluid -->
  </section>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->
@endsection

@push('scripts')
<!-- date-range-picker -->
<script src="{{asset('plugins/daterangepicker/daterangepicker.js')}}"></script>


<style type="text/css">
    .dataTables_wrapper .dt-buttons {
    margin-bottom: -3em
  }
</style>


<script>
    $(function () {
      var start_date = moment().subtract(1, 'M');
      var end_date = moment();

      $('.daterange').daterangepicker({
        locale: {
            format: 'DD/MM/YYYY'
        },
        startDate : start_date,
        endDate : end_date
      });

      var table = $("#reports-table").DataTable({
        "responsive": true, "lengthChange": false, "autoWidth": false,
        buttons: [
            {
                extend: 'copy',
                footer: true,
                exportOptions: {
                    columns: [0,1,2,3,4,5,6,7,8,9]
                }
            },
            {
                extend: 'csv',
                footer: true,
                exportOptions: {
                    columns: [0,1,2,3,4,5,6,7,8,9]
                }

            },
            {
                extend: 'excel',
                footer: true,
                exportOptions: {
                    columns: [0,1,2,3,4,5,6,7,8,9]
                }
            },
            {
                extend: 'pdf',
                footer: true,
                exportOptions: {
                    columns: [0,1,2,3,4,5,6,7,8,9]
                }
            },
            {
                extend: 'print',
                footer: true,
                exportOptions: {
                    columns: [0,1,2,3,4,5,6,7,8,9]
                }
            },
            {
                extend: 'colvis',
                footer: true,
                exportOptions: {
                    columns: [0,1,2,3,4,5,6,7,8,9]
                }
            }
        ],
        dom: 'Blfrtip',
        processing : true,
        serverSide : true,
        ajax: {
            url: "{{ route('off_work_reports.by_range') }}",
            data : function(d){
                d.from_date = $('.daterange').data('daterangepicker').startDate.format('YYYY-MM-DD');
                d.to_date = $('.daterange').data('daterangepicker').endDate.format('YYYY-MM-DD');
            }
        },
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex'},
            {data: 'employee_code', name: 'employee_code'},
            {data: 'employee_name', name: 'employee_name'},
            {data: 'position', name: 'position'},
            {data: 'division', name: 'division'},
            {data: 'department', name: 'department'},
            {data: 'probation_contract_date', name: 'probation_contract_date'},
            {data: 'formal_contract_date', name: 'formal_contract_date'},
            {data: 'end_date', name: 'end_date'},
            {data: 'off_reason', name: 'off_reason'},
       ]
      });

      $('.daterange').change(function(){
            table.draw();
        });
    });
  </script>
@endpush
