@extends('layouts.admin')

@section('content')
    <div class="card">
        <div class="card-header">
            Top Up Kuota Latihan
        </div>

        <div class="card-body">
            <h5 class="mb-3">Informasi Akun</h5>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label>Nama:</label>
                    <input type="text" class="form-control" value="{{ $client->user->name }}" readonly>
                </div>

                <div class="form-group col-md-6">
                    <label>Username:</label>
                    <input type="text" class="form-control" value="{{ $client->user->username }}" readonly>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label>Paket Latihan Kamu:</label>
                    <input type="text" class="form-control" value="{{ optional($client->services->first())->name ?? '-' }}"
                        readonly>
                </div>
                <div class="form-group col-md-6">
                    <label>Kuota Latihan:</label>
                    <input type="text" class="form-control" value="{{ $client->kuota }} sesi" readonly>
                </div>
            </div>

            <hr>
            <h5 class="mb-3">Informasi Harga Paket Latihan dan Alur Pembayaran</h5>

            <div class="form-group">
                <label for="paket_latihan">Paket Latihan:</label>
                <select id="paket_latihan" class="form-control">
                    <option value="">-- Pilih Paket Latihan --</option>
                    <option value="Kelas Anak Anak">Kelas Anak Anak</option>
                    <option value="Kelas Dewasa">Kelas Dewasa</option>
                    <option value="Kelas Terapi">Kelas Terapi</option>
                </select>
            </div>

            <div class="form-group d-none" id="person_group">
                <label for="person">Person:</label>
                <select id="person" class="form-control">
                    <option value="">-- Pilih Person --</option>
                </select>
            </div>

            <div class="form-group d-none" id="kuota_group">
                <label for="kuota">Kuota:</label>
                <select id="kuota" class="form-control">
                    <option value="">-- Pilih Kuota --</option>
                </select>
            </div>

            <div id="harga_final" class="mt-4 d-none text-center">
                <h4 class="text-success font-weight-bold">Rp. <span id="hargaText"></span></h4>
            </div>

            <p id="instruksi" class="mt-4 d-none alert alert-info">
                Harap melanjutkan pembayaran dan konfirmasi ke admin melalui tombol WhatsApp di pojok bawah.
            </p>

            <a href="#" target="_blank" id="wa_button" class="btn btn-success shadow d-none"
                style="position: fixed; bottom: 20px; right: 20px; border-radius: 50%; width: 60px; height: 60px; display: flex; align-items: center; justify-content: center; z-index: 999;">
                <i class="fab fa-whatsapp" style="font-size: 24px;"></i>
            </a>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        const hargaMap = {
            'Kelas Anak Anak': {
                '1 Murid 1 Pelatih': {
                    1: 70000,
                    4: 255000,
                    8: 490000
                },
                '2 Murid 1 Pelatih': {
                    1: 120000,
                    4: 450000
                },
                '3 Murid 1 Pelatih': {
                    1: 160000,
                    4: 600000
                }
            },
            'Kelas Dewasa': {
                '1 Murid 1 Pelatih': {
                    1: 75000,
                    4: 280000,
                    8: 540000
                },
                '2 Murid 1 Pelatih': {
                    1: 130000,
                    4: 500000
                }
            },
            'Kelas Terapi': {
                4: 400000,
                8: 750000
            }
        };

        const paketSelect = document.getElementById('paket_latihan');
        const personGroup = document.getElementById('person_group');
        const personSelect = document.getElementById('person');
        const kuotaGroup = document.getElementById('kuota_group');
        const kuotaSelect = document.getElementById('kuota');
        const hargaDiv = document.getElementById('harga_final');
        const hargaText = document.getElementById('hargaText');
        const instruksi = document.getElementById('instruksi');
        const waButton = document.getElementById('wa_button');

        paketSelect.addEventListener('change', function () {
            personGroup.classList.add('d-none');
            kuotaGroup.classList.add('d-none');
            hargaDiv.classList.add('d-none');
            instruksi.classList.add('d-none');
            waButton.classList.add('d-none');
            personSelect.innerHTML = '<option value="">-- Pilih Person --</option>';
            kuotaSelect.innerHTML = '<option value="">-- Pilih Kuota --</option>';

            const paket = this.value;

            if (paket === 'Kelas Terapi') {
                kuotaGroup.classList.remove('d-none');
                [4, 8].forEach(k => {
                    kuotaSelect.innerHTML += `<option value="${k}">${k}</option>`;
                });
            } else if (paket === 'Kelas Anak Anak' || paket === 'Kelas Dewasa') {
                personGroup.classList.remove('d-none');
                ['1 Murid 1 Pelatih', '2 Murid 1 Pelatih', '3 Murid 1 Pelatih'].forEach(p => {
                    if (paket === 'Kelas Dewasa' && p === '3 Murid 1 Pelatih') return;
                    personSelect.innerHTML += `<option value="${p}">${p}</option>`;
                });
            }
        });

        personSelect.addEventListener('change', function () {
            kuotaGroup.classList.remove('d-none');
            kuotaSelect.innerHTML = '<option value="">-- Pilih Kuota --</option>';
            const paket = paketSelect.value;
            const person = this.value;

            Object.keys(hargaMap[paket][person]).forEach(q => {
                kuotaSelect.innerHTML += `<option value="${q}">${q}</option>`;
            });
        });

        kuotaSelect.addEventListener('change', function () {
            const paket = paketSelect.value;
            const person = personSelect.value;
            const kuota = this.value;

            let harga = 0;
            if (paket === 'Kelas Terapi') {
                harga = hargaMap[paket][kuota];
            } else {
                harga = hargaMap[paket][person][kuota];
            }

            hargaText.innerText = harga.toLocaleString('id-ID');
            hargaDiv.classList.remove('d-none');
            instruksi.classList.remove('d-none');

            const nama = @json($client->user->name);
            const username = @json($client->user->username);

            let waText = `Halo Admin Ngelangi Yuk, aku:%0A%0A` +
                `Nama: ${nama}%0A` +
                `Username: ${username}%0A%0A` +
                `Ingin Top Up Kuota Latihan dengan detail:%0A` +
                `Paket Latihan: ${paket}%0A`;

            if (paket !== 'Kelas Terapi') {
                waText += `Person: ${person}%0A`;
            }

            waText += `Kuota: ${kuota}x sesi%0A` +
                `Harga: Rp. ${harga.toLocaleString('id-ID')}%0A%0A` +
                `Mohon bantuannya yaa ^-^`;

            const waNumber = '{{ config('app.admin_whatsapp') }}';
            waButton.href = `https://wa.me/${waNumber}?text=${waText}`;
            waButton.classList.remove('d-none');
        });
    </script>
@endsection