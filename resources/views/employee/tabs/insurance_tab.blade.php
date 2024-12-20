<!-- Insurance Tab -->
<div class="tab-pane" id="tab-insurance">
    <div class="card card-secondary">
        <div class="card-header">
            Bảo hiểm
        </div>
        <!-- /.card-header -->
        <div class="card-body">
            @can('create', App\Models\Insurance::class)
            <a href="#create_insurance{{' . $employee->id . '}}" class="btn btn-success" data-toggle="modal" data-target="#create_insurance{{$employee->id}}"><i class="fas fa-plus"></i></a>
            <br>
            <br>
            @endcan
            <table id="employee-insurances-table" class="table table-bordered table-striped">
                <thead>
                  <tr>
                    <th>Loại bảo hiểm</th>
                    <th>Ngày bắt đầu</th>
                    <th>Ngày kết thúc</th>
                    <th>Tỷ lệ đóng (%)</th>
                    @can('create', App\Models\Insurance::class)
                    <th style="width:12%;">Thao tác</th>
                    @endcan
                  </tr>
                </thead>
                <tbody>
                    @foreach ($insurances as $insurance)
                    <tr>
                        <td>{{$insurance->insurance_type->name}}</td>
                      @php
                          $action_edit_insurances = '<a href="' . route("insurances.edit", $insurance->id) . '" class="btn btn-success btn-sm"><i class="fas fa-edit"></i></a>
                                  <form style="display:inline" action="'. route("insurances.destroy", $insurance->id) . '" method="POST">
                                  <input type="hidden" name="_method" value="DELETE">
                                  <button type="submit" name="submit" onclick="return confirm(\'Bạn có muốn xóa?\');" class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i></button>
                                  <input type="hidden" name="_token" value="' . csrf_token(). '"></form>';

                          $action = '';
                          if (Auth::user()->can('create', App\Models\Insurance::class)) {
                              $action = $action . $action_edit_insurances;
                          }
                      @endphp
                      <td>
                        {{date('d/m/Y', strtotime($insurance->start_date))}}
                      </td>
                      <td>
                        @if($insurance->end_date)
                        {{date('d/m/Y', strtotime($insurance->end_date))}}
                        @endif
                      </td>
                      <td>{{$insurance->pay_rate}}</td>
                      @can('create', App\Models\Insurance::class)
                      <td>{!! $action !!}</td>
                      @endcan
                    </tr>
                  @endforeach
                </tbody>
            </table>

            <!-- Modals for create employee insurance -->
            <form class="form-horizontal" method="post" action="{{ route('insurances.store') }}" name="create_insurance" id="create_insurance" novalidate="novalidate">
                {{ csrf_field() }}
                <div class="modal fade" id="create_insurance{{$employee->id}}">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4>Bảo hiểm của {{$employee->name}}</h4>
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
                                                <label class="required-field" class="control-label">Loại bảo hiểm</label>
                                                <div class="controls">
                                                    <select name="insurance_type_id" id="insurance_type_id" data-placeholder="Chọn" class="form-control select2" style="width: 100%;">
                                                        <option value="-- Chọn --" disabled="disabled" selected="selected">-- Chọn --</option>
                                                        @foreach ($insurance_types as $insurance_type)
                                                            <option value="{{$insurance_type->id}}">{{$insurance_type->name}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-6">
                                        <div class="control-group">
                                            <label class="control-label">Tỷ lệ đóng (%)</label>
                                            <input class="form-control" type="number" name="pay_rate" id="pay_rate" step="any">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-6">
                                        <label class="required-field">Thời gian bắt đầu</label>
                                        <div class="input-group date" id="insurance_s_date" data-target-input="nearest">
                                            <input type="text" name="insurance_s_date" class="form-control datetimepicker-input" data-target="#insurance_s_date"/>
                                            <div class="input-group-append" data-target="#insurance_s_date" data-toggle="datetimepicker">
                                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <label>Thời gian kết thúc</label>
                                        <div class="input-group date" id="insurance_e_date" data-target-input="nearest">
                                            <input type="text" name="insurance_e_date" class="form-control datetimepicker-input" data-target="#insurance_e_date"/>
                                            <div class="input-group-append" data-target="#insurance_e_date" data-toggle="datetimepicker">
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
            Chế độ BH
        </div>
        <!-- /.card-header -->
        <div class="card-body">
            @can('create', App\Models\Regime::class)
            <a href="#create_regime{{' . $employee->id . '}}" class="btn btn-success" data-toggle="modal" data-target="#create_regime{{$employee->id}}"><i class="fas fa-plus"></i></a>
            <br>
            <br>
            @endcan
            <table id="regimes-table" class="table table-bordered table-striped">
                <thead>
                  <tr>
                    <th>Chế độ</th>
                    <th>Ngày bắt đầu nghỉ</th>
                    <th>Ngày kết thúc nghỉ</th>
                    <th>Đợt thanh toán</th>
                    <th>Số tiền được thanh toán</th>
                    <th>Trạng thái</th>
                    @can('create', App\Models\Regime::class)
                    <th style="width:12%;">Thao tác</th>
                    @endcan
                  </tr>
                </thead>
                <tbody>
                    @foreach ($regimes as $regime)
                    <tr>
                      @php
                          $action_edit_regimes = '<a href="' . route("regimes.edit", $regime->id) . '" class="btn btn-success btn-sm"><i class="fas fa-edit"></i></a>
                                  <form style="display:inline" action="'. route("regimes.destroy", $regime->id) . '" method="POST">
                                  <input type="hidden" name="_method" value="DELETE">
                                  <button type="submit" name="submit" onclick="return confirm(\'Bạn có muốn xóa?\');" class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i></button>
                                  <input type="hidden" name="_token" value="' . csrf_token(). '"></form>';

                          $action = '';
                          if (Auth::user()->can('create', App\Models\Regime::class)) {
                              $action = $action . $action_edit_regimes;
                          }
                      @endphp
                      <td>{{$regime->regime_type->name}}</td>
                      <td>
                        @if($regime->off_start_date)
                            {{date('d/m/Y', strtotime($regime->off_start_date))}}
                        @endif
                      </td>
                      <td>
                        @if($regime->off_end_date)
                            {{date('d/m/Y', strtotime($regime->off_end_date))}}
                        @endif
                      </td>
                      <td>{{$regime->payment_period}}</td>
                      <td>
                        @if ($regime->payment_amount)
                            {{number_format($regime->payment_amount, 0, '.', ',')}}
                        @endif
                      </td>
                      <td>
                        <span class="badge @if ("Mở" == $regime->status) badge-success @else badge-secondary @endif">{{$regime->status}}</span>
                      </td>
                      @can('create', App\Models\Regime::class)
                      <td>{!! $action !!}</td>
                      @endcan
                    </tr>
                  @endforeach
                </tbody>
            </table>

            <!-- Modals for create regime -->
            <form class="form-horizontal" method="post" action="{{ route('regimes.store') }}" name="create_regime" id="create_regime" novalidate="novalidate">
                {{ csrf_field() }}
                <div class="modal fade" id="create_regime{{$employee->id}}">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4>Chế độ BH của {{$employee->name}}</h4>
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
                                                <label class="required-field" class="control-label">Loại chế độ</label>
                                                <div class="controls">
                                                    <select name="regime_type_id" id="regime_type_id" data-placeholder="Chọn" class="form-control select2" style="width: 100%;">
                                                        <option value="-- Chọn --" disabled="disabled" selected="selected">-- Chọn --</option>
                                                        @foreach ($regime_types as $regime_type)
                                                            <option value="{{$regime_type->id}}">{{$regime_type->name}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-6">
                                        <div class="control-group">
                                            <label class="control-label">Đợt thanh toán</label>
                                            <input class="form-control" type="text" name="payment_period" id="payment_period">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-6">
                                      <label>Thời gian bắt đầu nghỉ</label>
                                      <div class="input-group date" id="off_start_date" data-target-input="nearest">
                                          <input type="text" name="off_start_date" class="form-control datetimepicker-input" data-target="#off_start_date"/>
                                          <div class="input-group-append" data-target="#off_start_date" data-toggle="datetimepicker">
                                              <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                          </div>
                                      </div>
                                    </div>
                                    <div class="col-6">
                                      <label>Thời gian kết thúc nghỉ</label>
                                      <div class="input-group date" id="off_end_date" data-target-input="nearest">
                                          <input type="text" name="off_end_date" class="form-control datetimepicker-input" data-target="#off_end_date"/>
                                          <div class="input-group-append" data-target="#off_end_date" data-toggle="datetimepicker">
                                              <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                          </div>
                                      </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-12">
                                      <label>Số tiền được thanh toán</label>
                                      <input class="form-control" type="number" name="payment_amount" id="payment_amount">
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
        //Initialize Select2 Elements
        $('.select2').select2({
        theme: 'bootstrap4'
        });

        //Date picker
        $('#insurance_s_date').datetimepicker({
            format: 'DD/MM/YYYY'
        });
        $('#insurance_e_date').datetimepicker({
            format: 'DD/MM/YYYY'
        });
        $('#off_start_date').datetimepicker({
            format: 'DD/MM/YYYY'
        });
        $('#off_end_date').datetimepicker({
            format: 'DD/MM/YYYY'
        });
    });
</script>
@endpush






