@section('title')
{{ 'Sửa phụ lục' }}
@endsection

@extends('layouts.base')
@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Sửa phụ lục</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ route('appendixes.index') }}">Tất cả phụ lục</a></li>
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
                    <form class="form-horizontal" method="post" action="{{ route('appendixes.update', $appendix->id) }}" enctype="multipart/form-data" name="update_appendix" id="update_appendix" novalidate="novalidate">
                        {{ csrf_field() }}
                        @method('PATCH')
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="row">
                                <div class="col-6">
                                    <div class="control-group">
                                        <div class="control-group">
                                            <label class="required-field" class="control-label">Lý do</label>
                                            <div class="controls">
                                                <select name="reason" id="reason" data-placeholder="Chọn" class="form-control select2" style="width: 100%;">
                                                    <option value="Điều chỉnh lương" @if ('Điều chỉnh lương' == $appendix->reason) selected="selected" @endif>Điều chỉnh lương</option>
                                                    <option value="Điều chỉnh chức danh" @if ('Điều chỉnh chức danh' == $appendix->reason) selected="selected" @endif>Điều chỉnh chức danh</option>
                                                    <option value="Khác" @if ('Khác' == $appendix->reason) selected="selected" @endif>Khác</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                  <div class="control-group">
                                    <label class="control-label">File (pdf)</label>
                                    <div class="custom-file text-left">
                                        <input type="file" name="file_path" accept="application/pdf" class="custom-file-input" id="file_path">
                                        <label class="custom-file-label" for="img_path">Chọn file</label>
                                    </div>
                                  </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12">
                                    <label class="required-field control-label">Mô tả</label>
                                    <textarea id="description" name="description">
                                        {!! $appendix->description !!}
                                    </textarea>
                                </div>
                            </div>

                            <br>
                            <div class="control-group">
                                <div class="controls">
                                    <input type="submit" value="Lưu" class="btn btn-success">
                                </div>
                            </div>
                        <div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection


@push('scripts')
<!-- Select2 -->
<script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>
<script src="{{asset('plugins/moment/moment.min.js')}}"></script>
<!-- Summernote -->
<script src="{{ asset('plugins/summernote/summernote-bs4.min.js') }}"></script>

<script>
    $(function () {
        //Initialize Select2 Elements
        $('.select2').select2({
        theme: 'bootstrap4'
        })

        // Summernote
        $("#description").on("summernote.enter", function(we, e) {
            $(this).summernote("pasteHTML", "<br><br>");
            e.preventDefault();
        });
        $('#description').summernote({
            height: 90,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'underline', 'clear']],
                ['color', ['color']],
            ]
        })

        // Add the following code if you want the name of the file appear on select
        $(".custom-file-input").on("change", function() {
            var fileName = $(this).val().split("\\").pop();
            $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
        });
    })
</script>
@endpush

