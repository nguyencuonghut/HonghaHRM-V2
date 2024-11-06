@section('title')
{{ 'Sửa lương' }}
@endsection

@extends('layouts.base')
@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Sửa lương</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ route('salaries.index') }}">Tất cả lương</a></li>
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
                    <form class="form-horizontal" method="post" action="{{ route('salaries.update', $salary->id) }}" name="update_salary" id="update_salary" novalidate="novalidate">
                        {{ csrf_field() }}
                        @method('PATCH')
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="row">
                                <div class="col-6">
                                    <div class="control-group">
                                        <label class="control-label">Lương vị trí</label>
                                        <input class="form-control" type="number" name="position_salary" id="position_salary" value="{{$salary->position_salary}}">
                                    </div>
                                  </div>
                                  <div class="col-6">
                                    <div class="control-group">
                                        <label class="control-label">Lương năng lực</label>
                                        <input class="form-control" type="number" name="capacity_salary" id="capacity_salary" value="{{$salary->capacity_salary}}">
                                    </div>
                                  </div>
                            </div>

                            <div class="row">
                                <div class="col-6">
                                    <div class="control-group">
                                        <label class="control-label">Phụ cấp vị trí</label>
                                        <input class="form-control" type="number" name="position_allowance" id="position_allowance" value="{{$salary->position_allowance}}">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="control-group">
                                        <label class="required-field" class="control-label">Lương bảo hiểm</label>
                                        <input class="form-control" type="number" name="insurance_salary" id="insurance_salary" value="{{$salary->insurance_salary}}">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12">
                                  <label class="required-field">Thời gian bắt đầu</label>
                                  <div class="input-group date" id="salary_start_date" data-target-input="nearest">
                                      <input type="text" name="salary_start_date" class="form-control datetimepicker-input" data-target="#salary_start_date" value="{{date('d/m/Y', strtotime($salary->start_date))}}"/>
                                      <div class="input-group-append" data-target="#salary_start_date" data-toggle="datetimepicker">
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
        //Date picker
        $('#salary_start_date').datetimepicker({
            format: 'DD/MM/YYYY'
        });
    });
</script>
@endpush
