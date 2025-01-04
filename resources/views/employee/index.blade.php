@section('title')
{{ 'Nhân sự' }}
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
          <h1 class="m-0">Tất cả nhân sự</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Trang chủ</a></li>
            <li class="breadcrumb-item active">Nhân sự</li>
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
                <div class="btn-group">
                    @can('create', App\Models\Employee::class)
                        <a href="{{ route('employees.create') }}" class="btn btn-success"><i class="fas fa-plus"></i> Thêm</a>
                    @endcan
                    @can('import', App\Models\Employee::class)
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#import_employees">
                            <i class="fas fa-upload"></i> Import
                        </button>
                    @endcan
                </div>

                <div class="btn-group float-right">
                    &nbsp;
                    <a href="{{route('employees.index')}}" class="btn btn-primary {{Route::is('employees.index') ? 'active' : ''}}">
                        <i class="fas fa-bars"></i>
                    </a>
                    <a href="{{ route('employees.gallery') }}" class="btn btn-secondary {{Route::is('employees.gallery') ? 'active' : ''}}">
                        <i class="fas fa-th"></i>
                    </a>
                    <a href="{{route('employees.export')}}" class="btn btn-success {{Route::is('employees.export') ? 'active' : ''}}">
                        <i class="fas fa-download"></i>
                    </a>
                </div>
                @cannot('create', App\Models\Employee::class)
                <div class="row mt-4"></div>
                @endcannot
                <table id="employees-table" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>STT</th>
                    <th>Mã</th>
                    <th>Họ tên</th>
                    <th>Phòng/ban</th>
                    <th>Email</th>
                    <th>Điện thoại</th>
                    <th>Thường trú</th>
                    <th>CCCD</th>
                    <th>Trạng thái</th>
                    <th style="width: 12%;">Thao tác</th>
                  </tr>
                  </thead>
                </table>


                <!-- modal -->
                <form class="form-horizontal" method="post" action="{{ route('employees.import') }}" enctype="multipart/form-data" name="import-employees" id="import-employees">
                    {{ csrf_field() }}
                    <div class="modal fade" id="import_employees">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4>Import nhân sự</h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="form-group mb-4">
                                        <div class="custom-file text-left">
                                            <input type="file" name="file" class="custom-file-input" id="customFile" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel">
                                            <label class="custom-file-label" for="customFile">Chọn file</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer justify-content-between">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Đóng</button>
                                <button type="submit" class="btn btn-primary">Import</button>
                                </div>
                            </div>
                            <!-- /.modal-content -->
                        </div>
                    </div>
                  </form>
                  <!-- /.modal -->
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
        // Add the following code if you want the name of the file appear on select
        $(".custom-file-input").on("change", function() {
            var fileName = $(this).val().split("\\").pop();
            $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
        });

      $("#employees-table").DataTable({
        "responsive": true, "lengthChange": false, "autoWidth": false,
        buttons: [
            {
                extend: 'copy',
                footer: true,
                exportOptions: {
                    columns: [0,1,2,3,4,5,6,7,8]
                }
            },
            {
                extend: 'csv',
                footer: true,
                exportOptions: {
                    columns: [0,1,2,3,4,5,6,7,8]
                }

            },
            {
                extend: 'excel',
                footer: true,
                exportOptions: {
                    columns: [0,1,2,3,4,5,6,7,8]
                }
            },
            {
                extend: 'pdf',
                footer: true,
                exportOptions: {
                    columns: [0,1,2,3,4,5,6,7,8]
                }
            },
            {
                extend: 'print',
                footer: true,
                exportOptions: {
                    columns: [0,1,2,3,4,5,6,7,8]
                }
            },
            {
                extend: 'colvis',
                footer: true,
                exportOptions: {
                    columns: [0,1,2,3,4,5,6,7,8]
                }
            }
        ],
        dom: 'Blfrtip',
        ajax: ' {!! route('employees.data') !!}',
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex'},
            {data: 'code', name: 'code'},
            {data: 'name', name: 'name'},
            {data: 'department', name: 'department'},
            {data: 'email', name: 'email'},
            {data: 'phone', name: 'phone'},
            {data: 'addr', name: 'addr'},
            {data: 'cccd', name: 'cccd'},
            {data: 'status', name: 'status'},
            {data: 'actions', name: 'actions', orderable: false, searchable: false},
       ]
      }).buttons().container().appendTo('#employees-table_wrapper .col-md-6:eq(0)');
    });
  </script>
@endpush
