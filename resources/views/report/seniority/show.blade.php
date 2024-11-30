@section('title')
{{ 'Báo cáo thâm niên chi tiết' }}
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
          <h1 class="m-0">Báo cáo thâm niên 5 năm</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('seniority_reports.index') }}">Tất cả</a></li>
            <li class="breadcrumb-item active">5 năm</li>
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
                <form class="form-horizontal" method="post" action="{{route('seniority_reports.by_year')}}" name="filter_by_year" id="filter_by_year" novalidate="novalidate">
                    {{ csrf_field() }}
                    <div class="row">
                        <div class="col-3">
                            <div class="input-group">
                                <select name="year" id="year" data-placeholder="Chọn" class="form-control select2" style="width: 20%;">
                                    <option value="5" selected="selected">5</option>
                                    <option value="10">10</option>
                                    <option value="15">15</option>
                                    <option value="20">20</option>
                                    <option value="25">25</option>
                                    <option value="30">30</option>
                                </select>
                                &nbsp;
                                <span class="input-group-btn">
                                    <input type="submit" value="Tìm" class="btn btn-success">
                                </span>
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
                    <th>Mã</th>
                    <th>Nhân viên</th>
                    <th>Phòng/ban</th>
                    <th>Ngày vào</th>
                    <th>Thâm niên (năm)</th>
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
      $("#reports-table").DataTable({
        "responsive": true, "lengthChange": false, "autoWidth": false,
        buttons: [
            {
                extend: 'copy',
                footer: true,
                exportOptions: {
                    columns: [0,1,2,3,4]
                }
            },
            {
                extend: 'csv',
                footer: true,
                exportOptions: {
                    columns: [0,1,2,3,4]
                }

            },
            {
                extend: 'excel',
                footer: true,
                exportOptions: {
                    columns: [0,1,2,3,4]
                }
            },
            {
                extend: 'pdf',
                footer: true,
                exportOptions: {
                    columns: [0,1,2,3,4]
                }
            },
            {
                extend: 'print',
                footer: true,
                exportOptions: {
                    columns: [0,1,2,3,4]
                }
            },
            {
                extend: 'colvis',
                footer: true,
                exportOptions: {
                    columns: [0,1,2,3,4]
                }
            }
        ],
        dom: 'Blfrtip',
        ajax: ' {!! route('seniority_reports.byYearData', ['year' => $year]) !!}',
        columns: [
            {data: 'id', name: 'id'},
            {data: 'code', name: 'code'},
            {data: 'name', name: 'name'},
            {data: 'department', name: 'department', orderable: false, searchable: false},
            {data: 'join_date', name: 'join_date'},
            {data: 'seniority', name: 'seniority', orderable: false, searchable: false},
       ]
      }).buttons().container().appendTo('#reports-table_wrapper .col-md-6:eq(0)');
    });
  </script>
@endpush
