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
                            <a class="nav-link active" id="recruitment-tab-1" data-toggle="pill" href="#recruitment-1" role="tab" aria-controls="recruitment-1" aria-selected="true">Yêu cầu</a>
                          </li>
                          <li class="nav-item">
                            <a class="nav-link" id="recruitment-tab-2" data-toggle="pill" href="#recruitment-2" role="tab" aria-controls="recruitment-2" aria-selected="false">Kế hoạch</a>
                          </li>
                          <li class="nav-item">
                            <a class="nav-link" id="recruitment-tab-3" data-toggle="pill" href="#recruitment-3" role="tab" aria-controls="recruitment-3" aria-selected="false">Đăng tin</a>
                          </li>
                          <li class="nav-item">
                            <a class="nav-link" id="recruitment-tab-4" data-toggle="pill" href="#recruitment-4" role="tab" aria-controls="recruitment-4" aria-selected="false">Ứng viên</a>
                          </li>
                          <li class="nav-item">
                            <a class="nav-link" id="recruitment-tab-5" data-toggle="pill" href="#recruitment-5" role="tab" aria-controls="recruitment-5" aria-selected="false">Lọc hồ sơ</a>
                          </li>
                          <li class="nav-item">
                            <a class="nav-link" id="recruitment-tab-6" data-toggle="pill" href="#recruitment-6" role="tab" aria-controls="recruitment-6" aria-selected="false">Phỏng vấn sơ bộ</a>
                          </li>
                          <li class="nav-item">
                            <a class="nav-link" id="recruitment-tab-7" data-toggle="pill" href="#recruitment-7" role="tab" aria-controls="recruitment-7" aria-selected="false">Phỏng vấn lần 1</a>
                          </li>
                          <li class="nav-item">
                            <a class="nav-link" id="recruitment-tab-8" data-toggle="pill" href="#recruitment-8" role="tab" aria-controls="recruitment-8" aria-selected="false">Phỏng vấn lần 2</a>
                          </li>
                          <li class="nav-item">
                            <a class="nav-link" id="recruitment-tab-9" data-toggle="pill" href="#recruitment-9" role="tab" aria-controls="recruitment-9" aria-selected="false">Offer chế độ</a>
                          </li>
                        </ul>
                      </div>
                      <div class="card-body">
                        <div class="tab-content" id="custom-tabs-one-tabContent">
                            @include('recruitment.tabs.request_tab')
                            @include('recruitment.tabs.plan_tab')
                            @include('recruitment.tabs.announcement_tab')
                            @include('recruitment.tabs.candidate_tab')
                            @include('recruitment.tabs.filter_tab')
                            @include('recruitment.tabs.initial_interview_tab')
                            @include('recruitment.tabs.first_interview_tab')
                            @include('recruitment.tabs.second_interview_tab')
                            @include('recruitment.tabs.offer_tab')
                      </div>
                      <!-- /.card -->
                    </div>
                  </div>
            </div>
        </div>
    </section>
</div>
@endsection

