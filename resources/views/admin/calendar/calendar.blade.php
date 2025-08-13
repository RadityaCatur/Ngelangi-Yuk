@extends('layouts.admin')

@section('styles')
  <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css" rel="stylesheet" />
  <style>
    .calendar-wrapper {
    width: 100%;
    height: 80vh;
    }

    #calendar {
    width: 100%;
    height: 100%;
    }

    .fc-event.event-empty {
    background-color: #A2D5AB !important;
    border: none !important;
    color: #1F3A2E !important;
    font-weight: bold;
    text-align: center;
    }

    .fc-event.event-filled {
    background-color: #E6A9A9 !important;
    border: none !important;
    color: #4A2F2F !important;
    font-weight: bold;
    text-align: center;
    }

    .fc-daygrid-day {
    aspect-ratio: 1 / 1;
    padding: 4px;
    }

    .fc .fc-daygrid-day-frame {
    display: flex;
    flex-direction: column;
    justify-content: flex-start;
    height: 100%;
    padding: 0 !important;
    }

    .fc .fc-daygrid-event {
    margin: 0 !important;
    padding: 4px !important;
    width: 100% !important;
    height: 100% !important;
    box-sizing: border-box;
    display: flex !important;
    align-items: center;
    justify-content: center;
    flex-grow: 1 !important;
    font-size: 12px !important;
    }

    .fc-header-toolbar .fc-toolbar-chunk {
    display: flex;
    align-items: center;
    gap: 8px;
    }

    /* Responsif */
    @media (max-width: 768px) {
    .calendar-wrapper {
      height: 80vh;
    }

    .fc-daygrid-day {
      aspect-ratio: 1 / 1;
      padding: 0;
    }

    .fc .fc-daygrid-event {
      font-size: 11px !important;
      padding: 4px !important;
    }

    .fc-header-toolbar .fc-today-button {
      display: none !important;
    }

    /* Geser prev-next ke kanan */
    .fc-header-toolbar .fc-toolbar-chunk:last-child {
      margin-left: auto;
    }
    }
  </style>
@endsection

@section('content')
  <h3 class="page-title">{{ trans('global.systemCalendar') }}</h3>
  <div class="card">
    <div class="card-body">
    <div class="calendar-wrapper">
      <div id="calendar"></div>
    </div>
    </div>
  </div>
@endsection

@section('scripts')
  <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function () {
    const calendarEl = document.getElementById('calendar');

    const events = {!! json_encode($events) !!}.map(ev => ({
      title: ev.title,
      start: ev.start,
      allDay: true,
      url: ev.url,
      classNames: [ev.className]
    }));

    const calendar = new FullCalendar.Calendar(calendarEl, {
      timeZone: 'local',
      initialView: 'dayGridMonth',
      locale: 'id',
      contentHeight: 'auto',
      height: '100%',
      events: events,
      headerToolbar: {
      left: 'title',
      right: 'prev,next today'
      },

      eventContent: function (arg) {
      const container = document.createElement('div');
      container.style.textAlign = 'center';
      container.style.fontWeight = 'bold';
      container.style.fontSize = window.innerWidth < 768 ? '14px' : '13px';

      const title = arg.event.title;
      const isMobile = window.innerWidth < 768;

      if (isMobile) {
        // Coba ambil angka dari judul "5 Appointment"
        const match = title.match(/\d+/);
        container.textContent = match ? match[0] : ''; // hanya angka, atau kosong
      } else {
        container.textContent = title; // desktop: tampilkan semua
      }

      return { domNodes: [container] };
      },


      windowResize: function () {
      calendar.updateSize();
      calendar.rerenderEvents();
      }
    });

    calendar.render();
    });
  </script>
@endsection