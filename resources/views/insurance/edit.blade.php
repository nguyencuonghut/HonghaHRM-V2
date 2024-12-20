@section('title')
{{ 'Sửa bảo hiểm' }}
@endsection

@extends('layouts.base')
@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Sửa bảo hiểm</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ route('insurances.index') }}">Tất cả bảo hiểm</a></li>
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
                    <form class="form-horizontal" method="post" action="{{ route('insurances.update', $insurance->id) }}" name="update_insurance" id="update_insurance" novalidate="novalidate">
                        {{ csrf_field() }}
                        @method('PATCH')
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="row">
                                <div class="col-6">
                                    <div class="control-group">
                                        <div class="control-group">
                                            <label class="required-field" class="control-label">Loại bảo hiểm</label>
                                            <div class="controls">
                                                <select name="insurance_type_id" id="insurance_type_id" data-placeholder="Chọn" class="form-control select2" style="width: 100%;">
                                                    <option value="-- Chọn --" disabled="disabled" selected="selected">-- Chọn --</option>
                                                    @foreach ($insurance_types as $insurance_type)
                                                        <option value="{{$insurance_type->id}}" @if($insurance_type->id == $insurance->insurance_type_id) selected="selected" @endif>{{$insurance_type->name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-6">
                                    <div class="control-group">
                                        <label class="control-label">Tỷ lệ đóng (%)</label>
                                        <input class="form-control" type="number" name="pay_rate" id="pay_rate" step="any" value="{{$insurance->pay_rate}}">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-6">
                                  <label class="required-field">Thời gian bắt đầu</label>
                                  <div class="input-group date" id="insurance_s_date" data-target-input="nearest">
                                      <input type="text" name="insurance_s_date" class="form-control datetimepicker-input" value="{{date('d/m/Y', strtotime($insurance->start_date))}}" data-target="#insurance_s_date"/>
                                      <div class="input-group-append" data-target="#insurance_s_date" data-toggle="datetimepicker">
                                          <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                      </div>
                                  </div>
                                </div>
                                <div class="col-6">
                                  <label>Thời gian kết thúc</label>
                                  <div class="input-group date" id="insurance_e_date" data-target-input="nearest">
                                      <input type="text" name="insurance_e_date" class="form-control datetimepicker-input" @if($insurance->end_date) value="{{date('d/m/Y', strtotime($insurance->end_date))}}" @endif data-target="#insurance_e_date"/>
                                      <div class="input-group-append" data-target="#insurance_e_date" data-toggle="datetimepicker">
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
        $('#insurance_s_date').datetimepicker({
            format: 'DD/MM/YYYY'
        });
        $('#insurance_e_date').datetimepicker({
            format: 'DD/MM/YYYY'
        });
    })
</script>
@endpush
