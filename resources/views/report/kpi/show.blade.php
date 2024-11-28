@section('title')
{{ 'Báo cáo hiệu suất' }}
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
          <h1 class="m-0">Báo cáo hiệu suất năm {{Carbon\Carbon::now()->year}}</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Trang chủ</a></li>
            <li class="breadcrumb-item active">Báo cáo hiệu suất năm {{Carbon\Carbon::now()->year}}</li>
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
                <form class="form-horizontal" method="post" action="{{route('kpi_reports.by_year')}}" name="filter_by_year" id="filter_by_year" novalidate="novalidate">
                    {{ csrf_field() }}
                    <div class="row">
                        <div class="col-4">
                            <div class="control-group">
                                <label class="control-label">Chọn năm</label>
                                <div class="input-group date" id="year" data-target-input="nearest">
                                    <input type="text" id="year" name="year" class="form-control datetimepicker-input" value="{{Carbon\Carbon::now()->year}}" data-target="#year"/>
                                    <div class="input-group-append" data-target="#year" data-toggle="datetimepicker">
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
                    <th>Chức danh</th>
                    <th>Bộ phận</th>
                    <th>Phòng/ban</th>
                    <th>Năm</th>
                    <th>T1</th>
                    <th>T2</th>
                    <th>T3</th>
                    <th>T4</th>
                    <th>T5</th>
                    <th>T6</th>
                    <th>T7</th>
                    <th>T8</th>
                    <th>T9</th>
                    <th>T10</th>
                    <th>T11</th>
                    <th>T12</th>
                    <th>TB năm</th>
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
      $('#year').datetimepicker({
          format: 'YYYY',
          minViewMode: 'years',
          viewMode: 'years',
          pickTime: false
      });

      $("#reports-table").DataTable({
        "responsive": true, "lengthChange": false, "autoWidth": false,
        buttons: [
            {
                extend: 'copy',
                footer: true,
                exportOptions: {
                    columns: [0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19]
                }
            },
            {
                extend: 'csv',
                footer: true,
                exportOptions: {
                    columns: [0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19]
                }

            },
            {
                extend: 'excel',
                footer: true,
                exportOptions: {
                    columns: [0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19]
                }
            },
            {
                extend: 'pdf',
                footer: true,
                exportOptions: {
                    columns: [0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19]
                }
            },
            {
                extend: 'print',
                footer: true,
                exportOptions: {
                    columns: [0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19]
                }
            },
            {
                extend: 'colvis',
                footer: true,
                exportOptions: {
                    columns: [0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19]
                }
            }
        ],
        dom: 'Blfrtip',
        ajax: ' {!! route('kpi_reports.byYearData', ['year' => $year]) !!}',
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex'},
            {data: 'employee_code', name: 'employee_code'},
            {data: 'employee_name', name: 'employee_name'},
            {data: 'position', name: 'position'},
            {data: 'division', name: 'division'},
            {data: 'department', name: 'department'},
            {data: 'year', name: 'year'},
            {data: 'jan', name: 'jan'},
            {data: 'feb', name: 'feb'},
            {data: 'mar', name: 'mar'},
            {data: 'apr', name: 'apr'},
            {data: 'may', name: 'may'},
            {data: 'jun', name: 'jun'},
            {data: 'jul', name: 'jul'},
            {data: 'aug', name: 'aug'},
            {data: 'sep', name: 'sep'},
            {data: 'oct', name: 'oct'},
            {data: 'nov', name: 'nov'},
            {data: 'dec', name: 'dec'},
            {data: 'year_avarage', name: 'year_avarage'},
       ]
      }).buttons().container().appendTo('#reports-table_wrapper .col-md-6:eq(0)');
    });
  </script>
@endpush
