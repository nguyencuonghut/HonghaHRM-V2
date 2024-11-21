@section('title')
{{ 'Tất cả lịch' }}
@endsection
@push('styles')
<!-- fullCalendar -->
<link rel="stylesheet" href="{{ asset('plugins/fullcalendar/main.css')}}">


<style type="text/css">

.fc-daygrid-event{
  display: block!important;
  padding-left: 15px!important;
}
.fc-daygrid-event {
  white-space: normal !important;
  align-items: normal !important;
}
.fc-daygrid-event-dot{
  display: inline-flex;
  position: absolute;
  left: 0px;
  top: 6px;
}
.fc-event-time,.fc-event-title{
  display: inline;
}

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
          <h1 class="m-0">Tất cả lịch</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Trang chủ</a></li>
            <li class="breadcrumb-item active">Lịch</li>
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
              <!-- /.card-header -->
              <div class="card-body">
                <div id='calendar'></div>
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
<!-- CalendarJS -->
<script src="{{ asset('plugins/fullcalendar/main.js')}}"></script>

<script>
    /* initialize the calendar
     -----------------------------------------------------------------*/
    //Date for the calendar events (dummy data)
    var date = new Date()
    var d    = date.getDate(),
        m    = date.getMonth(),
        y    = date.getFullYear()

    var Calendar = FullCalendar.Calendar;

    var containerEl = document.getElementById('external-events');
    var checkbox = document.getElementById('drop-remove');
    var calendarEl = document.getElementById('calendar');

    var events =  {{ Js::from($events) }};
    var calendar = new Calendar(calendarEl, {
      locale: 'vi',
      headerToolbar: {
        left  : 'prev,next today',
        center: 'title',
        right : 'dayGridMonth,timeGridWeek,timeGridDay,listMonth'
      },
      themeSystem: 'bootstrap',
      events: events,
      eventTimeFormat: {
        hour: '2-digit',
        minute: '2-digit',
        hour12:false
      }
    });

    calendar.render();
  </script>
@endpush
