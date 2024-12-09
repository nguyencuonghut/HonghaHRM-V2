@section('title')
{{ 'Người dùng' }}
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
          <h1 class="m-0">Tất cả người dùng</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Trang chủ</a></li>
            <li class="breadcrumb-item active">Người dùng</li>
          </ol>
        </div><!-- /.col -->
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>
  <!-- /.content-header -->

  <!-- Main content -->
  <section class="content">
    <!-- Default box -->
    <div class="card card-solid">
        <!-- /.card-header -->
        <div class="card-body">
            @can('create', App\Models\Employee::class)
            <a href="{{ route('employees.create') }}" class="btn btn-success"><i class="fas fa-plus"></i> Thêm</a>
            @endcan
            <div class="btn-group float-right">
                &nbsp;
                <a href="{{route('employees.index')}}" class="btn btn-primary {{Route::is('employees.index') ? 'active' : ''}}">
                    <i class="fas fa-bars"></i>
                </a>
                <a href="{{route('employees.gallery')}}" class="btn btn-secondary {{Route::is('employees.gallery') ? 'active' : ''}}">
                    <i class="fas fa-th"></i>
                </a>
                <a href="{{route('employees.export')}}" class="btn btn-success {{Route::is('employees.export') ? 'active' : ''}}">
                    <i class="fas fa-download"></i>
                </a>
            </div>

            <div class="row">
                &nbsp;
                <div class="col-md-12">
                    <form action="{{route('employees.gallery')}}" method="get" novalidate="novalidate">
                        {{ csrf_field() }}
                        <div class="input-group">
                            <input type="search" name="search" id="search" class="form-control form-control-lg" placeholder="Nhập từ khóa tìm kiếm">
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-lg btn-default">
                                    <i class="fa fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

        </div>
      <div class="card-body pb-0">
        @if($employees->count())
        <div class="row">
            @foreach ($employees as $employee)
                <div class="col-12 col-sm-6 col-md-4 d-flex align-items-stretch flex-column">
                    <div class="card bg-light d-flex flex-fill">
                    @php
                    $works = App\Models\Work::where('employee_id', $employee->id)
                                            ->where('status', 'On')
                                            ->where(function ($query) {
                                                $query->whereIn('off_type_id', [2,3,4,5])//2: Nghỉ thai sản, 3: Nghỉ không lương, 4: Nghỉ ốm, 5: Thay đổi chức danh
                                                    ->orWhereNull('off_type_id');
                                            })
                                            ->get();
                        $i = 0;
                        $positions_list = '';
                        $departments_list = '';
                        foreach ($works as $work) {
                            if(++$i === $works->count()) {
                                $positions_list =  $positions_list . $work->position->name;
                                $departments_list =  $departments_list . $work->position->department->name;
                            } else {
                                $positions_list = $positions_list . $work->position->name . ' | ';
                                $departments_list = $departments_list . $work->position->department->name . ' | ';
                            }
                        }

                        $works = App\Models\Work::where('employee_id', $employee->id)->get();
                        $status_str = '';
                        if (0 == $works->count()) {//Không tồn tại QT công tác nào
                            $status_str = 'Không có QT công tác';
                        } else {//Có QT công tác
                            //Tìm QT công tác ở trạng thái On
                            $on_works = App\Models\Work::where('employee_id', $employee->id)
                                            ->where('status', 'On')
                                            ->get();
                            if ($on_works->count()) {//Đang có QT công tác
                                $status_str = 'Đang làm';
                            } else { //Chỉ có QT công tác, nhưng ở trạng thái Off
                                $last_off_work = App\Models\Work::where('employee_id', $employee->id)
                                                ->where('status', 'Off')
                                                ->orderBy('start_date' ,'desc')
                                                ->first();
                                switch ($last_off_work->off_type_id) {
                                    case 1://Nghỉ việc
                                        $status_str = 'Nghỉ việc';
                                        break;
                                    case 2://Nghỉ thai sản
                                        $status_str = 'Nghỉ thai sản';
                                        break;
                                    case 3://Nghỉ không lương
                                        $status_str = 'Nghỉ không lương';
                                        break;
                                    case 4://Nghỉ ốm
                                        $status_str = 'Nghỉ ốm';
                                        break;
                                    case 6://Nghỉ hưu
                                        $status_str = 'Nghỉ hưu';
                                        break;
                                    default:
                                    $status_str = '-';
                                }

                            }
                        }
                    @endphp
                    <div class="card-header text-muted border-bottom-0">
                        {{$positions_list}}
                    </div>
                    <div class="card-body pt-0">
                        <div class="row">
                            <div class="ribbon-wrapper ribbon-lg">
                                <div class="ribbon @if('Đang làm' == $status_str) bg-success @elseif('Nghỉ việc' == $status_str) bg-danger @else bg-secondary @endif">
                                  {{$status_str}}
                                </div>
                              </div>
                        <div class="col-7">
                            <h2 class="lead"><b>{{$employee->code}} | {{$employee->name}}</b></h2>
                            <p class="text-muted text-sm">
                                {{$departments_list}}
                            </p>
                            <ul class="ml-4 mb-0 fa-ul text-muted">
                                @if ($employee->company_email || $employee->private_email)
                                <li class="small"><span class="fa-li"><i class="fas fa-lg fa-envelope"></i></span>
                                    {{$employee->company_email}} {{$employee->private_email}}
                                </li>
                                @endif
                                <li class="small"><span class="fa-li"><i class="fas fa-lg fa-map-marker-alt"></i></span>
                                    {{$employee->commune->name}}, {{$employee->commune->district->name}}, {{$employee->commune->district->province->name}}
                                </li>
                                <li class="small"><span class="fa-li"><i class="fas fa-lg fa-mobile-alt"></i></span>{{$employee->phone}}</li>
                            </ul>
                        </div>
                        <div class="col-5 text-center">
                            <img src="{{asset($employee->img_path)}}" alt="user-avatar" class="img-circle img-fluid">
                        </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="text-right">
                        <a href="{{route('employees.show', $employee->id)}}" class="btn btn-sm btn-primary">
                            <i class="fas fa-user"></i> Xem chi tiết
                        </a>
                        </div>
                    </div>
                    </div>
                </div>
            @endforeach
      </div>
      @else
        Không tìm thấy kết quả
      @endif
    </div>
    <!-- Modal -->
    <form class="form-horizontal" method="post" action="#" enctype="multipart/form-data" name="import-user" id="import-user" novalidate="novalidate">
        {{ csrf_field() }}
        <div class="modal fade" id="import_user">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group mb-4">
                            <div class="custom-file text-left">
                                <input type="file" name="file" class="custom-file-input" id="customFile">
                                <label class="custom-file-label" for="customFile">Chọn file</label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-primary">Import</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
        </div>
    </form>
    <!-- /.modal -->

    <!-- /.card-body -->
    <div class="card-footer">
        {{-- @if ($paginator->hasPages()) --}}
        <nav aria-label="Contacts Page Navigation">
            <ul class="pagination justify-content-center m-0">
                {{ $employees->appends(Request::except('page'))->links() }}
            </ul>
        </nav>
        {{-- @endif --}}
    </div>
    <!-- /.card-footer -->
    </div>
  </section>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->
@endsection


@push('scripts')
  <script>
    // Add the following code if you want the name of the file appear on select
    $(".custom-file-input").on("change", function() {
        var fileName = $(this).val().split("\\").pop();
        $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
    });
  </script>
@endpush
