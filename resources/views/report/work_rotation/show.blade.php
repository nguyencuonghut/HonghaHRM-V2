@section('title')
{{ 'Báo cáo luân chuyển chi tiết' }}
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
          <h1 class="m-0">Báo cáo luân chuyển tháng {{Carbon\Carbon::now()->month}} năm {{Carbon\Carbon::now()->year}}</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('work_rotation_reports.index') }}">Tất cả</a></li>
            <li class="breadcrumb-item active">Tháng {{Carbon\Carbon::now()->month}} năm {{Carbon\Carbon::now()->year}}</li>
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
            <div class="col-12">
                <form class="form-horizontal" method="post" action="{{route('work_rotation_reports.by_month')}}" name="filter_by_month" id="filter_by_month" novalidate="novalidate">
                    {{ csrf_field() }}
                    <div class="row">
                        <div class="col-4">
                            <div class="control-group">
                                <label class="control-label">Chọn tháng</label>
                                <div class="input-group date" id="month_of_year" data-target-input="nearest">
                                    <input type="text" id="month_of_year" name="month_of_year" class="form-control datetimepicker-input" value="{{Carbon\Carbon::now()->month . '/' . Carbon\Carbon::now()->year}}" data-target="#month_of_year"/>
                                    <div class="input-group-append" data-target="#month_of_year" data-toggle="datetimepicker">
                                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="control-group">
                                <label class="control-label"></label>
                                <div class="controls mt-2">
                                    <input type="submit" value="Tìm" class="btn btn-success">
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
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
                    <th>Chức danh cũ</th>
                    <th>Bộ phận cũ</th>
                    <th>Phòng/ban cũ</th>
                    <th>Chức danh mới</th>
                    <th>Bộ phận mới</th>
                    <th>Phòng/ban mới</th>
                    <th>Ngày luân chuyển</th>
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
      //Date picker
      $('#month_of_year').datetimepicker({
          format: 'MM/YYYY',
          minViewMode: 'months',
          viewMode: 'months',
          pickTime: false
      });

      $("#reports-table").DataTable({
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
        ajax: ' {!! route('work_rotation_reports.byMonthData', ['month' => $month, 'year' => $year]) !!}',
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex'},
            {data: 'employee_code', name: 'employee_code'},
            {data: 'employee_name', name: 'employee_name'},
            {data: 'old_position', name: 'old_position'},
            {data: 'old_division', name: 'old_division'},
            {data: 'old_department', name: 'old_department'},
            {data: 'new_position', name: 'new_position'},
            {data: 'new_division', name: 'new_division'},
            {data: 'new_department', name: 'new_department'},
            {data: 'rotation_date', name: 'rotation_date'},
       ]
      }).buttons().container().appendTo('#reports-table_wrapper .col-md-6:eq(0)');
    });
  </script>
@endpush
