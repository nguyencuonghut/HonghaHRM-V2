
<!-- Offer chế độ -->
<div class="tab-pane fade" id="recruitment-9" role="tabpanel" aria-labelledby="recruitment-tab-9">
    @foreach ($recruitment->candidates as $candidate)
      @php
        $recruitment_candidate = App\Models\RecruitmentCandidate::where('recruitment_id', $recruitment->id)->where('candidate_id', $candidate->id)->first();
        $second_interview_result = App\Models\SecondInterviewResult::where('recruitment_candidate_id', $recruitment_candidate->id)->first();
        $offer = App\Models\Offer::where('recruitment_candidate_id', $recruitment_candidate->id)->first();
      @endphp
      @if ($second_interview_result)
        @if ('Đạt' == $second_interview_result->result)
        <div class="card card-secondary">
            <div class="card-header">
                {{$candidate->name}}
            </div>
            <div class="card-body">
                @if(!$offer)
                  @can('create', App\Models\Offer::class)
                  <a href="#create_offer{{' . $recruitment_candidate->id . '}}" class="btn btn-success" data-toggle="modal" data-target="#create_offer{{$recruitment_candidate->id}}"><i class="fas fa-plus"></i></a>
                  <br>
                  <br>
                  @endcan
                @endif
                <div class="table-responsive">
                    <table id="offer-table" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Lương hiện tại</th>
                            <th>Lương mong muốn</th>
                            <th>Lương vị trí</th>
                            <th>Lương năng lực</th>
                            <th>Phụ cấp vị trí</th>
                            <th>Lương bảo hiểm</th>
                            <th>Ghi chú</th>
                            <th>Phản hồi</th>
                            <th>Kết quả</th>
                            @can('create', App\Models\Offer::class)
                            <th>Thao tác</th>
                            @endcan
                        </tr>
                    </thead>
                    <tbody>
                        @if ($offer)
                          @php
                            $action_create_offer = '<a href="#edit_offer{{' . $recruitment_candidate->id . '}}" class="btn btn-success btn-sm" data-toggle="modal" data-target="#edit_offer' . $recruitment_candidate->id. '"><i class="fas fa-edit"></i></a>
                                    <form style="display:inline" action="'. route("offers.destroy", $offer->id) . '" method="POST">
                                    <input type="hidden" name="_method" value="DELETE">
                                    <button type="submit" name="submit" onclick="return confirm(\'Bạn có muốn xóa?\');" class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i></button>
                                    <input type="hidden" name="_token" value="' . csrf_token(). '"></form>';
                            $action_approve_offer = '<a href="#approve_offer{{' . $recruitment_candidate->id . '}}" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#approve_offer' . $recruitment_candidate->id. '"><i class="fas fa-check"></i></a>';

                            $action_create_employee = '<a href="' . route("employees.create_from_candidate", $recruitment_candidate->id) . '" class="btn btn-success btn-sm"' . $recruitment_candidate->id. '"><i class="fas fa-user-plus"></i></a>';
                            $action = '';
                            if (Auth::user()->can('create', App\Models\Offer::class)) {
                                $action = $action . $action_create_offer;
                                if (null != $offer->result && 'Không đạt' != $offer->result) {
                                    $action = $action . $action_create_employee;
                                }
                            }
                            if (Auth::user()->can('approve', $offer)) {
                                $action = $action . $action_approve_offer;
                            }
                          @endphp
                          <tr>
                            <td>{{number_format($offer->current_salary, 0, '.', ',')}}<sup>đ</sup></td>
                            <td>{{number_format($offer->desired_salary, 0, '.', ',')}}<sup>đ</sup></td>
                            <td>{{number_format($offer->position_salary, 0, '.', ',')}}<sup>đ</sup></td>
                            <td>{{number_format($offer->capacity_salary, 0, '.', ',')}}<sup>đ</sup></td>
                            <td>{{number_format($offer->position_allowance, 0, '.', ',')}}<sup>đ</sup></td>
                            <td>{{number_format($offer->insurance_salary, 0, '.', ',')}}<sup>đ</sup></td>
                            <td>{!! $offer->note !!}</td>
                            <td>
                                <span class="badge @if ("Đồng ý" == $offer->feedback) badge-success @else badge-danger @endif">{{$offer->feedback}}</span>
                            </td>
                            @if ($offer->result)
                            <td><span class="badge @if ("Không đạt" == $offer->result) badge-danger @else badge-success @endif">{{$offer->result}}</span> <br> <small>({{$offer->approver->name}})</small></td>
                            @else
                            <td></td>
                            @endif
                            @canany(['update', 'approve'], $offer)
                            <td>{!! $action !!}</td>
                            @endcanany

                            <!-- Modals for edit offer -->
                            <form class="form-horizontal" method="post" action="{{ route('offers.update', $offer->id) }}" name="update_offer" id="update_offer" novalidate="novalidate">
                                @method('PATCH')
                                {{ csrf_field() }}
                                <div class="modal fade" tabindex="-1" id="edit_offer{{$recruitment_candidate->id}}">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h4>Đề xuất chế độ ứng viên {{$candidate->name}}</h4>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <input type="hidden" name="recruitment_candidate_id" id="recruitment_candidate_id" value="{{$recruitment_candidate->id}}">
                                                <div class="row">
                                                    <div class="col-6">
                                                        <label class="required-field" class="control-label">Lương hiện tại</label>
                                                        <div class="controls">
                                                            <input type="number" class="form-control" name="current_salary" id="current_salary" required="" @if ($offer) value="{{$offer->current_salary}}" @endif>
                                                        </div>
                                                    </div>
                                                    <div class="col-6">
                                                        <label class="required-field" class="control-label">Lương yêu cầu</label>
                                                        <div class="controls">
                                                            <input type="number" class="form-control" name="desired_salary" id="desired_salary" required="" @if ($offer) value="{{$offer->desired_salary}}" @endif>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-6">
                                                        <label class="required-field" class="control-label">Lương bảo hiểm</label>
                                                        <div class="controls">
                                                            <input type="number" class="form-control" name="insurance_salary" id="insurance_salary" required="" @if ($offer) value="{{$offer->insurance_salary}}" @else value="{{$recruitment->position->insurance_salary}}" @endif>
                                                        </div>
                                                    </div>
                                                    <div class="col-6">
                                                        <label class="required-field" class="control-label">Lương vị trí</label>
                                                        <div class="controls">
                                                            <input type="number" class="form-control" name="position_salary" id="position_salary" required="" @if ($offer) value="{{$offer->position_salary}}" @else value="{{$recruitment->position->position_salary}}" @endif>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-6">
                                                        <label class="required-field" class="control-label">Lương năng lực</label>
                                                        <div class="controls">
                                                            <input type="number" class="form-control" name="capacity_salary" id="capacity_salary" required="" @if ($offer) value="{{$offer->capacity_salary}}" @else value="{{$recruitment->position->max_capacity_salary}}" @endif>
                                                        </div>
                                                    </div>
                                                    <div class="col-6">
                                                        <label class="required-field" class="control-label">Phụ cấp vị trí</label>
                                                        <div class="controls">
                                                            <input type="number" class="form-control" name="position_allowance" id="position_allowance" required="" @if ($offer) value="{{$offer->position_allowance}}" @else value="{{$recruitment->position->position_allowance}}" @endif>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-6">
                                                        <div class="control-group">
                                                            <div class="control-group">
                                                                <label class="required-field" class="control-label">Phản hồi</label>
                                                                <div class="controls">
                                                                    <select name="feedback" id="feedback" data-placeholder="Chọn" class="form-control select2" style="width: 100%;">
                                                                        <option value="-- Chọn --" disabled="disabled" selected="selected">-- Chọn --</option>
                                                                        <option value="Đồng ý" @if ($offer && 'Đồng ý' == $offer->feedback) selected="selected" @endif>Đồng ý</option>
                                                                        <option value="Từ chối" @if ($offer && 'Từ chối' == $offer->feedback) selected="selected" @endif>Từ chối</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-6">
                                                        <label class="control-label">Ghi chú</label>
                                                        <div class="controls">
                                                            <input type="text" class="form-control" name="offer_note" id="offer_note" required="" @if ($offer) value="{{$offer->note}}" @endif>
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

                            <!-- Modals for approve offer -->
                            <form class="form-horizontal" method="post" action="{{ route('offers.approve', $offer->id) }}" name="approve_offer" id="approve_offer" novalidate="novalidate">
                                {{ csrf_field() }}
                                <div class="modal fade" tabindex="-1" id="approve_offer{{$recruitment_candidate->id}}">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h4>Duyệt đề xuất chế độ ứng viên {{$candidate->name}}</h4>
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
                                                                <label class="required-field" class="control-label">Kết quả</label>
                                                                <div class="controls">
                                                                    <select name="result" id="result" data-placeholder="Chọn" class="form-control select2" style="width: 100%;">
                                                                        <option value="-- Chọn --" disabled="disabled" selected="selected">-- Chọn --</option>
                                                                        <option value="Ký HĐLĐ">Ký HĐLĐ</option>
                                                                        <option value="Ký HĐTV">Ký HĐTV</option>
                                                                        <option value="Ký HĐHV">Ký HĐHV</option>
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
                          </tr>
                        @endif
                    </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Modals for create offer -->
        <form class="form-horizontal" method="post" action="{{ route('offers.store') }}" name="create_offer" id="create_offer" novalidate="novalidate">
            {{ csrf_field() }}
            <div class="modal fade" tabindex="-1" id="create_offer{{$recruitment_candidate->id}}">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4>Đề xuất chế độ cho ứng viên {{$candidate->name}}</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="recruitment_candidate_id" id="recruitment_candidate_id" value="{{$recruitment_candidate->id}}">
                            <div class="row">
                                <div class="col-6">
                                    <label class="required-field" class="control-label">Lương hiện tại</label>
                                    <div class="controls">
                                        <input type="number" class="form-control" name="current_salary" id="current_salary" required="">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <label class="required-field" class="control-label">Lương yêu cầu</label>
                                    <div class="controls">
                                        <input type="number" class="form-control" name="desired_salary" id="desired_salary" required="">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-6">
                                    <label class="required-field" class="control-label">Lương bảo hiểm</label>
                                    <div class="controls">
                                        <input type="number" class="form-control" name="insurance_salary" id="insurance_salary" required="" @if ($offer) value="{{$offer->insurance_salary}}" @else value="{{$recruitment->position->insurance_salary}}" @endif>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <label class="required-field" class="control-label">Lương vị trí</label>
                                    <div class="controls">
                                        <input type="number" class="form-control" name="position_salary" id="position_salary" required="" @if ($offer) value="{{$offer->position_salary}}" @else value="{{$recruitment->position->position_salary}}" @endif>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-6">
                                    <label class="required-field" class="control-label">Lương năng lực</label>
                                    <div class="controls">
                                        <input type="number" class="form-control" name="capacity_salary" id="capacity_salary" required="" @if ($offer) value="{{$offer->capacity_salary}}" @else value="{{$recruitment->position->max_capacity_salary}}" @endif>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <label class="required-field" class="control-label">Phụ cấp vị trí</label>
                                    <div class="controls">
                                        <input type="number" class="form-control" name="position_allowance" id="position_allowance" required="" @if ($offer) value="{{$offer->position_allowance}}" @else value="{{$recruitment->position->position_allowance}}" @endif>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-6">
                                    <div class="control-group">
                                        <div class="control-group">
                                            <label class="required-field" class="control-label">Phản hồi</label>
                                            <div class="controls">
                                                <select name="feedback" id="feedback" data-placeholder="Chọn" class="form-control select2" style="width: 100%;">
                                                    <option value="-- Chọn --" disabled="disabled" selected="selected">-- Chọn --</option>
                                                    <option value="Đồng ý" @if ($offer && 'Đồng ý' == $offer->feedback) selected="selected" @endif>Đồng ý</option>
                                                    <option value="Từ chối" @if ($offer && 'Từ chối' == $offer->feedback) selected="selected" @endif>Từ chối</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <label class="control-label">Ghi chú</label>
                                    <div class="controls">
                                        <input type="text" class="form-control" name="offer_note" id="offer_note" required="" @if ($offer) value="{{$offer->note}}" @endif>
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

@push('scripts')
<script>
    $(function () {
        // Summernote
        $("#detail").on("summernote.enter", function(we, e) {
            $(this).summernote("pasteHTML", "<br><br>");
            e.preventDefault();
        });
        $('#detail').summernote({
            height: 90,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'underline', 'clear']],
                ['color', ['color']],
            ]
        });
    });
</script>
@endpush
