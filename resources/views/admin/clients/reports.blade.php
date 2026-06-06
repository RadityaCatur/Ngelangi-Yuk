@extends('layouts.admin')

@section('styles')
<style>
    /* Styling Header Halaman */
    .report-header-container {
        border-bottom: 2px solid #f1f3f5;
        padding-bottom: 12px;
        margin-bottom: 25px;
    }
    .report-header-title {
        font-weight: 800 !important;
        font-size: 1.5rem;
        color: #2c3e50;
    }

    /* Timeline Wrapper */
    .report-timeline {
        position: relative;
        padding-left: 45px;
        margin-top: 10px;
    }

    /* Garis vertikal timeline dengan warna gradasi air */
    .report-timeline::before {
        content: '';
        position: absolute;
        top: 15px;
        left: 19px;
        bottom: 15px;
        width: 3px;
        background: linear-gradient(to bottom, #019db2, #74c2ce, #e3f2fd);
        border-radius: 2px;
    }

    /* Wrapper per baris */
    .timeline-item {
        position: relative;
        margin-bottom: 30px;
    }

    /* Card Rapor bergaya Bubble/Modern */
    .report-card {
        background-color: #fff;
        border: 2px solid #eef2f7;
        border-radius: 16px;
        padding: 22px;
        position: relative;
        box-shadow: 0 6px 18px rgba(1, 157, 178, 0.04);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .report-card:hover {
        transform: translateY(-4px);
        border-color: #74c2ce;
        box-shadow: 0 10px 25px rgba(1, 157, 178, 0.08);
    }

    /* Ikon Karakter Lumba-Lumba / Berenang Mengapung */
    .timeline-icon {
        position: absolute;
        top: 15px;
        left: -41px;
        width: 30px;
        height: 30px;
        background: linear-gradient(135deg, #019db2, #74c2ce);
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 15px;
        z-index: 2;
        box-shadow: 0 0 0 5px #fff, 0 4px 10px rgba(1, 157, 178, 0.2);
    }

    /* Typo & Warna Elemen */
    .report-date {
        font-weight: 800;
        color: #019db2;
        font-size: 1.1rem;
        margin-bottom: 6px;
    }

    .report-trainer {
        color: #4a5568;
        font-size: 0.95rem;
        font-weight: 600;
        margin-bottom: 12px;
        background: #f7fafc;
        display: inline-block;
        padding: 4px 12px;
        border-radius: 20px;
    }

    .report-trainer i {
        color: #019db2;
        margin-right: 6px;
    }

    .report-content {
        color: #4a5568;
        line-height: 1.7;
        font-size: 0.98rem;
    }

    /* Box Catatan Kosong Apresiatif */
    .report-content .empty-note {
        background-color: rgba(40, 167, 69, 0.04);
        border: 1px dashed #28a745;
        padding: 15px;
        border-radius: 12px;
        color: #155724;
        font-weight: 500;
    }

    #loading-spinner {
        text-align: center;
        padding: 20px;
        display: none;
        color: #019db2;
        font-weight: bold;
    }

    #load-more-container {
        display: none;
    }

    @media (max-width: 768px) {
        .report-header-title {
            font-size: 1.25rem;
        }
        .report-timeline {
            padding-left: 35px;
        }
        .report-timeline::before {
            left: 14px;
        }
        .timeline-icon {
            left: -31px;
            width: 26px;
            height: 26px;
            font-size: 13px;
        }
    }
</style>
@endsection

@section('content')
<div class="content">
    <div class="row">
        <div class="col-lg-12">
            
            <div class="d-flex align-items-center report-header-container">
                <a href="{{ route('admin.systemCalendar') }}" class="text-decoration-none mr-3" style="color: #019db2; font-size: 1.25rem;">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <h2 class="report-header-title mb-0">📘 Report Latihanmu</h2>
            </div>

            <div id="report-timeline" class="report-timeline">
                @forelse ($appointments as $appointment)
                    <div class="timeline-item">
                        <div class="timeline-icon">🏊</div>
                        
                        <div class="report-card">
                            <div class="report-date">
                                {{ $appointment->start_time->locale('id')->translatedFormat('l, d F Y') }}
                            </div>
                            <div class="report-trainer">
                                <i class="fas fa-id-badge"></i>
                                Pelatih: <strong>{{ $appointment->employee->name ?? 'Pelatih tidak diketahui' }}</strong>
                            </div>
                            <div class="report-content">
                                @if ($appointment->comments)
                                    <div class="p-2" style="background: #fff; border-radius: 8px;">
                                        {!! nl2br(e($appointment->comments)) !!}
                                    </div>
                                @else
                                    <div class="empty-note">
                                        🚀 Tidak ada catatan khusus dari pelatih hari ini. Hebat! Artinya, semua gerakanmu sudah
                                        bagus banget. Terus pertahankan semangat belajarmu ya, kamu keren! ⭐
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="alert alert-info border-0 p-4 shadow-sm" style="border-radius: 12px; background-color: #e0f7fa; color: #019db2;">
                        <i class="fas fa-info-circle mr-2"></i> Kamu belum memiliki report latihan nih. Ayo terus berlatih dengan giat dan ceria!
                    </div>
                @endforelse
            </div>

            <div id="load-more-container">
                {{ $appointments->links() }}
            </div>

            <div id="loading-spinner">
                <i class="fas fa-spinner fa-spin mr-2"></i> Lagi ngambil data rapor baru...
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    let loading = false;
    let nextPageUrl = document.querySelector('#load-more-container a[rel="next"]')?.getAttribute('href');

    window.addEventListener('scroll', async function() {
        if (loading || !nextPageUrl) return;

        const scrollTop = window.scrollY;
        const windowHeight = window.innerHeight;
        const docHeight = document.documentElement.scrollHeight;

        if (scrollTop + windowHeight >= docHeight - (windowHeight * 0.1)) {
            loading = true;
            document.getElementById('loading-spinner').style.display = 'block';

            try {
                const response = await fetch(nextPageUrl, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                });
                const data = await response.text();
                const parser = new DOMParser();
                const html = parser.parseFromString(data, 'text/html');

                const newReports = html.querySelectorAll('#report-timeline .timeline-item');
                newReports.forEach(el => {
                    document.getElementById('report-timeline').appendChild(el);
                });

                const newNext = html.querySelector('#load-more-container a[rel="next"]');
                nextPageUrl = newNext ? newNext.getAttribute('href') : null;

                if (!nextPageUrl) {
                    document.getElementById('loading-spinner').innerHTML = '✨ Hore! Semua report latihanmu sudah ditunjukkan.';
                } else {
                    document.getElementById('loading-spinner').style.display = 'none';
                }
            } catch (err) {
                console.error(err);
                document.getElementById('loading-spinner').innerHTML = '⚠️ Waduh, gagal mengambil data. Coba cek internetmu ya.';
            } finally {
                loading = false;
            }
        }
    });
});
</script>
@endsection