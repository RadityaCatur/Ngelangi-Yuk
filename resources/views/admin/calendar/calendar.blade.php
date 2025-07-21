@extends('layouts.admin')

@section('styles')
  <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.1.0/fullcalendar.min.css' />
  <style>
    .calendar-wrapper {
    overflow-x: auto;
    }

    #calendar {
    min-width: 900px;
    }

    .fc-time-grid-event {
    white-space: normal;
    overflow: visible !important;
    }

    .fc-event {
    padding: 8px;
    font-size: 12px;
    text-align: center;
    }

    .fc-event img {
    width: 140px;
    height: 140px;
    object-fit: cover;
    border-radius: 10px;
    display: block;
    margin: 0 auto 8px auto;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .fc-event:hover img {
    transform: scale(1.05);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
    }

    .fc-slats td {
    height: 200px !important;
    }

    .fc-tooltip {
    background: #333;
    color: white;
    padding: 6px 10px;
    border-radius: 5px;
    font-size: 13px;
    position: absolute;
    z-index: 10001;
    white-space: nowrap;
    }

    /* ✅ Mobile layout fix */
    @media (max-width: 768px) {
    #calendar {
      min-width: 1000px;
    }

    .fc-event img {
      width: 100px;
      height: 100px;
    }

    .fc-event {
      font-size: 11px;
    }

    .fc-toolbar.fc-header-toolbar {
      display: flex;
      flex-direction: column;
      align-items: stretch;
    }

    .fc-toolbar .fc-center {
      order: 1;
      text-align: center;
      font-size: 16px;
      font-weight: bold;
      margin-bottom: 8px;
    }

    .fc-toolbar .fc-left {
      order: 2;
      display: flex;
      gap: 10px;
      justify-content: flex-start;
      padding-left: 10px;
      flex-wrap: wrap;
    }

    .fc-button {
      padding: 4px 8px;
      font-size: 12px;
    }
    }
  </style>
@endsection

@section('content')
  <h3 class="page-title">{{ trans('global.systemCalendar') }}</h3>
  <div class="card">
    <div class="card-header">
    {{ trans('global.systemCalendar') }}
    </div>

    <div class="card-body">
    <div class="calendar-wrapper">
      <div id='calendar'></div>
    </div>
    </div>
  </div>
@endsection

@section('scripts')
  <script src='https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.17.1/moment.min.js'></script>
  <script src='https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.17.1/locale/id.js'></script>
  <script src='https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.1.0/fullcalendar.min.js'></script>
  <script>
    $(document).ready(function () {
    moment.locale('id');
    const events = {!! json_encode($events) !!};

    $('#calendar').fullCalendar({
      events: events,
      defaultView: 'agendaWeek',
      locale: 'en-gb',
      timeFormat: 'H:mm',
      slotLabelFormat: 'H:mm',
      slotDuration: '01:00:00',
      allDaySlot: false,
      minTime: "06:00:00",
      maxTime: "22:00:00",
      height: "auto",

      eventRender: function (event, element) {
      element.find('.fc-content').html('');

      if (event.employee_photo_url) {
        element.find('.fc-content').append(
        $('<img>', {
          src: event.employee_photo_url,
          alt: event.title
        })
        );
      }

      element.find('.fc-content').append(
        $('<div>').text(event.title).css({
        'font-weight': 'bold',
        'font-size': '14px',
        'margin-top': '5px'
        })
      );

      element.find('.fc-content').append(
        $('<div>').text(
        moment(event.start).format('HH:mm') + ' - ' + moment(event.end).format('HH:mm')
        ).css({
        'font-size': '12px',
        'margin-top': '4px'
        })
      );
      },

      eventAfterRender: function (event, element) {
      element.hover(
        function (e) {
        const tooltip = $('<div class="fc-tooltip">')
          .text(event.title)
          .appendTo('body');

        $(this).data('tooltip', tooltip);
        tooltip.css({
          top: e.pageY + 10,
          left: e.pageX + 10
        });
        },
        function () {
        $(this).data('tooltip').remove();
        }
      ).mousemove(function (e) {
        $(this).data('tooltip').css({
        top: e.pageY + 10,
        left: e.pageX + 10
        });
      });
      }
    });
    });
  </script>
@endsection