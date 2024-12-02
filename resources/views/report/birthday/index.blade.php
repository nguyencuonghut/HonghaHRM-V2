@section('title')
{{ 'Báo cáo sinh nhật' }}
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
          <h1 class="m-0">Báo cáo sinh nhật</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Trang chủ</a></li>
            <li class="breadcrumb-item active">Sinh nhật</li>
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
                    <div class="control-group">
                        <label class="control-label">Lọc theo tháng</label>
                        <div class="controls">
                            <select name="month" id="month" data-placeholder="Chọn" class="form-control select2" style="width: 20%;">
                                <option value="1" @if(1 == Carbon\Carbon::now()->month) selected="selected" @endif>1</option>
                                <option value="2" @if(2 == Carbon\Carbon::now()->month) selected="selected" @endif>2</option>
                                <option value="3" @if(3 == Carbon\Carbon::now()->month) selected="selected" @endif>3</option>
                                <option value="4" @if(4 == Carbon\Carbon::now()->month) selected="selected" @endif>4</option>
                                <option value="5" @if(5 == Carbon\Carbon::now()->month) selected="selected" @endif>5</option>
                                <option value="6" @if(6 == Carbon\Carbon::now()->month) selected="selected" @endif>6</option>
                                <option value="7" @if(7 == Carbon\Carbon::now()->month) selected="selected" @endif>7</option>
                                <option value="8" @if(8 == Carbon\Carbon::now()->month) selected="selected" @endif>8</option>
                                <option value="9" @if(9 == Carbon\Carbon::now()->month) selected="selected" @endif>9</option>
                                <option value="10" @if(10 == Carbon\Carbon::now()->month) selected="selected" @endif>10</option>
                                <option value="11" @if(11== Carbon\Carbon::now()->month) selected="selected" @endif>11</option>
                                <option value="12" @if(12 == Carbon\Carbon::now()->month) selected="selected" @endif>12</option>
                            </select>
                        </div>
                    </div>
                <table id="reports-table" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>STT</th>
                    <th>Mã NV</th>
                    <th>Tên</th>
                    <th>Chức danh</th>
                    <th>Phòng/ban</th>
                    <th>Giới tính</th>
                    <th>Ngày sinh</th>
                    <th>Ngày ký HĐ chính thức</th>
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
        //Initialize Select2 Elements
        $('.select2').select2({
        theme: 'bootstrap4'
        })

      // Datatables
      var table = $('#reports-table').DataTable({
        "responsive": true, "lengthChange": false, "autoWidth": false,
        processing: true,
        serverSide: true,
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
        ajax: {
          url: "{{ route('birthday_reports.index') }}",
          data: function (d) {
                d.month = $('#month').val(),
                d.search = $('input[type="search"]').val()
            }
        },
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
            {data: 'employee_code', name: 'employee_code'},
            {data: 'employee_name', name: 'employee_name'},
            {data: 'position', name: 'position'},
            {data: 'department', name: 'department'},
            {data: 'gender', name: 'gender'},
            {data: 'date_of_birth', name: 'date_of_birth'},
            {data: 'formal_contract_start_date', name: 'formal_contract_start_date'},
        ]
        });

        $('#month').change(function(){
            table.draw();
        });
    });
  </script>
@endpush
