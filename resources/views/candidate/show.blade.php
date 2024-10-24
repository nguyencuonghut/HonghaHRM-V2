@section('title')
{{ 'Chi tiết ứng viên' }}
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
            <h1 class="m-0">Chi tiết {{$candidate->name}}</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ route('candidates.index') }}">Tất cả ứng viên</a></li>
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
            <div class="col-md-4">
                <!-- Profile Image -->
                <div class="card card-primary card-outline">
                <div class="card-body box-profile">
                    <div class="text-center">

                    <h3 class="profile-username text-center">{{$candidate->name}}</h3>

                    <p class="text-muted text-center">{{$candidate->email}}</p>
                    <a href="{{route('candidates.edit', $candidate->id)}}" class="btn btn-warning btn-block"><b>Sửa thông tin</b></a>
                </div>
                <!-- /.card-body -->
                </div>
                </div>
                <!-- /.card -->
                <div class="card card-primary">
                    <div class="card-header">
                      <h3 class="card-title">Chi tiết</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                      <strong><i class="fas fa-mobile-alt mr-1"></i> Điện thoại</strong>
                      <p class="text-muted">
                        - Cá nhân: {{$candidate->phone}} <br>
                        @if($candidate->relative_phone)
                        - Người thân: {{$candidate->relative_phone}}
                        @endif
                      </p>
                      <hr>

                      <strong><i class="fas fa-calendar-alt mr-1"></i> Ngày sinh</strong>
                      <p class="text-muted">
                        {{date('d/m/Y', strtotime($candidate->date_of_birth))}}
                      </p>
                      <hr>

                      @if ($candidate->cccd)
                      <strong><i class="fas fa-id-card mr-1"></i> CCCD</strong>
                      <p class="text-muted">
                        - Số: {{$candidate->cccd}} <br>
                        - Cấp bởi: {{$candidate->issued_by}}
                      </p>
                      <hr>
                      @endif

                      <strong><i class="fas fa-map-marker-alt mr-1"></i> Địa chỉ</strong>
                      <p class="text-muted">
                        {{$candidate->address}}, {{$candidate->commune->name}}, {{$candidate->commune->district->name}}, {{$candidate->commune->district->province->name}}
                      </p>
                      <hr>

                      <strong><i class="fas fa-graduation-cap mr-1"></i> Học vấn</strong>
                      <p class="text-muted">
                        @php
                            $schools_info = '';

                            foreach ($candidate->schools as $school) {
                                $candidate_school = App\Models\CandidateSchool::where('candidate_id', $candidate->id)->where('school_id', $school->id)->first();
                                $degree = App\Models\Degree::findOrFail($candidate_school->degree_id);
                                if ($candidate_school->major) {
                                    $schools_info = $schools_info . $school->name . ' - ' . $degree->name . ' - ' . $candidate_school->major . '<br>';
                                } else {
                                    $schools_info = $schools_info . $school->name;
                                }

                            }
                        @endphp
                        {!! $schools_info !!}
                      </p>

                      <strong><i class="fas fa-suitcase mr-1"></i> Kinh nghiệm</strong>
                      <p class="text-muted">
                        {!! preg_replace('/<p[^>]*>(.*)<\/p[^>]*>/i', '$1', $candidate->experience) !!}
                      </p>

                      <hr>
                    </div>
                    <!-- /.card-body -->
                  </div>
                  <!-- /.card -->
            </div>
            <div class="col-md-8">
                <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Tất cả ứng tuyển</h5>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <div class="row">
                        <div class="table-responsive">
                            <table id="recruitments-table" class="table">
                                <thead>
                                <tr>
                                  <th>Vị trí</th>
                                  <th>Bộ phận</th>
                                  <th>Phòng ban</th>
                                  <th>Đợt</th>
                                  <th>CV</th>
                                  <th>Kết quả</th>
                                  <th>Thời gian</th>
                                </tr>
                                </thead>
                                <tbody>
                                    @foreach ($candidate->recruitments as $recruitment)
                                    <tr>
                                      <td>
                                        <a href="{{route('recruitments.show', $recruitment->id)}}">
                                            {{$recruitment->position->name}}
                                        </a>
                                      </td>
                                      <td>
                                        @if ($recruitment->position->division_id)
                                        {{$recruitment->position->division->name}}
                                        @else
                                        -
                                        @endif
                                      </td>
                                      <td>{{$recruitment->position->department->name}}</td>
                                      @php
                                          $recruitment_candidate = App\Models\RecruitmentCandidate::where('candidate_id', $candidate->id)->where('recruitment_id', $recruitment->id)->first();
                                          $url = '<a target="_blank" href="../../../' . $recruitment_candidate->cv_file . '"><i class="far fa-file-pdf"></i></a>';
                                      @endphp
                                      <td>{{ $recruitment_candidate->batch}}</td>
                                      <td>{!! $url !!}</td>
                                      <td>-</td>
                                      <td>{{ date('d/m/Y', strtotime($recruitment_candidate->created_at)) }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                              </table>
                        </div>
                    <!-- /.table-responsive -->
                    </div>
                    <!-- /.row -->
                </div>
                <!-- ./card-body -->
                </div>
                <!-- /.card -->
            </div>
            <!-- /.col -->
            </div>
            <!-- /.row -->
        </div><!--/. container-fluid -->
    </section>
    <!-- /.content -->
  </div>
@endsection



