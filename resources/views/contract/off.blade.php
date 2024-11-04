@section('title')
{{ 'Kết thúc hợp đồng' }}
@endsection

@extends('layouts.base')
@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Kết thúc hợp đồng</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ route('contracts.index') }}">Tất cả hợp đồng</a></li>
              <li class="breadcrumb-item active">Kết thúc</li>
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
                    <form class="form-horizontal" method="post" action="{{ route('contracts.off', $contract->id) }}" name="off_contract" id="off_contract" novalidate="novalidate">
                        {{ csrf_field() }}
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="row">
                                <div class="col-6">
                                  <label class="required-field">Thời gian kết thúc</label>
                                  <div class="input-group date" id="e_date" data-target-input="nearest">
                                      <input type="text" name="e_date" class="form-control datetimepicker-input" data-target="#e_date" @if($contract->end_date) value="{{date('d/m/Y', strtotime($contract->end_date))}}" @endif/>
                                      <div class="input-group-append" data-target="#e_date" data-toggle="datetimepicker">
                                          <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                      </div>
                                  </div>
                                </div>
                                <div class="col-6">
                                  <label>Ngày viết đơn</label>
                                  <div class="input-group date" id="request_terminate_date" data-target-input="nearest">
                                      <input type="text" name="request_terminate_date" class="form-control datetimepicker-input" data-target="#request_terminate_date" @if($contract->request_terminate_date) value="{{date('d/m/Y', strtotime($contract->request_terminate_date))}}" @endif/>
                                      <div class="input-group-append" data-target="#request_terminate_date" data-toggle="datetimepicker">
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
        $('#e_date').datetimepicker({
            format: 'DD/MM/YYYY'
        });
        $('#request_terminate_date').datetimepicker({
            format: 'DD/MM/YYYY'
        });
    })
</script>
@endpush
