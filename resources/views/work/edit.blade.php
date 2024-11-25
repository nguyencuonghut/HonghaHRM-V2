@section('title')
{{ 'Sửa QT công tác' }}
@endsection

@extends('layouts.base')
@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Sửa QT công tác</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ route('works.index') }}">Tất cả QT công tác</a></li>
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
                    <form class="form-horizontal" method="post" action="{{ route('works.update', $work->id) }}" name="update_work" id="update_work" novalidate="novalidate">
                        {{ csrf_field() }}
                        @method('PATCH')
                        <!-- /.card-header -->
                        <div class="card-body">
                            @php
                                $contract = App\Models\Contract::where('employee_id', $work->employee_id)->orderBy('id', 'desc')->first();
                            @endphp
                            <div class="row">
                                <div class="col-6">
                                    <div class="control-group">
                                        <div class="control-group">
                                            <label class="required-field" class="control-label">Vị trí</label>
                                            <div class="controls">
                                                <select name="position_id" id="position_id" data-placeholder="Chọn" class="form-control select2" style="width: 100%;">
                                                    <option value="-- Chọn --" disabled="disabled" selected="selected">-- Chọn --</option>
                                                    @foreach ($positions as $position)
                                                        <option value="{{$position->id}}" @if ($position->id == $contract->position_id) selected="selected" @endif>{{$position->name}} {{$position->division_id ? (' - ' . $position->division->name) : ''}} {{$position->department_id ? ( ' - ' . $position->department->name) : ''}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @php
                                    $contracts = App\Models\Contract::where('employee_id', $work->employee_id)->orderBy('id', 'desc')->get();
                                @endphp
                                <div class="col-6">
                                    <div class="control-group">
                                        <div class="control-group">
                                            <label class="required-field" class="control-label">Mã hợp đồng</label>
                                            <div class="controls">
                                                <select name="contract_code" id="contract_code" data-placeholder="Chọn" class="form-control select2" style="width: 100%;">
                                                    @foreach ($contracts as $contract)
                                                        <option value="{{$contract->code}}" @if ($contract->code == $work->contract_code) selected="selected" @endif>{{$contract->code}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                              <div class="col-6">
                                  <label class="required-field">Thời gian bắt đầu</label>
                                  <div class="input-group date" id="s_date" data-target-input="nearest">
                                      <input type="text" name="s_date" class="form-control datetimepicker-input" value="{{date('d/m/Y', strtotime($work->start_date))}}" data-target="#s_date"/>
                                      <div class="input-group-append" data-target="#s_date" data-toggle="datetimepicker">
                                          <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                      </div>
                                  </div>
                              </div>
                              <div class="col-6">
                                <div class="control-group">
                                    <div class="control-group">
                                        <label class="required-field" class="control-label">Phân loại tạo</label>
                                        <div class="controls">
                                            <select name="on_type_id" id="on_type_id" data-placeholder="Chọn" class="form-control select2" style="width: 100%;">
                                                <option value="-- Chọn --" disabled="disabled" selected="selected">-- Chọn --</option>
                                                @foreach ($on_types as $on_type)
                                                    <option value="{{$on_type->id}}" @if($on_type->id == $work->on_type_id) selected="selected" @endif>{{$on_type->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
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
    })
</script>
@endpush
