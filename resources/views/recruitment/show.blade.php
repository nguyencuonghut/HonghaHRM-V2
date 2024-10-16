@section('title')
{{ 'Chi tiết đề xuất' }}
@endsection

@extends('layouts.base')
@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Tuyển dụng {{$recruitment->position->name}}</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ route('recruitments.index') }}">Tất cả yêu cầu</a></li>
              <li class="breadcrumb-item active">Chi tiết</li>
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
                <div class="col-12 col-sm-12">
                    <div class="card card-secondary card-outline card-tabs">
                      <div class="card-header p-0 pt-1 border-bottom-0">
                        <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
                          <li class="nav-item">
                            <a class="nav-link active" id="recruitment-request-tab-1" data-toggle="pill" href="#recruitment-request-1" role="tab" aria-controls="recruitment-request-1" aria-selected="true">Yêu cầu</a>
                          </li>
                          <li class="nav-item">
                            <a class="nav-link" id="recruitment-request-tab-2" data-toggle="pill" href="#recruitment-request-2" role="tab" aria-controls="recruitment-request-2" aria-selected="false">Kế hoạch</a>
                          </li>
                        </ul>
                      </div>
                      <div class="card-body">
                        <div class="tab-content" id="custom-tabs-one-tabContent">
                            @include('recruitment.tabs.request_tab')
                            @include('recruitment.tabs.plan_tab')
                      </div>
                      <!-- /.card -->
                    </div>
                  </div>
            </div>
        </div>
    </section>
</div>
@endsection

