@section('title')
{{ 'Sửa nhân sự' }}
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
            <h1 class="m-0">Sửa thông tin</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ route('employees.index') }}">Tất cả nhân sự</a></li>
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
                        <form class="form-horizontal" method="post" action="{{ route('employees.update', $employee->id) }}" enctype="multipart/form-data" name="edit_employee" id="edit_employee" novalidate="novalidate">
                            {{ csrf_field() }}
                            @method('PATCH')
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-4">
                                        <div class="control-group">
                                            <label class="required-field" class="control-label">Mã</label>
                                            <div class="controls">
                                                <input type="text" class="form-control" name="code" id="code" required="" value="{{$employee->code}}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="control-group">
                                            <label class="required-field" class="control-label">Họ tên</label>
                                            <div class="controls">
                                                <input type="text" class="form-control" name="name" id="name" required="" value="{{$employee->name}}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="control-group">
                                            <label class="control-label">Email cá nhân</label>
                                            <div class="controls">
                                                <input type="email" class="form-control" name="private_email" id="private_email" required="" value="{{$employee->private_email}}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-4">
                                        <div class="control-group">
                                            <label class="control-label">Email công ty</label>
                                            <div class="controls">
                                                <input type="email" class="form-control" name="company_email" id="company_email" required="" value="{{$employee->company_email}}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="control-group">
                                            <label class="required-field" class="control-label">Số điện thoại</label>
                                            <div class="controls">
                                                <input type="text" class="form-control" name="phone" id="phone" required="" value="{{$employee->phone}}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="control-group">
                                            <label class="control-label">Số điện thoại người thân</label>
                                            <div class="controls">
                                                <input type="text" class="form-control" name="relative_phone" id="relative_phone" required="" value="{{$employee->relative_phone}}">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-4">
                                        <label class="required-field">Ngày sinh</label>
                                        <div class="input-group date" id="date_of_birth" data-target-input="nearest">
                                            <input type="text" name="date_of_birth" class="form-control datetimepicker-input" data-target="#date_of_birth" value="{{date('d/m/Y', strtotime($employee->date_of_birth))}}"/>
                                            <div class="input-group-append" data-target="#date_of_birth" data-toggle="datetimepicker">
                                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="control-group">
                                            <label class="control-label">CCCD</label>
                                            <div class="controls">
                                                <input type="text" class="form-control" name="cccd" id="cccd" required="" value="{{$employee->cccd}}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <label class="control-label">Ngày cấp</label>
                                        <div class="input-group date" id="issued_date" data-target-input="nearest">
                                            <input type="text" name="issued_date" class="form-control datetimepicker-input" data-target="#issued_date" @if ($employee->issued_date) value="{{date('d/m/Y', strtotime($employee->issued_date))}}" @endif/>
                                            <div class="input-group-append" data-target="#issued_date" data-toggle="datetimepicker">
                                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-4">
                                        <div class="control-group">
                                            <label class="control-label">Nơi cấp</label>
                                            <div class="controls">
                                                <input type="text" class="form-control" name="issued_by" id="issued_by" required="" value="{{$employee->issued_by}}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="control-group">
                                            <label class="required-field" class="control-label">Giới tính</label>
                                            <div class="controls">
                                                <select name="gender" id="gender" data-placeholder="Chọn giới tính" class="form-control select2" style="width: 100%;">
                                                    <option value="-- Chọn --" disabled="disabled" selected="selected">-- Chọn --</option>
                                                    <option value="Nam" @if ('Nam' == $employee->gender) selected @endif>Nam</option>
                                                    <option value="Nữ" @if ('Nữ' == $employee->gender) selected @endif>Nữ</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="control-group">
                                            <label class="control-label">Tình trạng hôn nhân</label>
                                            <div class="controls">
                                                <select name="marriage_status" id="marriage_status" data-placeholder="Chọn địa chỉ" class="form-control select2" style="width: 100%;">
                                                    <option value="-- Chọn --" disabled="disabled" selected="selected">-- Chọn --</option>
                                                    <option value="Kết hôn" @if('Kết hôn' == $employee->marriage_status) selected @endif>Kết hôn</option>
                                                    <option value="Độc thân" @if('Độc thân' == $employee->marriage_status) selected @endif>Độc thân</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-6">
                                        <div class="control-group">
                                            <label class="control-label">Số nhà thường trú</label>
                                            <div class="controls">
                                                <input type="text" class="form-control" name="address" id="address" required="" value="{{$employee->address}}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="control-group">
                                            <label class="required-field" class="control-label">Địa chỉ thường trú</label>
                                            <div class="controls">
                                                <select name="commune_id" id="commune_id" data-placeholder="Chọn địa chỉ" class="form-control select2" style="width: 100%;">
                                                    <option value="-- Chọn --" disabled="disabled" selected="selected">-- Chọn --</option>
                                                    @foreach($communes as $commune)
                                                        <option value="{{$commune->id}}" @if ($commune->id == $employee->commune_id) selected @endif>{{$commune->name}} - {{$commune->district->name}} - {{$commune->district->province->name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-6">
                                        <div class="control-group">
                                            <label class="control-label">Số nhà tạm trú</label>
                                            <div class="controls">
                                                <input type="text" class="form-control" name="temp_address" id="temp_address" required="" value="{{$employee->temporary_address}}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="control-group">
                                            <label class="control-label">Địa chỉ tạm trú</label>
                                            <div class="controls">
                                                <select name="temp_commune_id" id="temp_commune_id" data-placeholder="Chọn địa chỉ" class="form-control select2" style="width: 100%;">
                                                    <option value="-- Chọn --" disabled="disabled" selected="selected">-- Chọn --</option>
                                                    @foreach($communes as $commune)
                                                    <option value="{{$commune->id}}" @if ($commune->id == $employee->temporary_commune_id) selected @endif>{{$commune->name}} - {{$commune->district->name}} - {{$commune->district->province->name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-6">
                                        <div class="control-group">
                                            <label class="control-label">Ảnh</label>
                                            <div class="custom-file text-left">
                                                <input type="file" name="img_path" accept="image/*" class="custom-file-input" id="img_path">
                                                <label class="custom-file-label" for="img_path">Chọn file</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="control-group">
                                            <label class="control-label">Số BHXH</label>
                                            <div class="controls">
                                                <input type="text" class="form-control" required="" name="bhxh" id="bhxh" value="{{$employee->bhxh}}">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-12">
                                        <label class="required-field" class="control-label">Học vấn</label>
                                        <table class="table table-bordered" id="dynamicTable">
                                            <tr>
                                                <th class="required-field">
                                                    Trường
                                                </th>
                                                <th class="required-field">Trình độ</th>
                                                <th>Ngành</th>
                                                <th style="width: 14%;"><button type="button" name="add_school" id="add_school" class="btn btn-success">Thêm</button></th>
                                            </tr>
                                            @php
                                                $i = 0;
                                            @endphp
                                            @foreach ($employee->schools as $item)
                                            <tr>
                                                <td>
                                                    <select name="addmore[{{$i}}][school_id]" class="form-control select2" style="width: 100%;">
                                                        <option selected="selected" disabled>Chọn trường</option>
                                                        @foreach($schools as $school)
                                                            <option value="{{$school->id}}" @if ($school->id == $item->id) selected @endif>{{$school->name}}</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                @php
                                                    $my_employee_school = App\Models\EmployeeSchool::where('school_id', $item->id)->where('employee_id', $employee->id)->first();
                                                    $degree = App\Models\Degree::findOrFail($my_employee_school->degree_id);
                                                @endphp
                                                <td>
                                                    <select name="addmore[{{$i}}][degree_id]" class="form-control select2" style="width: 100%;">
                                                        <option selected="selected" disabled>Chọn trình độ</option>
                                                        @foreach($degrees as $degree)
                                                            <option value="{{$degree->id}}" @if ($degree->id == $my_employee_school->degree_id) selected @endif>{{$degree->name}}</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td><input type="text" name="addmore[{{$i}}][major]" placeholder="Ngành" class="form-control" value="{{$my_employee_school->major}}"/></td>
                                                <td><button type="button" class="btn btn-danger remove-tr">Xóa</button></td>
                                            </tr>
                                            @php
                                                $i++;
                                            @endphp
                                            @endforeach
                                        </table>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-12">
                                        <label class="required-field" class="control-label">Kinh nghiệm</label>
                                        <textarea id="experience" name="experience">
                                            {{$employee->experience}}
                                        </textarea>
                                    </div>
                                </div>

                                <div class="control-group">
                                    <div class="controls">
                                        <input type="submit" value="Sửa" class="btn btn-success">
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div><!--/. container-fluid -->
    </section>
    <!-- /.content -->
  </div>
@endsection


@push('scripts')
<style type="text/css">
    .dataTables_wrapper .dt-buttons {
    margin-bottom: -3em
  }
</style>

<script>
    $(function () {
        //Initialize Select2 Elements
        $('.select2').select2({
        theme: 'bootstrap4'
        })

        // Add the following code if you want the name of the file appear on select
        $(".custom-file-input").on("change", function() {
            var fileName = $(this).val().split("\\").pop();
            $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
        });

        //Date picker
        $('#date_of_birth').datetimepicker({
            format: 'DD/MM/YYYY'
        });
        $('#issued_date').datetimepicker({
            format: 'DD/MM/YYYY'
        });

        // Summernote
        $("#experience").on("summernote.enter", function(we, e) {
            $(this).summernote("pasteHTML", "<br><br>");
            e.preventDefault();
        });
        $('#experience').summernote({
            height: 90,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'underline', 'clear']],
                ['color', ['color']],
            ]
        })

        var i = 100;
        $("#add_school").click(function(){
            ++i;
            $("#dynamicTable").append('<tr><td><select name="addmore['+i+'][school_id]" class="form-control select2" style="width: 100%;"><option selected="selected" disabled>Chọn trường</option>@foreach($schools as $school)<option value="{{$school->id}}">{{$school->name}}</option>@endforeach</select></td><td><select name="addmore['+i+'][degree_id]" class="form-control select2" style="width: 100%;"><option selected="selected" disabled>Chọn trình độ</option>@foreach($degrees as $degree)<option value="{{$degree->id}}">{{$degree->name}}</option>@endforeach</select></td><td><input type="text" name="addmore['+i+'][major]" placeholder="Ngành" class="form-control" /></td><td><button type="button" class="btn btn-danger remove-tr"><i class="fas fa-trash-alt"></i></button></td></tr>');

            //Reinitialize Select2 Elements
            $('.select2').select2({
                theme: 'bootstrap4'
            })
        });

        $(document).on('click', '.remove-tr', function(){
            $(this).parents('tr').remove();
        });
    })
</script>
@endpush




