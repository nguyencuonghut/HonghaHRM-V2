@section('title')
{{ 'Sửa phó phòng' }}
@endsection

@extends('layouts.base')
@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Sửa phó phòng</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ route('department_vices.index') }}">Tất phó phòng</a></li>
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
                    <form class="form-horizontal" method="post" action="{{ route('department_vices.update', $department_vice->id) }}" name="edit_department_vice" id="edit_department_vice" novalidate="novalidate">
                        {{ csrf_field() }}
                        @method('PATCH')
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="row">
                                <div class="col-6">
                                    <div class="control-group">
                                        <label class="required-field" class="control-label">Phòng ban</label>
                                        <div class="controls">
                                            <select name="department_id" id="department_id" data-placeholder="Chọn" class="form-control select2" style="width: 100%;">
                                                @foreach($departments as $department)
                                                    <option value="{{$department->id}}" @if ($department->id == $department_vice->department_id) selected @endif>{{$department->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="control-group">
                                        <label class="required-field" class="control-label">Phó phòng</label>
                                        <div class="controls">
                                            <select name="vice_id" id="vice_id" data-placeholder="Chọn" class="form-control select2" style="width: 100%;">
                                                @foreach($employees as $employee)
                                                    <option value="{{$employee->id}}" @if ($employee->id == $department_vice->vice_id) selected @endif>{{$employee->code}} - {{$employee->name}}</option>
                                                @endforeach
                                            </select>
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
    });
</script>
@endpush
