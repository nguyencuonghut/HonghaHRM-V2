
<!-- Phỏng vấn lần 1 -->
<div class="tab-pane fade" id="recruitment-7" role="tabpanel" aria-labelledby="recruitment-tab-7">
    @foreach ($recruitment->candidates as $candidate)
      @php
        $recruitment_candidate = App\Models\RecruitmentCandidate::where('recruitment_id', $recruitment->id)->where('candidate_id', $candidate->id)->first();
        $initial_interview = App\Models\InitialInterview::where('recruitment_candidate_id', $recruitment_candidate->id)->first();
        $first_interview_details = App\Models\FirstInterviewDetail::where('recruitment_candidate_id', $recruitment_candidate->id)->get();
      @endphp
      @if ($initial_interview)
        @if ('Đạt' == $initial_interview->result)
        <div class="card card-secondary">
            <div class="card-header">
                {{$candidate->name}}
            </div>
            <div class="card-body">
                @can('create', App\Models\FirstInterviewDetail::class)
                <a href="#first_interview_detail{{' . $recruitment_candidate->id . '}}" class="btn btn-success" data-toggle="modal" data-target="#create_first_interview_detail{{$recruitment_candidate->id}}"><i class="fas fa-plus"></i></a>
                <br>
                <br>
                @endcan
                <div class="table-responsive">
                    <table id="first-interview-table" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Nội dung phỏng vấn</th>
                            <th>Nhận xét</th>
                            <th>Điểm</th>
                            @can('create', App\Models\FirstInterviewDetail::class)
                            <th>Thao tác</th>
                            @endcan
                        </tr>
                    </thead>
                    <tbody>
                        @if ($first_interview_details)
                          @foreach ($first_interview_details  as $first_interview_detail)
                          @php
                            $action = '<a href="#edit_first_interview_detail{{' . $first_interview_detail->id . '}}" class="btn btn-success btn-sm" data-toggle="modal" data-target="#edit_first_interview_detail' . $first_interview_detail->id. '"><i class="fas fa-edit"></i></a>
                                    <form style="display:inline" action="'. route("first_interview_details.destroy", $first_interview_detail->id) . '" method="POST">
                                    <input type="hidden" name="_method" value="DELETE">
                                    <button type="submit" name="submit" onclick="return confirm(\'Bạn có muốn xóa?\');" class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i></button>
                                    <input type="hidden" name="_token" value="' . csrf_token(). '"></form>';
                          @endphp
                          <tr>
                            <td>{{$first_interview_detail->content}}</td>
                            <td>{{$first_interview_detail->comment}}</td>
                            <td>{{$first_interview_detail->score}}</td>
                            @can('create', App\Models\FirstInterviewResult::class)
                            <td>{!! $action !!}</td>
                            @elsecan('create', App\Models\SecondInterviewInvitation::class)
                            <td></td>
                            @endcan

                            <!-- Modals for edit first_interview_detail -->
                            @if ($first_interview_detail)
                                <form class="form-horizontal" method="post" action="{{ route('first_interview_details.update', $first_interview_detail->id) }}" name="update_first_interview_detail" id="update_first_interview_detail" novalidate="novalidate">
                                    @method('PATCH')
                                    {{ csrf_field() }}
                                    <div class="modal fade" tabindex="-1" id="edit_first_interview_detail{{$first_interview_detail->id}}">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h4>Phỏng vấn lần 1: {{$candidate->name}}</h4>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <input type="hidden" name="recruitment_candidate_id" id="recruitment_candidate_id" value="{{$recruitment_candidate->id}}">
                                                    <div class="row">
                                                        <div class="col-4">
                                                            <label class="control-label">Nội dung</label>
                                                            <div class="controls">
                                                                <input type="text" class="form-control" name="content" id="content" required="" @if ($first_interview_detail) value="{{$first_interview_detail->content}}" @endif>
                                                            </div>
                                                        </div>
                                                        <div class="col-4">
                                                            <label class="control-label">Nhận xét</label>
                                                            <div class="controls">
                                                                <input type="text" class="form-control" name="comment" id="comment" required="" @if ($first_interview_detail) value="{{$first_interview_detail->comment}}" @endif>
                                                            </div>
                                                        </div>
                                                        <div class="col-4">
                                                            <div class="control-group">
                                                                <div class="control-group">
                                                                    <label class="control-label">Điểm</label>
                                                                    <div class="controls">
                                                                        <select name="score" id="score" data-placeholder="Chọn" class="form-control select2" style="width: 100%;">
                                                                            <option value="-- Chọn --" disabled="disabled" selected="selected">-- Chọn --</option>
                                                                            <option value="1" @if ($first_interview_detail && 1 == $first_interview_detail->score) selected="selected" @endif>1</option>
                                                                            <option value="2" @if ($first_interview_detail && 2 == $first_interview_detail->score) selected="selected" @endif>2</option>
                                                                            <option value="3" @if ($first_interview_detail && 3 == $first_interview_detail->score) selected="selected" @endif>3</option>
                                                                            <option value="4" @if ($first_interview_detail && 4 == $first_interview_detail->score) selected="selected" @endif>4</option>
                                                                            <option value="5" @if ($first_interview_detail && 5 == $first_interview_detail->score) selected="selected" @endif>5</option>
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
                            @endif
                          </tr>
                          @endforeach
                          <tr>
                            @php
                              $first_interview_result = App\Models\FirstInterviewResult::where('recruitment_candidate_id', $recruitment_candidate->id)->first();

                              if ($first_interview_details) {
                                $action = '<a href="#create_first_interview_result" class="btn btn-success btn-sm" data-toggle="modal" data-target="#create_first_interview_result' . $recruitment_candidate->id. '"><i class="fas fa-plus"></i></a>';
                                if ($first_interview_result) {
                                    $action = '<a href="#edit_first_interview_result{{' . $recruitment_candidate->id . '}}" class="btn btn-success btn-sm" data-toggle="modal" data-target="#edit_first_interview_result' . $recruitment_candidate->id. '"><i class="fas fa-edit"></i></a>
                                        <form style="display:inline" action="'. route("first_interview_results.destroy", $first_interview_result->id) . '" method="POST">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <button type="submit" name="submit" onclick="return confirm(\'Bạn có muốn xóa?\');" class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i></button>
                                        <input type="hidden" name="_token" value="' . csrf_token(). '"></form>';
                                }
                              }
                            @endphp
                            @if ($first_interview_result)
                            <td colspan="3"><strong>Kết quả: </strong>  <span class="badge @if ("Đạt" == $first_interview_result->result) badge-success @else badge-danger @endif">{{$first_interview_result->result}}</span> - phỏng vấn bởi {{$first_interview_result->interviewer->name}}</td>
                            @can('create', App\Models\FirstInterviewResult::class)
                            <td>{!! $action !!}</td>
                            @elsecan('create', App\Models\SecondInterviewInvitation::class)
                            <td></td>
                            @endcan
                            @elseif ($first_interview_details->count())
                            <td colspan="3"><strong>Kết quả</strong></td>
                            @can('create', App\Models\FirstInterviewResult::class)
                            <td>{!! $action !!}</td>
                            @elsecan('create', App\Models\SecondInterviewInvitation::class)
                            <td></td>
                            @endcan
                            @endif

                            <!-- Modals for create first_interview_result -->
                            <form class="form-horizontal" method="post" action="{{ route('first_interview_results.store') }}" name="create_first_interview_result" id="create_first_interview_result" novalidate="novalidate">
                                {{ csrf_field() }}
                                <div class="modal fade" tabindex="-1" id="create_first_interview_result{{$recruitment_candidate->id}}">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h4>Phỏng vấn lần 1: {{$candidate->name}}</h4>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <input type="hidden" name="recruitment_candidate_id" id="recruitment_candidate_id" value="{{$recruitment_candidate->id}}">
                                                <div class="row">
                                                    <div class="col-12">
                                                        <div class="control-group">
                                                            <div class="control-group">
                                                                <label class="control-label">Kết quả</label>
                                                                <div class="controls">
                                                                    <select name="result" id="result" data-placeholder="Chọn" class="form-control select2" style="width: 100%;">
                                                                        <option value="-- Chọn --" disabled="disabled" selected="selected">-- Chọn --</option>
                                                                        <option value="Đạt">Đạt</option>
                                                                        <option value="Không đạt">Không đạt</option>
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

                            <!-- Modals for edit first_interview_result -->
                            @if ($first_interview_result)
                                <form class="form-horizontal" method="post" action="{{ route('first_interview_results.update', $first_interview_result->id) }}" name="edit_first_interview_result" id="edit_first_interview_result" novalidate="novalidate">
                                    {{ csrf_field() }}
                                    @method('PATCH')
                                    <div class="modal fade" tabindex="-1" id="edit_first_interview_result{{$recruitment_candidate->id}}">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h4>Phỏng vấn lần 1: {{$candidate->name}}</h4>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <input type="hidden" name="recruitment_candidate_id" id="recruitment_candidate_id" value="{{$recruitment_candidate->id}}">
                                                    <div class="row">
                                                        <div class="col-12">
                                                            <div class="control-group">
                                                                <div class="control-group">
                                                                    <label class="control-label">Kết quả</label>
                                                                    <div class="controls">
                                                                        <select name="result" id="result" data-placeholder="Chọn" class="form-control select2" style="width: 100%;">
                                                                            <option value="-- Chọn --" disabled="disabled" selected="selected">-- Chọn --</option>
                                                                            <option value="Đạt" @if ($first_interview_result && "Đạt" == $first_interview_result->result) selected="selected"@endif>Đạt</option>
                                                                            <option value="Không đạt" @if ($first_interview_result && "Không đạt" == $first_interview_result->result) selected="selected"@endif>Không đạt</option>
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
                            @endif
                          </tr>
                          @php
                              $first_interview_result = App\Models\FirstInterviewResult::where('recruitment_candidate_id', $recruitment_candidate->id)->first();
                              $second_interview_invitation = App\Models\SecondInterviewInvitation::where('recruitment_candidate_id', $recruitment_candidate->id)->first();
                          @endphp
                          @if ($first_interview_result)
                            @if ('Đạt' == $first_interview_result->result)
                              <tr>
                                @if ($second_interview_invitation)
                                    @php
                                    $action = '<a href="' . route('second_interview_invitations.add', $recruitment_candidate->id). '" class="btn btn-success btn-sm"><i class="fas fa-paper-plane"></i></a>';
                                    $action .= '&nbsp';
                                    $action .='<a href="'. route('second_interview_invitations.feedback', $recruitment_candidate->id) . '" class="btn btn-success btn-sm"><i class="fas fa-reply"></i></a>';
                                    @endphp
                                  <td colspan="3">
                                    <strong>Mời phỏng vấn:</strong> <br>
                                    @if ($second_interview_invitation)
                                        - {{date('d/m/Y H:i', strtotime($second_interview_invitation->interview_time))}}, tại {{$second_interview_invitation->interview_location}}
                                        <br>
                                        @if ('Đồng ý' == $second_interview_invitation->feedback)
                                        -
                                        <span class="badge badge-success">
                                            {{$second_interview_invitation->feedback}}
                                        </span>
                                        @elseif ('Từ chối' == $second_interview_invitation->feedback)
                                        -
                                        <span class="badge badge-danger">
                                            {{$second_interview_invitation->feedback}}
                                        </span>
                                        @elseif ('Hẹn lại' == $second_interview_invitation->feedback)
                                        -
                                        <span class="badge badge-warning">
                                            {{$second_interview_invitation->feedback}}
                                        </span>
                                        @endif
                                        {{$second_interview_invitation->note}}
                                    @else
                                    @endif
                                  </td>
                                  @can('create', App\Models\SecondInterviewInvitation::class)
                                  <td>{!! $action !!}</td>
                                  @elsecan('create', App\Models\FirstInterviewResult::class)
                                  <td></td>
                                  @endcan
                                @else
                                    @php
                                        $action = '<a href="' . route('second_interview_invitations.add', $recruitment_candidate->id). '" class="btn btn-success btn-sm"><i class="fas fa-paper-plane"></i></a>';
                                    @endphp
                                  <td colspan="3">
                                    <strong>Mời phỏng vấn:</strong>
                                  </td>
                                  @can('create', App\Models\SecondInterviewInvitation::class)
                                  <td>{!! $action !!}</td>
                                  @elsecan('create', App\Models\FirstInterviewResult::class)
                                  <td></td>
                                  @endcan
                                @endif
                              </tr>
                            @endif
                          @endif
                        @else
                        <td></td>
                        <td></td>
                        <td></td>
                        @endif
                    </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Modals for create first_interview_detail -->
        <form class="form-horizontal" method="post" action="{{ route('first_interview_details.store') }}" name="create_first_interview_detail" id="create_first_interview_detail" novalidate="novalidate">
            {{ csrf_field() }}
            <div class="modal fade" tabindex="-1" id="create_first_interview_detail{{$recruitment_candidate->id}}">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4>Phỏng vấn lần 1: {{$candidate->name}}</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="recruitment_candidate_id" id="recruitment_candidate_id" value="{{$recruitment_candidate->id}}">
                            <div class="row">
                                <div class="col-4">
                                    <label class="control-label">Nội dung</label>
                                    <div class="controls">
                                        <input type="text" class="form-control" name="content" id="content" required="">
                                    </div>
                                </div>
                                <div class="col-4">
                                    <label class="control-label">Nhận xét</label>
                                    <div class="controls">
                                        <input type="text" class="form-control" name="comment" id="comment" required="">
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="control-group">
                                        <div class="control-group">
                                            <label class="control-label">Điểm</label>
                                            <div class="controls">
                                                <select name="score" id="score" data-placeholder="Chọn" class="form-control select2" style="width: 100%;">
                                                    <option value="-- Chọn --" disabled="disabled" selected="selected">-- Chọn --</option>
                                                    <option value="1">1</option>
                                                    <option value="2">2</option>
                                                    <option value="3">3</option>
                                                    <option value="4">4</option>
                                                    <option value="5">5</option>
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

          @endif
      @endif
    @endforeach
</div>
