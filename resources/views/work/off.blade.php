@section('title')
{{ 'Kết thúc QT công tác' }}
@endsection

@push('styles')
@extends('layouts.base')
@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Kết thúc QT công tác</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ route('works.index') }}">Tất cả QT công tác</a></li>
              <li class="breadcrumb-item active">Kết thúc</li>
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
                    <form class="form-horizontal" method="post" action="{{ route('works.off', $work->id) }}" name="off_work" id="off_work" novalidate="novalidate">
                        {{ csrf_field() }}
                        <!-- /.card-header -->
                        <div class="card-body">
                            @php
                                $contract = App\Models\Contract::where('employee_id', $work->employee_id)->orderBy('id', 'desc')->first();
                            @endphp
                            <div class="row">
                                <div class="col-6">
                                  <label class="required-field">Thời gian kết thúc</label>
                                  <div class="input-group date" id="e_date" data-target-input="nearest">
                                      <input type="text" name="e_date" class="form-control datetimepicker-input"
                                            @if($contract->end_date)
                                                value="{{date('d/m/Y', strtotime($contract->end_date))}}"
                                            @else
                                                @if($work->end_date)
                                                value="{{date('d/m/Y', strtotime($work->end_date))}}"
                                                @endif
                                            @endif data-target="#e_date"
                                      />
                                      <div class="input-group-append" data-target="#e_date" data-toggle="datetimepicker">
                                          <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                      </div>
                                  </div>
                                </div>
                                <div class="col-6">
                                    <div class="control-group">
                                        <div class="control-group">
                                            <label class="control-label">Phân loại nghỉ việc</label>
                                            <div class="controls">
                                                <select name="off_type_id" id="off_type_id" data-placeholder="Chọn" class="form-control select2" style="width: 100%;">
                                                    <option value="-- Chọn --" disabled="disabled" selected="selected">-- Chọn --</option>
                                                    @foreach ($off_types as $off_type)
                                                    <option value="{{$off_type->id}}" @if($off_type->id == $work->off_type_id) selected="selected" @endif>{{$off_type->name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <label class="control-label">Lý do nghỉ</label>
                                    <textarea id="off_reason" name="off_reason">
                                        @if ($work->off_reason)
                                        {{$work->off_reason}}
                                        @endif
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

        // Summernote
        $("#off_reason").on("summernote.enter", function(we, e) {
            $(this).summernote("pasteHTML", "<br><br>");
            e.preventDefault();
        });
        $('#off_reason').summernote({
            height: 90,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'underline', 'clear']],
                ['color', ['color']],
            ]
        })

        //Date picker
        $('#e_date').datetimepicker({
            format: 'DD/MM/YYYY'
        });
    })
</script>
@endpush
