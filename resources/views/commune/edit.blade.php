@section('title')
{{ 'Sửa phường xã' }}
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
          <h1 class="m-0">Sửa phường xã</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('communes.index') }}">Tất cả phường xã</a></li>
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
                <form class="form-horizontal" method="post" action="{{ route('communes.update', $commune->id) }}" name="edit_commune" id="edit_commune" novalidate="novalidate">{{ csrf_field() }}
                    @method('PATCH')
                    <!-- /.card-header -->
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6">
                                <div class="control-group">
                                    <label class="required-field" class="control-label">Tên</label>
                                    <div class="controls">
                                        <input type="text" class="form-control" name="name" id="name" required="" value="{{ $commune->name }}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="control-group">
                                    <label class="required-field" class="control-label">Quận/Huyện - Thàn phố/Tỉnh</label>
                                    <div class="controls">
                                        <select name="district_id" id="district_id" data-placeholder="Chọn tỉnh" class="form-control select2" style="width: 100%;">
                                            @foreach ($districts as $district)
                                                <option value="{{$district->id}}" @if($district->id == $commune->district_id) selected @endif>{{$district->name}} - {{$district->province->name}}</option>
                                            @endforeach
                                        </select>
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
