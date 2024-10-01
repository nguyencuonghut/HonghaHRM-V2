@section('title')
{{ 'Tất cả vị trí' }}
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
          <h1 class="m-0">Tất cả vị trí</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Trang chủ</a></li>
            <li class="breadcrumb-item active">Vị trí</li>
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
                <a href="{{ route('positions.create') }}" class="btn btn-success"><i class="fas fa-plus"></i> Thêm</a>

                <button type="button" class="btn btn-success float-right" data-toggle="modal" data-target="#import_positions">
                  <i class="fas fa-file-excel"></i> Import
                </button>
                <table id="positions-table" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>STT</th>
                    <th>Tên</th>
                    <th>Phòng/ban</th>
                    <th>Bộ phận</th>
                    <th>Lương bảo hiểm</th>
                    <th>Lương vị trí</th>
                    <th>Lương năng lực</th>
                    <th>Phụ cấp vị trí</th>
                    <th>TC tuyển dụng</th>
                    <th>Thao tác</th>
                  </tr>
                  </thead>
                </table>

                <!-- modal -->
                <form class="form-horizontal" method="post" action="{{ route('positions.import') }}" enctype="multipart/form-data" name="import-positions" id="import-positions">
                    {{ csrf_field() }}
                    <div class="modal fade" id="import_positions">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4>Import bộ phận</h4>
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
    // Add the following code if you want the name of the file appear on select
    $(".custom-file-input").on("change", function() {
        var fileName = $(this).val().split("\\").pop();
        $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
    });

    $(function () {
      $("#positions-table").DataTable({
        "responsive": true, "lengthChange": false, "autoWidth": false,
        buttons: [
            {
                extend: 'copy',
                footer: true,
                exportOptions: {
                    columns: [0,1,2,3,4,5,6,7]
                }
            },
            {
                extend: 'csv',
                footer: true,
                exportOptions: {
                    columns: [0,1,2,3,4,5,6,7]
                }

            },
            {
                extend: 'excel',
                footer: true,
                exportOptions: {
                    columns: [0,1,2,3,4,5,6,7]
                }
            },
            {
                extend: 'pdf',
                footer: true,
                exportOptions: {
                    columns: [0,1,2,3,4,5,6,7]
                }
            },
            {
                extend: 'print',
                footer: true,
                exportOptions: {
                    columns: [0,1,2,3,4,5,6,7]
                }
            },
            {
                extend: 'colvis',
                footer: true,
                exportOptions: {
                    columns: [0,1,2,3,4,5,6,7]
                }
            }
        ],
        dom: 'Blfrtip',
        ajax: ' {!! route('positions.data') !!}',
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex'},
            {data: 'name', name: 'name'},
            {data: 'department', name: 'department'},
            {data: 'division', name: 'division'},
            {data: 'insurance_salary', name: 'insurance_salary'},
            {data: 'position_salary', name: 'position_salary'},
            {data: 'max_capacity_salary', name: 'max_capacity_salary'},
            {data: 'position_allowance', name: 'position_allowance'},
            {data: 'recruitment_standard_file', name: 'recruitment_standard_file'},
            {data: 'actions', name: 'actions', orderable: false, searchable: false},
       ]
      }).buttons().container().appendTo('#positions-table_wrapper .col-md-6:eq(0)');
    });
  </script>
@endpush
