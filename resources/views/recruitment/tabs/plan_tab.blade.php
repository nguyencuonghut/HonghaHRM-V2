<!-- Request Tab -->
<div class="tab-pane fade" id="recruitment-2" role="tabpanel" aria-labelledby="recruitment-tab-2">
    <div class="card">
        <!-- /.card-header -->
        <div class="card-body">
            <!-- /.card-header -->
            <div class="card-body">
            @can('create', App\Models\Plan::class)
                @if(!$recruitment->plan
                    && 'Đã duyệt' == $recruitment->status)
                    <button type="button" class="btn btn-success float-left" data-toggle="modal" data-target="#create_plan">
                        Tạo
                    </button>
                @endif
            @endcan
            @if ($recruitment->plan)
            <table id="plans-table" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Người tạo</th>
                        <th>Ngân sách</th>
                        <th>Cách thức</th>
                        <th>Người duyệt</th>
                        <th>Trạng thái</th>
                        @if (Auth::user()->can('create', App\Models\Plan::class)
                            || Auth::user()->can('update', $recruitment->plan)
                            || Auth::user()->can('delete', $recruitment->plan)
                            || Auth::user()->can('approve', $recruitment->plan))
                        <th>Thao tác</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{$recruitment->plan->creator->name}}</td>
                        <td>{!! number_format($recruitment->plan->budget, 0, '.', ',') . '<sup>đ</sup>' !!}</td>
                        <td>
                            @php
                                $i = 0;
                                $length = count($recruitment->plan->methods);
                                $methods_list = '';
                                foreach ($recruitment->plan->methods as $item) {
                                    if(++$i === $length) {
                                        $methods_list =  $methods_list . $item->name;
                                    } else {
                                        $methods_list = $methods_list . $item->name . ',<br>';
                                    }
                                }
                            @endphp
                            {!! $methods_list !!}
                        </td>
                        <td>
                            @php
                                if ('Đồng ý' == $recruitment->plan->approver_result) {
                                    $approver_result_str = '<span class="badge badge-success">' . $recruitment->plan->approver_result . '</span>';
                                } else {
                                    $approver_result_str = '<span class="badge badge-danger">' . $recruitment->plan->approver_result . '</span>';
                                }
                            @endphp
                            @if ($recruitment->plan->approver_id)
                                {{$recruitment->plan->approver->name}} - {!! $approver_result_str !!}
                                @if ($recruitment->plan->approver_comment)
                                    <br>
                                    <small>({!!$recruitment->plan->approver_comment !!})</small>
                                @endif
                            @endif
                        </td>
                        <td>
                            @php
                                if ('Chưa duyệt' == $recruitment->plan->status) {
                                    $status_str = '<span class="badge badge-secondary">' . $recruitment->plan->status . '</span>';
                                } else {
                                    $status_str = '<span class="badge badge-success">' . $recruitment->plan->status . '</span>';
                                }

                            @endphp
                            {!! $status_str !!}
                        </td>
                        @if (Auth::user()->can('create', App\Models\Plan::class)
                            || Auth::user()->can('update', $recruitment->plan)
                            || Auth::user()->can('delete', $recruitment->plan)
                            || Auth::user()->can('approve', $recruitment->plan))
                        <td>
                            @php
                            $update = '';
                            $delete = '';
                            $approve = '';
                            if (Auth::user()->can('update', $recruitment->plan)) {
                                $update = '<a href="#edit{{' . $recruitment->plan->id . '}}" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#edit-plan-' . $recruitment->plan->id. '"><i class="fas fa-edit"></i></a>';
                            }
                            if (Auth::user()->can('delete', $recruitment->plan)) {
                                $delete = '<form style="display:inline" action="'. route("plans.destroy", $recruitment->plan->id) . '" method="POST">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <button type="submit" name="submit" onclick="return confirm(\'Bạn có muốn xóa?\');" class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i></button>
                                        <input type="hidden" name="_token" value="' . csrf_token(). '"></form>';
                            }

                            if (Auth::user()->can('approve', $recruitment->plan)) {
                                $approve = '<a href="#approve-plan-{{' . $recruitment->plan->id . '}}" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#approve-plan-' . $recruitment->plan->id. '"><i class="fas fa-check-square"></i></a>';
                            }
                            $action = $update . $delete . $approve;
                            @endphp
                            {!! $action !!}
                        </td>
                        @endif
                    </tr>
                </tbody>
            </table>
            @endif
            </div>
        </div>
    </div>
