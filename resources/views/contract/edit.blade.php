@section('title')
{{ 'Sửa hợp đồng' }}
@endsection

@extends('layouts.base')
@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Sửa hợp đồng</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ route('contracts.index') }}">Tất cả hợp đồng</a></li>
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
                    <form class="form-horizontal" method="post" action="{{ route('contracts.update', $contract->id) }}" enctype="multipart/form-data" name="update_contract" id="update_contract" novalidate="novalidate">
                        {{ csrf_field() }}
                        @method('PATCH')
                        <div class="card-header">
                            <h4 class="m-0">Số {{$contract->code}} của {{$contract->employee->name}}</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-6">
                                    <div class="control-group">
                                        <div class="control-group">
                                            <label class="required-field" class="control-label">Vị trí</label>
                                            <div class="controls">
                                                <select name="position_id" id="position_id" data-placeholder="Chọn" class="form-control select2" style="width: 100%;">
                                                    <option value="-- Chọn --" disabled="disabled" selected="selected">-- Chọn --</option>
                                                    @foreach ($positions as $position)
                                                        <option value="{{$position->id}}" @if ($contract && $position->id == $contract->position_id) selected="selected" @endif>{{$position->name}} {{$position->division_id ? (' - ' . $position->division->name) : ''}} {{$position->department_id ? ( ' - ' . $position->department->name) : ''}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="control-group">
                                        <div class="control-group">
                                            <label class="required-field" class="control-label">Loại tạo</label>
                                            <div class="controls">
                                                <select name="created_type" id="created_type" data-placeholder="Chọn" class="form-control select2" style="width: 100%;">
                                                    <option value="Ký mới" @if('Ký mới' == $contract->created_type) selected="selected" @endif>Ký mới</option>
                                                    <option value="Tái ký" @if('Tái ký' == $contract->created_type) selected="selected" @endif>Tái ký</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <div class="control-group">
                                        <div class="control-group">
                                            <label class="required-field" class="control-label">Loại HĐ</label>
                                            <div class="controls">
                                                <select name="contract_type_id" id="contract_type_id" data-placeholder="Chọn" class="form-control select2" style="width: 100%;">
                                                    <option value="-- Chọn --" disabled="disabled" selected="selected">-- Chọn --</option>
                                                    @foreach ($contract_types as $contract_type)
                                                        <option value="{{$contract_type->id}}" @if ($contract_type->id == $contract->contract_type_id) selected="selected" @endif>{{$contract_type->name}}</option>
                                                    @endforeach
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
                                <div class="col-6">
                                  <label class="required-field">Thời gian bắt đầu</label>
                                  <div class="input-group date" id="s_date" data-target-input="nearest">
                                      <input type="text" name="s_date" class="form-control datetimepicker-input" value="{{date('d/m/Y', strtotime($contract->start_date))}}" data-target="#s_date"/>
                                      <div class="input-group-append" data-target="#s_date" data-toggle="datetimepicker">
                                          <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                      </div>
                                  </div>
                                </div>
                                <div class="col-6">
                                  <label class="required-field">Thời gian kết thúc</label>
                                  <div class="input-group date" id="e_date" data-target-input="nearest">
                                      <input type="text" name="e_date" class="form-control datetimepicker-input" @if ($contract->end_date) value="{{date('d/m/Y', strtotime($contract->end_date))}}" @endif data-target="#e_date"/>
                                      <div class="input-group-append" data-target="#e_date" data-toggle="datetimepicker">
                                          <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                      </div>
                                  </div>
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

        //Date picker
        $('#s_date').datetimepicker({
            format: 'DD/MM/YYYY'
        });
        $('#e_date').datetimepicker({
            format: 'DD/MM/YYYY'
        });

        // Add the following code if you want the name of the file appear on select
        $(".custom-file-input").on("change", function() {
            var fileName = $(this).val().split("\\").pop();
            $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
        });
    })
</script>
@endpush
