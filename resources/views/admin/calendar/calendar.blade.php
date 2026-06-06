@extends('layouts.admin')

@section('styles')
  <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css" rel="stylesheet" />
  <style>
    .calendar-wrapper {
      width: 100%;
      min-height: 80vh;
    }

    #calendar {
      width: 100%;
      height: 100%;
    }
    
    .calendar-legend {
      display: flex;
      justify-content: center;
      gap: 20px;
      margin-bottom: 20px;
      flex-wrap: wrap;
    }
    .legend-item {
      display: flex;
      align-items: center;
      font-size: 14px;
      font-weight: bold;
      color: #555;
    }
    .legend-color {
      width: 16px;
      height: 16px;
      border-radius: 4px;
      margin-right: 8px;
    }
    .legend-empty { background-color: #A2D5AB; }
    .legend-filled { background-color: #E6A9A9; }
    .legend-past { background-color: #C9C9C9; }

    .fc-bg-event.event-past {
      background-color: #C9C9C9 !important;
      opacity: 0.6 !important; 
    }
    .fc-bg-event.event-empty {
      background-color: #A2D5AB !important;
      opacity: 0.6 !important;
    }
    .fc-bg-event.event-filled {
      background-color: #E6A9A9 !important;
      opacity: 0.6 !important;
    }

    .fc-daygrid-day {
      aspect-ratio: 1 / 1;
      cursor: pointer;
      transition: background-color 0.2s;
    }
    
    .fc-daygrid-day:hover {
        background-color: rgba(0,0,0,0.02);
    }

    .fc-header-toolbar .fc-toolbar-chunk {
      display: flex;
      align-items: center;
      gap: 8px;
    }

    @media (max-width: 768px) {
      .fc-header-toolbar .fc-today-button {
        display: none !important;
      }
      .fc-header-toolbar .fc-toolbar-chunk:last-child {
        margin-left: auto;
      }
      .calendar-legend {
        gap: 10px;
      }
      .legend-item {
        font-size: 12px;
      }
    }
  </style>
@endsection

@section('content')
  <div class="card">
    <div class="card-body">
      
      <div class="calendar-legend">
        <div class="legend-item"><span class="legend-color legend-empty"></span> Tersedia</div>
        <div class="legend-item"><span class="legend-color legend-filled"></span> Penuh</div>
        <div class="legend-item"><span class="legend-color legend-past"></span> Berlalu</div>
      </div>

      <div class="calendar-wrapper">
        <div id="calendar"></div>
      </div>
      
    </div>
  </div>
@endsection

@section('scripts')
  @parent
  <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const calendarEl = document.getElementById('calendar');

      const urlParams = new URLSearchParams(window.location.search);
      const currentView = urlParams.get('view') === 'mine' ? 'mine' : 'all';

      function toggleView() {
        const newView = currentView === 'all' ? 'mine' : 'all';
        const currentUrl = new URL(window.location);

        if (newView === 'mine') {
          currentUrl.searchParams.set('view', 'mine');
        } else {
          currentUrl.searchParams.delete('view');
        }
        window.location.href = currentUrl.toString();
      }

      const eventsData = {!! json_encode($events) !!};
        
      const today = new Date();
      today.setHours(0, 0, 0, 0);
        
      const backgroundEvents = eventsData.map(event => {
        const eventDate = new Date(event.start);
        let bgClass = 'event-empty';
        
        if (eventDate < today) {
          bgClass = 'event-past'; 
        } else if (event.className === 'event-filled' || (typeof event.className === 'string' && event.className.includes('event-filled'))) {
          bgClass = 'event-filled';
        }
        
        return {
            start: event.start,
            display: 'background',
            className: bgClass
        };
      });

      const calendar = new FullCalendar.Calendar(calendarEl, {
        timeZone: 'local',
        initialView: 'dayGridMonth',
        locale: 'id',
        contentHeight: 'auto',
        events: backgroundEvents,
        headerToolbar: {
          left: 'title',
          center: '{{ auth()->user()->hasRole('Pelatih') ? 'viewTogglemine' : '' }}',
          right: 'prev,next today'
        },

        customButtons: {
          viewTogglemine: {
            text: currentView === 'mine' ? 'Tampilkan Semua' : 'Hanya Jadwal Saya',
            click: toggleView
          }
        },

        dateClick: function(info) {
            const clickedEvent = eventsData.find(e => e.start === info.dateStr);
            
            if (clickedEvent && clickedEvent.url) {
                window.location.href = clickedEvent.url;
            }
        },

        windowResize: function () {
          calendar.updateSize();
        }
      });

      calendar.render();
    });
  </script>
@endsection