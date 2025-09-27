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

        @media (max-width: 768px) {
            .btn-daftar {
                width: 100%;
                display: block;
                margin-top: 0.5rem;
            }
        }
    </style>
@endsection

@section('content')
    <h3 class="page-title mb-3">Detail Sesi Latihan</h3>

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

        <div class="d-flex justify-content-around text-center mb-3">
            @foreach ($dates as $d)
                <a href="{{ route('admin.systemCalendar.details', ['date' => $d['date']->format('Y-m-d')]) }}"
                    class="text-decoration-none flex-fill mx-1">
                    <div class="card {{ $d['isActive'] ? 'bg-secondary text-white' : 'bg-light text-dark border' }}">
                        <div class="card-body p-2">
                            <div class="fw-bold">{{ $d['label'] }}</div>
                            <div class="small">{{ $d['date']->translatedFormat('d M Y') }}</div>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>

        @can('appointment_create')
            <div class="d-flex justify-content-end mb-3">
                <a href="{{ route('admin.appointments.create', ['date' => $current->format('Y-m-d')]) }}"
                    class="btn btn-success mr-2">
                    <i class="fas fa-plus"></i> Tambah Jadwal
                </a>
                @if($appointments->isNotEmpty())
                    <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#duplicateModal">
                        <i class="fas fa-copy"></i> Duplikasi Jadwal
                    </button>
                @endif
            </div>
        @endcan

        {{-- 1. Lakukan grouping data di sini --}}
        @php
            $groupedAppointments = $appointments->sortBy('start_time')->groupBy(function ($item) {
                return $item->start_time->format('H:i') . ' - ' . $item->finish_time->format('H:i');
            });
        @endphp

        {{-- 2. Loop luar berdasarkan hasil grouping (per rentang waktu) --}}
        @forelse ($groupedAppointments as $timeSlot => $appointmentsInSlot)
            <div class="session-card">
                <div class="card-body">
                    <div class="session-time">
                        {{ $timeSlot }}
                    </div>

                    {{-- Divider pertama, tepat di bawah waktu --}}
                    <div class="session-divider"></div>

                    {{-- 3. Loop dalam untuk setiap jadwal di dalam grup waktu yang sama --}}
                    @foreach ($appointmentsInSlot as $appointment)

                        {{-- Tambahkan divider antar jadwal jika ini BUKAN item pertama --}}
                        @if (!$loop->first)
                            <div class="session-divider"></div>
                        @endif

                        {{-- Di sini kita paste SEMUA KONTEN ASLI dari jadwal individu --}}
                        <div class="d-flex justify-content-between align-items-center flex-wrap">
                            <div>
                                <strong>{{ optional($appointment->employee)->user->name ?? '-' }}</strong><br>
                                <small>
                                    {{ $appointment->services->pluck('category')->unique()->join(', ') }}
                                </small>
                            </div>

                            @if ($client)
                                @php
                                    $isOwnedByClient = $appointment->client_id === $client->id;
                                    $now = \Carbon\Carbon::now();
                                    $start = $appointment->start_time;
                                    $finish = $appointment->finish_time;
                                    $hasStarted = $now->gte($start);
                                    $lessThan12Hours = $now->lt($start) && $now->diffInHours($start) < 12;
                                    $pastFinish = $now->gt($finish);
                                    $canShowCancel = $isOwnedByClient && !$pastFinish && !$lessThan12Hours && !$hasStarted;
                                @endphp

                                @if ($isOwnedByClient)
                                    <div class="d-flex align-items-center gap-2 mt-2 mt-md-0">
                                        <a href="{{ route('admin.appointments.show', $appointment->id) }}" class="btn btn-success mr-2">
                                            Terdaftar
                                        </a>

                                        @if ($canShowCancel)
                                            <form method="POST" action="{{ route('admin.appointments.leave', $appointment->id) }}"
                                                class="leave-form">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="btn btn-danger trigger-leave">Batalkan Jadwal</button>
                                            </form>
                                        @endif
                                    </div>

                                @elseif ($appointment->client_id)
                                    <button class="btn btn-booked mt-2 mt-md-0">Terisi</button>

                                @else
                                    @if ($hasStarted || $lessThan12Hours)
                                        <button class="btn btn-secondary mt-2 mt-md-0" disabled>-</button>
                                    @else
                                        <form method="POST" action="{{ route('admin.appointments.join', $appointment->id) }}" class="join-form">
                                            @csrf
                                            <button type="button" class="btn btn-daftar mt-2 mt-md-0 trigger-confirm">Daftar</button>
                                        </form>
                                    @endif
                                @endif
                            @else
                                {{-- Admin atau Pelatih --}}
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
                                    class="btn {{ $btnClass }} mt-2 mt-md-0">
                                    Detail
                                </a>
                            @endif
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
    {{-- Modal Konfirmasi --}}
    <div class="modal fade" id="confirmJoinModal" tabindex="-1" aria-labelledby="confirmJoinModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmJoinModalLabel">Konfirmasi Pendaftaran</h5>
                </div>
                <div class="modal-body">
                    Yakin ingin daftar ke sesi ini? Kuota akan berkurang.
                    @if($client)
                        <br>
                        <small>Sisa Kuota Anda: <strong>{{ $client->kuota ?? '0' }}</strong></small>
                    @endif
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

    {{-- Script Modal --}}
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
            });

        $(document).ready(function () {
            $('#continueDuplicateBtn').on('click', function () {
                var destinationDate = $('#destination_date_input').val();
                if (!destinationDate) {
                    alert('Silakan pilih tanggal tujuan terlebih dahulu.');
                    return;
                }

                // Format tanggal untuk ditampilkan
                var dateObj = new Date(destinationDate + 'T00:00:00'); // Tambah T00:00 untuk hindari masalah timezone
                var options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
                var formattedDate = dateObj.toLocaleDateString('id-ID', options);

                // Isi data di modal konfirmasi
                $('#destination_date_hidden').val(destinationDate);
                $('#confirmDestinationDateText').text(formattedDate);

                // Pindahkan modal
                $('#duplicateModal').modal('hide');
                $('#confirmDuplicateModal').modal('show');
            });
        });
    </script>
@endsection