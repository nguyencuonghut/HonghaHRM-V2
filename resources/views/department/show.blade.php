@section('title')
{{ 'Sơ đồ tổ chức' }}
@endsection
@push('styles')
<!-- Orgchart -->
<link rel="stylesheet" href="{{ asset('plugins/orgchart/css/jquery.orgchart.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/orgchart/css/styles.orgchart.css') }}">
<style type="text/css">
    #chart-container {
      height: 550px;
    }
    .orgchart .node .title {
      height: unset;
      text-align: left;
      line-height: 40px;
      width: 230px;
    }
    .orgchart .node .content {
      text-align: left;
      padding: 0 5px;
      width: 230px;
    }
    .orgchart .node .content .symbol {
      color: #aaa;
      margin-right: 20px;
    }
    .oci-leader::before, .oci-leader::after {
      background-color: rgba(217, 83, 79, 0.8);
    }
    .orgchart .node .avatar {
      width: 60px;
      height: 60px;
      border-radius: 30px;
      float: left;
      margin: 5px;
    }
    .orgchart .node .title {
      background-color: #cc0066;
    }
    .orgchart .node .content {
      text-align: center;
    }


    .orgchart { background: #fff; }
    /* .orgchart td.left, .orgchart td.right, .orgchart td.top { border-color: #aaa; }
    .orgchart td>.down { background-color: #aaa; }
    .orgchart .middle-level .title { background-color: #006699; }
    .orgchart .middle-level .content { border-color: #006699; }
    .orgchart .product-dept .title { background-color: #009933; }
    .orgchart .product-dept .content { border-color: #009933; }
    .orgchart .rd-dept .title { background-color: #993366; }
    .orgchart .rd-dept .content { border-color: #993366; }
    .orgchart .pipeline1 .title { background-color: #996633; }
    .orgchart .pipeline1 .content { border-color: #996633; }
    .orgchart .frontend1 .title { background-color: #cc0066; }
    .orgchart .frontend1 .content { border-color: #cc0066; } */

</style>
@endpush

@extends('layouts.base')
@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Sơ đồ tổ chức {{$department->name}}</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('departments.index') }}">Tất cả phòng/ban</a></li>
            <li class="breadcrumb-item active">Sơ đồ tổ chức</li>
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
              <div class="card-header">
                Sơ đồ {{$department->name}}
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                    <div id="chart-container"></div>
              </div>
            </div>
        </div>
      </div>
      <!-- /.row (main row) -->
    </div><!-- /.container-fluid -->
  </section>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->
@endsection

@push('scripts')
<script src="{{ asset('plugins/orgchart/js/jquery.orgchart.js') }}"></script>
<script src="{{ asset('plugins/orgchart/js/html2canvas.js') }}"></script>
<script type="text/javascript">
$(function() {
    var datasource = {{ Js::from($datasource) }};

    $('#chart-container').orgchart({
      'exportButton': true,
      'exportFilename': 'MyOrgChart',
      'data' : datasource,
      'nodeContent': 'title',
      'nodeID': 'id',
      'createNode': function($node, data) {
        $node.find('.title').append(`<img class="avatar" src="http://localhost:8000/${data.id}" crossorigin="anonymous" />`);
        $node.find('.content').prepend($node.find('.symbol'));
      }
    });
});
</script>
@endpush
