@section('title')
{{ 'Thêm nhân sự' }}
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
          <h1 class="m-0">Thêm nhân sự (không qua tuyển dụng)</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('employees.index') }}">Tất cả nhân sự</a></li>
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
                <form class="form-horizontal" method="post" action="{{ url('employees') }}" name="add_employee" id="add_employee" enctype="multipart/form-data" novalidate="novalidate">{{ csrf_field() }}
                    <!-- /.card-header -->
                    <div class="card-body">
                        <div class="row">
                            <div class="col-4">
                                <div class="control-group">
                                    <label class="required-field" class="control-label">Mã</label>
                                    <div class="controls">
                                        <input type="text" class="form-control" name="code" id="code" required="">
                                    </div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="control-group">
                                    <label class="required-field" class="control-label">Họ tên</label>
                                    <div class="controls">
                                        <input type="text" class="form-control" name="name" id="name" required="">
                                    </div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="control-group">
                                    <label class="control-label">Email cá nhân</label>
                                    <div class="controls">
                                        <input type="email" class="form-control" name="private_email" id="private_email" required="">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-4">
                                <div class="control-group">
                                    <label class="control-label">Email công ty</label>
                                    <div class="controls">
                                        <input type="email" class="form-control" name="company_email" id="company_email" required="">
                                    </div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="control-group">
                                    <label class="required-field" class="control-label">Số điện thoại</label>
                                    <div class="controls">
                                        <input type="text" class="form-control" name="phone" id="phone" required="">
                                    </div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="control-group">
                                    <label class="required-field" class="control-label">Số điện thoại người thân</label>
                                    <div class="controls">
                                        <input type="text" class="form-control" name="relative_phone" id="relative_phone" required="">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-4">
                                <label class="required-field">Ngày sinh</label>
                                <div class="input-group date" id="date_of_birth" data-target-input="nearest">
                                    <input type="text" name="date_of_birth" class="form-control datetimepicker-input" data-target="#date_of_birth"/>
                                    <div class="input-group-append" data-target="#date_of_birth" data-toggle="datetimepicker">
                                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="control-group">
                                    <label class="required-field" class="control-label">CCCD</label>
                                    <div class="controls">
                                        <input type="text" class="form-control" name="cccd" id="cccd" required="">
                                    </div>
                                </div>
                            </div>
                            <div class="col-4">
                                <label class="required-field" class="control-label">Ngày cấp</label>
                                <div class="input-group date" id="issued_date" data-target-input="nearest">
                                    <input type="text" name="issued_date" class="form-control datetimepicker-input" data-target="#issued_date"/>
                                    <div class="input-group-append" data-target="#issued_date" data-toggle="datetimepicker">
                                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-4">
                                <div class="control-group">
                                    <label class="required-field" class="control-label">Nơi cấp</label>
                                    <div class="controls">
                                        <input type="text" class="form-control" name="issued_by" id="issued_by" required="">
                                    </div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="control-group">
                                    <label class="required-field" class="control-label">Giới tính</label>
                                    <div class="controls">
                                        <select name="gender" id="gender" data-placeholder="Chọn giới tính" class="form-control select2" style="width: 100%;">
                                            <option value="-- Chọn --" disabled="disabled" selected="selected">-- Chọn --</option>
                                            <option value="Nam">Nam</option>
                                            <option value="Nữ">Nữ</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="control-group">
                                    <label class="required-field control-label">Tình trạng hôn nhân</label>
                                    <div class="controls">
                                        <select name="marriage_status" id="marriage_status" data-placeholder="Chọn địa chỉ" class="form-control select2" style="width: 100%;">
                                            <option value="-- Chọn --" disabled="disabled" selected="selected">-- Chọn --</option>
                                            <option value="Kết hôn">Kết hôn</option>
                                            <option value="Độc thân">Độc thân</option>
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
                                        <input type="text" class="form-control" name="address" id="address" required="">
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
                                                <option value="{{$commune->id}}">{{$commune->name}} - {{$commune->district->name}} - {{$commune->district->province->name}}</option>
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
                                        <input type="text" class="form-control" name="temp_address" id="temp_address" required="">
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
                                                <option value="{{$commune->id}}">{{$commune->name}} - {{$commune->district->name}} - {{$commune->district->province->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-6">
                                <div class="control-group">
                                    <label class="required-field" class="control-label">Ảnh</label>
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
                                        <input type="text" class="form-control" required="" name="bhxh" id="bhxh">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <label class="required-field" class="control-label">Học vấn</label>
                                <table class="table table-bordered" id="dynamicTable">
                                    <tr>
                                        <th class="required-field" style="width: 40%;">
                                            Trường
                                        </th>
                                        <th class="required-field" style="width: 25%;">Trình độ</th>
                                        <th>Ngành</th>
                                        <th>#</th>
                                    </tr>
                                    <tr>
                                        <td>
                                            <select name="addmore[0][school_id]" class="form-control select2" style="width: 100%;">
                                                <option selected="selected" disabled>Chọn trường</option>
                                                @foreach($schools as $school)
                                                    <option value="{{$school->id}}">{{$school->name}}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <select name="addmore[0][degree_id]" class="form-control select2" style="width: 100%;">
                                                <option selected="selected" disabled>Chọn trình độ</option>
                                                @foreach($degrees as $degree)
                                                    <option value="{{$degree->id}}">{{$degree->name}}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td><input type="text" name="addmore[0][major]" placeholder="Ngành" class="form-control" /></td>
                                        <td><button type="button" name="add_school" id="add_school" class="btn btn-success"><i class="fas fa-plus"></i></button></td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <label class="required-field" class="control-label">Kinh nghiệm</label>
                                <textarea id="experience" name="experience">
                                </textarea>
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
        });
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


        var i = 0;
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
    });
  </script>
@endpush
