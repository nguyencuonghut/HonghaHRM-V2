<!-- Reward Discipline Tab -->
<div class="tab-pane" id="tab-reward-discipline">
    <!-- KPI table -->
    <div class="card card-secondary">
        <div class="card-header">
            Khen thưởng
        </div>
        <!-- /.card-header -->
        <div class="card-body">
            @can('create', App\Models\Reward::class)
            <a href="#create_reward{{' . $employee->id . '}}" class="btn btn-success mb-2" data-toggle="modal" data-target="#create_reward{{$employee->id}}"><i class="fas fa-plus"></i></a>
            @endcan
            <br>
            <table id="employee-rewards-table" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>STT</th>
                        <th>Vị trí</th>
                        <th>Số</th>
                        <th>Ngày</th>
                        <th>Nội dung</th>
                        <th>Ghi chú</th>
                        @can('create', App\Models\Reward::class)
                        <th>Thao tác</th>
                        @endcan
                    </tr>
                </thead>
            </table>

            <!-- Modals for create employee reward -->
            <form class="form-horizontal" method="post" action="{{ route('rewards.store') }}" name="create_reward" id="create_reward" novalidate="novalidate">
                {{ csrf_field() }}
                <div class="modal fade" id="create_reward{{$employee->id}}">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4>Khen thưởng của {{$employee->name}}</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <input type="hidden" name="employee_id" id="employee_id" value="{{$employee->id}}">
                                <div class="row">
                                    <div class="col-6">
                                      <div class="control-group">
                                          <label class="required-field" class="control-label">Số</label>
                                          <input class="form-control" type="text" name="code" id="code">
                                      </div>
                                    </div>
                                    <div class="col-6">
                                        <label class="required-field">Ngày</label>
                                        <div class="input-group date" id="sign_date" data-target-input="nearest">
                                            <input type="text" name="sign_date" class="form-control datetimepicker-input" data-target="#sign_date"/>
                                            <div class="input-group-append" data-target="#sign_date" data-toggle="datetimepicker">
                                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <label class="required-field control-label">Vị trí</label>
                                        <div class="controls">
                                            <select name="position_id" id="position_id" data-placeholder="Chọn vị trí" class="form-control select2" style="width: 100%;">
                                                <option value="-- Chọn --" disabled="disabled" selected="selected">-- Chọn --</option>
                                                @foreach($my_positions as $position)
                                                    <option value="{{$position->id}}">{{$position->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <label class="required-field control-label">Nội dung</label>
                                        <textarea id="content" name="content">
                                        </textarea>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <label class="control-label">Ghi chú</label>
                                        <textarea id="note" name="note">
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

    <!-- Kỷ luật-->
    <div class="card card-secondary">
        <div class="card-header">
            Kỷ luật
        </div>
        <!-- /.card-header -->
        <div class="card-body">
            @can('create', App\Models\Discipline::class)
            <a href="#create_discipline{{' . $employee->id . '}}" class="btn btn-success mb-2" data-toggle="modal" data-target="#create_discipline{{$employee->id}}"><i class="fas fa-plus"></i></a>
            @endcan
            <table id="employee-disciplines-table" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>STT</th>
                        <th>Vị trí</th>
                        <th>Số</th>
                        <th>Ngày</th>
                        <th>Nội dung</th>
                        <th>Ghi chú</th>
                        @can('create', App\Models\Discipline::class)
                        <th>Thao tác</th>
                        @endcan
                    </tr>
                </thead>
            </table>

            <!-- Modals for create employee discipline -->
            <form class="form-horizontal" method="post" action="{{ route('disciplines.store') }}" name="create_discipline" id="create_discipline" novalidate="novalidate">
                {{ csrf_field() }}
                <div class="modal fade" id="create_discipline{{$employee->id}}">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4>Kỷ luật của {{$employee->name}}</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <input type="hidden" name="employee_id" id="employee_id" value="{{$employee->id}}">
                                <div class="row">
                                    <div class="col-6">
                                      <div class="control-group">
                                          <label class="required-field" class="control-label">Số</label>
                                          <input class="form-control" type="text" name="code" id="code">
                                      </div>
                                    </div>
                                    <div class="col-6">
                                        <label class="required-field">Ngày</label>
                                        <div class="input-group date" id="dis_sign_date" data-target-input="nearest">
                                            <input type="text" name="dis_sign_date" class="form-control datetimepicker-input" data-target="#dis_sign_date"/>
                                            <div class="input-group-append" data-target="#dis_sign_date" data-toggle="datetimepicker">
                                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <label class="required-field control-label">Vị trí</label>
                                        <div class="controls">
                                            <select name="position_id" id="position_id" data-placeholder="Chọn vị trí" class="form-control select2" style="width: 100%;">
                                                <option value="-- Chọn --" disabled="disabled" selected="selected">-- Chọn --</option>
                                                @foreach($my_positions as $position)
                                                    <option value="{{$position->id}}">{{$position->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <label class="required-field control-label">Nội dung</label>
                                        <textarea id="dis_content" name="dis_content">
                                        </textarea>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <label class="control-label">Ghi chú</label>
                                        <textarea id="dis_note" name="dis_note">
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
        //Date picker
        $('#sign_date').datetimepicker({
            format: 'DD/MM/YYYY'
        });
        $('#dis_sign_date').datetimepicker({
            format: 'DD/MM/YYYY'
        });


      $("#employee-rewards-table").DataTable({
        "responsive": true, "lengthChange": false, "autoWidth": false,
        buttons: [
            {
                extend: 'copy',
                footer: true,
                exportOptions: {
                    columns: [0,1,2,3,4,5]
                }
            },
            {
                extend: 'csv',
                footer: true,
                exportOptions: {
                    columns: [0,1,2,3,4,5]
                }

            },
            {
                extend: 'excel',
                footer: true,
                exportOptions: {
                    columns: [0,1,2,3,4,5]
                }
            },
            {
                extend: 'pdf',
                footer: true,
                exportOptions: {
                    columns: [0,1,2,3,4,5]
                }
            },
            {
                extend: 'print',
                footer: true,
                exportOptions: {
                    columns: [0,1,2,3,4,5]
                }
            },
            {
                extend: 'colvis',
                footer: true,
                exportOptions: {
                    columns: [0,1,2,3,4,5]
                }
            }
        ],
        dom: 'Blfrtip',
        ajax: ' {!! route('rewards.employeeData', $employee->id) !!}',
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex'},
            {data: 'position', name: 'position'},
            {data: 'code', name: 'code'},
            {data: 'sign_date', name: 'sign_date'},
            {data: 'content', name: 'content'},
            {data: 'note', name: 'note'},
            {data: 'actions', name: 'actions', orderable: false, searchable: false},
       ]
      }).buttons().container().appendTo('#employee-rewards-table_wrapper .col-md-6:eq(0)');

      $("#employee-disciplines-table").DataTable({
        "responsive": true, "lengthChange": false, "autoWidth": false,
        buttons: [
            {
                extend: 'copy',
                footer: true,
                exportOptions: {
                    columns: [0,1,2,3,4,5]
                }
            },
            {
                extend: 'csv',
                footer: true,
                exportOptions: {
                    columns: [0,1,2,3,4,5]
                }

            },
            {
                extend: 'excel',
                footer: true,
                exportOptions: {
                    columns: [0,1,2,3,4,5]
                }
            },
            {
                extend: 'pdf',
                footer: true,
                exportOptions: {
                    columns: [0,1,2,3,4,5]
                }
            },
            {
                extend: 'print',
                footer: true,
                exportOptions: {
                    columns: [0,1,2,3,4,5]
                }
            },
            {
                extend: 'colvis',
                footer: true,
                exportOptions: {
                    columns: [0,1,2,3,4,5]
                }
            }
        ],
        dom: 'Blfrtip',
        ajax: ' {!! route('disciplines.employeeData', $employee->id) !!}',
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex'},
            {data: 'position', name: 'position'},
            {data: 'code', name: 'code'},
            {data: 'sign_date', name: 'sign_date'},
            {data: 'content', name: 'content'},
            {data: 'note', name: 'note'},
            {data: 'actions', name: 'actions', orderable: false, searchable: false},
       ]
      }).buttons().container().appendTo('#employee-disciplines-table_wrapper .col-md-6:eq(0)');

      // Summernote
        $("#content").on("summernote.enter", function(we, e) {
            $(this).summernote("pasteHTML", "<br><br>");
            e.preventDefault();
        });
        $("#note").on("summernote.enter", function(we, e) {
            $(this).summernote("pasteHTML", "<br><br>");
            e.preventDefault();
        });
        $("#dis_content").on("summernote.enter", function(we, e) {
            $(this).summernote("pasteHTML", "<br><br>");
            e.preventDefault();
        });
        $("#dis_note").on("summernote.enter", function(we, e) {
            $(this).summernote("pasteHTML", "<br><br>");
            e.preventDefault();
        });

        $('#content').summernote({
            height: 90,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'underline', 'clear']],
                ['color', ['color']],
            ]
        })
        $('#note').summernote({
            height: 90,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'underline', 'clear']],
                ['color', ['color']],
            ]
        })
        $('#dis_content').summernote({
            height: 90,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'underline', 'clear']],
                ['color', ['color']],
            ]
        })
        $('#dis_note').summernote({
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



