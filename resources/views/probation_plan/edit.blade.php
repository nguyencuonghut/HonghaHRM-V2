@section('title')
{{ 'Sửa kế hoạch thử việc' }}
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
            <h1 class="m-0">Sửa kế hoạch thử việc</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ route('probation_plans.index') }}">Tất cả kế hoạch thử việc</a></li>
              <li class="breadcrumb-item active">Sửa</li>
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
                        <form class="form-horizontal" method="post" action="{{ route('probation_plans.update', $probation_plan->id) }}" name="edit_plan" id="edit_plan" novalidate="novalidate">
                            {{ csrf_field() }}
                            @method('PATCH')
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="control-group">
                                            <label class="required-field" class="control-label">Nội dung công việc</label>
                                            <div class="controls">
                                                <input type="text" class="form-control" name="work_title" id="work_title" required="" value="{{$probation_plan->work_title}}">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-12">
                                        <label class="required-field" class="control-label">Yêu cầu</label>
                                        <textarea id="work_requirement" name="work_requirement">
                                            {{$probation_plan->work_requirement}}
                                        </textarea>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-6">
                                        <label class="required-field">Deadline</label>
                                        <div class="input-group date" id="work_deadline" data-target-input="nearest">
                                            <input type="text" name="work_deadline" class="form-control datetimepicker-input" data-target="#work_deadline" value="{{date('d/m/Y', strtotime($probation_plan->work_deadline))}}"/>
                                            <div class="input-group-append" data-target="#work_deadline" data-toggle="datetimepicker">
                                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-6">
                                        <div class="control-group">
                                            <label class="control-label">Người hướng dẫn</label>
                                            <div class="controls">
                                                <input type="text" class="form-control" name="instructor" id="instructor" required="" value="{{$probation_plan->instructor}}">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-12">
                                        <label class="control-label">Kết quả</label>
                                        <textarea id="work_result" name="work_result">
                                            {{$probation_plan->work_result}}
                                        </textarea>
                                    </div>
                                </div>

                                <br>
                                <div class="control-group">
                                    <div class="controls">
                                        <input type="submit" value="Sửa" class="btn btn-success">
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div><!--/. container-fluid -->
    </section>
    <!-- /.content -->
  </div>
@endsection


@push('scripts')
<style type="text/css">
    .dataTables_wrapper .dt-buttons {
    margin-bottom: -3em
  }
</style>

<script>
    $(function () {
        //Date picker
        $('#work_deadline').datetimepicker({
            format: 'DD/MM/YYYY'
        });

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
        $("#work_result").on("summernote.enter", function(we, e) {
            $(this).summernote("pasteHTML", "<br><br>");
            e.preventDefault();
        });
        $('#work_result').summernote({
            height: 90,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'underline', 'clear']],
                ['color', ['color']],
            ]
        })
    })
</script>
@endpush




