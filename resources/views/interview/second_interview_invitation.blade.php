@section('title')
{{ 'Mời phỏng vấn lần 2' }}
@endsection

@extends('layouts.base')
@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Mời phỏng vấn lần 2</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ route('recruitments.index') }}">Tất cả đề xuất</a></li>
              <li class="breadcrumb-item active">Mời phỏng vấn lần 2</li>
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
                        <form class="form-horizontal" method="post" action="{{ route('second_interview_invitations.store', $recruitment_candidate->id) }}" name="create_first_interview_invitation" id="create_first_interview_invitation" novalidate="novalidate">
                            {{ csrf_field() }}
                            <div class="card-body">
                                <input type="hidden" name="recruitment_candidate_id" id="recruitment_candidate_id" value="{{$recruitment_candidate->id}}">
                                <div class="row">
                                    <div class="col-6">
                                        <label class="required-field">Thời gian</label>
                                        <div class="input-group date" id="interview_time_get" name="interview_time_get" data-target-input="nearest">
                                            <input type="text" id="interview_time" name="interview_time" class="form-control datetimepicker-input" data-target="#interview_time_get"/>
                                            <div class="input-group-append" data-target="#interview_time_get" data-toggle="datetimepicker">
                                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <label class="required-field">Địa điểm</label>
                                        <div class="controls">
                                            <input type="text" class="form-control" name="interview_location" id="interview_location" required="">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <label class="required-field">Người liên hệ</label>
                                        <div class="controls">
                                            <input type="text" class="form-control" name="contact" id="contact" required="">
                                        </div>
                                    </div>
                                </div>

                                <br>
                                <div class="control-group">
                                    <div class="controls">
                                        <input type="submit" value="Gửi" class="btn btn-success">
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
