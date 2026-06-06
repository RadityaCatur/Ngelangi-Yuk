@extends('layouts.admin')

@section('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<style>
    /* Styling Header */
    .card-header {
        font-weight: 800 !important;
        font-size: 1.2rem;
        color: #2c3e50;
        border-bottom: 2px solid #f1f3f5;
    }

    /* -----------------------------------------
       STYLING UNTUK DATE RANGE PICKER (FLATPICKR)
       ----------------------------------------- */
    .flatpickr-calendar {
        box-shadow: 0 10px 25px rgba(0,0,0,0.1) !important;
        border: none !important;
        border-radius: 12px !important;
        padding: 10px;
        background: #fff !important;
    }
    .flatpickr-current-month .flatpickr-monthDropdown-months {
        appearance: none; 
        background: transparent !important;
        color: #2c3e50 !important; 
        font-size: 1.1rem !important; 
        font-weight: bold;
    }
    .flatpickr-current-month .flatpickr-monthDropdown-months .flatpickr-monthDropdown-month {
        background-color: #fff !important;
        color: #2c3e50 !important;
        font-size: 1rem !important;
    }
    .flatpickr-current-month input.cur-year {
        color: #2c3e50 !important;
        font-size: 1.1rem !important;
        font-weight: bold;
    }
    .flatpickr-months .flatpickr-prev-month, .flatpickr-months .flatpickr-next-month {
        fill: #2c3e50 !important;
        color: #2c3e50 !important;
    }
    .flatpickr-day.selected, .flatpickr-day.startRange, .flatpickr-day.endRange, 
    .flatpickr-day.selected.prevMonthDay, .flatpickr-day.selected.nextMonthDay {
        background: #019db2 !important; 
        border-color: #019db2 !important;
        border-radius: 8px !important; 
    }
    .flatpickr-day.inRange {
        background: rgba(1, 157, 178, 0.15) !important;
        box-shadow: none !important;
    }
    .flatpickr-day:hover {
        border-radius: 8px !important;
    }
    .flatpickr-weekday {
        color: #6c757d !important;
        font-weight: bold;
    }

    /* -----------------------------------------
       STYLING UNTUK ADMIN ACCORDION
       ----------------------------------------- */
    .trainer-details {
        border: 1px solid #e9ecef;
        border-radius: 8px;
        margin-bottom: 12px;
        background-color: #fff;
        box-shadow: 0 2px 5px rgba(0,0,0,0.02);
    }
    .trainer-summary {
        padding: 15px 20px;
        font-size: 16px;
        cursor: pointer;
        background-color: #f8f9fa;
        border-radius: 8px;
        display: flex;
        align-items: center; 
        list-style: none;
        transition: background-color 0.2s;
    }
    .trainer-name {
        flex-grow: 1; 
        font-weight: bold;
        color: #2c3e50;
    }
    .trainer-badge-container {
        min-width: 120px;
        text-align: right;
        margin-right: 15px; 
    }
    .trainer-summary::after {
        content: '▼';
        font-size: 12px;
        color: #019db2;
        transition: transform 0.2s ease-in-out;
        flex-shrink: 0; 
    }
    details[open] .trainer-summary::after {
        transform: rotate(180deg);
    }
    details[open] .trainer-summary {
        border-bottom-left-radius: 0;
        border-bottom-right-radius: 0;
        border-bottom: 1px solid #e9ecef;
    }
    .trainer-summary:hover {
        background-color: #e2e6ea;
    }
    .trainer-summary:focus {
        outline: none;
    }
    .trainer-content {
        padding: 20px;
    }
    .daily-list {
        margin-bottom: 15px;
        padding-bottom: 10px;
        border-bottom: 1px dashed #e9ecef;
    }
    .daily-list:last-child {
        margin-bottom: 0;
        padding-bottom: 0;
        border-bottom: none;
    }

    /* -----------------------------------------
       STYLING UNTUK PELATIH (SUMMARY WIDGET)
       ----------------------------------------- */
    .employee-summary-card {
        background: linear-gradient(135deg, #019db2, #74c2ce);
        color: white;
        border-radius: 15px;
        padding: 30px 20px;
        text-align: center;
        margin-bottom: 25px;
        box-shadow: 0 8px 20px rgba(1, 157, 178, 0.25);
        position: relative;
        overflow: hidden;
    }
    /* Dekorasi Lingkaran di Background Card */
    .employee-summary-card::before {
        content: "";
        position: absolute;
        top: -20px;
        left: -20px;
        width: 100px;
        height: 100px;
        background: rgba(255,255,255,0.1);
        border-radius: 50%;
    }
    .employee-summary-card::after {
        content: "";
        position: absolute;
        bottom: -30px;
        right: -30px;
        width: 150px;
        height: 150px;
        background: rgba(255,255,255,0.1);
        border-radius: 50%;
    }
    .employee-summary-card h2 {
        font-size: 3.5rem;
        font-weight: 800;
        margin: 10px 0;
        text-shadow: 2px 2px 4px rgba(0,0,0,0.1);
    }
    .daily-card {
        border-left: 5px solid #019db2;
        background: #f8f9fa;
        border-radius: 0 10px 10px 0;
        padding: 20px;
        margin-bottom: 15px;
        transition: all 0.3s ease;
        height: 100%; /* Biar tinggi card di row seragam */
    }
    .daily-card:hover {
        transform: translateY(-3px);
        background: #fff;
        box-shadow: 0 6px 15px rgba(0,0,0,0.05);
    }
    
    @media (max-width: 768px) {
        .employee-summary-card h2 {
            font-size: 2.8rem;
        }
        .trainer-summary {
            padding: 12px;
        }
    }
</style>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        Laporan Jam Latihan
    </div>

    <div class="card-body">

        <form method="GET" class="mb-4" id="filterForm">
            <div class="row">
                <div class="col-md-6 col-12">
                    <div class="input-group" style="box-shadow: 0 2px 5px rgba(0,0,0,0.05); border-radius: 5px;">
                        <div class="input-group-prepend">
                            <span class="input-group-text bg-white border-right-0"><i class="fas fa-calendar-alt text-muted"></i></span>
                        </div>
                        <input type="text" id="date_range" class="form-control border-left-0 bg-white date-range-reusable" 
                               placeholder="Pilih rentang tanggal..." readonly style="cursor: pointer;">
                        <div class="input-group-append">
                            <button class="btn btn-info text-white" style="background-color: #019db2; border-color: #019db2;" type="submit">
                                <i class="fas fa-filter mr-1"></i> Filter
                            </button>
                        </div>
                    </div>
                    <input type="hidden" name="start_date" id="start_date" value="{{ $startDate }}">
                    <input type="hidden" name="end_date" id="end_date" value="{{ $endDate }}">
                </div>
            </div>
        </form>

        @if($mode === 'admin')
            @foreach($data as $employeeId => $row)
                <details class="trainer-details">
                    <summary class="trainer-summary">
                        <span class="trainer-name">{{ $row['name'] }}</span>
                        
                        <span class="trainer-badge-container">
                            <span class="badge badge-success px-3 py-2" style="font-size: 14px; background-color: #019db2;">
                                <i class="fas fa-clock mr-1"></i> {{ round($row['total_minutes'] / 60, 2) }} Jam
                            </span>
                        </span>
                    </summary>
                    
                    <div class="trainer-content">
                        @if(count($row['perDay']) > 0)
                            <div class="row">
                                @foreach($row['perDay'] as $date => $dayData)
                                    <div class="col-md-6 col-lg-4 mb-3">
                                        <div class="daily-card py-3 px-3" style="border-left-color: #74c2ce;">
                                            <strong class="d-block mb-1" style="color: #2c3e50;">{{ \Carbon\Carbon::parse($date)->translatedFormat('d F Y') }}</strong>
                                            <span class="text-muted d-block mb-2" style="font-size: 0.9rem;">
                                                <i class="far fa-clock"></i> {{ round($dayData['minutes'] / 60, 2) }} jam
                                            </span>
                                            <span style="font-size: 0.9rem;">
                                                <i class="fas fa-users text-muted mr-1"></i> {{ implode(', ', $dayData['clients']) }}
                                            </span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-3 text-muted">
                                <i class="fas fa-inbox fa-2x mb-2" style="color: #e9ecef;"></i>
                                <p class="mb-0">Belum ada sesi latihan di periode ini.</p>
                            </div>
                        @endif
                    </div>
                </details>
            @endforeach

        @else
            <div class="employee-summary-card">
                <p class="mb-0" style="font-size: 1.1rem; opacity: 0.9; text-transform: uppercase; letter-spacing: 1px;">Total Jam Mengajar</p>
                <h2>{{ round($totalMinutes / 60, 2) }} <span style="font-size: 1.8rem; font-weight: 500;">Jam</span></h2>
                <p class="mb-0 mt-2" style="font-size: 0.95rem; opacity: 0.85;">
                    <i class="far fa-calendar-check mr-1"></i> 
                    Periode: <strong>{{ \Carbon\Carbon::parse($startDate)->translatedFormat('d M Y') }}</strong> s/d <strong>{{ \Carbon\Carbon::parse($endDate)->translatedFormat('d M Y') }}</strong>
                </p>
            </div>

            <h5 class="mb-3" style="color: #2c3e50; font-weight: bold; border-bottom: 2px solid #f1f3f5; padding-bottom: 10px;">Rincian Per Hari</h5>
            
            <div class="row">
                @forelse($perDay as $date => $row)
                    <div class="col-md-6 col-xl-4 mb-3">
                        <div class="daily-card">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div>
                                    <strong style="color: #2c3e50; font-size: 1.1rem; display: block;">
                                        {{ \Carbon\Carbon::parse($date)->translatedFormat('l') }}
                                    </strong>
                                    <span class="text-muted" style="font-size: 0.85rem;">
                                        {{ \Carbon\Carbon::parse($date)->translatedFormat('d M Y') }}
                                    </span>
                                </div>
                                <span class="badge badge-info px-2 py-1" style="background-color: #e0f7fa; color: #019db2; border: 1px solid #b2ebf2;">
                                    <i class="fas fa-stopwatch mr-1"></i> {{ round($row['minutes'] / 60, 2) }} Jam
                                </span>
                            </div>
                            <div style="font-size: 0.95rem; line-height: 1.5; color: #555;">
                                <i class="fas fa-users mr-2 text-muted"></i> Murid:<br>
                                <strong style="color: #2c3e50;">{{ implode(', ', $row['clients']) }}</strong>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-5">
                        <i class="far fa-calendar-times fa-4x mb-3" style="color: #dee2e6;"></i>
                        <h5 class="text-muted" style="font-weight: normal;">Anda belum memiliki jadwal mengajar di rentang waktu ini.</h5>
                    </div>
                @endforelse
            </div>
            
        @endif

    </div>
</div>
@endsection

@section('scripts')
@parent
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://npmcdn.com/flatpickr/dist/l10n/id.js"></script>
<script>
    $(document).ready(function () {
        // Inisialisasi Date Range Picker
        $(".date-range-reusable").flatpickr({
            locale: "id",
            mode: "range",
            dateFormat: "Y-m-d",
            // Set default date berdasarkan data dari backend
            defaultDate: ["{{ $startDate }}", "{{ $endDate }}"],
            disableMobile: "true",
            onChange: function(selectedDates, dateStr, instance) {
                // Saat user memilih range (2 tanggal terpilih), update input hidden
                if (selectedDates.length === 2) {
                    let start = instance.formatDate(selectedDates[0], "Y-m-d");
                    let end = instance.formatDate(selectedDates[1], "Y-m-d");
                    $('#start_date').val(start);
                    $('#end_date').val(end);
                }
            }
        });
    });
</script>
@endsection