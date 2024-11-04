@section('title')
{{ 'Thêm phụ lục' }}
@endsection

@extends('layouts.base')
@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Thêm phụ lục cho HĐ số {{$contract->code}}</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ route('appendixes.index') }}">Tất cả phụ lục</a></li>
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
            <div class="row">
                <div class="col-12">
                    <div class="card">
                    <form class="form-horizontal" method="post" action="{{ route('appendixes.add', $contract->id) }}" enctype="multipart/form-data" name="add_appendix" id="add_appendix" novalidate="novalidate">
                        {{ csrf_field() }}
                        <!-- /.card-header -->
                        <div class="card-body">
                            <input type="hidden" name="employee_id" id="employee_id" value="{{$contract->employee_id}}">
                            <input type="hidden" name="contract_id" id="contract_id" value="{{$contract->id}}">
                            <div class="row">
                                <div class="col-6">
                                    <div class="control-group">
                                        <div class="control-group">
                                            <label class="required-field" class="control-label">Lý do</label>
                                            <div class="controls">
                                                <select name="reason" id="reason" data-placeholder="Chọn" class="form-control select2" style="width: 100%;">
                                                    <option value="-- Chọn --" disabled="disabled" selected="selected">-- Chọn --</option>
                                                    <option value="Điều chỉnh lương">Điều chỉnh lương</option>
                                                    <option value="Điều chỉnh chức danh">Điều chỉnh chức danh</option>
                                                    <option value="Khác">Khác</option>
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
                                    </textarea>
                                </div>
                            </div>

                            <br>
                            <div class="control-group">
                                <div class="controls">
                                    <input type="submit" value="Thêm" class="btn btn-success">
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
