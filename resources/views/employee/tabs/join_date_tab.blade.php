<!-- Join Date Tab -->
<div class="tab-pane" id="tab-join-date">
    <div class="card card-secondary">
        <div class="card-header">
            Ngày vào
        </div>
        <!-- /.card-header -->
        <div class="card-body">
            @can('create', App\Models\JoinDate::class)
            <a href="#create_join_date{{' . $employee->id . '}}" class="btn btn-success" data-toggle="modal" data-target="#create_join_date{{$employee->id}}"><i class="fas fa-plus"></i></a>
            <br>
            <br>
            @endcan
            <table id="join-dates-table" class="table table-bordered table-striped">
                <thead>
                  <tr>
                    <th>Ngày vào</th>
                    @can('create', App\Models\JoinDate::class)
                    <th>Thao tác</th>
                    @endcan
                  </tr>
                </thead>
                <tbody>
                    @foreach ($join_dates as $join_date)
                    <tr>
                        <td>{{date('d/m/Y', strtotime($join_date->join_date))}}</td>
                      @php
                          $action = '<a href="' . route("join_dates.edit", $join_date->id) . '" class="btn btn-success btn-sm"><i class="fas fa-edit"></i></a>
                                  <form style="display:inline" action="'. route("join_dates.destroy", $join_date->id) . '" method="POST">
                                  <input type="hidden" name="_method" value="DELETE">
                                  <button type="submit" name="submit" onclick="return confirm(\'Bạn có muốn xóa?\');" class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i></button>
                                  <input type="hidden" name="_token" value="' . csrf_token(). '"></form>';
                      @endphp
                      @can('create', App\Models\JoinDate::class)
                      <td>{!! $action !!}</td>
                      @endcan
                    </tr>
                  @endforeach
                </tbody>
            </table>

            <!-- Modals for create join date -->
            <form class="form-horizontal" method="post" action="{{ route('join_dates.store') }}" name="create_contract" id="create_join_date" novalidate="novalidate">
                {{ csrf_field() }}
                <div class="modal fade" id="create_join_date{{$employee->id}}">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4>Ngày vào của {{$employee->name}}</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <input type="hidden" name="employee_id" id="employee_id" value="{{$employee->id}}">
                                @php
                                    $recent_contract = App\Models\Contract::where('employee_id', $employee->id)->orderBy('id', 'desc')->first();
                                @endphp
                                <div class="row">
                                    <div class="col-12">
                                        <label class="required-field">Ngày vào</label>
                                        <div class="input-group date" id="join_date" data-target-input="nearest">
                                            <input type="text" name="join_date" class="form-control datetimepicker-input" @if($recent_contract) value="{{date('d/m/Y', strtotime($recent_contract->start_date))}}" @endif data-target="#join_date"/>
                                            <div class="input-group-append" data-target="#join_date" data-toggle="datetimepicker">
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
</div>


@push('scripts')
<script>
    $(function () {
        //Date picker
        $('#join_date').datetimepicker({
            format: 'DD/MM/YYYY'
        });
    });
</script>
@endpush



