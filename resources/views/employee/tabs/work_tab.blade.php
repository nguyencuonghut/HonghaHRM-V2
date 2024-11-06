<!-- Work Tab -->
<div class="tab-pane" id="tab-work">
    <div class="card card-secondary">
        <div class="card-header">
            Quá trình công tác
        </div>
        <!-- /.card-header -->

        <div class="card-body">
            @can('create', App\Models\Work::class)
            <a href="#create_work{{' . $employee->id . '}}" class="btn btn-success" data-toggle="modal" data-target="#create_work{{$employee->id}}"><i class="fas fa-plus"></i></a>
            <br>
            <br>
            @endcan
            <table id="employee-works-table" class="table table-bordered table-striped">
                <thead>
                  <tr>
                    <th>Mã HĐ</th>
                    <th>Vị trí</th>
                    <th>Ngày bắt đầu</th>
                    <th>Ngày kết thúc</th>
                    <th>Phân loại tạo</th>
                    <th>Phân loại nghỉ</th>
                    <th>Lý do nghỉ</th>
                    <th>Trạng thái</th>
                    @can('create', App\Models\Work::class)
                    <th style="width:12%;">Thao tác</th>
                    @endcan
                  </tr>
                </thead>
                <tbody>
                    @foreach ($works as $work)
                    <tr>
                      @php
                          $position = App\Models\Position::findOrFail($work->position_id);
                          $action_edit_work = '<a href="' . route("works.edit", $work->id) . '" class="btn btn-success btn-sm"><i class="fas fa-edit"></i></a>
                                  <a href="'.route("works.getOff", $work->id) . '" class="btn btn-secondary btn-sm"><i class="fas fa-power-off"></i></a>
                                  <form style="display:inline" action="'. route("works.destroy", $work->id) . '" method="POST">
                                  <input type="hidden" name="_method" value="DELETE">
                                  <button type="submit" name="submit" onclick="return confirm(\'Bạn có muốn xóa?\');" class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i></button>
                                  <input type="hidden" name="_token" value="' . csrf_token(). '"></form>';

                          $action = '';
                          if (Auth::user()->can('create', App\Models\Work::class)) {
                              $action = $action . $action_edit_work;
                          }
                      @endphp
                      <td>{{$work->contract_code}}</td>
                      <td>
                        @php
                            $position_str = '';
                            if ($position->division_id) {
                                $position_str .= $position->name . ' - '. $position->division->name . ' - ' . $position->department->name;
                            } else {
                                $position_str .= $position->name . ' - ' . $position->department->name;
                            }
                        @endphp
                        {!! $position_str !!}
                      </td>
                      <td>{{date('d/m/Y', strtotime($work->start_date))}}</td>
                      <td>
                        @if ($work->end_date)
                          {{date('d/m/Y', strtotime($work->end_date))}}
                        @else
                        -
                        @endif
                      </td>
                      <td>
                        @if($work->on_type_id)
                        {{$work->on_type->name}}
                        @endif
                      </td>
                      <td>
                        @if($work->off_type_id)
                        {{$work->off_type->name}}
                        @endif
                      </td>
                      <td>{!! $work->off_reason !!}</td>
                      <td>
                        <span class="badge @if ("On" == $work->status) badge-success @else badge-danger @endif">
                            {{$work->status}}
                        </span>
                      </td>
                      @can('create', App\Models\Work::class)
                      <td>{!! $action !!}</td>
                      @endcan
                    </tr>
                  @endforeach
                </tbody>
            </table>

            <!-- Modals for create employee work -->
            <form class="form-horizontal" method="post" action="{{ route('works.store') }}" name="create_work" id="create_work" novalidate="novalidate">
                {{ csrf_field() }}
                <div class="modal fade" id="create_work{{$employee->id}}">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4>QT công tác của {{$employee->name}}</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                @php
                                    $contract = App\Models\Contract::where('employee_id', $employee->id)->orderBy('id', 'desc')->first();
                                @endphp
                                <input type="hidden" name="employee_id" id="employee_id" value="{{$employee->id}}">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="control-group">
                                            <div class="control-group">
                                                <label class="required-field" class="control-label">Vị trí</label>
                                                <div class="controls">
                                                    <select name="position_id" id="position_id" data-placeholder="Chọn" class="form-control select2" style="width: 100%;">
                                                        <option value="-- Chọn --" disabled="disabled" selected="selected">-- Chọn --</option>
                                                        @foreach ($positions as $position)
                                                            <option value="{{$position->id}}" @if($contract && $position->id == $contract->position_id) selected="selected" @endif>{{$position->name}} {{$position->division_id ? (' - ' . $position->division->name) : ''}} {{$position->department_id ? ( ' - ' . $position->department->name) : ''}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                @php
                                    $contracts = App\Models\Contract::where('employee_id', $employee->id)->orderBy('id', 'desc')->get();
                                @endphp
                                <div class="row">
                                    <div class="col-12">
                                        <div class="control-group">
                                            <div class="control-group">
                                                <label class="required-field" class="control-label">Mã hợp đồng</label>
                                                <div class="controls">
                                                    <select name="contract_code" id="contract_code" data-placeholder="Chọn" class="form-control select2" style="width: 100%;">
                                                        @foreach ($contracts as $contract)
                                                            <option value="{{$contract->code}}">{{$contract->code}} </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-6">
                                        <label class="required-field">Thời gian bắt đầu</label>
                                        <div class="input-group date" id="s_date" data-target-input="nearest">
                                            <input type="text" name="s_date" class="form-control datetimepicker-input" @if($contract) value="{{date('d/m/Y', strtotime($contract->start_date))}}" @endif data-target="#s_date"/>
                                            <div class="input-group-append" data-target="#s_date" data-toggle="datetimepicker">
                                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="control-group">
                                            <div class="control-group">
                                                <label class="required-field" class="control-label">Phân loại tạo</label>
                                                <div class="controls">
                                                    <select name="on_type_id" id="on_type_id" data-placeholder="Chọn" class="form-control select2" style="width: 100%;">
                                                        <option value="-- Chọn --" disabled="disabled" selected="selected">-- Chọn --</option>
                                                        @foreach ($on_types as $on_type)
                                                            <option value="{{$on_type->id}}">{{$on_type->name}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
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
        </div>
    </div>
</div>


@push('scripts')
<script>
    $(function () {
        //Date picker
        $('#s_date').datetimepicker({
            format: 'DD/MM/YYYY'
        });
    });
</script>
@endpush

