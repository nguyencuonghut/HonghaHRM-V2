<!-- Phỏng vấn lần 2 -->
<div class="tab-pane fade" id="recruitment-8" role="tabpanel" aria-labelledby="recruitment-tab-8">
    @foreach ($recruitment->candidates as $candidate)
      @php
        $recruitment_candidate = App\Models\RecruitmentCandidate::where('recruitment_id', $recruitment->id)->where('candidate_id', $candidate->id)->first();
        $second_interview_invitation = App\Models\SecondInterviewInvitation::where('recruitment_candidate_id', $recruitment_candidate->id)->first();
        $second_interview_details = App\Models\SecondInterviewDetail::where('recruitment_candidate_id', $recruitment_candidate->id)->get();
        $first_interview_result = App\Models\FirstInterviewResult::where('recruitment_candidate_id', $recruitment_candidate->id)->first();
      @endphp
      @if ((null == $second_interview_invitation && ($first_interview_result && 'Đạt' == $first_interview_result->result))
      || ($second_interview_invitation && 'Từ chối' != $second_interview_invitation->feedback))
        <div class="card card-secondary">
            <div class="card-header">
                {{$candidate->name}}
            </div>
            <div class="card-body">
                @can('create', App\Models\SecondInterviewDetail::class)
                <a href="#second_interview_detail{{' . $recruitment_candidate->id . '}}" class="btn btn-success" data-toggle="modal" data-target="#create_second_interview_detail{{$recruitment_candidate->id}}"><i class="fas fa-plus"></i></a>
                <br>
                <br>
                @endcan
                <div class="table-responsive">
                    <table id="second-interview-table" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Nội dung phỏng vấn</th>
                            <th>Nhận xét</th>
                            <th>Điểm</th>
                            @can('create', App\Models\SecondInterviewDetail::class)
                            <th>Thao tác</th>
                            @endcan
                        </tr>
                    </thead>
                    <tbody>
                        @if ($second_interview_details)
                          @foreach ($second_interview_details  as $second_interview_detail)
                          @php
                            $action = '<a href="#edit_second_interview_detail{{' . $second_interview_detail->id . '}}" class="btn btn-success btn-sm" data-toggle="modal" data-target="#edit_second_interview_detail' . $second_interview_detail->id. '"><i class="fas fa-edit"></i></a>
                                    <form style="display:inline" action="'. route("second_interview_details.destroy", $second_interview_detail->id) . '" method="POST">
                                    <input type="hidden" name="_method" value="DELETE">
                                    <button type="submit" name="submit" onclick="return confirm(\'Bạn có muốn xóa?\');" class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i></button>
                                    <input type="hidden" name="_token" value="' . csrf_token(). '"></form>';
                          @endphp
                          <tr>
                            <td>{{$second_interview_detail->content}}</td>
                            <td>{{$second_interview_detail->comment}}</td>
                            <td>{{$second_interview_detail->score}}</td>
                            @can('create', App\Models\SecondInterviewResult::class)
                            <td>{!! $action !!}</td>
                            @endcan

                            <!-- Modals for edit second_interview_detail -->
                            @if ($second_interview_detail)
                                <form class="form-horizontal" method="post" action="{{ route('second_interview_details.update', $second_interview_detail->id) }}" name="update_second_interview_detail" id="update_second_interview_detail" novalidate="novalidate">
                                    @method('PATCH')
                                    {{ csrf_field() }}
                                    <div class="modal fade" tabindex="-1" id="edit_second_interview_detail{{$second_interview_detail->id}}">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h4>Phỏng vấn lần 2: {{$candidate->name}}</h4>
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
                                                                <input type="text" class="form-control" name="content" id="content" required="" @if ($second_interview_detail) value="{{$second_interview_detail->content}}" @endif>
                                                            </div>
                                                        </div>
                                                        <div class="col-4">
                                                            <label class="control-label">Nhận xét</label>
                                                            <div class="controls">
                                                                <input type="text" class="form-control" name="comment" id="comment" required="" @if ($second_interview_detail) value="{{$second_interview_detail->comment}}" @endif>
                                                            </div>
                                                        </div>
                                                        <div class="col-4">
                                                            <div class="control-group">
                                                                <div class="control-group">
                                                                    <label class="control-label">Điểm</label>
                                                                    <div class="controls">
                                                                        <select name="score" id="score" data-placeholder="Chọn" class="form-control select2" style="width: 100%;">
                                                                            <option value="-- Chọn --" disabled="disabled" selected="selected">-- Chọn --</option>
                                                                            <option value="1" @if ($second_interview_detail && 1 == $second_interview_detail->score) selected="selected" @endif>1</option>
                                                                            <option value="2" @if ($second_interview_detail && 2 == $second_interview_detail->score) selected="selected" @endif>2</option>
                                                                            <option value="3" @if ($second_interview_detail && 3 == $second_interview_detail->score) selected="selected" @endif>3</option>
                                                                            <option value="4" @if ($second_interview_detail && 4 == $second_interview_detail->score) selected="selected" @endif>4</option>
                                                                            <option value="5" @if ($second_interview_detail && 5 == $second_interview_detail->score) selected="selected" @endif>5</option>
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
                              $second_interview_result = App\Models\SecondInterviewResult::where('recruitment_candidate_id', $recruitment_candidate->id)->first();
                              $action = '';
                              if ($second_interview_details->count()) {
                                $action = '<a href="#create_second_interview_result" class="btn btn-success btn-sm" data-toggle="modal" data-target="#create_second_interview_result' . $recruitment_candidate->id. '"><i class="fas fa-plus"></i></a>';
                                if ($second_interview_result) {
                                    $action = '<a href="#edit_second_interview_result{{' . $recruitment_candidate->id . '}}" class="btn btn-success btn-sm" data-toggle="modal" data-target="#edit_second_interview_result' . $recruitment_candidate->id. '"><i class="fas fa-edit"></i></a>
                                        <form style="display:inline" action="'. route("second_interview_results.destroy", $second_interview_result->id) . '" method="POST">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <button type="submit" name="submit" onclick="return confirm(\'Bạn có muốn xóa?\');" class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i></button>
                                        <input type="hidden" name="_token" value="' . csrf_token(). '"></form>';
                                }
                              }
                            @endphp
                            @if ($second_interview_result)
                                <td colspan="3"><strong>Kết quả: </strong>  <span class="badge @if ("Đạt" == $second_interview_result->result) badge-success @else badge-danger @endif">{{$second_interview_result->result}}</span> - phỏng vấn bởi {{$second_interview_result->interviewer->name}}</td>
                              @can('create', App\Models\SecondInterviewResult::class)
                                <td>{!! $action !!}</td>
                              @endcan
                            @else
                              @can('create', App\Models\SecondInterviewResult::class)
                                <td colspan="3"><strong>Kết quả</strong></td>
                                <td>{!! $action !!}</td>
                              @endcan
                            @endif

                            <!-- Modals for create second_interview_result -->
                            <form class="form-horizontal" method="post" action="{{ route('second_interview_results.store') }}" name="create_second_interview_result" id="create_second_interview_result" novalidate="novalidate">
                                {{ csrf_field() }}
                                <div class="modal fade" tabindex="-1" id="create_second_interview_result{{$recruitment_candidate->id}}">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h4>Phỏng vấn lần 2: {{$candidate->name}}</h4>
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

                            <!-- Modals for edit second_interview_result -->
                            @if ($second_interview_result)
                                <form class="form-horizontal" method="post" action="{{ route('second_interview_results.update', $second_interview_result->id) }}" name="edit_second_interview_result" id="edit_second_interview_result" novalidate="novalidate">
                                    {{ csrf_field() }}
                                    @method('PATCH')
                                    <div class="modal fade" tabindex="-1" id="edit_second_interview_result{{$recruitment_candidate->id}}">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h4>Phỏng vấn lần 2: {{$candidate->name}}</h4>
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
                                                                            <option value="Đạt" @if ($second_interview_result && "Đạt" == $second_interview_result->result) selected="selected"@endif>Đạt</option>
                                                                            <option value="Không đạt" @if ($second_interview_result && "Không đạt" == $second_interview_result->result) selected="selected"@endif>Không đạt</option>
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
                              $second_interview_result = App\Models\SecondInterviewResult::where('recruitment_candidate_id', $recruitment_candidate->id)->first();
                          @endphp
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

        <!-- Modals for create second_interview_detail -->
        <form class="form-horizontal" method="post" action="{{ route('second_interview_details.store') }}" name="create_second_interview_detail" id="create_second_interview_detail" novalidate="novalidate">
            {{ csrf_field() }}
            <div class="modal fade" tabindex="-1" id="create_second_interview_detail{{$recruitment_candidate->id}}">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4>Phỏng vấn lần 2: {{$candidate->name}}</h4>
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
    @endforeach
</div>
