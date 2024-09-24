@section('title')
{{ 'Sửa người dùng' }}
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
          <h1 class="m-0">Sửa người dùng</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Trang chủ</a></li>
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
      <!-- Small boxes (Stat box) -->
      <div class="row">
        <div class="col-12">
            <div class="card">
                <form class="form-horizontal" method="post" action="{{ route('users.update', $user->id) }}" name="edit_user" id="edit_user" enctype="multipart/form-data" novalidate="novalidate">{{ csrf_field() }}
                    @method('PATCH')
                    <!-- /.card-header -->
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6">
                                <div class="control-group">
                                    <label class="required-field" class="control-label">Tên</label>
                                    <div class="controls">
                                        <input type="text" class="form-control" name="name" id="name" required="" value="{{ $user->name }}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="control-group">
                                    <label class="required-field" class="control-label">Email</label>
                                    <div class="controls">
                                        <input type="email" class="form-control" name="email" id="email" required="" value="{{ $user->email }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="control-group">
                            <div class="controls">
                                <input type="submit" value="Sửa" class="btn btn-success">
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
