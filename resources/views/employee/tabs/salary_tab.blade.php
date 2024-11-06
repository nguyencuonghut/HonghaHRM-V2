<!-- Salary Tab -->
<div class="tab-pane" id="tab-salary">
    <!-- Salary table -->
    <div class="card card-secondary">
        <div class="card-header">
            Lương
        </div>

        @php
            $work = App\Models\Work::where('employee_id', $employee->id)
                                    ->orderBy('start_date', 'desc')
                                    ->first();
        @endphp

        <!-- /.card-header -->
        <div class="card-body">
            @if ($work)
                @can('create', App\Models\Salary::class)
                <a href="#create_salary{{' . $employee->id . '}}" class="btn btn-success mb-2" data-toggle="modal" data-target="#create_salary{{$employee->id}}"><i class="fas fa-plus"></i></a>
                @endcan
                <br>
                <table id="salaries-table" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>STT</th>
                            <th>Lương vị trí</th>
                            <th>Lương năng lực</th>
                            <th>Phụ cấp vị trí</th>
                            <th>Lương BHXH</th>
                            <th>Thời gian bắt đầu</th>
                            <th>Thời gian kết thúc</th>
                            <th>Trạng thái</th>
                            @can('create', App\Models\Salary::class)
                            <th style="width:12%;">Thao tác</th>
                            @endcan
                        </tr>
                    </thead>
                </table>
                <!-- Modals for create employee salary -->
                <form class="form-horizontal" method="post" action="{{ route('salaries.store', $employee->id) }}" name="create_salary" id="create_salary" novalidate="novalidate">
                    {{ csrf_field() }}
                    <div class="modal fade" id="create_salary{{$employee->id}}">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4>Lương của {{$employee->name}}</h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <input type="hidden" name="employee_id" id="employee_id" value="{{$employee->id}}">
                                    <div class="row">
                                        <div class="col-6">
                                        <div class="control-group">
                                            <label class="control-label">Lương vị trí</label>
                                            <input class="form-control" type="number" name="position_salary" value="{{$work->position->position_salary}}" id="position_salary">
                                        </div>
                                        </div>
                                        <div class="col-6">
                                        <div class="control-group">
                                            <label class="control-label">Lương năng lực</label>
                                            <input class="form-control" type="number" name="capacity_salary" value="{{$work->position->max_capacity_salary}}" id="capacity_salary">
                                        </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-6">
                                        <div class="control-group">
                                            <label class="control-label">Phụ cấp vị trí</label>
                                            <input class="form-control" type="number" name="position_allowance" value="{{$work->position->position_allowance}}" id="position_allowance">
                                        </div>
                                        </div>
                                        <div class="col-6">
                                        <div class="control-group">
                                            <label class="required-field" class="control-label">Lương bảo hiểm</label>
                                            <input class="form-control" type="number" name="insurance_salary" value="{{$work->position->insurance_salary}}" id="insurance_salary">
                                        </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-12">
                                        <label class="required-field">Thời gian bắt đầu</label>
                                        <div class="input-group date" id="salary_start_date" data-target-input="nearest">
                                            <input type="text" name="salary_start_date" class="form-control datetimepicker-input" value="{{date('d/m/Y', strtotime($work->start_date))}}" data-target="#salary_start_date"/>
                                            <div class="input-group-append" data-target="#salary_start_date" data-toggle="datetimepicker">
                                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                            </div>
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
            @else
                !! Chưa tạo QT công tác !!
            @endif
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
        $('#salary_start_date').datetimepicker({
            format: 'DD/MM/YYYY'
        });

      //Datatables
      $("#salaries-table").DataTable({
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
        ajax: ' {!! route('salaries.employeeData', $employee->id) !!}',
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex'},
            {data: 'position_salary', name: 'position_salary'},
            {data: 'capacity_salary', name: 'capacity_salary'},
            {data: 'position_allowance', name: 'position_allowance'},
            {data: 'insurance_salary', name: 'insurance_salary'},
            {data: 'start_date', name: 'start_date'},
            {data: 'end_date', name: 'end_date'},
            {data: 'status', name: 'status'},
            {data: 'actions', name: 'actions', orderable: false, searchable: false},
       ]
      }).buttons().container().appendTo('#salaries-table_wrapper .col-md-6:eq(0)');
    });
  </script>
@endpush




