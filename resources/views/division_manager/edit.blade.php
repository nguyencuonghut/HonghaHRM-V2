@section('title')
{{ 'Sửa quản lý bộ phận' }}
@endsection

@extends('layouts.base')
@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Sửa quản lý bộ phận</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ route('division_managers.index') }}">Tất quản lý bộ phận</a></li>
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
                    <form class="form-horizontal" method="post" action="{{ route('division_managers.update', $division_manager->id) }}" name="edit_division_manager" id="edit_divison_manager" novalidate="novalidate">
                        {{ csrf_field() }}
                        @method('PATCH')
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="row">
                                <div class="col-6">
                                    <div class="control-group">
                                        <label class="required-field" class="control-label">Bộ phận</label>
                                        <div class="controls">
                                            <select name="division_id" id="division_id" data-placeholder="Chọn" class="form-control select2" style="width: 100%;">
                                                @foreach($divisions as $division)
                                                    <option value="{{$division->id}}" @if ($division->id == $division_manager->division_id) selected @endif>{{$division->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="control-group">
                                        <label class="required-field" class="control-label">Quản lý</label>
                                        <div class="controls">
                                            <select name="manager_id" id="manager_id" data-placeholder="Chọn" class="form-control select2" style="width: 100%;">
                                                @foreach($employees as $employee)
                                                    <option value="{{$employee->id}}" @if ($employee->id == $division_manager->manager_id) selected @endif>{{$employee->code}} - {{$employee->name}}</option>
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
