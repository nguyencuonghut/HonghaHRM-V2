<!-- Candidate Tab -->
<div class="tab-pane fade" id="recruitment-4" role="tabpanel" aria-labelledby="recruitment-tab-4">
    <div class="card card-secondary">
        <div class="card-header">
            Danh sách ứng viên
        </div>
        <!-- /.card-header -->
        <div class="card-body">
            @can('create', App\Models\RecruitmentCandidate::class)
                @if($recruitment->plan)
                    <button type="button" class="btn btn-success float-left mb-4" data-toggle="modal" data-target="#add_recruitment_candidate">
                        Tạo
                    </button>
                @endif
            @endcan

            <div class="table-responsive">
              <table id="candidates-table" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th style="width: 12%;">Tên</th>
                  <th style="width: 12%;">Email</th>
                  <th>Điện thoại</th>
                  <th>Ngày sinh</th>
                  <th>CCCD</th>
                  <th>Địa chỉ</th>
                  <th>CV</th>
                  <th>Đợt</th>
                  <th>Thao tác</th>
                </tr>
                </thead>

                <tbody>
                    @foreach ($recruitment->candidates as $candidate)
                    <tr>
                      <td>
                        <a href="{{route('candidates.show', $candidate->id)}}">{{$candidate->name}}</a>
                      </td>
                      <td>{{$candidate->email}}</td>
                      <td>{{$candidate->phone}}</td>
                      <td>{{ date('d/m/Y', strtotime($candidate->date_of_birth)) }}</td>
                      <td>{{$candidate->cccd}}</td>
                      <td> @if($candidate->address) {{$candidate->address}}, @endif {{$candidate->commune->name}}, {{$candidate->commune->district->name}}, {{$candidate->commune->district->province->name}}</td>
                      @php
                          $recruitment_candidate = App\Models\RecruitmentCandidate::where('recruitment_id', $recruitment->id)->where('candidate_id', $candidate->id)->first();
                          $url = '<a target="_blank" href="../../../' . $recruitment_candidate->cv_file . '"><i class="far fa-file-pdf"></i></a>';
                          $action = '<a href="#edit{{' . $recruitment_candidate->id . '}}" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#edit' . $recruitment_candidate->id. '"><i class="fas fa-edit"></i></a>
                                        <form style="display:inline" action="'. route("recruitment_candidates.destroy", $recruitment_candidate->id) . '" method="POST">
                                    <input type="hidden" name="_method" value="DELETE">
                                    <button type="submit" name="submit" onclick="return confirm(\'Bạn có muốn xóa?\');" class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i></button>
                                    <input type="hidden" name="_token" value="' . csrf_token(). '"></form>';
                      @endphp
                      <td>{!! $url !!}</td>
                      <td>{{$recruitment_candidate->batch}}</td>
                      <td>{!! $action !!}</td>
                    </tr>
                    <!-- Modals for edit recruitment_candidate -->
                    <form class="form-horizontal" method="post" enctype="multipart/form-data" action="{{ route('recruitment_candidates.update', $recruitment_candidate->id) }}" name="update_recruitment_candidate" id="update_recruitment_candidate" novalidate="novalidate">
                        {{ csrf_field() }}
                        @method('PATCH')
                        <div class="modal fade" tabindex="-1" id="edit{{$recruitment_candidate->id}}">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4>Sửa ứng viên</h4>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <input type="hidden" name="recruitment_id" id="recruitment_id" value="{{$recruitment->id}}">
                                        <div class="row">
                                            <div class="col-12">
                                                <label class="required-field" class="control-label">Chọn ứng viên</label>
                                                @can('create', App\Models\Candidate::class)
                                                    <a href="{{route('candidates.create')}}"><i class="fas fa-plus text-success"></i></a>
                                                @endcan
                                                <div class="controls">
                                                    <select name="candidate_id" id="candidate_id" data-placeholder="Chọn" class="form-control select2" style="width: 100%;">
                                                        <option value="-- Chọn --" disabled="disabled" selected="selected">-- Chọn --</option>
                                                        @foreach($candidates as $candidate)
                                                            <option value="{{$candidate->id}}" @if ($candidate->id == $recruitment_candidate->candidate_id) selected @endif>{{$candidate->name}} - {{$candidate->email}} - CCCD {{$candidate->cccd}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-12">
                                                <div class="control-group">
                                                    <label class="required-field" class="control-label">CV</label>
                                                    <div class="custom-file text-left">
                                                        <input type="file" name="cv_file" accept="application/pdf" class="custom-file-input" id="cv_file">
                                                        <label class="custom-file-label" for="cv_file">Chọn file</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-6">
                                                <div class="control-group">
                                                    <label class="required-field" class="control-label">Nguồn tin</label>
                                                    @can('create', App\Models\Channel::class)
                                                    <a href="{{route('channels.create')}}"><i class="fas fa-plus text-success"></i></a>
                                                    @endcan

                                                    <div class="controls">
                                                        <select name="channel_id" id="channel_id" data-placeholder="Chọn" class="form-control select2" style="width: 100%;">
                                                            <option value="-- Chọn --" disabled="disabled" selected="selected">-- Chọn --</option>
                                                            @foreach ($channels as $channel)
                                                                <option value="{{$channel->id}}" @if ($channel->id == $recruitment_candidate->channel_id) selected @endif>{{$channel->name}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="control-group">
                                                    <label class="required-field" class="control-label">Đợt</label>
                                                    <div class="controls">
                                                        <select name="batch" id="batch" data-placeholder="Chọn" class="form-control select2" style="width: 100%;">
                                                            <option value="-- Chọn --" disabled="disabled" selected="selected">-- Chọn --</option>
                                                            <option value="Đợt 1" @if ('Đợt 1' == $recruitment_candidate->batch) selected @endif>Đợt 1</option>
                                                            <option value="Đợt 2" @if ('Đợt 2' == $recruitment_candidate->batch) selected @endif>Đợt 2</option>
                                                            <option value="Đợt 3" @if ('Đợt 3' == $recruitment_candidate->batch) selected @endif>Đợt 3</option>
                                                            <option value="Đợt 4" @if ('Đợt 4' == $recruitment_candidate->batch) selected @endif>Đợt 4</option>
                                                            <option value="Đợt 5" @if ('Đợt 5' == $recruitment_candidate->batch) selected @endif>Đợt 5</option>
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
                    @endforeach
                </tbody>
              </table>
            </div>
        </div>
    </div>
</div>

<!-- Modals for create recruitment_candidate -->
<form class="form-horizontal" method="post" enctype="multipart/form-data" action="{{ route('recruitment_candidates.store') }}" name="create_recruitment_candidate" id="create_recruitment_candidate" novalidate="novalidate">
{{ csrf_field() }}
<div class="modal fade" id="add_recruitment_candidate">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4>Thêm ứng viên</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="recruitment_id" id="recruitment_id" value="{{$recruitment->id}}">
                <div class="row">
                    <div class="col-12">
                        <label class="required-field" class="control-label">Nhập ứng viên</label>
                        @can('create', App\Models\Candidate::class)
                            <a href="{{route('candidates.create')}}"><i class="fas fa-plus text-success"></i></a>
                        @endcan

                        <div class="controls">
                            <select name="candidate_id" id="candidate_id" data-placeholder="Chọn" class="form-control select2" style="width: 100%;">
                                <option value="-- Chọn --" disabled="disabled" selected="selected">-- Chọn --</option>
                                @foreach($candidates as $candidate)
                                    <option value="{{$candidate->id}}">{{$candidate->name}} - {{$candidate->phone}} - {{$candidate->commune->name}}, {{$candidate->commune->district->name}}, {{$candidate->commune->district->province->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="control-group">
                            <label class="required-field" class="control-label">CV</label>
                            <div class="custom-file text-left">
                                <input type="file" name="cv_file" accept="application/pdf" class="custom-file-input" id="cv_file">
                                <label class="custom-file-label" for="cv_file">Chọn file</label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-6">
                        <div class="control-group">
                            <label class="required-field" class="control-label">Nguồn tin</label>
                            @can('create', App\Models\Channel::class)
                            <a href="{{route('channels.create')}}"><i class="fas fa-plus text-success"></i></a>
                            @endcan

                            <div class="controls">
                                <select name="channel_id" id="channel_id" data-placeholder="Chọn" class="form-control select2" style="width: 100%;">
                                    <option value="-- Chọn --" disabled="disabled" selected="selected">-- Chọn --</option>
                                    @foreach ($channels as $channel)
                                        <option value="{{$channel->id}}">{{$channel->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="control-group">
                            <label class="required-field" class="control-label">Đợt</label>
                            <div class="controls">
                                <select name="batch" id="batch" data-placeholder="Chọn" class="form-control select2" style="width: 100%;">
                                    <option value="-- Chọn --" disabled="disabled" selected="selected">-- Chọn --</option>
                                    <option value="Đợt 1">Đợt 1</option>
                                    <option value="Đợt 2">Đợt 2</option>
                                    <option value="Đợt 3">Đợt 3</option>
                                    <option value="Đợt 4">Đợt 4</option>
                                    <option value="Đợt 5">Đợt 5</option>
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

@push('scripts')
<script>
    // Add the following code if you want the name of the file appear on select
    $(".custom-file-input").on("change", function() {
        var fileName = $(this).val().split("\\").pop();
        $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
    });
</script>
@endpush




