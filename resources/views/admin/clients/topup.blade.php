@extends('layouts.admin')

@section('styles')
<style>
    /* Styling Header Card */
    .card {
        border: none !important;
        border-radius: 16px !important;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05) !important;
        overflow: hidden;
    }
    .card-header {
        background: linear-gradient(135deg, #019db2, #74c2ce) !important;
        color: white !important;
        font-weight: 800 !important;
        font-size: 1.3rem;
        padding: 20px 25px !important;
        border: none !important;
    }

    /* Info Badge Widget */
    .info-box-widget {
        background-color: #f8f9fa;
        border-radius: 12px;
        padding: 15px 20px;
        border-left: 5px solid #019db2;
        height: 100%;
    }
    .info-box-widget label {
        font-size: 0.85rem;
        color: #6c757d;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 5px;
    }
    .info-box-widget .value {
        font-size: 1.15rem;
        font-weight: bold;
        color: #2c3e50;
    }

    /* -----------------------------------------
       STYLING CARDS KUOTA / PRICING CARDS (WOW)
       ----------------------------------------- */
    .pricing-container {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 20px;
        margin-top: 15px;
    }
    .quota-card {
        background: #fff;
        border: 2px solid #e9ecef;
        border-radius: 16px;
        padding: 25px 20px;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
    }
    .quota-card:hover {
        transform: translateY(-5px);
        border-color: #74c2ce;
        box-shadow: 0 12px 20px rgba(1, 157, 178, 0.08);
    }
    .quota-card.selected {
        border-color: #019db2;
        background-color: rgba(1, 157, 178, 0.02);
        box-shadow: 0 12px 24px rgba(1, 157, 178, 0.15);
    }
    /* Badge Checkmark untuk kartu terpilih */
    .quota-card.selected::before {
        content: "\f058";
        font-family: "Font Awesome 5 Free";
        font-weight: 900;
        position: absolute;
        top: 12px;
        right: 15px;
        color: #019db2;
        font-size: 1.2rem;
    }
    .quota-title {
        font-size: 1.4rem;
        font-weight: 800;
        color: #2c3e50;
        margin-bottom: 10px;
    }
    .quota-price {
        font-size: 1.25rem;
        color: #019db2;
        font-weight: 700;
    }
    .quota-unit-price {
        font-size: 0.8rem;
        color: #6c757d;
        display: block;
        margin-top: 5px;
    }

    /* Final Price Section */
    #harga_final_container {
        background: #fff;
        border: 2px dashed #019db2;
        border-radius: 12px;
        padding: 20px;
        margin-top: 30px;
    }

    /* Floating WhatsApp Button Pulse Animation */
    .btn-wa-floating {
        position: fixed;
        bottom: 30px;
        right: 30px;
        border-radius: 50px !important;
        padding: 15px 25px !important;
        font-weight: bold;
        font-size: 1rem;
        box-shadow: 0 8px 25px rgba(40, 167, 69, 0.3) !important;
        z-index: 999;
        transition: all 0.3s;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .btn-wa-floating:hover {
        transform: scale(1.05) translateY(-3px);
        box-shadow: 0 12px 30px rgba(40, 167, 69, 0.4) !important;
    }
</style>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <i class="fas fa-wallet mr-2"></i> Top Up Kuota Latihan
        </div>

        <div class="card-body p-4">
            <h5 class="mb-3 text-muted" style="font-weight: 700;"><i class="fas fa-id-card mr-2 text-info"></i> Informasi Akun Murid</h5>
            <div class="row mb-4">
                <div class="col-md-4 mb-3">
                    <div class="info-box-widget">
                        <label>Nama Pengguna</label>
                        <div class="value">{{ $client->user->name }} ({{ $client->user->username }})</div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="info-box-widget" style="border-left-color: #28a745;">
                        <label>Paket / Kelas Aktif</label>
                        <div class="value text-success" id="default_paket_text">
                            {{ optional($client->services->first())->name ?? 'Kelas Anak Anak' }}
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="info-box-widget" style="border-left-color: #ffc107;">
                        <label>Sisa Kuota & Masa Berlaku</label>
                        <div class="value">
                            {{ $client->kuota }} Sesi 
                            <span style="font-size: 0.85rem; font-weight: normal;" class="ml-1 {{ $client->kuota_valid_until && \Carbon\Carbon::parse($client->kuota_valid_until)->isPast() ? 'text-danger font-weight-bold' : 'text-muted' }}">
                                ({{ $client->kuota_valid_until ? \Carbon\Carbon::parse($client->kuota_valid_until)->translatedFormat('d M Y') : 'Aktif Selamanya' }})
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <hr style="border-top: 2px solid #f1f3f5; margin: 30px 0;">

            <h5 class="mb-2 style-title" style="font-weight: 700; color: #2c3e50;">
                <i class="fas fa-cubes mr-2" style="color: #019db2;"></i> Pilih Jumlah Sesi Kuota yang Diinginkan
            </h5>
            <p class="text-muted small mb-3">Sistem otomatis menampilkan harga yang sesuai dengan Paket Aktif akun kamu.</p>

            <div class="pricing-container" id="pricingCardsWrapper"></div>

            <div id="harga_final_container" class="d-none text-center animate__animated animate__fadeIn">
                <p class="text-muted mb-1" style="text-transform: uppercase; font-size: 0.85rem; letter-spacing: 0.5px;">Total Biaya Pembayaran</p>
                <h2 class="font-weight-bold mb-3" style="color: #2c3e50;">Rp. <span id="hargaText">0</span></h2>
                <div class="alert alert-warning d-inline-block px-4 py-2 small mb-0" style="border-radius: 30px; color: #856404; background-color: #fff3cd; border: 1px solid #ffeeba;">
                    <i class="fas fa-info-circle mr-1"></i> Klik tombol WhatsApp di bawah untuk mengirim invoice otomatis dan konfirmasi ke Admin.
                </div>
            </div>

            <a href="#" target="_blank" id="wa_button" class="btn btn-success btn-wa-floating d-none">
                <i class="fab fa-whatsapp" style="font-size: 20px;"></i> Konfirmasi via WhatsApp
            </a>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        // Database harga internal sistem
        const hargaMap = {
            'Kelas Anak Anak': {
                '1 Murid 1 Pelatih': { 1: 70000, 4: 255000, 8: 490000 }
            },
            'Kelas Dewasa': {
                '1 Murid 1 Pelatih': { 1: 75000, 4: 280000, 8: 540000 }
            },
            'Kelas Terapi': {
                4: 400000, 8: 750000
            }
        };

        document.addEventListener('DOMContentLoaded', function () {
            // Read default value kelas langsung dari text widget di atas
            let userPaket = document.getElementById('default_paket_text').innerText.trim();
            
            // Fallback safety filter jika relasi kosong / salah ketik
            if (!hargaMap[userPaket]) {
                userPaket = 'Kelas Anak Anak'; 
            }

            const cardsWrapper = document.getElementById('pricingCardsWrapper');
            cardsWrapper.innerHTML = ''; // Clear container

            // Ambil data kuota yang tersedia berdasarkan paket aktif murid
            let availableQuotas = [];
            let defaultPerson = '1 Murid 1 Pelatih';

            if (userPaket === 'Kelas Terapi') {
                availableQuotas = Object.keys(hargaMap[userPaket]);
            } else {
                availableQuotas = Object.keys(hargaMap[userPaket][defaultPerson]);
            }

            // Render Kartu Pilihan secara dinamis
            availableQuotas.forEach(quota => {
                let currentHarga = (userPaket === 'Kelas Terapi') 
                    ? hargaMap[userPaket][quota] 
                    : hargaMap[userPaket][defaultPerson][quota];
                
                let perSesiPrice = Math.round(currentHarga / quota);

                cardsWrapper.innerHTML += `
                    <div class="quota-card" data-quota="${quota}" data-price="${currentHarga}">
                        <div class="quota-title">${quota} Sesi</div>
                        <div class="quota-price">Rp. ${currentHarga.toLocaleString('id-ID')}</div>
                        <span class="quota-unit-price">(Rp. ${perSesiPrice.toLocaleString('id-ID')} / sesi)</span>
                    </div>
                `;
            });

            // Logika Klik pada Kartu Pilihan
            const cards = document.querySelectorAll('.quota-card');
            const hargaDiv = document.getElementById('harga_final_container');
            const hargaText = document.getElementById('hargaText');
            const waButton = document.getElementById('wa_button');

            cards.forEach(card => {
                card.addEventListener('click', function() {
                    // Reset status seleksi kartu lain
                    cards.forEach(c => c.classList.remove('selected'));
                    
                    // Aktifkan status kartu yang diklik
                    this.classList.add('selected');

                    const selectedQuota = this.dataset.quota;
                    const selectedPrice = parseInt(this.dataset.price);

                    // Tampilkan area checkout & hitung harga final
                    hargaText.innerText = selectedPrice.toLocaleString('id-ID');
                    hargaDiv.classList.remove('d-none');

                    // Bangun struktur pesan teks WhatsApp otomatis untuk TOP UP
                    const nama = @json($client->user->name);
                    const username = @json($client->user->username);

                    // PERBAIKAN: Teks WhatsApp khusus untuk proses Top Up
                    let waText = `Halo Admin NgelangiYuk 👋%0A` +
                                 `Aku mau Top Up Kuota Latihan nih! Dataku sebagai berikut yaa:%0A%0A` +
                                 `Nama: ${nama}%0A` +
                                 `Username: ${username}%0A%0A` +
                                 `Detail Pembelian:%0A` +
                                 `Paket Latihan: ${userPaket}%0A`;

                    if (userPaket !== 'Kelas Terapi') {
                        waText += `Person: ${defaultPerson}%0A`;
                    }

                    waText += `Kuota: ${selectedQuota}x Sesi%0A` +
                              `Total Harga: Rp. ${selectedPrice.toLocaleString('id-ID')}%0A%0A` +
                              `Mohon panduan pembayarannya yaa Admin! Terima kasih ☺️`;

                    const waNumber = '{{ config('app.admin_whatsapp') }}';
                    waButton.href = `https://wa.me/${waNumber}?text=${waText}`;
                    waButton.classList.remove('d-none');
                });
            });
        });
    </script>
@endsection