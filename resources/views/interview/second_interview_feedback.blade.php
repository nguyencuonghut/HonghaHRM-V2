@section('title')
{{ 'Phản hồi phỏng vấn lần 2' }}
@endsection

@extends('layouts.base')
@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Phản hồi phỏng vấn lần 2</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ route('recruitments.index') }}">Tất cả đề xuất</a></li>
              <li class="breadcrumb-item active">Phản hồi phỏng vấn lần 2</li>
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
                        <form class="form-horizontal" method="post" action="{{ route('second_interview_invitations.update', $second_interview_invitation->id) }}" name="create_second_interview_feedback" id="create_second_interview_feedback" novalidate="novalidate">
                            @method('PATCH')
                            {{ csrf_field() }}
                            <div class="card-body">
                                <input type="hidden" name="recruitment_candidate_id" id="recruitment_candidate_id" value="{{$second_interview_invitation->recruitment_candidate_id}}">
                                <div class="row">
                                    <div class="col-6">
                                        <div class="control-group">
                                            <label class="required-field" class="control-label">Phản hồi</label>
                                            <div class="controls">
                                                <select name="feedback" id="feedback" data-placeholder="Chọn" class="form-control select2" style="width: 100%;">
                                                    <option value="-- Chọn --" disabled="disabled" selected="selected">-- Chọn --</option>
                                                    <option value="Đồng ý" @if ($second_interview_invitation && 'Đồng ý' == $second_interview_invitation->feedback) selected="selected" @endif>Đồng ý</option>
                                                    <option value="Từ chối" @if ($second_interview_invitation && 'Từ chối' == $second_interview_invitation->feedback) selected="selected" @endif>Từ chối</option>
                                                    <option value="Hẹn lại" @if ($second_interview_invitation && 'Hẹn lại' == $second_interview_invitation->feedback) selected="selected" @endif>Hẹn lại</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <label>Ghi chú</label>
                                        <div class="controls">
                                            <input type="text" class="form-control" name="note" id="note" required="" value="{{$second_interview_invitation->note}}">
                                        </div>
                                    </div>
                                </div>

                                <br>
                                <div class="control-group">
                                    <div class="controls">
                                        <input type="submit" value="Lưu" class="btn btn-success">
                                    </div>
                                </div>
                            </div>
                        </form>
                        <!-- /.modal -->
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

        //Date picker
        $("#interview_time_get").datetimepicker({
            format: 'DD/MM/YYYY HH:mm',
            icons: { time: 'far fa-clock' }
        });
    });
</script>
@endpush
