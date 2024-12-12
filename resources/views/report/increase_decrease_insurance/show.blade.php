@section('title')
{{ 'Báo cáo tăng giảm BHXH' }}
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
          <h1 class="m-0">Báo tăng giảm BHXH tháng {{Carbon\Carbon::now()->month}} - {{Carbon\Carbon::now()->year}}</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Trang chủ</a></li>
            <li class="breadcrumb-item active">Tháng {{Carbon\Carbon::now()->month}} - {{Carbon\Carbon::now()->year}}</li>
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
                <form class="form-horizontal" method="post" action="{{route('increase_decrease_insurance_reports.by_month')}}" name="filter_by_month" id="filter_by_month" novalidate="novalidate">
                    {{ csrf_field() }}
                    <div class="row">
                        <div class="col-4">
                            <div class="control-group">
                                <label class="control-label">Chọn tháng</label>
                                <div class="input-group date" id="month_of_year" data-target-input="nearest">
                                    <input type="text" id="month_of_year" name="month_of_year" class="form-control datetimepicker-input" value="{{$month . '/' . $year}}" data-target="#month_of_year"/>
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
            <div class="card card-secondary">
                <div class="card-header">
                    Phát sinh tăng
                </div>
              <div class="card-body">
                <a href="{{route('increase_decrease_insurance_reports.exportIncBhxh', ['month' => $month, 'year' => $year])}}" class="btn btn-sm btn-primary"><i class="fas fa-cloud-download-alt"></i></a>

                <table id="increase-insurance-reports-table" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>STT</th>
                    <th>Mã</th>
                    <th>Họ tên</th>
                    <th>Vị trí</th>
                    <th>Ngày phát sinh tăng</th>
                    <th>Tháng báo tăng</th>
                    <th>Lương BHXH</th>
                    <th>Tiền tăng BHXH</th>
                    <th>Tiền tăng BHTN</th>
                  </tr>
                  </thead><tfoot>
                    <tr>
                        <th colspan="7">Tổng tăng</th>
                        <th id="total_inc_bhxh"></th>
                        <th id="total_inc_bhtn"></th>
                    </tr>
                  </tfoot>
                </table>
              </div>
            </div>
        </div>
      </div>
      <!-- /.row (main row) -->
      <div class="row mt-4">
        <div class="col-12">
            <div class="card card-secondary">
                <div class="card-header">
                    Phát sinh giảm
                </div>
              <div class="card-body">
                <a href="{{route('increase_decrease_insurance_reports.exportDecBhxh', ['month' => $month, 'year' => $year])}}" class="btn btn-sm btn-primary"><i class="fas fa-cloud-download-alt"></i></a>

                <table id="decrease-insurance-reports-table" class="table table-bordered table-striped">
                    <thead>
                    <tr>
                      <th>STT</th>
                      <th>Mã</th>
                      <th>Họ tên</th>
                      <th>Vị trí</th>
                      <th>Ngày phát sinh giảm</th>
                      <th>Tháng báo giảm</th>
                      <th>Lương BHXH</th>
                      <th>Tiền giảm BHXH</th>
                      <th>Tiền giảm BHTN</th>
                    </tr>
                    </thead><tfoot>
                      <tr>
                          <th colspan="7">Tổng giảm</th>
                          <th id="total_dec_bhxh"></th>
                          <th id="total_dec_bhtn"></th>
                      </tr>
                    </tfoot>
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

      // Datatables
      $('#increase-insurance-reports-table').DataTable({
        "responsive": true, "lengthChange": false, "autoWidth": false,
        processing: true,
        serverSide: true,
        // buttons: [
        //     {
        //         extend: 'copy',
        //         footer: true,
        //         exportOptions: {
        //             columns: [0,1,2,3,4,5,6,7]
        //         }
        //     },
        //     {
        //         extend: 'csv',
        //         footer: true,
        //         exportOptions: {
        //             columns: [0,1,2,3,4,5,6,7]
        //         }

        //     },
        //     {
        //         extend: 'excel',
        //         footer: true,
        //         exportOptions: {
        //             columns: [0,1,2,3,4,5,6,7]
        //         }
        //     },
        //     {
        //         extend: 'pdf',
        //         footer: true,
        //         exportOptions: {
        //             columns: [0,1,2,3,4,5,6,7]
        //         }
        //     },
        //     {
        //         extend: 'print',
        //         footer: true,
        //         exportOptions: {
        //             columns: [0,1,2,3,4,5,6,7]
        //         }
        //     },
        //     {
        //         extend: 'colvis',
        //         footer: true,
        //         exportOptions: {
        //             columns: [0,1,2,3,4,5,6,7]
        //         }
        //     }
        // ],
        //dom: 'Blfrtip',
        ajax: ' {!! route('increase_decrease_insurance_reports.increaseByMonthData', ['month' => $month, 'year' => $year]) !!}',
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
            {data: 'code', name: 'code'},
            {data: 'name', name: 'name'},
            {data: 'position', name: 'position'},
            {data: 'start_date', name: 'start_date'},
            {data: 'confirmed_month', name: 'confirmed_month'},
            {data: 'insurance_salary', name: 'insurance_salary'},
            {data: 'bhxh_increase', name: 'bhxh_increase'},
            {data: 'bhtn_increase', name: 'bhtn_increase'},
        ],
        drawCallback:function(settings) {
            var api = this.api();
            var intVal = function(i) {
                return typeof i === 'string' ?
                i.replace(/[\,]/g, '') * 1:
                typeof i === 'number' ?
                i : 0;
            };

            var total_inc_bhxh = api
                .column(7)
                .data()
                .reduce(function(a,b) {
                    return intVal(a) + intVal(b);
                }, 0);
            $('#total_inc_bhxh').html(total_inc_bhxh.toLocaleString(
                undefined, // leave undefined to use the visitor's browser
                            // locale or a string like 'en-US' to override it.
                { minimumFractionDigits: 0 }
            ));

            var total_inc_bhtn = api
                .column(8)
                .data()
                .reduce(function(a,b) {
                    return intVal(a) + intVal(b);
                }, 0);
            $('#total_inc_bhtn').html(total_inc_bhtn.toLocaleString(
                undefined, // leave undefined to use the visitor's browser
                            // locale or a string like 'en-US' to override it.
                { minimumFractionDigits: 0 }
            ));
        }
        });


      // Datatables
      $('#decrease-insurance-reports-table').DataTable({
        "responsive": true, "lengthChange": false, "autoWidth": false,
        processing: true,
        serverSide: true,
        // buttons: [
        //     {
        //         extend: 'copy',
        //         footer: true,
        //         exportOptions: {
        //             columns: [0,1,2,3,4,5,6,7]
        //         }
        //     },
        //     {
        //         extend: 'csv',
        //         footer: true,
        //         exportOptions: {
        //             columns: [0,1,2,3,4,5,6,7]
        //         }

        //     },
        //     {
        //         extend: 'excel',
        //         footer: true,
        //         exportOptions: {
        //             columns: [0,1,2,3,4,5,6,7]
        //         }
        //     },
        //     {
        //         extend: 'pdf',
        //         footer: true,
        //         exportOptions: {
        //             columns: [0,1,2,3,4,5,6,7]
        //         }
        //     },
        //     {
        //         extend: 'print',
        //         footer: true,
        //         exportOptions: {
        //             columns: [0,1,2,3,4,5,6,7]
        //         }
        //     },
        //     {
        //         extend: 'colvis',
        //         footer: true,
        //         exportOptions: {
        //             columns: [0,1,2,3,4,5,6,7]
        //         }
        //     }
        // ],
        //dom: 'Blfrtip',
        ajax: ' {!! route('increase_decrease_insurance_reports.decreaseByMonthData', ['month' => $month, 'year' => $year]) !!}',
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
            {data: 'code', name: 'code'},
            {data: 'name', name: 'name'},
            {data: 'position', name: 'position'},
            {data: 'start_date', name: 'start_date'},
            {data: 'confirmed_month', name: 'confirmed_month'},
            {data: 'insurance_salary', name: 'insurance_salary'},
            {data: 'bhxh_decrease', name: 'bhxh_decrease'},
            {data: 'bhtn_decrease', name: 'bhtn_decrease'},
        ],
        drawCallback:function(settings) {
            var api = this.api();
            var intVal = function(i) {
                return typeof i === 'string' ?
                i.replace(/[\,]/g, '') * 1:
                typeof i === 'number' ?
                i : 0;
            };

            var total_dec_bhxh = api
                .column(7)
                .data()
                .reduce(function(a,b) {
                    return intVal(a) + intVal(b);
                }, 0);
            $('#total_dec_bhxh').html(total_dec_bhxh.toLocaleString(
                undefined, // leave undefined to use the visitor's browser
                            // locale or a string like 'en-US' to override it.
                { minimumFractionDigits: 0 }
            ));

            var total_dec_bhtn = api
                .column(8)
                .data()
                .reduce(function(a,b) {
                    return intVal(a) + intVal(b);
                }, 0);
            $('#total_dec_bhtn').html(total_dec_bhtn.toLocaleString(
                undefined, // leave undefined to use the visitor's browser
                            // locale or a string like 'en-US' to override it.
                { minimumFractionDigits: 0 }
            ));
        }
        });
    });
  </script>
@endpush
