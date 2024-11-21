<!-- Lọc hồ sơ -->
<div class="tab-pane fade" id="recruitment-5" role="tabpanel" aria-labelledby="recruitment-tab-5">
    <div class="card card-secondary">
        <div class="card-header">
            Lọc hồ sơ
        </div>
        <!-- /.card-header -->
        <div class="card-body">
            <div class="table-responsive">
              <table id="candidates-table" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>Tên</th>
                  <th>Nơi làm việc</th>
                  <th>Mức lương</th>
                  <th>Kiểm tra</th>
                  <th>Duyệt</th>
                  <th>Mời phỏng vấn</th>
                  <th>Đợt</th>
                  @if('Ban lãnh đạo' != Auth::user()->role->name)
                  <th></th>
                  @endif
                </tr>
                </thead>

                <tbody>
                    @foreach ($recruitment->candidates as $candidate)
                    <tr>
                      <td>
                        <a href="{{route('candidates.show', $candidate->id)}}">{{$candidate->name}}</a>
                      </td>
                      @php
                          $recruitment_candidate = App\Models\RecruitmentCandidate::where('recruitment_id', $recruitment->id)->where('candidate_id', $candidate->id)->first();
                          $filter = App\Models\Filter::where('recruitment_candidate_id', $recruitment_candidate->id)->first();
                          $action = '';
                          $first_interview_invitation = App\Models\FirstInterviewInvitation::where('recruitment_candidate_id', $recruitment_candidate->id)->first();
                          if ($filter) {// Có filter
                            if ($first_interview_invitation) {
                                $action = '<div class="dropdown">
                                    <a class="btn dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-expanded="false">
                                        Thao tác
                                    </a>

                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" href="#filter{{' . $recruitment_candidate->id . '}}" data-toggle="modal" data-target="#filter' . $recruitment_candidate->id. '">Lọc</a>
                                        <a class="dropdown-item" href="#approve{{' . $recruitment_candidate->id . '}}" data-toggle="modal" data-target="#approve' . $recruitment_candidate->id. '">Duyệt</a>
                                        <a class="dropdown-item" href="' . route("first_interview_invitations.add", $recruitment_candidate->id) . '">Mời</a>
                                        <a class="dropdown-item" href="' . route("first_interview_invitations.feedback", $recruitment_candidate->id) . '">Phản hồi</a>
                                        <form style="display:inline" action="'. route("filters.destroy", $filter->id) . '" method="POST">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <button class="dropdown-item" type="submit" name="submit" onclick="return confirm(\'Bạn có muốn xóa?\');" class="btn btn-sm">Xóa</button>
                                        <input type="hidden" name="_token" value="' . csrf_token(). '"></form>
                                    </div>
                                    </div>';
                            } else {
                                if ('Đạt' == $filter->approver_result) {
                                    $action = '<div class="dropdown">
                                        <a class="btn dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-expanded="false">
                                            Thao tác
                                        </a>

                                        <div class="dropdown-menu">
                                            <a class="dropdown-item" href="#filter{{' . $recruitment_candidate->id . '}}" data-toggle="modal" data-target="#filter' . $recruitment_candidate->id. '">Lọc</a>
                                            <a class="dropdown-item" href="#approve{{' . $recruitment_candidate->id . '}}" data-toggle="modal" data-target="#approve' . $recruitment_candidate->id. '">Duyệt</a>
                                            <a class="dropdown-item" href="' . route("first_interview_invitations.add", $recruitment_candidate->id) . '">Mời</a>
                                            <form style="display:inline" action="'. route("filters.destroy", $filter->id) . '" method="POST">
                                            <input type="hidden" name="_method" value="DELETE">
                                            <button class="dropdown-item" type="submit" name="submit" onclick="return confirm(\'Bạn có muốn xóa?\');" class="btn btn-sm">Xóa</button>
                                            <input type="hidden" name="_token" value="' . csrf_token(). '"></form>
                                        </div>
                                        </div>';
                                } else {
                                    $action = '<div class="dropdown">
                                        <a class="btn dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-expanded="false">
                                            Thao tác
                                        </a>

                                        <div class="dropdown-menu">
                                            <a class="dropdown-item" href="#filter{{' . $recruitment_candidate->id . '}}" data-toggle="modal" data-target="#filter' . $recruitment_candidate->id. '">Lọc</a>
                                            <a class="dropdown-item" href="#approve{{' . $recruitment_candidate->id . '}}" data-toggle="modal" data-target="#approve' . $recruitment_candidate->id. '">Duyệt</a>
                                            <form style="display:inline" action="'. route("filters.destroy", $filter->id) . '" method="POST">
                                            <input type="hidden" name="_method" value="DELETE">
                                            <button class="dropdown-item" type="submit" name="submit" onclick="return confirm(\'Bạn có muốn xóa?\');" class="btn btn-sm">Xóa</button>
                                            <input type="hidden" name="_token" value="' . csrf_token(). '"></form>
                                        </div>
                                        </div>';
                                }
                            }
                          } else {//Chưa có filter
                                $action = '<div class="dropdown">
                                        <a class="btn dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-expanded="false">
                                            Thao tác
                                        </a>

                                        <div class="dropdown-menu">
                                            <a class="dropdown-item" href="#filter{{' . $recruitment_candidate->id . '}}" data-toggle="modal" data-target="#filter' . $recruitment_candidate->id. '">Lọc</a>
                                        </div>
                                        </div>';
                          }
                      @endphp
                      @if($filter)
                      <td>
                        {{$filter->work_location}}
                      </td>
                      <td>{{number_format($filter->salary, 0, '.', ',')}} <sup>đ</sup></td>
                      <td>
                        @if($filter->reviewer_result == 'Đạt')
                            <span class="badge badge-success">{{$filter->reviewer_result}}</span> - {{$filter->reviewer->name}}
                            @if ($filter->reviewer_comment)
                                <br><small>({{$filter->reviewer_comment}})</small>
                            @endif
                        @else
                            <span class="badge badge-danger">{{$filter->reviewer_result}}</span> - {{$filter->reviewer->name}}
                            @if ($filter->reviewer_comment)
                                <br><small>({{$filter->reviewer_comment}})</small>
                            @endif
                        @endif
                      </td>
                      <td>
                        @if ($filter->approver_result )
                            @if($filter->approver_result == 'Đạt')
                                <span class="badge badge-success">{{$filter->approver_result}}</span> - {{$filter->approver->name}}
                            @else
                                <span class="badge badge-danger">{{$filter->approver_result}}</span> - {{$filter->approver->name}}
                            @endif
                        @endif
                      </td>
                      <td>
                        @if ($first_interview_invitation)
                            @if ('Đã gửi' == $first_interview_invitation->status)
                                - {{date('d/m/Y H:i', strtotime($first_interview_invitation->interview_time))}} tại {{$first_interview_invitation->interview_location}} <br>
                            @endif
                            @if ('Đồng ý' == $first_interview_invitation->feedback)
                                - <span class="badge badge-success">{{$first_interview_invitation->feedback}}</span>
                            @elseif ('Từ chối' == $first_interview_invitation->feedback)
                                - <span class="badge badge-danger">{{$first_interview_invitation->feedback}}</span>
                                @if ($first_interview_invitation->note)
                                  <small>({{$first_interview_invitation->note}})</small>
                                @endif
                            @elseif ('Hẹn lại' == $first_interview_invitation->feedback)
                                - <span class="badge badge-warning">{{$first_interview_invitation->feedback}}</span>
                                @if ($first_interview_invitation->note)
                                  <small>({{$first_interview_invitation->note}})</small>
                                @endif
                            @endif
                        @endif
                      </td>
                      @else
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      @endif
                      <td>{{$recruitment_candidate->batch}}</td>
                      @if('Ban lãnh đạo' != Auth::user()->role->name)
                      <td>{!! $action !!}</td>
                      @endif
                    </tr>
                    <!-- Modals for filter -->
                    @if ($filter)
                    <form class="form-horizontal" method="post" action="{{ route('filters.update', $filter->id) }}" name="update_filter" id="update_filter" novalidate="novalidate">
                        @method('PATCH')
                    @else
                    <form class="form-horizontal" method="post" action="{{ route('filters.store') }}" name="create_filter" id="filter" novalidate="novalidate">
                    @endif
                        {{ csrf_field() }}
                        <div class="modal fade" tabindex="-1" id="filter{{$recruitment_candidate->id}}">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4>Lọc ứng viên</h4>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <input type="hidden" name="recruitment_candidate_id" id="recruitment_candidate_id" value="{{$recruitment_candidate->id}}">
                                        <div class="row">
                                            <div class="col-6">
                                                <label class="required-field" class="control-label">Nơi làm việc</label>
                                                <div class="controls">
                                                    <input type="text" class="form-control" name="work_location" id="work_location" required="" @if ($filter) value="{{$filter->work_location}}" @endif>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="control-group">
                                                    <label class="required-field" class="control-label">Lương mong muốn</label>
                                                    <div class="custom-file text-left">
                                                        <input type="number" class="form-control" name="salary" id="salary" required="" @if ($filter) value="{{$filter->salary}}" @endif>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-6">
                                                <div class="control-group">
                                                    <label class="control-label">Ghi chú</label>
                                                    <div class="custom-file text-left">
                                                        <input type="text" class="form-control" name="reviewer_comment" id="reviewer_comment" required="" @if ($filter) value="{{$filter->reviewer_comment}}" @endif>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="control-group">
                                                    <label class="required-field" class="control-label">Kết quả</label>
                                                    <div class="controls">
                                                        <select name="reviewer_result" id="reviewer_result" data-placeholder="Chọn" class="form-control select2" style="width: 100%;">
                                                            <option value="-- Chọn --" disabled="disabled" selected="selected">-- Chọn --</option>
                                                            <option value="Đạt" @if ($filter && 'Đạt' == $filter->reviewer_result) selected="selected" @endif>Đạt</option>
                                                            <option value="Loại" @if ($filter && 'Loại' == $filter->reviewer_result) selected="selected" @endif>Loại</option>
                                                        </select>
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


                    <!-- Modals for approve -->
                    @if ($filter)
                    <form class="form-horizontal" method="post" action="{{ route('filters.approve', $filter->id) }}" name="approve_filter" id="approve_filter" novalidate="novalidate">
                        {{ csrf_field() }}
                        <div class="modal fade" tabindex="-1" id="approve{{$recruitment_candidate->id}}">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4>Lọc ứng viên</h4>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-6">
                                                <div class="control-group">
                                                    <label class="required-field" class="control-label">Kết quả</label>
                                                    <div class="controls">
                                                        <select name="approver_result" id="approver_result" data-placeholder="Chọn" class="form-control select2" style="width: 100%;">
                                                            <option value="-- Chọn --" disabled="disabled" selected="selected">-- Chọn --</option>
                                                            <option value="Đạt" @if ($filter && 'Đạt' == $filter->approve_result) selected="selected" @endif>Đạt</option>
                                                            <option value="Loại" @if ($filter && 'Loại' == $filter->approve_result) selected="selected" @endif>Loại</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="control-group">
                                                    <label class="control-label">Ghi chú</label>
                                                    <div class="custom-file text-left">
                                                        <input type="text" class="form-control" name="approver_comment" id="approver_comment" required="" @if ($filter) value="{{$filter->approver_comment}}" @endif>
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
                    <!-- /.modal -->
                    @endforeach
                </tbody>
              </table>
            </div>
        </div>
    </div>
</div>
