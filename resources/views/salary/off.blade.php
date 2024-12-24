@section('title')
{{ 'Kết thúc áp dụng lương' }}
@endsection

@extends('layouts.base')
@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Kết thúc áp dụng lương</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ route('salaries.index') }}">Tất cả lương</a></li>
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
                    <form class="form-horizontal" method="post" action="{{ route('salaries.off', $salary->id) }}" name="off_salary" id="off_salary" novalidate="novalidate">
                        {{ csrf_field() }}
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                  <label class="required-field">Thời gian kết thúc</label>
                                  <div class="input-group date" id="salary_end_date" data-target-input="nearest">
                                      <input type="text" name="salary_end_date" class="form-control datetimepicker-input" data-target="#salary_end_date" @if($salary->end_date) value="{{date('d/m/Y', strtotime($salary->end_date))}}" @endif/>
                                      <div class="input-group-append" data-target="#salary_end_date" data-toggle="datetimepicker">
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
        $('#salary_end_date').datetimepicker({
            format: 'DD/MM/YYYY'
        });
    })
</script>
@endpush
