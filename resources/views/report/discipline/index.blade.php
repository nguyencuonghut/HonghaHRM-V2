@section('title')
{{ 'Báo cáo kỷ luật' }}
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
          <h1 class="m-0">Báo cáo kỷ luật</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Trang chủ</a></li>
            <li class="breadcrumb-item active">Báo cáo kỷ luật</li>
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
                <table id="reports-table" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>STT</th>
                    <th>Mã NV</th>
                    <th>Tên</th>
                    <th>Chức danh</th>
                    <th>Bộ phận</th>
                    <th>Phòng/ban</th>
                    <th>Số</th>
                    <th>Ngày</th>
                    <th>Nội dung</th>
                    <th>Ghi chú</th>
                    <th>Tiền phạt</th>
                  </tr>
                  </thead>
                  <tfoot>
                    <tr>
                        <th colspan="10">Tổng phạt</th>
                        <th id="total_money"></th>
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
      $("#reports-table").DataTable({
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
        ajax: ' {!! route('discipline_reports.data') !!}',
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex'},
            {data: 'employee_code', name: 'employee_code'},
            {data: 'employee_name', name: 'employee_name'},
            {data: 'position', name: 'position'},
            {data: 'division', name: 'division'},
            {data: 'department', name: 'department'},
            {data: 'code', name: 'code'},
            {data: 'sign_date', name: 'sign_date'},
            {data: 'content', name: 'content'},
            {data: 'note', name: 'note'},
            {data: 'money', name: 'money'},
       ],
       drawCallback:function(settings) {
            var api = this.api();
            var intVal = function(i) {
                return typeof i === 'string' ?
                i.replace(/[\,]/g, '') * 1:
                typeof i === 'number' ?
                i : 0;
            };

            var total_money = api
                .column( 10, {search:'applied'} )
                .data()
                .reduce(function(a,b) {
                    return intVal(a) + intVal(b);
                }, 0);
            $('#total_money').html(total_money.toLocaleString(
                undefined, // leave undefined to use the visitor's browser
                            // locale or a string like 'en-US' to override it.
                { minimumFractionDigits: 0 }
            ));
        }
      });
    });
  </script>
@endpush
