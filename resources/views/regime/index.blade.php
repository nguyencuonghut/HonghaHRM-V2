@section('title')
{{ 'Tất cả chế độ BH' }}
@endsection

@extends('layouts.base')
@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Tất cả chế độ BH</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Trang chủ</a></li>
            <li class="breadcrumb-item active">Chế độ BH</li>
          </ol>
        </div><!-- /.col -->
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>
  <!-- /.content-header -->

  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
      <!-- Small boxes (Stat box) -->
      <div class="row">
        <div class="col-12">
            <div class="card">
              <!-- /.card-header -->
              <div class="card-body">
                <table id="regimes-table" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>STT</th>
                    <th>Mã</th>
                    <th>Nhân viên</th>
                    <th>Phòng ban</th>
                    <th>Số BHXH</th>
                    <th>Ngày bắt đầu nghỉ</th>
                    <th>Ngày kết thúc nghỉ</th>
                    <th>Đợt thanh toán</th>
                    <th>Số tiền được thanh toán</th>
                    <th>Chế độ</th>
                    <th>Trạng thái</th>
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
<style type="text/css">
    .dataTables_wrapper .dt-buttons {
    margin-bottom: -3em
  }
</style>


<script>
    $(function () {
      $("#regimes-table").DataTable({
        "responsive": true, "lengthChange": false, "autoWidth": false,
        buttons: [
            {
                extend: 'copy',
                footer: true,
                exportOptions: {
                    columns: [0,1,2,3,4,5,6,7,8,9,10]
                }
            },
            {
                extend: 'csv',
                footer: true,
                exportOptions: {
                    columns: [0,1,2,3,4,5,6,7,8,9,10]
                }

            },
            {
                extend: 'excel',
                footer: true,
                exportOptions: {
                    columns: [0,1,2,3,4,5,6,7,8,9,10]
                }
            },
            {
                extend: 'pdf',
                footer: true,
                exportOptions: {
                    columns: [0,1,2,3,4,5,6,7,8,9,10]
                }
            },
            {
                extend: 'print',
                footer: true,
                exportOptions: {
                    columns: [0,1,2,3,4,5,6,7,8,9,10]
                }
            },
            {
                extend: 'colvis',
                footer: true,
                exportOptions: {
                    columns: [0,1,2,3,4,5,6,7,8,9,10]
                }
            }
        ],
        dom: 'Blfrtip',
        ajax: ' {!! route('regimes.data') !!}',
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex'},
            {data: 'employee_code', name: 'employee_code'},
            {data: 'employee_name', name: 'employee_name'},
            {data: 'employee_department', name: 'employee_department'},
            {data: 'employee_bhxh', name: 'employee_bhxh'},
            {data: 'off_start_date', name: 'off_start_date'},
            {data: 'off_end_date', name: 'off_end_date'},
            {data: 'payment_period', name: 'payment_period'},
            {data: 'payment_amount', name: 'payment_amount'},
            {data: 'regime_type', name: 'regime_type'},
            {data: 'status', name: 'status'},
       ]
      }).buttons().container().appendTo('#regimes-table_wrapper .col-md-6:eq(0)');
    });
  </script>
@endpush
