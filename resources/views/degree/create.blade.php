@section('title')
{{ 'Thêm trình độ' }}
@endsection

@extends('layouts.base')
@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Thêm trình độ</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('degrees.index') }}">Tất cả trình độ</a></li>
            <li class="breadcrumb-item active">Thêm</li>
          </ol>
        </div><!-- /.col -->
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>
  <!-- /.content-header -->

  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
      <!-- Small boxes (Stat box) -->
      <div class="row">
        <div class="col-12">
            <div class="card">
                <form class="form-horizontal" method="post" action="{{ url('degrees') }}" name="add_degree" id="add_degree" novalidate="novalidate">{{ csrf_field() }}
                    <!-- /.card-header -->
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="control-group">
                                    <label class="required-field" class="control-label">Tên</label>
                                    <div class="controls">
                                        <input type="text" class="form-control" name="name" id="name" required="">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="control-group">
                            <div class="controls">
                                <input type="submit" value="Thêm" class="btn btn-success">
                            </div>
                        </div>
                    </div>
                </form>
      </div>
      <!-- /.row (main row) -->
    </div><!-- /.container-fluid -->
  </section>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->
@endsection
