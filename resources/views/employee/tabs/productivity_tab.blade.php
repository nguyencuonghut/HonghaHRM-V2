<!-- Productivity Tab -->
<div class="tab-pane" id="tab-productivity">
    <!-- KPI table -->
    <div class="card card-secondary">
        <div class="card-header">
            KPI
        </div>
        <!-- /.card-header -->
        <div class="card-body">
            @can('create', App\Models\Kpi::class)
            <a href="#create_kpi{{' . $employee->id . '}}" class="btn btn-success mb-2" data-toggle="modal" data-target="#create_kpi{{$employee->id}}"><i class="fas fa-plus"></i></a>
            @endcan
            <br>
            <h4>Trung bình năm {{Carbon\Carbon::now()->year}}: <strong>{{number_format($this_year_kpi_average, 2, '.', ',')}} </strong></h4>
            <table id="employee-kpis-table" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>STT</th>
                        <th>Năm</th>
                        <th>Tháng</th>
                        <th>Điểm</th>
                        @can('create', App\Models\Kpi::class)
                        <th>Thao tác</th>
                        @endcan
                    </tr>
                </thead>
            </table>

            <!-- Modals for create employee kpi -->
            <form class="form-horizontal" method="post" action="{{ route('kpis.store') }}" name="create_kpi" id="create_kpi" novalidate="novalidate">
                {{ csrf_field() }}
                <div class="modal fade" id="create_kpi{{$employee->id}}">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4>KPI của {{$employee->name}}</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <input type="hidden" name="employee_id" id="employee_id" value="{{$employee->id}}">
                                <div class="row">
                                    <div class="col-4">
                                      <div class="control-group">
                                          <label class="required-field" class="control-label">Năm</label>
                                          <input class="form-control" type="number" name="year" id="year" value="{{Carbon\Carbon::now()->year}}">
                                      </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="control-group">
                                              <label class="required-field" class="control-label">Tháng</label>
                                              <div class="controls">
                                                  <select name="month" id="month" data-placeholder="Chọn" class="form-control select2" style="width: 100%;">
                                                      <option value="-- Chọn --" disabled="disabled" selected="selected">-- Chọn --</option>
                                                      <option value="Tháng 1" @if(1 == Carbon\Carbon::now()->month - 1) selected="selected" @endif>Tháng 1</option>
                                                      <option value="Tháng 2" @if(2 == Carbon\Carbon::now()->month - 1) selected="selected" @endif>Tháng 2</option>
                                                      <option value="Tháng 3" @if(3 == Carbon\Carbon::now()->month - 1) selected="selected" @endif>Tháng 3</option>
                                                      <option value="Tháng 4" @if(4 == Carbon\Carbon::now()->month - 1) selected="selected" @endif>Tháng 4</option>
                                                      <option value="Tháng 5" @if(5 == Carbon\Carbon::now()->month - 1) selected="selected" @endif>Tháng 5</option>
                                                      <option value="Tháng 6" @if(6 == Carbon\Carbon::now()->month - 1) selected="selected" @endif>Tháng 6</option>
                                                      <option value="Tháng 7" @if(7 == Carbon\Carbon::now()->month - 1) selected="selected" @endif>Tháng 7</option>
                                                      <option value="Tháng 8" @if(8 == Carbon\Carbon::now()->month - 1) selected="selected" @endif>Tháng 8</option>
                                                      <option value="Tháng 9" @if(9 == Carbon\Carbon::now()->month - 1) selected="selected" @endif>Tháng 9</option>
                                                      <option value="Tháng 10" @if(10 == Carbon\Carbon::now()->month - 1) selected="selected" @endif>Tháng 10</option>
                                                      <option value="Tháng 11" @if(11 == Carbon\Carbon::now()->month - 1) selected="selected" @endif>Tháng 11</option>
                                                      <option value="Tháng 12" @if(12 == Carbon\Carbon::now()->month - 1) selected="selected" @endif>Tháng 12</option>
                                                  </select>
                                              </div>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                      <div class="control-group">
                                          <label class="required-field" class="control-label">Điểm</label>
                                          <input class="form-control" type="number" name="score" id="score">
                                      </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Đóng</button>
                            <button type="submit" class="btn btn-primary">Lưu</button>
                            </div>
                        </div>
                        <!-- /.modal-content -->
                    </div>
                </div>
            </form>
            <!-- /.modal -->
        </div>
    </div>

    <!-- Đánh giá cuối năm -->
    <div class="card card-secondary">
        <div class="card-header">
            Đánh giá cuối năm
        </div>
        <!-- /.card-header -->
        <div class="card-body">
            @can('create', App\Models\YearReview::class)
            <a href="#create_year_review{{' . $employee->id . '}}" class="btn btn-success mb-2" data-toggle="modal" data-target="#create_year_review{{$employee->id}}"><i class="fas fa-plus"></i></a>
            @endcan
            <table id="employee-year-review-table" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>STT</th>
                        <th>Năm</th>
                        <th>KPI trung bình</th>
                        <th>Kết quả</th>
                        <th>Chi tiết</th>
                        @can('create', App\Models\YearReview::class)
                        <th>Thao tác</th>
                        @endcan
                    </tr>
                </thead>
            </table>

            <!-- Modals for create employee year review -->
            <form class="form-horizontal" method="post" action="{{ route('year_reviews.store') }}" name="create_year_review" id="create_year_review" novalidate="novalidate">
                {{ csrf_field() }}
                <div class="modal fade" id="create_year_review{{$employee->id}}">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4>Đánh giá cuối năm của {{$employee->name}}</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <input type="hidden" name="employee_id" id="employee_id" value="{{$employee->id}}">
                                <div class="row">
                                    <div class="col-4">
                                      <div class="control-group">
                                          <label class="required-field" class="control-label">Năm</label>
                                          <input class="form-control" type="number" name="year" id="year" value="{{Carbon\Carbon::now()->year}}">
                                      </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="control-group">
                                            <label class="required-field" class="control-label">KPI trung bình</label>
                                            <input class="form-control" type="number" name="kpi_average" id="kpi_average" step="any">
                                        </div>
                                    </div>
                                    <div class="col-4">
                                      <div class="control-group">
                                            <label class="required-field" class="control-label">Kết quả</label>
                                            <div class="controls">
                                                <select name="result" id="result" data-placeholder="Chọn" class="form-control select2" style="width: 100%;">
                                                    <option value="-- Chọn --" disabled="disabled" selected="selected">-- Chọn --</option>
                                                    <option value="Xuất sắc">Xuất sắc</option>
                                                    <option value="Tốt">Tốt</option>
                                                    <option value="Đạt">Đạt</option>
                                                    <option value="Cải thiện">Cải thiện</option>
                                                </select>
                                            </div>
                                      </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-12">
                                        <label class="control-label">Chi tiết</label>
                                        <textarea id="detail" name="detail">
                                        </textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Đóng</button>
                            <button type="submit" class="btn btn-primary">Lưu</button>
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
        });

      $("#employee-kpis-table").DataTable({
        "responsive": true, "lengthChange": false, "autoWidth": false,
        buttons: [
            {
                extend: 'copy',
                footer: true,
                exportOptions: {
                    columns: [0,1,2,3]
                }
            },
            {
                extend: 'csv',
                footer: true,
                exportOptions: {
                    columns: [0,1,2,3]
                }

            },
            {
                extend: 'excel',
                footer: true,
                exportOptions: {
                    columns: [0,1,2,3]
                }
            },
            {
                extend: 'pdf',
                footer: true,
                exportOptions: {
                    columns: [0,1,2,3]
                }
            },
            {
                extend: 'print',
                footer: true,
                exportOptions: {
                    columns: [0,1,2,3]
                }
            },
            {
                extend: 'colvis',
                footer: true,
                exportOptions: {
                    columns: [0,1,2,3]
                }
            }
        ],
        dom: 'Blfrtip',
        ajax: ' {!! route('kpis.employeeData', $employee->id) !!}',
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex'},
            {data: 'year', name: 'year'},
            {data: 'month', name: 'month'},
            {data: 'score', name: 'score'},
            {data: 'actions', name: 'actions', orderable: false, searchable: false},
       ]
      }).buttons().container().appendTo('#employee-kpis-table_wrapper .col-md-6:eq(0)');

      $("#employee-year-review-table").DataTable({
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
        ajax: ' {!! route('year_reviews.employeeData', $employee->id) !!}',
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex'},
            {data: 'year', name: 'year'},
            {data: 'kpi_average', name: 'kpi_average'},
            {data: 'result', name: 'result'},
            {data: 'detail', name: 'detail'},
            {data: 'actions', name: 'actions', orderable: false, searchable: false},
       ]
      }).buttons().container().appendTo('#employee-year-review-table_wrapper .col-md-6:eq(0)');

      // Summernote
        $("#detail").on("summernote.enter", function(we, e) {
            $(this).summernote("pasteHTML", "<br><br>");
            e.preventDefault();
        });
        $('#detail').summernote({
            height: 90,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'underline', 'clear']],
                ['color', ['color']],
            ]
        })
    });
  </script>
@endpush




