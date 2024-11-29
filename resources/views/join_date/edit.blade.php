@section('title')
{{ 'Sửa ngày vào' }}
@endsection

@extends('layouts.base')
@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Sửa ngày vào</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ route('join_dates.index') }}">Tất cả ngày vào</a></li>
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
                    <form class="form-horizontal" method="post" action="{{ route('join_dates.update', $join_date->id) }}" name="update_join_date" id="update_join_date" novalidate="novalidate">
                        {{ csrf_field() }}
                        @method('PATCH')
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <label class="required-field">Ngày vào</label>
                                    <div class="input-group date" id="join_date" data-target-input="nearest">
                                        <input type="text" name="join_date" class="form-control datetimepicker-input" value="{{date('d/m/Y', strtotime($join_date->join_date))}}" data-target="#join_date"/>
                                        <div class="input-group-append" data-target="#join_date" data-toggle="datetimepicker">
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
        $('#join_date').datetimepicker({
            format: 'DD/MM/YYYY'
        });
    });
</script>
@endpush

