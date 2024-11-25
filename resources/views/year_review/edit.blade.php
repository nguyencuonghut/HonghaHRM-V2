@section('title')
{{ 'Sửa đánh giá cuối năm' }}
@endsection

@extends('layouts.base')
@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Sửa đánh giá</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ route('year_reviews.index') }}">Tất cả đánh giá cuối năm</a></li>
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
                    <form class="form-horizontal" method="post" action="{{ route('year_reviews.update', $year_review->id) }}" name="update_year_review" id="update_year_review" novalidate="novalidate">
                        {{ csrf_field() }}
                        @method('PATCH')
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="row">
                                <div class="col-6">
                                  <div class="control-group">
                                      <label class="required-field" class="control-label">Năm</label>
                                      <input class="form-control" type="number" name="year" id="year" value="{{$year_review->year}}">
                                  </div>
                                </div>
                                <div class="col-6">
                                    <div class="control-group">
                                        <label class="required-field" class="control-label">KPI trung bình</label>
                                        <input class="form-control" type="number" name="kpi_average" id="kpi_average" step="any" value="{{$year_review->kpi_average}}">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <label class="required-field control-label">Vị trí</label>
                                    <div class="controls">
                                        <select name="position_id" id="position_id" data-placeholder="Chọn vị trí" class="form-control select2" style="width: 100%;">
                                            <option value="-- Chọn --" disabled="disabled" selected="selected">-- Chọn --</option>
                                            @foreach($my_positions as $position)
                                                <option value="{{$position->id}}" @if($position->name == $year_review->position->name) selected="selected" @endif>{{$position->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-6">
                                  <div class="control-group">
                                        <label class="required-field" class="control-label">Kết quả</label>
                                        <div class="controls">
                                            <select name="result" id="result" data-placeholder="Chọn" class="form-control select2" style="width: 100%;">
                                                <option value="-- Chọn --" disabled="disabled" selected="selected">-- Chọn --</option>
                                                <option value="Xuất sắc" @if('Xuất sắc' == $year_review->result) selected="selected" @endif>Xuất sắc</option>
                                                <option value="Tốt" @if('Tốt' == $year_review->result) selected="selected" @endif>Tốt</option>
                                                <option value="Đạt" @if('Đạt' == $year_review->result) selected="selected" @endif>Đạt</option>
                                                <option value="Cải thiện" @if('Cải thiện' == $year_review->result) selected="selected" @endif>Cải thiện</option>
                                            </select>
                                        </div>
                                  </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12">
                                    <label class="control-label">Chi tiết</label>
                                    <textarea id="detail" name="detail">
                                        {!! $year_review->detail !!}
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
<script>
    $(function () {
        //Initialize Select2 Elements
        $('.select2').select2({
        theme: 'bootstrap4'
        })
    })

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
    })
</script>
@endpush
