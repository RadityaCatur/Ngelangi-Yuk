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
            $prev = $current->copy()->subDay();  // satu hari sebelum
            $next = $current->copy()->addDay();  // satu hari sesudah

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

        @forelse ($appointments as $appointment)
            <div class="session-card">
                <div class="card-body">
                    <div class="session-time">
                        {{ $appointment->start_time->format('H:i') }} - {{ $appointment->finish_time->format('H:i') }}
                    </div>

                    <div class="session-divider"></div>

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
                                // batal hanya muncul kalau: milik user, belum mulai, dan masih >= 12 jam
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
                                {{-- Jadwal kosong --}}
                                @if ($hasStarted || $lessThan12Hours)
                                    {{-- sudah mulai ATAU < 12 jam ke start -> tidak bisa daftar --}}
                                        <button class="btn btn-secondary mt-2 mt-md-0" disabled>-</button>
                                @else
                                        <form method="POST" action="{{ route('admin.appointments.join', $appointment->id) }}"
                                            class="join-form">
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
                                        // Sudah terisi → merah
                                        $btnClass = 'btn-booked-accessible';
                                    } elseif ($lessThan12Hours || $pastFinish) {
                                        // Kosong tapi tidak bisa didaftar → abu
                                        $btnClass = 'btn-secondary';
                                    } else {
                                        // Kosong dan bisa didaftar → hijau
                                        $btnClass = 'btn-success';
                                    }
                                @endphp

                                <a href="{{ route('admin.appointments.show', $appointment->id) }}"
                                    class="btn {{ $btnClass }} mt-2 mt-md-0">
                                    Detail
                                </a>
                            @endif
                    </div>
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
    </script>
@endsection