</div>


<!-- Modals for create plan -->
<form class="form-horizontal" method="post" action="{{ route('plans.store') }}" name="make_plan" id="make_plan" novalidate="novalidate">
    {{ csrf_field() }}
    <div class="modal fade" id="create_plan">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4>Tạo kế hoạch</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="recruitment_id" id="recruitment_id" value="{{$recruitment->id}}">
                    <div class="row">
                        <div class="col-12">
                            <div class="control-group">
                                <label class="required-field" class="control-label">Cách thức tuyển</label>
                                <div class="controls">
                                    <select name="method_id[]" id="method_id[]" data-placeholder="Chọn" class="form-control select2" multiple="multiple" style="width: 100%;">
                                        @foreach($methods as $method)
                                            <option value="{{$method->id}}">{{$method->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="control-group">
                                <label class="control-label">Ngân sách (VNĐ)</label>
                                <div class="controls">
                                    <input type="number" class="form-control" name="budget" id="budget" required="">
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

<!-- Modals for edit plan -->
@if ($recruitment->plan)
<form class="form-horizontal" method="post" action="{{ route('plans.update', $recruitment->plan->id) }}" name="edit_plan" id="edit_plan" novalidate="novalidate">
    {{ csrf_field() }}
    @method('PATCH')
    <div class="modal fade" id="edit-plan-{{$recruitment->plan->id}}">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4>Sửa kế hoạch</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="recruitment_id" id="recruitment_id" value="{{$recruitment->id}}">
                    <div class="row">
                        <div class="col-12">
                            <div class="control-group">
                                <label class="required-field" class="control-label">Cách thức tuyển</label>
                                <div class="controls">
                                    <select name="method_id[]" id="method_id[]" data-placeholder="Chọn" class="form-control select2" multiple="multiple" style="width: 100%;">
                                        @php
                                            $selected_methods = App\Models\PlanMethod::where('plan_id', $recruitment->plan->id)->pluck('method_id')->toArray();
                                        @endphp
                                        @foreach($methods as $method)
                                            <option value="{{$method->id}}" @if(in_array($method->id, $selected_methods)) selected="selected" @endif>{{$method->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="control-group">
                                <label class="control-label">Ngân sách (VNĐ)</label>
                                <div class="controls">
                                    <input type="number" class="form-control" name="budget" id="budget" value="{{$recruitment->plan->budget}}" required="">
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
@endif

@if ($recruitment->plan)
<!-- Modals for plan approve -->
<form class="form-horizontal" method="post" action="{{route('plans.approve', $recruitment->plan->id)}}" name="make_plan_approve" id="make_plan_approve" novalidate="novalidate">
    {{ csrf_field() }}
    <div class="modal fade" id="approve-plan-{{$recruitment->plan->id}}">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="control-group">
                                <label class="required-field" class="control-label">Kết quả</label>
                                <div class="controls">
                                    <select name="approver_result" id="approver_result" class="form-control" style="width: 100%;">
                                        <option disabled="disabled" selected="selected" disabled>-- Chọn --</option>
                                        <option value="Đồng ý">Đồng ý</option>
                                        <option value="Từ chối">Từ chối</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-12">
                            <div class="control-group">
                                <label class="control-label">Giải thích</label>
                                <div class="controls">
                                    <input type="text" class="form-control" name="approver_comment" id="approver_comment" required="">
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
@endif

@push('scripts')
<script>
    $(function () {
        //Initialize Select2 Elements
        $('.select2').select2({
            theme: 'bootstrap4'
        })
    })
</script>
@endpush

