@section('title')
{{ 'Sửa chế độ' }}
@endsection

@extends('layouts.base')
@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Sửa chế độ BH</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ route('regimes.index') }}">Tất cả chế độ BH</a></li>
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
                    <form class="form-horizontal" method="post" action="{{ route('regimes.update', $regime->id) }}" name="update_regime" id="update_regime" novalidate="novalidate">
                        {{ csrf_field() }}
                        @method('PATCH')
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="row">
                                <div class="col-6">
                                    <div class="control-group">
                                        <div class="control-group">
                                            <label class="required-field" class="control-label">Loại chế độ</label>
                                            <div class="controls">
                                                <select name="regime_type_id" id="regime_type_id" data-placeholder="Chọn" class="form-control select2" style="width: 100%;">
                                                    <option value="-- Chọn --" disabled="disabled" selected="selected">-- Chọn --</option>
                                                    @foreach ($regime_types as $regime_type)
                                                        <option value="{{$regime_type->id}}" @if($regime_type->id == $regime->regime_type_id) selected="selected" @endif>{{$regime_type->name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-6">
                                    <div class="control-group">
                                        <label class="control-label">Đợt thanh toán</label>
                                        <input class="form-control" type="text" name="payment_period" id="payment_period" value="{{$regime->payment_period}}">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-6">
                                  <label>Thời gian bắt đầu nghỉ</label>
                                  <div class="input-group date" id="off_start_date" data-target-input="nearest">
                                      <input type="text" name="off_start_date" class="form-control datetimepicker-input" @if ($regime->off_start_date) value="{{date('d/m/Y', strtotime($regime->off_start_date))}}" @endif data-target="#off_start_date"/>
                                      <div class="input-group-append" data-target="#off_start_date" data-toggle="datetimepicker">
                                          <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                      </div>
                                  </div>
                                </div>
                                <div class="col-6">
                                  <label>Thời gian kết thúc nghỉ</label>
                                  <div class="input-group date" id="off_end_date" data-target-input="nearest">
                                      <input type="text" name="off_end_date" class="form-control datetimepicker-input" @if($regime->off_end_date) value="{{date('d/m/Y', strtotime($regime->off_end_date))}}" @endif data-target="#off_end_date"/>
                                      <div class="input-group-append" data-target="#off_end_date" data-toggle="datetimepicker">
                                          <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                      </div>
                                  </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12">
                                  <label>Số tiền được thanh toán</label>
                                  <input class="form-control" type="number" name="payment_amount" id="payment_amount" value="{{$regime->payment_amount}}">
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
        $('#off_start_date').datetimepicker({
            format: 'DD/MM/YYYY'
        });
        $('#off_end_date').datetimepicker({
            format: 'DD/MM/YYYY'
        });
    })
</script>
@endpush
