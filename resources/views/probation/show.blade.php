@section('title')
{{ 'Chi tiết kế hoạch thử việc' }}
@endsection

@extends('layouts.base')
@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h4 class="m-0">Kế hoạch thử việc {{$probation->employee->name}}</h4>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ route('probations.index') }}">Tất cả kế hoạch thử việc</a></li>
              <li class="breadcrumb-item active">Chi tiết</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="row invoice-info">
                                <div class="col-sm-4 invoice-col">
                                  <address>
                                    <strong>Vị trí</strong><br>
                                    {{$probation->recruitment->position->name}} - {{$probation->recruitment->position->department->name}}<br>
                                  </address>
                                </div>
                                <!-- /.col -->
                                <div class="col-sm-4 invoice-col">
                                  <address>
                                    <strong>Thời gian</strong><br>
                                    {{date('d/m/Y', strtotime($probation->start_date))}} - {{date('d/m/Y', strtotime($probation->end_date))}}<br>
                                  </address>
                                </div>
                                <!-- /.col -->
                                <div class="col-sm-4 invoice-col">
                                  <address>
                                    <strong>Người tạo</strong><br>
                                    {{$probation->creator->name}}<br>
                                  </address>
                                </div>
                            </div>
                            <hr>
                            @if ($probation->result_of_attitude)
                            <div class="row invoice-info">
                                <div class="col-sm-4 invoice-col">
                                  <address>
                                    <strong>Kết quả công việc</strong><br>
                                    @if ('Hoàn thành' == $probation->result_of_work)
                                        <span class="badge badge-success">{{$probation->result_of_work}}</span>
                                    @else
                                        <span class="badge badge-danger">{{$probation->result_of_work}}</span>
                                    @endif
                                  </address>
                                </div>
                                <!-- /.col -->
                                <div class="col-sm-4 invoice-col">
                                  <address>
                                    <strong>Ý thức thái độ</strong><br>
                                    @if ('Tốt' == $probation->result_of_attitude)
                                        <span class="badge badge-success">{{$probation->result_of_attitude}}</span>
                                    @elseif ('Khá' == $probation->result_of_attitude)
                                        <span class="badge badge-primary">{{$probation->result_of_attitude}}</span>
                                    @elseif ('Trung bình' == $probation->result_of_attitude)
                                        <span class="badge badge-warning">{{$probation->result_of_attitude}}</span>
                                    @else
                                        <span class="badge badge-danger">{{$probation->result_of_attitude}}</span>
                                    @endif
                                  </address>
                                </div>
                                <!-- /.col -->
                                <div class="col-sm-4 invoice-col">
                                  <address>
                                    <strong>QL đánh giá</strong><br>
                                    @if ('Đạt' == $probation->result_manager_status)
                                        <span class="badge badge-success">{{$probation->result_manager_status}}</span>
                                    @else
                                        <span class="badge badge-danger">{{$probation->result_manager_status}}</span>
                                    @endif
                                  </address>
                                </div>
                            </div>
                            <hr>
                            @endif

                            @if ($probation->result_reviewer_status)
                            <div class="row invoice-info">
                                <div class="col-sm-4 invoice-col">
                                  <address>
                                    <strong>Kết quả kiểm tra</strong><br>
                                    @if ('Đồng ý' == $probation->result_reviewer_status)
                                        <span class="badge badge-success">{{$probation->result_reviewer_status}}</span>
                                    @else
                                        <span class="badge badge-danger">{{$probation->result_reviewer_status}}</span>
                                    @endif
                                  </address>
                                </div>
                                <!-- /.col -->
                                <div class="col-sm-4 invoice-col">
                                  <address>
                                    <strong>Thời gian kiểm tra</strong><br>
                                    {{date('d/m/Y H:i', strtotime($probation->result_review_time))}}<br>
                                  </address>
                                </div>
                                <!-- /.col -->
                                <div class="col-sm-4 invoice-col">
                                  <address>
                                    <strong>Người kiểm tra</strong><br>
                                    {{$probation->result_reviewer->name}}<br>
                                </div>
                            </div>
                            <hr>
                            @endif

                            @if ($probation->approver_result)
                            <div class="row invoice-info">
                                <div class="col-sm-4 invoice-col">
                                  <address>
                                    <strong>Kết quả phê duyệt</strong><br>
                                    @if ('Đồng ý' == $probation->approver_result)
                                        <span class="badge badge-success">{{$probation->approver_result}}</span>
                                    @else
                                        <span class="badge badge-danger">{{$probation->approver_result}}</span>
                                    @endif
                                  </address>
                                </div>
                                <!-- /.col -->
                                <div class="col-sm-4 invoice-col">
                                  <address>
                                    <strong>Thời gian duyệt</strong><br>
                                    {{date('d/m/Y H:i', strtotime($probation->approver_time))}}<br>
                                  </address>
                                </div>
                                <!-- /.col -->
                                <div class="col-sm-4 invoice-col">
                                  <address>
                                    <strong>Người phê duyệt</strong><br>
                                    {{$probation->approver->name}}
                                    @if ($probation->approver_comment)
                                        - {!! preg_replace('/(<br>)+$/', '', $probation->approver_comment) !!}<br>
                                    @endif
                                </div>
                            </div>
                            <hr>
                            @endif

                            @if(Auth::user()->can('create', App\Models\Probation::class)
                                && null == $probation->result_manager_status)
                            <a href="#create_plan{{' . $probation->id . '}}" class="btn btn-success" data-toggle="modal" data-target="#create_plan{{$probation->id}}"><i class="fas fa-plus"></i></a>
                            <br>
                            <br>
                            @endif
                            <table id="employees-table" class="table table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th>Nội dung công việc</th>
                                    <th>Yêu cầu đạt được</th>
                                    <th>Deadline</th>
                                    <th>Người hướng dẫn</th>
                                    <th>Kết quả</th>
                                    @if(Auth::user()->can('create', App\Models\Probation::class)
                                        && null == $probation->result_manager_status)
                                    <th>Thao tác</th>
                                    @endif
                                </tr>
                                </thead>
                                <tbody>
                                    @foreach ($probation->probation_plans as $probation_plan)
                                    @php
                                        $action_edit_plan = '<a href="' . route("probation_plans.edit", $probation_plan->id) . '" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></a>
                                                <form style="display:inline" action="'. route("probation_plans.destroy", $probation_plan->id) . '" method="POST">
                                                <input type="hidden" name="_method" value="DELETE">
                                                <button type="submit" name="submit" onclick="return confirm(\'Bạn có muốn xóa?\');" class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i></button>
                                                <input type="hidden" name="_token" value="' . csrf_token(). '"></form>';
                                    @endphp
                                    <tr>
                                        <td>{{$probation_plan->work_title}}</td>
                                        <td>{!! $probation_plan->work_requirement !!}</td>
                                        <td>{{date('d/m/Y', strtotime($probation_plan->work_deadline))}}</td>
                                        <td>{{$probation_plan->instructor}}</td>
                                        <td>{!! $probation_plan->work_result !!}</td>
                                        @if(Auth::user()->can('create', App\Models\Probation::class)
                                            && null == $probation->result_manager_status)
                                        <td>{!! $action_edit_plan !!}</td>
                                        @endif
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>

                            @if(Auth::user()->can('create', App\Models\Probation::class)
                                && $probation->probation_plans->count())
                            <a href="#evaluate_probation{{' . $probation->id . '}}" class="btn btn-primary mt-4" data-toggle="modal" data-target="#evaluate_probation{{$probation->id}}"><i class="fas fa-check"></i></a>
                            @endif

                            @if(Auth::user()->can('review', $probation)
                                && $probation->result_manager_status)
                            <a href="#review_probation{{' . $probation->id . '}}" class="btn btn-primary mt-4" data-toggle="modal" data-target="#review_probation{{$probation->id}}"><i class="fas fa-check-double"></i></a>
                            @endcan

                            @if(Auth::user()->can('approve', $probation)
                                && $probation->result_reviewer_status)
                            <a href="#approve_probation{{' . $probation->id . '}}" class="btn btn-success mt-4 float-right" data-toggle="modal" data-target="#approve_probation{{$probation->id}}"><i class="fas fa-check"></i></a>
                            @endcan

                            <!-- Modals for create employee probation plan -->
                            <form class="form-horizontal" method="post" action="{{ route('probation_plans.store') }}" name="create_plan" id="create_plan" novalidate="novalidate">
                                {{ csrf_field() }}
                                <div class="modal fade" id="create_plan{{$probation->id}}">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h4>Kế hoạch thử việc cho vị trí {{$probation->recruitment->position->name}}</h4>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <input type="hidden" name="probation_id" id="probation_id" value="{{$probation->id}}">

                                                <div class="row">
                                                    <div class="col-12">
                                                        <div class="control-group">
                                                            <label class="required-field" class="control-label">Nội dung công việc</label>
                                                            <div class="controls">
                                                                <input type="text" class="form-control" name="work_title" id="work_title" required="">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-12">
                                                        <label class="required-field" class="control-label">Yêu cầu</label>
                                                        <textarea id="work_requirement" name="work_requirement">
                                                        </textarea>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-6">
                                                        <label class="required-field">Deadline</label>
                                                        <div class="input-group date" id="work_deadline" data-target-input="nearest">
                                                            <input type="text" name="work_deadline" class="form-control datetimepicker-input" data-target="#work_deadline"/>
                                                            <div class="input-group-append" data-target="#work_deadline" data-toggle="datetimepicker">
                                                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-6">
                                                        <div class="control-group">
                                                            <label class="control-label">Người hướng dẫn</label>
                                                            <div class="controls">
                                                                <input type="text" class="form-control" name="instructor" id="instructor" required="">
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

                            <!-- Modals for evaluate the probation-->
                            <form class="form-horizontal" method="post" action="{{ route('probations.evaluate', $probation->id) }}" name="evaluate_probation" id="evaluate_probation" novalidate="novalidate">
                                {{ csrf_field() }}
                                <div class="modal fade" id="evaluate_probation{{$probation->id}}">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h4>Đánh giá thử việc cho vị trí {{$probation->recruitment->position->name}}</h4>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-6">
                                                        <div class="control-group">
                                                            <label class="required-field control-label">Kết quả công việc</label>
                                                            <div class="controls">
                                                                <select name="result_of_work" id="result_of_work" data-placeholder="Chọn" class="form-control select2" style="width: 100%;">
                                                                    <option selected="selected" disabled>-- Chọn -- </option>
                                                                    <option value='Hoàn thành' @if ('Hoàn thành' == $probation->result_of_work) selected @endif>Hoàn thành</option>
                                                                    <option value='Không hoàn thành' @if ('Không hoàn thành' == $probation->result_of_work) selected @endif>Không hoàn thành</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-6">
                                                        <div class="control-group">
                                                            <label class="required-field control-label">Ý thức, thái độ</label>
                                                            <div class="controls">
                                                                <select name="result_of_attitude" id="result_of_attitude" data-placeholder="Chọn" class="form-control select2" style="width: 100%;">
                                                                    <option selected="selected" disabled>-- Chọn -- </option>
                                                                    <option value='Tốt' @if ('Tốt' == $probation->result_of_attitude) selected @endif>Tốt</option>
                                                                    <option value='Khá' @if ('Khá' == $probation->result_of_attitude) selected @endif>Khá</option>
                                                                    <option value='Trung bình' @if ('Trung bình' == $probation->result_of_attitude) selected @endif>Trung bình</option>
                                                                    <option value='Kém' @if ('Kém' == $probation->result_of_attitude) selected @endif>Kém</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-12">
                                                        <div class="control-group">
                                                            <label class="required-field control-label">Đánh giá</label>
                                                            <div class="controls">
                                                                <select name="result_manager_status" id="result_manager_status" data-placeholder="Chọn" class="form-control select2" style="width: 100%;">
                                                                    <option selected="selected" disabled>-- Chọn -- </option>
                                                                    <option value='Đạt' @if ('Đạt' == $probation->result_manager_status) selected @endif>Đạt</option>
                                                                    <option value='Không đạt' @if ('Không đạt' == $probation->result_manager_status) selected @endif>Không đạt</option>
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

                            <!-- Modals for review the probation-->
                            <form class="form-horizontal" method="post" action="{{ route('probations.review', $probation->id) }}" name="review_probation" id="review_probation" novalidate="novalidate">
                                {{ csrf_field() }}
                                <div class="modal fade" id="review_probation{{$probation->id}}">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h4>Kiểm tra kết quả thử việc cho vị trí {{$probation->recruitment->position->name}}</h4>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-12">
                                                        <div class="control-group">
                                                            <label class="required-field control-label">Kết quả kiểm tra</label>
                                                            <div class="controls">
                                                                <select name="result_reviewer_status" id="result_reviewer_status" data-placeholder="Chọn" class="form-control select2" style="width: 100%;">
                                                                    <option selected="selected" disabled>-- Chọn -- </option>
                                                                    <option value='Đồng ý' @if ('Đồng ý' == $probation->result_reviewer_status) selected @endif>Đồng ý</option>
                                                                    <option value='Từ chối' @if ('Từ chối' == $probation->result_reviewer_status) selected @endif>Từ chối</option>
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

                            <!-- Modals for approve the probation-->
                            <form class="form-horizontal" method="post" action="{{ route('probations.approve', $probation->id) }}" name="approve_probation" id="approve_probation" novalidate="novalidate">
                                {{ csrf_field() }}
                                <div class="modal fade" id="approve_probation{{$probation->id}}">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h4>Duyệt kết quả thử việc cho vị trí {{$probation->recruitment->position->name}}</h4>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-12">
                                                        <div class="control-group">
                                                            <label class="required-field control-label">Kết quả duyệt</label>
                                                            <div class="controls">
                                                                <select name="approver_result" id="approver_result" data-placeholder="Chọn" class="form-control select2" style="width: 100%;">
                                                                    <option selected="selected" disabled>-- Chọn -- </option>
                                                                    <option value='Đồng ý' @if ('Đồng ý' == $probation->approver_result) selected @endif>Đồng ý</option>
                                                                    <option value='Từ chối' @if ('Từ chối' == $probation->approver_result) selected @endif>Từ chối</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-12">
                                                        <label class="control-label">Ghi chú</label>
                                                        <textarea id="approver_comment" name="approver_comment">
                                                        </textarea>
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
            </div>
        </div>
    </section>
</div>
@endsection

@push('scripts')
<script>
    $(function () {
        //Initialize Select2 Elements
        $('.select2').select2({
        theme: 'bootstrap4'
        })

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
        $("#approver_comment").on("summernote.enter", function(we, e) {
            $(this).summernote("pasteHTML", "<br><br>");
            e.preventDefault();
        });
        $('#approver_comment').summernote({
            height: 90,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'underline', 'clear']],
                ['color', ['color']],
            ]
        })

        //Date picker
        $('#work_deadline').datetimepicker({
            format: 'DD/MM/YYYY'
        });
    });
</script>
@endpush
