<!-- Contract Tab -->
<div class="tab-pane" id="tab-contract">
    <div class="card card-secondary">
        <div class="card-header">
            Hợp đồng
        </div>
        <!-- /.card-header -->
        <div class="card-body">
            @can('create', App\Models\Contract::class)
            <a href="#create_contract{{' . $employee->id . '}}" class="btn btn-success" data-toggle="modal" data-target="#create_contract{{$employee->id}}"><i class="fas fa-plus"></i></a>
            <br>
            <br>
            @endcan
            <table id="employee-contracts-table" class="table table-bordered table-striped">
                <thead>
                  <tr>
                    <th>Số HĐ</th>
                    <th>Hợp đồng</th>
                    <th>Vị trí</th>
                    <th>Ngày bắt đầu</th>
                    <th>Ngày kết thúc</th>
                    <th>Trạng thái</th>
                    <th>Form HĐ</th>
                    <th>File HĐ</th>
                    <th>Ngày viết đơn</th>
                    <th>Form KT</th>
                    @can('create', App\Models\Contract::class)
                    <th style="width:14%;">Thao tác</th>
                    @endcan
                  </tr>
                </thead>
                <tbody>
                    @foreach ($contracts as $contract)
                    <tr>
                        <td>{{$contract->code}}</td>
                      @php
                          $position = App\Models\Position::findOrFail($contract->position_id);
                          $action_edit_contracts = '<a href="' . route("contracts.edit", $contract->id) . '" class="btn btn-success btn-sm"><i class="fas fa-edit"></i></a>
                                  <a href="'.route("contracts.getOff", $contract->id) . '" class="btn btn-secondary btn-sm"><i class="fas fa-power-off"></i></a>
                                  <a href="'.route("appendixes.getAdd", $contract->id) . '" class="btn btn-primary btn-sm"><i class="fas fa-code-branch"></i></a>
                                  <form style="display:inline" action="'. route("contracts.destroy", $contract->id) . '" method="POST">
                                  <input type="hidden" name="_method" value="DELETE">
                                  <button type="submit" name="submit" onclick="return confirm(\'Bạn có muốn xóa?\');" class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i></button>
                                  <input type="hidden" name="_token" value="' . csrf_token(). '"></form>';

                          $action = '';
                          if (Auth::user()->can('create', App\Models\Contract::class)) {
                              $action = $action . $action_edit_contracts;
                          }
                      @endphp
                      <td>{{$contract->contract_type->name}}</td>
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
                      <td>{{date('d/m/Y', strtotime($contract->start_date))}}</td>
                      <td>
                        @if ($contract->end_date)
                          {{date('d/m/Y', strtotime($contract->end_date))}}
                        @else
                        -
                        @endif
                      </td>
                      <td>
                        <span class="badge @if ("On" == $contract->status) badge-success @else badge-danger @endif">
                            {{$contract->status}}
                        </span>
                      </td>
                      @php
                            $contract_form_url = '<a href="'.route("contracts.export", $contract->id) . '"><i class="fas fa-file-excel"></i></a>';
                      @endphp
                      <td>{!! $contract_form_url !!}</td>
                      @php
                            $url = '';
                            if ($contract->file_path) {
                                $url .= '<a target="_blank" href="../../../' . $contract->file_path . '"><i class="far fa-file-pdf"></i></a>';
                            }
                      @endphp
                      <td>{!! $url !!}</td>
                      <td>{{$contract->request_terminate_date ? date('d/m/Y', strtotime($contract->request_terminate_date)) : ''}}</td>
                      @php
                            $terminate_form_url = '';
                            if ('Off' == $contract->status
                                && $contract->request_terminate_date) {
                                $terminate_form_url = '<a href="'.route("contracts.terminate_form", $contract->id) . '"><i class="fas fa-file-excel"></i></a>';
                            }
                      @endphp
                      <td>
                        {!! $terminate_form_url !!}
                      </td>
                      @can('create', App\Models\Contract::class)
                      <td>{!! $action !!}</td>
                      @endcan
                    </tr>
                  @endforeach
                </tbody>
            </table>

            <!-- Modals for create contract -->
            <form class="form-horizontal" method="post" action="{{ route('contracts.store') }}" enctype="multipart/form-data" name="create_contract" id="create_contract" novalidate="novalidate">
                {{ csrf_field() }}
                <div class="modal fade" id="create_contract{{$employee->id}}">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4>Hợp đồng của {{$employee->name}}</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <input type="hidden" name="employee_id" id="employee_id" value="{{$employee->id}}">
                                <div class="row">
                                    <div class="col-6">
                                        <div class="control-group">
                                            <div class="control-group">
                                                <label class="required-field" class="control-label">Vị trí</label>
                                                <div class="controls">
                                                    <select name="ct_position_id" id="ct_position_id" data-placeholder="Chọn" class="form-control select2" style="width: 100%;">
                                                        <option value="-- Chọn --" disabled="disabled" selected="selected">-- Chọn --</option>
                                                        @foreach ($positions as $position)
                                                            <option value="{{$position->id}}">{{$position->name}} {{$position->division_id ? (' - ' . $position->division->name) : ''}} {{$position->department_id ? ( ' - ' . $position->department->name) : ''}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="control-group">
                                            <div class="control-group">
                                                <label class="required-field" class="control-label">Loại tạo</label>
                                                <div class="controls">
                                                    <select name="created_type" id="created_type" data-placeholder="Chọn" class="form-control select2" style="width: 100%;">
                                                        <option value="-- Chọn --" disabled="disabled" selected="selected">-- Chọn --</option>
                                                        <option value="Ký mới">Ký mới</option>
                                                        <option value="Tái ký">Tái ký</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-6">
                                        <div class="control-group">
                                            <div class="control-group">
                                                <label class="required-field" class="control-label">Loại HĐ</label>
                                                <div class="controls">
                                                    <select name="contract_type_id" id="contract_type_id" data-placeholder="Chọn" class="form-control select2" style="width: 100%;">
                                                        <option value="-- Chọn --" disabled="disabled" selected="selected">-- Chọn --</option>
                                                        @foreach ($contract_types as $contract_type)
                                                            <option value="{{$contract_type->id}}">{{$contract_type->name}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="control-group">
                                            <label class="control-label">File (pdf)</label>
                                            <div class="custom-file text-left">
                                                <input type="file" name="file_path" accept="application/pdf" class="custom-file-input" id="file_path">
                                                <label class="custom-file-label" for="img_path">Chọn file</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                @php
                                    $recent_contract = App\Models\Contract::where('employee_id', $employee->id)->orderBy('id', 'desc')->first();
                                @endphp
                                <div class="row">
                                    <div class="col-6">
                                        <label class="required-field">Thời gian bắt đầu</label>
                                        <div class="input-group date" id="contract_s_date" data-target-input="nearest">
                                            <input type="text" name="contract_s_date" class="form-control datetimepicker-input" @if($recent_contract) value="{{date('d/m/Y', strtotime($recent_contract->end_date . '+ 1 days'))}}" @endif data-target="#contract_s_date"/>
                                            <div class="input-group-append" data-target="#contract_s_date" data-toggle="datetimepicker">
                                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <label>Thời gian kết thúc</label>
                                        <div class="input-group date" id="contract_e_date" data-target-input="nearest">
                                            <input type="text" name="contract_e_date" class="form-control datetimepicker-input" data-target="#contract_e_date"/>
                                            <div class="input-group-append" data-target="#contract_e_date" data-toggle="datetimepicker">
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
        </div>
    </div>

    <div class="card card-secondary">
        <div class="card-header">
            Phụ lục
        </div>

        <div class="card-body">
            <table id="employee-appendixes-table" class="table table-bordered table-striped">
                <thead>
                    <tr>
                      <th>Số HĐ</th>
                      <th>Số phụ lục</th>
                      <th>Mô tả</th>
                      <th>Lý do</th>
                      <th>File</th>
                      <th>Form PL</th>
                      @can('create', App\Models\Appendix::class)
                      <th>Thao tác</th>
                      @endcan
                    </tr>
                  </thead>
                  <tbody>
                      @foreach ($appendixes as $appendix)
                      <tr>
                          <td>{{$appendix->contract->code}}</td>
                          <td>{{$appendix->code}}</td>
                          <td>{!! $appendix->description !!}</td>
                          <td>{{$appendix->reason}}</td>
                        @php
                            $action_edit_appendixes = '<a href="' . route("appendixes.edit", $appendix->id) . '" class="btn btn-success btn-sm"><i class="fas fa-edit"></i></a>
                                    <form style="display:inline" action="'. route("appendixes.destroy", $appendix->id) . '" method="POST">
                                    <input type="hidden" name="_method" value="DELETE">
                                    <button type="submit" name="submit" onclick="return confirm(\'Bạn có muốn xóa?\');" class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i></button>
                                    <input type="hidden" name="_token" value="' . csrf_token(). '"></form>';

                            $action = '';
                            if (Auth::user()->can('create', App\Models\Appendix::class)) {
                                $action = $action . $action_edit_appendixes;
                            }
                        @endphp
                        @php
                              $url = '';
                              if ($appendix->file_path) {
                                  $url .= '<a target="_blank" href="../../../' . $appendix->file_path . '"><i class="far fa-file-pdf"></i></a>';
                              }
                              $appendix_form_url = '<a href="'.route("appendixes.export", $appendix->id) . '"><i class="fas fa-file-excel"></i></a>';

                        @endphp
                        <td>{!! $url !!}</td>
                        <td>{!! $appendix_form_url !!}</td>
                        @can('create', App\Models\Appendix::class)
                        <td>{!! $action !!}</td>
                        @endcan
                      </tr>
                    @endforeach
                  </tbody>
            </table>
        </div>
    </div>
</div>


@push('scripts')
<script>
    $(function () {
        //Initialize Select2 Elements
        $('.select2').select2({
        theme: 'bootstrap4'
        });

        //Date picker
        $('#contract_s_date').datetimepicker({
            format: 'DD/MM/YYYY'
        });
        $('#contract_e_date').datetimepicker({
            format: 'DD/MM/YYYY'
        });

        // Add the following code if you want the name of the file appear on select
        $(".custom-file-input").on("change", function() {
            var fileName = $(this).val().split("\\").pop();
            $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
        });
    });
</script>
@endpush



