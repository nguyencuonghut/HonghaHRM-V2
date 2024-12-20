@section('title')
{{ 'Sửa KPI' }}
@endsection

@extends('layouts.base')
@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Sửa KPI</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ route('kpis.index') }}">Tất cả KPI</a></li>
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
                    <form class="form-horizontal" method="post" action="{{ route('kpis.update', $kpi->id) }}" name="update_kpi" id="update_kpi" novalidate="novalidate">
                        {{ csrf_field() }}
                        @method('PATCH')
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="row">
                                <div class="col-6">
                                  <div class="control-group">
                                      <label class="required-field" class="control-label">Năm</label>
                                      <input class="form-control" type="number" name="year" id="year" value="{{$kpi->year}}">
                                  </div>
                                </div>
                                <div class="col-6">
                                    <div class="control-group">
                                          <label class="required-field" class="control-label">Tháng</label>
                                          <div class="controls">
                                              <select name="month" id="month" data-placeholder="Chọn" class="form-control select2" style="width: 100%;">
                                                  <option value="-- Chọn --" disabled="disabled" selected="selected">-- Chọn --</option>
                                                  <option value="1" @if("1" == $kpi->month) selected="selected" @endif>Tháng 1</option>
                                                  <option value="2" @if("2" == $kpi->month) selected="selected" @endif>Tháng 2</option>
                                                  <option value="3" @if("3" == $kpi->month) selected="selected" @endif>Tháng 3</option>
                                                  <option value="4" @if("4" == $kpi->month) selected="selected" @endif>Tháng 4</option>
                                                  <option value="5" @if("5" == $kpi->month) selected="selected" @endif>Tháng 5</option>
                                                  <option value="6" @if("6" == $kpi->month) selected="selected" @endif>Tháng 6</option>
                                                  <option value="7" @if("7" == $kpi->month) selected="selected" @endif>Tháng 7</option>
                                                  <option value="8" @if("8" == $kpi->month) selected="selected" @endif>Tháng 8</option>
                                                  <option value="9" @if("9" == $kpi->month) selected="selected" @endif>Tháng 9</option>
                                                  <option value="10" @if("10" == $kpi->month) selected="selected" @endif>Tháng 10</option>
                                                  <option value="11" @if("11" == $kpi->month) selected="selected" @endif>Tháng 11</option>
                                                  <option value="12" @if("12" == $kpi->month) selected="selected" @endif>Tháng 12</option>
                                              </select>
                                          </div>
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
                                                <option value="{{$position->id}}" @if($position->name == $kpi->position->name) selected="selected" @endif>{{$position->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-6">
                                  <div class="control-group">
                                      <label class="required-field" class="control-label">Điểm</label>
                                      <input class="form-control" type="number" name="score" id="score" value="{{$kpi->score}}">
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
    })
</script>
@endpush
