@section('title')
{{ 'Sửa phúc lợi' }}
@endsection

@extends('layouts.base')
@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Sửa phúc lợi</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ route('welfares.index') }}">Tất cả phúc lợi</a></li>
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
                    <form class="form-horizontal" method="post" action="{{ route('welfares.update', $welfare->id) }}" name="update_insurance" id="update_insurance" novalidate="novalidate">
                        {{ csrf_field() }}
                        @method('PATCH')
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="row">
                                <div class="col-4">
                                    <div class="control-group">
                                        <div class="control-group">
                                            <label class="required-field" class="control-label">Loại phúc lợi</label>
                                            <div class="controls">
                                                <select name="welfare_type_id" id="welfare_type_id" data-placeholder="Chọn" class="form-control select2" style="width: 100%;">
                                                    <option value="-- Chọn --" disabled="disabled" selected="selected">-- Chọn --</option>
                                                    @foreach ($welfare_types as $welfare_type)
                                                        <option value="{{$welfare_type->id}}" @if($welfare_type->id == $welfare->welfare_type_id) selected="selected" @endif>{{$welfare_type->name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-4">
                                  <label>Ngày chi trả</label>
                                  <div class="input-group date" id="payment_date" data-target-input="nearest">
                                      <input type="text" name="payment_date" class="form-control datetimepicker-input" @if($welfare->payment_date) value="{{date('d/m/Y', strtotime($welfare->payment_date))}}" @endif data-target="#payment_date"/>
                                      <div class="input-group-append" data-target="#payment_date" data-toggle="datetimepicker">
                                          <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                      </div>
                                  </div>
                                </div>

                                <div class="col-4">
                                    <label>Số tiền được thanh toán</label>
                                    <input class="form-control" type="number" name="payment_amount" id="payment_amount" value="{{$welfare->payment_amount}}">
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
        $('#payment_date').datetimepicker({
            format: 'DD/MM/YYYY'
        });
    })
</script>
@endpush
