@extends('layouts.admin')

@section('styles')
    <style>
        .report-timeline {
            position: relative;
            padding-left: 50px;
            /* Ruang untuk garis timeline dan ikon */
        }

        .report-card {
            background-color: #fff;
            border: 1px solid #eef2f7;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 25px;
            position: relative;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            transition: transform 0.2s ease;
        }

        .report-card:hover {
            transform: translateY(-5px);
        }

        /* Garis vertikal timeline */
        .report-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: -30px;
            height: 100%;
            width: 2px;
            background: #007bff;
            opacity: 0.3;
        }

        /* Ikon di timeline */
        .report-card::after {
            content: '🏊';
            /* Ikon perenang, bisa diganti dengan FontAwesome atau SVG */
            position: absolute;
            top: 20px;
            left: -43px;
            width: 25px;
            height: 25px;
            background-color: #007bff;
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            box-shadow: 0 0 0 4px #eef2f7;
        }

        /* Menghilangkan garis pada item terakhir */
        .report-timeline>div:last-child .report-card::before {
            display: none;
        }

        .report-date {
            font-weight: bold;
            color: #007bff;
            margin-bottom: 10px;
        }

        .report-content {
            color: #555;
            line-height: 1.6;
        }

        .report-content .empty-note {
            font-style: italic;
            color: #777;
        }

        .pagination {
            justify-content: center;
        }
    </style>
@endsection

@section('content')
    <div class="content">
        <div class="row">
            <div class="col-lg-12">
                <h2 class="mb-4">📘 Report Latihanmu</h2>

                <div class="report-timeline">
                    @forelse ($appointments as $appointment)
                        <div>
                            <div class="report-card">
                                <div class="report-date">
                                    {{ $appointment->start_time->locale('id')->translatedFormat('l, d F Y') }}
                                </div>
                                <div class="report-content">
                                    @if ($appointment->comments)
                                        {!! nl2br(e($appointment->comments)) !!}
                                    @else
                                        <p class="empty-note">
                                            Tidak ada catatan khusus dari pelatih hari ini. Hebat! Artinya, semua gerakanmu sudah
                                            bagus. Terus pertahankan semangatmu, ya! ⭐
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="alert alert-info">
                            Kamu belum memiliki report latihan. Ayo terus berlatih dengan giat!
                        </div>
                    @endforelse
                </div>

                <div class="mt-4">
                    {{ $appointments->links() }}
                </div>

            </div>
        </div>
    </div>
@endsection