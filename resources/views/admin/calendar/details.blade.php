@extends('layouts.admin')

@section('styles')
    <style>
        .calendar-date-navigation {
            background-color: #f1f3f5;
            padding: 1rem;
            margin-bottom: 1rem;
            border-radius: 0.375rem;
        }

        .date-nav-btn {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            font-weight: bold;
            font-size: 1.25rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .session-card {
            background: white;
            border: 1px solid #ddd;
            border-radius: 0.375rem;
            margin-bottom: 1rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        }

        .session-card .card-body {
            padding: 1rem;
        }

        .session-time {
            font-weight: bold;
            text-align: center;
            margin-bottom: 0.5rem;
        }

        .session-divider {
            height: 1px;
            background: #ccc;
            margin: 0.5rem 0;
        }

        .btn-booked {
            background-color: #dc3545;
            color: white;
            pointer-events: none;
            opacity: 0.6;
        }

        .btn-booked-accessible {
            background-color: #dc3545;
            color: white;
            opacity: 0.6;
            pointer-events: auto;
        }

        .btn-daftar {
            background-color: #28a745;
            color: white;
        }
        
        .appointment-card {
            position: relative;
            display: flex;
        } 
        .appointment-card.selected { box-shadow: 0 0 0 3px rgba(220,53,69,0.15); border-color: #dc3545; cursor: pointer; }
        .appointment-card.filter-hidden,
        .session-card.filter-hidden {
            display: none !important;
        }
        
        .select-checkbox {
          position: absolute;
          top: 8px;
          right: 8px;
          display: none; 
          z-index: 5;
        }
        .delete-mode .select-checkbox { display: block; } 
        
        #delete-action-bar {
          display: none; 
          position: fixed;
          bottom: 16px;
          left: 16px;
          right: 16px;
          z-index: 1050;
          background: #fff;
          border-radius: 8px;
          padding: 12px;
          box-shadow: 0 6px 20px rgba(0,0,0,0.12);
          
          align-items: center;
          justify-content: space-between; 
        }
        #delete-action-bar .count { font-weight: 600; margin-right: 12px; }

        .select-timeslot {
            display: none; 
        }
        .delete-mode .select-timeslot {
            display: inline-block; 
        }

        .card-date-content {
            display: flex;
            flex-direction: column; 
            align-items: center; 
        }
        .select-all-today {
            display: none; 
            font-weight: normal; 
            margin-top: 0.5rem; 
            align-self: flex-start; 
        }
        .delete-mode .select-all-today {
            display: block; 
        }
        .select-all-today .form-check-input {
            margin-right: 0.5rem; 
        }


        @media (max-width: 768px) {
            .btn-daftar {
                width: 100%;
                display: block;
                margin-top: 0.5rem;
            }
        }
        
        .calendar-actions {
          display: flex;
          gap: .5rem;
          flex-wrap: wrap;
          align-items: center;
        }
        
        @media (max-width: 768px) {
          .calendar-actions {
            justify-content: center; 
          }
        }
        
        .date-nav-card {
            transition: all 0.3s ease;
            border-radius: 12px;
            border: 2px solid transparent;
            height: 100%;
        }
        .date-nav-card.active-date {
            background: linear-gradient(135deg, #019db2, #74c2ce);
            color: #ffffff !important;
            box-shadow: 0 6px 15px rgba(1, 157, 178, 0.4);
            transform: scale(1.02); /* Sedikit diperbesar */
        }
        .date-nav-card.inactive-date {
            background-color: #ffffff;
            border-color: #e9ecef;
            color: #6c757d;
        }
        .date-nav-card.inactive-date:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
            border-color: #74c2ce;
            color: #019db2;
        }
        .date-nav-card .fw-bold {
            font-size: 1.1rem;
        }

        /* Filter Pelatih Styling */
        .trainer-filter-wrapper {
            position: relative;
        }
        .trainer-filter-wrapper .select2-container--default .select2-selection--multiple {
            border-radius: 8px;
            border: 1px solid #ced4da;
            padding: 2px 6px;
            min-height: 38px;
            background-color: #ffffff;
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        .trainer-filter-wrapper .select2-container--default.select2-container--focus .select2-selection--multiple {
            border-color: #019db2;
            box-shadow: 0 0 0 0.2rem rgba(1, 157, 178, 0.2);
        }
        .trainer-filter-wrapper .select2-container--default .select2-selection--multiple .select2-selection__choice {
            background-color: #e0f2f1;
            border: 1px solid #80cbc4;
            color: #004d40;
            border-radius: 4px;
            padding: 2px 8px;
            font-size: 0.85rem;
            font-weight: 600;
        }
        .trainer-filter-wrapper .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
            color: #004d40;
            margin-right: 5px;
        }
        .trainer-filter-wrapper .select2-container--default .select2-selection--multiple .select2-selection__choice__remove:hover {
            color: #d32f2f;
        }
        @media (max-width: 768px) {
            .trainer-filter-wrapper {
                max-width: 100% !important;
                width: 100%;
                margin-bottom: 0.5rem;
            }
        }
        /* Dynamic divider between visible appointment cards */
        .session-card .session-divider {
            display: none !important;
        }
        .session-card .appointment-card:not(.filter-hidden) ~ .appointment-card:not(.filter-hidden) {
            border-top: 1px solid #e9ecef;
            margin-top: 0.5rem;
            padding-top: 0.5rem;
        }
    </style>
@endsection

@section('content')
    @php
        $globalNow = \Carbon\Carbon::now();
        $isSystemClosed = $globalNow->hour >= 18 || $globalNow->hour < 5;
        $isMurid = auth()->user()->hasRole('Murid');
        $isAdmin = auth()->user()->hasRole('Admin');
        $employees = $employees ?? \App\Employee::orderBy('name')->pluck('name', 'id');
        $requestedTrainers = request()->query('trainers') ? explode(',', request()->query('trainers')) : (array) request()->query('employees', []);
    @endphp

    {{-- Tampilkan Banner Jika Sistem Tutup dan User adalah Murid --}}
    @if($isSystemClosed && $isMurid)
        <div class="alert alert-warning mb-3" style="border-left: 5px solid #ffc107;">
            <strong><i class="fas fa-clock"></i> Sistem Sedang Ditutup</strong><br>
            Proses pendaftaran dan pembatalan jadwal ditutup setiap pukul 18:00 dan dibuka lagi pukul 05:00
        </div>
    @endif

    <div class="calendar-date-navigation">
        @php
            $current = \Carbon\Carbon::parse($date);
            $prev = $current->copy()->subDay();
            $next = $current->copy()->addDay();

            $dates = [
                ['label' => $prev->translatedFormat('l'), 'date' => $prev, 'isActive' => false],
                ['label' => $current->translatedFormat('l'), 'date' => $current, 'isActive' => true],
                ['label' => $next->translatedFormat('l'), 'date' => $next, 'isActive' => false],
            ];
        @endphp

        <div class="d-flex justify-content-around text-center mb-4 mt-2">
            @foreach ($dates as $d)
                <a href="{{ route('admin.systemCalendar.details', ['date' => $d['date']->format('Y-m-d')]) }}"
                    class="text-decoration-none flex-fill mx-2">
                    <div class="card date-nav-card {{ $d['isActive'] ? 'active-date' : 'inactive-date' }}">
                        
                        <div class="card-body p-3 card-date-content">
                            <div class="fw-bold">{{ $d['label'] }}</div>
                            <div class="small mt-1">{{ $d['date']->translatedFormat('d M Y') }}</div>
                            
                            @if ($d['isActive'])
                                @can('appointment_delete')
                                    <label class="select-all-today form-check form-check-inline m-0 mt-2">
                                        <input type="checkbox" id="select-all-today-cb" class="form-check-input">
                                        <span class="form-check-label small" style="vertical-align: middle;">Pilih Semua</span>
                                    </label>
                                @endcan
                            @endif
                        </div>

                    </div>
                </a>
            @endforeach
        </div>

        <div class="d-flex justify-content-between align-items-center flex-wrap mb-3" style="gap: 0.5rem;">
            @if(auth()->user()->hasRole('Pelatih'))
                @php
                    $isShowingMine = request()->query('view') == 'mine';
                @endphp
                <a href="{{ route('admin.systemCalendar.details', ['date' => $date, 'view' => $isShowingMine ? null : 'mine']) }}"
                    class="btn btn-info text-white"> 
                    <i class="fas fa-filter"></i>
                    {{ $isShowingMine ? 'Tampilkan Semua' : 'Hanya Jadwal Saya' }}
                </a>
            @elseif(auth()->user()->hasRole('Admin'))
                <div class="trainer-filter-wrapper d-flex align-items-center" style="gap: 0.5rem; flex: 1; max-width: 450px; min-width: 250px;">
                    <div style="flex: 1;">
                        <select id="trainer-filter-select" class="form-control select2" multiple="multiple" data-placeholder="Filter Pelatih...">
                            @foreach($employees as $id => $name)
                                <option value="{{ $id }}" {{ in_array($id, $requestedTrainers) ? 'selected' : '' }}>{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="button" id="clear-trainer-filter" class="btn btn-outline-danger btn-sm" style="display: none; white-space: nowrap; height: 38px;" title="Reset Filter">
                        <i class="fas fa-times"></i> Reset
                    </button>
                </div>
            @else
                <div></div> 
            @endif

            <div class="calendar-actions"> 
                @can('appointment_create')
                    <a href="{{ route('admin.appointments.create', ['date' => $current->format('Y-m-d')]) }}"
                        class="btn btn-success"> 
                        <i class="fas fa-plus"></i> Tambah Jadwal
                    </a>
                
                    @if($appointments->isNotEmpty())
                        <button type="button" class="btn btn-warning text-white" style="color: #ffffff !important;" data-toggle="modal" data-target="#duplicateModal"> 
                            <i class="fas fa-copy"></i> Duplikasi Jadwal
                        </button>
                    @endif
                @endcan
                
                @can('appointment_delete')
                    <button id="activate-delete-mode" class="btn btn-danger">
                        <i class="fas fa-trash"></i> Hapus Jadwal
                    </button>
                @endcan
            </div>
        </div>

        {{-- Alert jika hasil filter kosong --}}
        <div id="no-filtered-appointments" class="alert alert-info" style="display: none;">
            <i class="fas fa-info-circle mr-1"></i> Tidak ada jadwal latihan untuk pelatih yang dipilih pada hari ini.
        </div>

        @php
            $groupedAppointments = $appointments->sortBy('start_time')->groupBy(function ($item) {
                return $item->start_time->format('H:i') . ' - ' . $item->finish_time->format('H:i');
            });
        @endphp

        @forelse ($groupedAppointments as $timeSlot => $appointmentsInSlot)
            <div class="session-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div class="session-time">
                            {{ $timeSlot }}
                        </div>
        
                        @can('appointment_delete')
                            <div>
                                <button class="btn btn-sm btn-outline-danger select-timeslot" data-timeslot="{{ $timeSlot }}">
                                    Pilih semua slot ini
                                </button>
                            </div>
                        @endcan
                    </div>
        
                    @foreach ($appointmentsInSlot as $appointment)
                        @if (!$loop->first)
                            <div class="session-divider"></div>
                        @endif
        
                        <div class="appointment-card justify-content-between align-items-center flex-wrap p-2"
                             data-appointment-id="{{ $appointment->id }}"
                             data-employee-id="{{ $appointment->employee_id }}"
                             data-timeslot="{{ $timeSlot }}">
        
                            @can('appointment_delete')
                                <input type="checkbox"
                                       class="select-checkbox"
                                       data-id="{{ $appointment->id }}"
                                       aria-label="Pilih jadwal {{ $appointment->id }}" />
                            @endcan
        
                            <div class="flex-grow-1" style="padding-right: 15px;">
                                <strong style="font-size: 1rem; color: #2c3e50;">
                                    {{ optional($appointment->employee)->user->name ?? '-' }}
                                </strong><br>
                                <div style="margin-bottom: 2px;">
                                    <strong style="font-size: 0.8rem; color: #2c3e50;">
                                        <i class="fas fa-map-marker-alt text-danger"></i> {{ $appointment->location_name }}
                                    </strong>
                                    @if($appointment->locationRelation && $appointment->locationRelation->URL)
                                        <a href="{{ $appointment->locationRelation->URL }}" target="_blank" class="btn btn-sm btn-outline-info" style="padding: 0 0.3rem; font-size: 0.7rem; margin-left: 4px; border-radius: 4px;">
                                            Buka Peta
                                        </a>
                                    @endif
                                </div>
                                
                                <span class="text-muted" style="font-size: 0.9rem;">
                                    {{ $appointment->services->pluck('category')->unique()->join(', ') }}
                                </span>
                                
                                @if($isAdmin && $appointment->client)
                                    <div style="margin-top: 5px;">
                                        <span class="badge" style="background-color: #e3f2fd; color: #0c5460; border: 1px solid #b8daff; font-weight: 600;">
                                            <i class="fas fa-user"></i> Murid: {{ $appointment->client->name }}
                                        </span>
                                    </div>
                                @endif
                            </div>
        
                            <div class="ms-2 d-flex align-items-center gap-2 mt-2 mt-md-0">
                                @if ($client)
                                    @php
                                        $isOwnedByClient = $appointment->client_id == $client->id; 
                                        $now = \Carbon\Carbon::now();
                                        $start = $appointment->start_time;
                                        $finish = $appointment->finish_time;
                                        $hasStarted = $now->gte($start);
                                        $lessThan12Hours = $now->lt($start) && $now->diffInHours($start) < 12;
                                        $pastFinish = $now->gt($finish);
                                        $canShowCancel = $isOwnedByClient && !$pastFinish && !$lessThan12Hours && !$hasStarted;
                                    @endphp
        
                                    @if ($isOwnedByClient)
                                        <a href="{{ route('admin.appointments.show', $appointment->id) }}" class="btn btn-success mr-2">
                                            Terdaftar
                                        </a>
        
                                        @if ($canShowCancel)
                                            @if($isSystemClosed && $isMurid)
                                                <button type="button" class="btn btn-secondary" disabled>Tutup</button>
                                            @else
                                                <form method="POST" action="{{ route('admin.appointments.leave', $appointment->id) }}"
                                                      class="leave-form d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" class="btn btn-danger trigger-leave">Batalkan Jadwal</button>
                                                </form>
                                            @endif
                                        @endif
        
                                    @elseif ($appointment->client_id)
                                        <button class="btn btn-booked mt-0">Terisi</button>
        
                                    @else
                                        @if ($hasStarted || $lessThan12Hours)
                                            <button class="btn btn-secondary" disabled>-</button>
                                        @elseif ($isSystemClosed && $isMurid)
                                            <button type="button" class="btn btn-secondary" disabled>Tutup</button>
                                        @else
                                            <form method="POST" action="{{ route('admin.appointments.join', $appointment->id) }}" class="join-form d-inline">
                                                @csrf
                                                <button type="button" class="btn btn-daftar trigger-confirm">Daftar</button>
                                            </form>
                                        @endif
                                    @endif
                                @else
                                    {{-- Admin / Pelatih view --}}
                                    @php
                                        $now = \Carbon\Carbon::now();
                                        $start = $appointment->start_time;
                                        $finish = $appointment->finish_time;
                                        $lessThan12Hours = $now->lt($start) && $now->diffInHours($start) < 12;
                                        $pastFinish = $now->gt($finish);
        
                                        if ($appointment->client_id) {
                                            $btnClass = 'btn-booked-accessible';
                                        } elseif ($lessThan12Hours || $pastFinish) {
                                            $btnClass = 'btn-secondary';
                                        } else {
                                            $btnClass = 'btn-success';
                                        }
                                    @endphp
        
                                    <a href="{{ route('admin.appointments.show', $appointment->id) }}"
                                       class="btn {{ $btnClass }}">
                                        Detail
                                    </a>
                                @endif
                            </div>
                        </div> 
                    @endforeach
                </div>
            </div>
        @empty
            <div class="alert alert-info">Tidak ada sesi latihan pada hari ini.</div>
        @endforelse
    </div>
@endsection

@section('scripts')
    
    {{-- Modal Konfirmasi Pendaftaran --}}
    <div class="modal fade" id="confirmJoinModal" tabindex="-1" aria-labelledby="confirmJoinModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmJoinModalLabel">Konfirmasi Pendaftaran</h5>
                </div>
                <div class="modal-body">
                    <p class="mb-2">
                        Yakin ingin daftar ke sesi ini? Kuota akan berkurang.
                    </p>
                
                    @if($client)
                        <small class="d-block mb-2">
                            Sisa Kuota Anda: <strong>{{ $client->kuota ?? '0' }}</strong><br>
                            Masa Berlaku: <strong>{{ $client->kuota_valid_until ? \Carbon\Carbon::parse($client->kuota_valid_until)->translatedFormat('d F Y') : 'Tanpa Batas Waktu' }}</strong>
                        </small>
                    @endif
                
                    <small class="text-muted" style="font-size: 0.8rem; line-height: 1.4;">
                        • Jadwal latihan tidak dapat dibatalkan dalam waktu kurang dari 12 jam sebelum sesi latihan dimulai.<br>
                        • Jika berhalangan hadir karena kondisi darurat, silakan hubungi admin melalui WhatsApp maksimal 1 jam sebelum sesi dimulai agar kuota dapat dikembalikan.<br>
                        • Tidak hadir tanpa konfirmasi maka kuota akan dianggap hangus.
                    </small>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" id="confirmJoinBtn">Konfirmasi</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Konfirmasi Batal --}}
    <div class="modal fade" id="confirmLeaveModal" tabindex="-1" aria-labelledby="confirmLeaveModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmLeaveModalLabel">Konfirmasi Pembatalan</h5>
                </div>
                <div class="modal-body">
                    Yakin ingin membatalkan jadwal latihan ini?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" id="confirmLeaveBtn">Konfirmasi</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Error Pembatalan --}}
    <div class="modal fade" id="errorLeaveModal" tabindex="-1" aria-labelledby="errorLeaveModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content bg-danger text-white">
                <div class="modal-header">
                    <h5 class="modal-title" id="errorLeaveModalLabel">Pembatalan Gagal</h5>
                </div>
                <div class="modal-body">
                    {{ session('errors') ? session('errors')->first('cancel') : '' }}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Error Pendaftaran (Join) - BARU --}}
    <div class="modal fade" id="errorJoinModal" tabindex="-1" aria-labelledby="errorJoinModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content bg-danger text-white">
                <div class="modal-header border-0">
                    <h5 class="modal-title" id="errorJoinModalLabel">Pendaftaran Gagal</h5>
                    <button type="button" class="close text-white" data-bs-dismiss="modal" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <i class="fas fa-exclamation-triangle mr-2"></i>
                    {{ session('errors') && session('errors')->has('join') ? session('errors')->first('join') : '' }}
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL DUPLIKASI 1: Pilih Tanggal Tujuan Duplikasi --}}
    <div class="modal fade" id="duplicateModal" tabindex="-1" role="dialog" aria-labelledby="duplicateModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="duplicateModalLabel">Duplikasi Jadwal</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>
                        Duplikasi <strong>{{ $appointments->count() }}</strong> jadwal pada tanggal
                        <strong>{{ $current->translatedFormat('d M Y') }}</strong> ke tanggal:
                    </p>
                    <div class="form-group">
                        <input type="date" id="destination_date_input" class="form-control"
                            min="{{ $current->copy()->addDay()->format('Y-m-d') }}">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" id="continueDuplicateBtn">Lanjut</button>
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL DUPLIKASI 2: Konfirmasi Akhir --}}
    <div class="modal fade" id="confirmDuplicateModal" tabindex="-1" role="dialog"
        aria-labelledby="confirmDuplicateModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmDuplicateModalLabel">Konfirmasi Duplikasi</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="duplicateForm" method="POST" action="{{ route('admin.appointments.duplicate') }}">
                    @csrf
                    <input type="hidden" name="source_date" value="{{ $current->format('Y-m-d') }}">
                    <input type="hidden" name="destination_date" id="destination_date_hidden">

                    <div class="modal-body">
                        <p>Anda yakin ingin menduplikasi <strong>{{ $appointments->count() }}</strong> jadwal latihan dari
                            tanggal:</p>
                        <p class="font-weight-bold">{{ $current->translatedFormat('l, d M Y') }}</p>
                        <p>ke tanggal:</p>
                        <p class="font-weight-bold" id="confirmDestinationDateText"></p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-danger">Ya, Duplikasi Sekarang</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    

   @can('appointment_delete')
     <div id="delete-action-bar">
        <div>
          <span class="count" id="selected-count">0</span> Jadwal dipilih
        </div>
        <div>
          <button id="cancel-delete-mode" class="btn btn-secondary mr-2">Batal</button>
          <button id="confirm-delete-selected" class="btn btn-danger">Hapus Sekarang</button>
        </div>
     </div>
   @endcan

    {{-- Script Modal (Join, Leave, Error) --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            let selectedForm = null;
            document.querySelectorAll('.trigger-confirm').forEach(btn => {
                btn.addEventListener('click', function () {
                    selectedForm = this.closest('form');
                    const modal = new bootstrap.Modal(document.getElementById('confirmJoinModal'));
                    modal.show();
                });
            });
            document.getElementById('confirmJoinBtn').addEventListener('click', function () {
                if (selectedForm) selectedForm.submit();
            });
        });

        let selectedLeaveForm = null;
        document.querySelectorAll('.trigger-leave').forEach(btn => {
            btn.addEventListener('click', function () {
                selectedLeaveForm = this.closest('form');
                const modal = new bootstrap.Modal(document.getElementById('confirmLeaveModal'));
                modal.show();
            });
        });
        document.getElementById('confirmLeaveBtn').addEventListener('click', function () {
            if (selectedLeaveForm) selectedLeaveForm.submit();
        });

        document.addEventListener('DOMContentLoaded', function () {
            @if(session('errors') && session('errors')->has('cancel'))
                const errorModal = new bootstrap.Modal(document.getElementById('errorLeaveModal'));
                errorModal.show();
            @endif

            @if(session('errors') && session('errors')->has('join'))
                const errorJoinModal = new bootstrap.Modal(document.getElementById('errorJoinModal'));
                errorJoinModal.show();
            @endif
        });

        $(document).ready(function () {
            $('#continueDuplicateBtn').on('click', function () {
                var destinationDate = $('#destination_date_input').val();
                if (!destinationDate) {
                    alert('Silakan pilih tanggal tujuan terlebih dahulu.');
                    return;
                }
                var dateObj = new Date(destinationDate + 'T00:00:00'); 
                var options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
                var formattedDate = dateObj.toLocaleDateString('id-ID', options);
                $('#destination_date_hidden').val(destinationDate);
                $('#confirmDestinationDateText').text(formattedDate);
                $('#duplicateModal').modal('hide');
                $('#confirmDuplicateModal').modal('show');
            });
        });
    </script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function () {
          let deleteMode = false;
          let selectedIds = new Set();
        
          const btnActivate = document.getElementById('activate-delete-mode');
          const btnCancel = document.getElementById('cancel-delete-mode');
          const cbSelectAllToday = document.getElementById('select-all-today-cb'); 
          const actionBar = document.getElementById('delete-action-bar');
          const selectedCountEl = document.getElementById('selected-count');
          const btnConfirmDelete = document.getElementById('confirm-delete-selected');

          function enterDeleteMode() {
            deleteMode = true;
            document.body.classList.add('delete-mode');
            document.querySelectorAll('.select-checkbox').forEach(cb => cb.checked = false);
            selectedIds.clear();
            updateActionBar();
          }

          function exitDeleteMode() {
            deleteMode = false;
            document.body.classList.remove('delete-mode');
            document.querySelectorAll('.select-checkbox').forEach(cb => cb.checked = false);
            selectedIds.clear();
            if (cbSelectAllToday) cbSelectAllToday.checked = false; 
            document.querySelectorAll('.appointment-card.selected').forEach(el => el.classList.remove('selected'));
            updateActionBar();
          }
          
          function updateActionBar() {
            if (!actionBar) return; 

            selectedCountEl.textContent = selectedIds.size;
            
            actionBar.style.display = deleteMode ? 'flex' : 'none'; 
            
            btnConfirmDelete.disabled = (selectedIds.size == 0);

            if (cbSelectAllToday) {
                const totalCheckboxes = document.querySelectorAll('.appointment-card:not(.filter-hidden) .select-checkbox').length;
                cbSelectAllToday.checked = (totalCheckboxes > 0 && selectedIds.size == totalCheckboxes);
            }
          }
        
          if (btnActivate) btnActivate.addEventListener('click', enterDeleteMode);
        
          if (btnCancel) btnCancel.addEventListener('click', exitDeleteMode);
        
          document.querySelectorAll('.select-checkbox').forEach(cb => {
            cb.addEventListener('change', function (e) {
              const id = this.dataset.id;
              const card = this.closest('.appointment-card');
              if (this.checked) {
                selectedIds.add(id);
                card.classList.add('selected');
              } else {
                selectedIds.delete(id);
                card.classList.remove('selected');
              }
              updateActionBar(); 
            });
          });
        
          document.querySelectorAll('.appointment-card').forEach(card => {
            card.addEventListener('click', function (e) {
              if (!deleteMode) return; 
              if (e.target.closest('a') || e.target.closest('button') || e.target.closest('form') || e.target.closest('.select-checkbox')) return;
              
              const cb = this.querySelector('.select-checkbox');
              cb.checked = !cb.checked;
              cb.dispatchEvent(new Event('change', { bubbles: true }));
            });
          });
        
          document.querySelectorAll('.select-timeslot').forEach(btn => {
            btn.addEventListener('click', function (e) {
              e.stopPropagation();
              const ts = this.dataset.timeslot;
              document.querySelectorAll('.appointment-card[data-timeslot="'+ts+'"]:not(.filter-hidden)').forEach(card => {
                const cb = card.querySelector('.select-checkbox');
                if (cb.checked != true) { cb.checked = true; cb.dispatchEvent(new Event('change')); }
              });
            });
          });
          
          if (cbSelectAllToday) {
              cbSelectAllToday.addEventListener('click', function() {
                  const allCheckboxes = document.querySelectorAll('.appointment-card:not(.filter-hidden) .select-checkbox');
                  const isChecked = this.checked; 
                  
                  allCheckboxes.forEach(cb => {
                      if (cb.checked != isChecked) { 
                          cb.checked = isChecked;
                          cb.dispatchEvent(new Event('change', { bubbles: true }));
                      }
                  });
              });
          }
        
          if (btnConfirmDelete) {
            btnConfirmDelete.addEventListener('click', function () {
              if (selectedIds.size == 0) return alert('Pilih minimal 1 jadwal.');
              if (!confirm('Yakin mau menghapus ' + selectedIds.size + ' jadwal?')) return;
          
              const idsArray = Array.from(selectedIds);
              fetch("{{ route('admin.appointments.massDestroy') }}", {
                method: 'POST',
                headers: {
                  'Content-Type': 'application/json',
                  'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ ids: idsArray, _method: 'DELETE' })
              })
              .then(r => {
                if (!r.ok) throw new Error('Server error');
                return r;
              })
              .then(() => {
                idsArray.forEach(id => {
                  const card = document.querySelector('.appointment-card[data-appointment-id="'+id+'"]');
                  if (card) {
                      const sessionCard = card.closest('.session-card');
                      card.remove(); 
                      
                      if (sessionCard) {
                          const remainingAppointments = sessionCard.querySelectorAll('.appointment-card');
                          if (remainingAppointments.length == 0) {
                              sessionCard.remove();
                          }
                      }
                  }
                });
                exitDeleteMode();
              })
              .catch(err => {
                console.error(err);
                alert('Gagal menghapus. Coba lagi.');
              });
            });
          }

          updateActionBar();
        });
    </script>

    {{-- Script Filter Pelatih untuk Admin --}}
    <script>
        $(document).ready(function () {
            const $trainerFilter = $('#trainer-filter-select');
            const $clearBtn = $('#clear-trainer-filter');
            const $noFilteredAlert = $('#no-filtered-appointments');

            if ($trainerFilter.length) {
                // Inisialisasi / konfigurasi Select2
                $trainerFilter.select2({
                    placeholder: "Filter Pelatih...",
                    allowClear: true,
                    width: '100%'
                });

                function applyTrainerFilter() {
                    // Ambil array ID pelatih yang dipilih (dipastikan string bersih)
                    const selectedEmployees = ($trainerFilter.val() || []).map(function(val) {
                        return String(val).trim();
                    }).filter(Boolean);

                    let totalVisible = 0;

                    if (selectedEmployees.length > 0) {
                        $clearBtn.show();
                    } else {
                        $clearBtn.hide();
                    }

                    $('.session-card').each(function () {
                        const $sessionCard = $(this);
                        let visibleInSlot = 0;

                        $sessionCard.find('.appointment-card').each(function () {
                            const $card = $(this);
                            const empId = String($card.attr('data-employee-id') || $card.data('employee-id') || '').trim();

                            // LOGIKA OR:
                            // Jika filter kosong: Tampilkan semua.
                            // Jika ada filter: Tampilkan HANYA jika empId kartu ini ADA dalam daftar pelatih terpilih (Pelatih A ATAU Pelatih B).
                            const isMatch = (selectedEmployees.length === 0) || (selectedEmployees.indexOf(empId) !== -1);

                            if (isMatch) {
                                $card.removeClass('filter-hidden');
                                visibleInSlot++;
                                totalVisible++;
                            } else {
                                $card.addClass('filter-hidden');
                                // Lepas centang jika kartu disembunyikan saat sedang tercentang
                                const cb = $card.find('.select-checkbox')[0];
                                if (cb && cb.checked) {
                                    cb.checked = false;
                                    cb.dispatchEvent(new Event('change', { bubbles: true }));
                                }
                            }
                        });

                        // Sembunyikan blok sesi jam latihan jika tidak ada satupun pelatih yang cocok di jam tersebut
                        if (visibleInSlot > 0) {
                            $sessionCard.removeClass('filter-hidden');
                        } else {
                            $sessionCard.addClass('filter-hidden');
                        }
                    });

                    const totalCards = $('.appointment-card').length;
                    if (totalCards > 0 && totalVisible === 0) {
                        $noFilteredAlert.removeClass('filter-hidden').show();
                    } else {
                        $noFilteredAlert.addClass('filter-hidden').hide();
                    }

                    // Sinkronisasi query param di URL tanpa reload halaman
                    const url = new URL(window.location);
                    if (selectedEmployees.length > 0) {
                        url.searchParams.set('trainers', selectedEmployees.join(','));
                    } else {
                        url.searchParams.delete('trainers');
                        url.searchParams.delete('employees');
                    }
                    window.history.replaceState({}, '', url);

                    // Perbarui link navigasi tanggal agar pilihan pelatih tetap terjaga saat berpindah hari
                    $('.date-nav-card').closest('a').each(function () {
                        const href = $(this).attr('href');
                        if (href) {
                            const linkUrl = new URL(href, window.location.origin);
                            if (selectedEmployees.length > 0) {
                                linkUrl.searchParams.set('trainers', selectedEmployees.join(','));
                            } else {
                                linkUrl.searchParams.delete('trainers');
                                linkUrl.searchParams.delete('employees');
                            }
                            $(this).attr('href', linkUrl.toString());
                        }
                    });
                }

                $trainerFilter.on('change', function () {
                    applyTrainerFilter();
                });

                $clearBtn.on('click', function () {
                    $trainerFilter.val(null).trigger('change');
                });

                // Terapkan filter saat pertama kali halaman dimuat jika ada nilai awal
                const urlParams = new URLSearchParams(window.location.search);
                const trainersParam = urlParams.get('trainers') || urlParams.get('employees');
                if (trainersParam) {
                    const initialTrainers = trainersParam.split(',').map(s => s.trim()).filter(Boolean);
                    if (initialTrainers.length > 0) {
                        $trainerFilter.val(initialTrainers).trigger('change');
                    }
                } else if ($trainerFilter.val() && $trainerFilter.val().length > 0) {
                    applyTrainerFilter();
                }
            }
        });
    </script>
@endsection