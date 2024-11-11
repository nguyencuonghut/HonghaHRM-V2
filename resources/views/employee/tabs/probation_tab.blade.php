<!-- Probation Tab -->
<div class="tab-pane" id="tab-probation">
    @php
        //Find the Recruitment according to this Employee
        $candidates = App\Models\Candidate::where('name', $employee->name)
                                ->where('date_of_birth', $employee->date_of_birth)
                                ->get();
        $recruitment_candidates = null;
        if ($candidates->count()) {
            //Continue to check if many Candidates have the same name and dob
            if ($candidates->count() > 1) {
                //Check the CCCD
                $candidate = App\Models\Candidate::where('name', $employee->name)
                                                ->where('date_of_birth', $employee->date_of_birth)
                                                ->where('cccd', $employee->cccd)
                                                ->first();
            } else {
                $candidate = App\Models\Candidate::where('name', $employee->name)
                                        ->where('date_of_birth', $employee->date_of_birth)
                                        ->first();
            }
            //Get the latest RecruitmentCandiate
            $recruitment_candidates = App\Models\RecruitmentCandidate::where('candidate_id', $candidate->id)
                                                                    ->orderBy('id', 'desc')
                                                                    ->get();
        }
    @endphp

    @can('create', App\Models\Probation::class)
        @if ($candidates->count())
        <a href="#create_probation{{' . $employee->id . '}}" class="btn btn-success" data-toggle="modal" data-target="#create_probation{{$employee->id}}"><i class="fas fa-plus"></i></a>
        <br>
        <br>
        @endif
    @endcan
    <table id="employee-probations-table" class="table table-bordered table-striped">
        <thead>
          <tr>
            <th>Vị trí</th>
            <th>Thời gian thử việc</th>
            <th>Người tạo</th>
            <th>Kết quả</th>
            @can('create', App\Models\Probation::class)
            <th>Thao tác</th>
            @endcan
          </tr>
        </thead>
        <tbody>
            @foreach ($probations as $probation)
            <tr>
              @php
                  $probation = App\Models\Probation::findOrFail($probation->id);
                  $action_edit_probation = '<a href="' . route("probations.edit", $probation->id) . '" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></a>
                          <form style="display:inline" action="'. route("probations.destroy", $probation->id) . '" method="POST">
                          <input type="hidden" name="_method" value="DELETE">
                          <button type="submit" name="submit" onclick="return confirm(\'Bạn có muốn xóa?\');" class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i></button>
                          <input type="hidden" name="_token" value="' . csrf_token(). '"></form>';

                  $action = '';
                  if (Auth::user()->can('create', App\Models\Probation::class)) {
                      $action = $action . $action_edit_probation;
                  }
              @endphp
              <td>{{ $probation->recruitment->position->name }} - {{$probation->recruitment->position->department->name}}</td>
              <td>
                @php
                    $url = '<a href="'.route('probations.show', $probation->id).'">'.date('d/m/Y', strtotime($probation->start_date)). ' - ' . date('d/m/Y', strtotime($probation->end_date)) . '</a>';
                @endphp
                {!! $url !!}
              </td>
              <td>{{ $probation->creator->name }}</td>
              <td>
                @if($probation->result_manager_status
                    && $probation->approver_result)
                    @if('Đạt' == $probation->result_manager_status)
                    <span class="badge badge-success">{{ $probation->result_manager_status }}</span>
                    @else
                    <span class="badge badge-danger">{{ $probation->result_manager_status }}</span>
                    @endif

                @else
                -
                @endif
              </td>
              @can('create', App\Models\Probation::class)
              <td>{!! $action !!}</td>
              @endcan
            </tr>
          @endforeach
        </tbody>
    </table>

    @if ($recruitment_candidates)
    <!-- Modals for create employee probation -->
    <form class="form-horizontal" method="post" action="{{ route('probations.store', $employee->id) }}" name="create_probation" id="create_probation" novalidate="novalidate">
        {{ csrf_field() }}
        <div class="modal fade" id="create_probation{{$employee->id}}">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4>Kế hoạch thử việc của {{$employee->name}}</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="employee_id" id="employee_id" value="{{$employee->id}}">

                        <div class="row">
                            <div class="col-12">
                                <div class="control-group">
                                    <label class="required-field" class="control-label">Tuyển dụng</label>
                                    <div class="controls">
                                        <select name="recruitment_id" id="recruitment_id" data-placeholder="Chọn tuyển dụng" class="form-control select2" style="width: 100%;">
                                            <option value="-- Chọn --" disabled="disabled" selected="selected">-- Chọn --</option>
                                            @foreach($recruitment_candidates as $recruitment_candidate)
                                                <option value="{{$recruitment_candidate->recruitment->id}}">{{$recruitment_candidate->recruitment->position->name}} - {{ date('d/m/Y', strtotime($recruitment_candidate->recruitment->created_at)) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <label class="required-field">Thời gian bắt đầu</label>
                                <div class="input-group date" id="start_date" data-target-input="nearest">
                                    <input type="text" name="start_date" class="form-control datetimepicker-input" data-target="#start_date"/>
                                    <div class="input-group-append" data-target="#start_date" data-toggle="datetimepicker">
                                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <label class="required-field">Thời gian kết thúc</label>
                                <div class="input-group date" id="end_date" data-target-input="nearest">
                                    <input type="text" name="end_date" class="form-control datetimepicker-input" data-target="#end_date"/>
                                    <div class="input-group-append" data-target="#end_date" data-toggle="datetimepicker">
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
    @endif
</div>

@push('scripts')
<script>
    $(function () {
        // Summernote
        $("#work_requirement").on("summernote.enter", function(we, e) {
            $(this).summernote("pasteHTML", "<br><br>");
            e.preventDefault();
        });
        $('#work_requirement').summernote({
            height: 90,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'underline', 'clear']],
                ['color', ['color']],
            ]
        })

        //Date picker
        $('#start_date').datetimepicker({
            format: 'DD/MM/YYYY'
        });
        $('#end_date').datetimepicker({
            format: 'DD/MM/YYYY'
        });
        $('#work_deadline').datetimepicker({
            format: 'DD/MM/YYYY'
        });
    });
</script>
@endpush




