@section('title')
{{ 'Thêm yêu cầu tuyển dụng' }}
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
          <h1 class="m-0">Thêm yêu cầu tuyển dụng</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('recruitments.index') }}">Tất cả yêu cầu tuyển dụng</a></li>
            <li class="breadcrumb-item active">Thêm</li>
          </ol>
        </div><!-- /.col -->
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>
  <!-- /.content-header -->

  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
      <!-- Small boxes (Stat box) -->
      <div class="row">
        <div class="col-12">
            <div class="card">
                <form class="form-horizontal" method="post" action="{{ url('recruitments') }}" name="add_request" id="add_request" novalidate="novalidate">{{ csrf_field() }}
                    <!-- /.card-header -->
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6">
                                <div class="control-group">
                                    <label class="required-field" class="control-label">Vị trí</label>
                                    <div class="controls">
                                        <select name="position_id" id="position_id" data-placeholder="Chọn vị trí" class="form-control select2" style="width: 100%;">
                                            <option value="-- Chọn --" disabled="disabled" selected="selected">-- Chọn --</option>
                                            @foreach ($positions as $position)
                                                <option value="{{$position->id}}">{{$position->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="control-group">
                                    <label class="required-field" class="control-label">Số lượng</label>
                                    <div class="controls">
                                        <input type="number" class="form-control" name="quantity" id="quantity" required="">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-6">
                                <div class="control-group">
                                    <label class="required-field" class="control-label">Lý do</label>
                                    <div class="controls">
                                        <textarea id="reason" name="reason">
                                        </textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="control-group">
                                    <label class="required-field" class="control-label">Yêu cầu</label>
                                    <div class="controls">
                                        <textarea id="requirement" name="requirement">
                                        </textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <div class="control-group">
                                    <label class="control-label">Mức lương (VNĐ)</label>
                                    <div class="controls">
                                        <input type="number" class="form-control" name="salary" id="salary" required="">
                                    </div>
                                </div>
                            </div>

                            <div class="col-6">
                                <label class="required-field">Thời gian cần</label>
                                <div class="input-group date" id="work_time" data-target-input="nearest">
                                    <input type="text" name="work_time" class="form-control datetimepicker-input" data-target="#work_time"/>
                                    <div class="input-group-append" data-target="#work_time" data-toggle="datetimepicker">
                                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="control-group">
                                    <label class="control-label">Ghi chú</label>
                                    <div class="controls">
                                        <textarea id="note" name="note">
                                        </textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <br>
                        <div class="control-group">
                            <div class="controls">
                                <input type="submit" value="Thêm" class="btn btn-success">
                            </div>
                        </div>
                    </div>
                </form>
      </div>
      <!-- /.row (main row) -->
    </div><!-- /.container-fluid -->
  </section>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->
@endsection

@push('scripts')
<script>
    $(function () {
        //Initialize Select2 Elements
        $('.select2').select2({
            theme: 'bootstrap4'
        })

        $('#reason').summernote({
            height: 80,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'underline', 'clear']],
                ['color', ['color']],
            ]
        })
        $('#note').summernote({
            height: 80,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'underline', 'clear']],
                ['color', ['color']],
            ]
        })

        $('#requirement').summernote({
            height: 80,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'underline', 'clear']],
                ['color', ['color']],
            ]
        })

        //Remove <p> tag by <br> when enter new line
        $("#reason").on("summernote.enter", function(we, e) {
            $(this).summernote("pasteHTML", "<br><br>");
            e.preventDefault();
        });
        $("#requirement").on("summernote.enter", function(we, e) {
            $(this).summernote("pasteHTML", "<br><br>");
            e.preventDefault();
        });
        $("#note").on("summernote.enter", function(we, e) {
            $(this).summernote("pasteHTML", "<br><br>");
            e.preventDefault();
        });

        //Date picker
        $('#work_time').datetimepicker({
            format: 'DD/MM/YYYY'
        });
    });
</script>
@endpush
