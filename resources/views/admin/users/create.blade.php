@extends('layouts.admin')

@section('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<style>
    /* 1. Header Pop Out & Bold */
    .card-header {
        font-weight: 800 !important;
        font-size: 1.2rem;
        color: #2c3e50;
        border-bottom: 2px solid #f1f3f5;
    }

    /* 5. Custom Date Picker Styling (Ngelangi Theme) */
    .flatpickr-calendar {
        box-shadow: 0 10px 25px rgba(0,0,0,0.1) !important;
        border: none !important;
        border-radius: 12px !important;
        padding: 10px;
        background: #fff !important; /* Pastikan background selalu putih */
    }
    
    /* PERBAIKAN: Fix ukuran dan warna list bulan yang kebesaran */
    .flatpickr-current-month .flatpickr-monthDropdown-months {
        appearance: none; 
        background: transparent !important;
        color: #2c3e50 !important; /* Warna teks gelap menyesuaikan teks halaman */
        font-size: 1.1rem !important; /* Ukuran normal, tidak raksasa */
        font-weight: bold;
    }
    
    /* PERBAIKAN: Fix dropdown option saat di-klik */
    .flatpickr-current-month .flatpickr-monthDropdown-months .flatpickr-monthDropdown-month {
        background-color: #fff !important;
        color: #2c3e50 !important;
        font-size: 1rem !important;
    }

    /* Fix ukuran input tahun */
    .flatpickr-current-month input.cur-year {
        color: #2c3e50 !important;
        font-size: 1.1rem !important;
        font-weight: bold;
    }

    /* Warna panah navigasi bulan */
    .flatpickr-months .flatpickr-prev-month, .flatpickr-months .flatpickr-next-month {
        fill: #2c3e50 !important;
        color: #2c3e50 !important;
    }

    /* Warna tanggal yang dipilih (Warna Biru Ngelangi) */
    .flatpickr-day.selected, .flatpickr-day.startRange, .flatpickr-day.endRange, 
    .flatpickr-day.selected.prevMonthDay, .flatpickr-day.selected.nextMonthDay {
        background: #019db2 !important; 
        border-color: #019db2 !important;
        border-radius: 8px !important; /* Dibuat agak kotak membulat */
    }
    
    /* Warna range (jika pakai daterange) */
    .flatpickr-day.inRange {
        background: rgba(1, 157, 178, 0.15) !important;
        box-shadow: none !important;
    }
    
    /* Efek hover hari */
    .flatpickr-day:hover {
        border-radius: 8px !important;
    }

    .flatpickr-weekday {
        color: #6c757d !important;
        font-weight: bold;
    }

    /* Input style for datepicker di form */
    .date-picker-input {
        background-color: #fff !important;
        cursor: pointer;
    }

    /* Styling for the role select2 to look more focused */
    .select2-container--default .select2-selection--single {
        height: calc(1.5em + .75rem + 2px) !important;
    }
</style>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.user.title_singular') }}
    </div>

    <div class="card-body">
        <form action="{{ route("admin.users.store") }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                <label for="name">{{ trans('cruds.user.fields.name') }}*</label>
                <input type="text" id="name" name="name" class="form-control"
                    value="{{ old('name', isset($user) ? $user->name : '') }}" required>
                @if($errors->has('name'))
                    <em class="invalid-feedback">{{ $errors->first('name') }}</em>
                @endif
            </div>

            <div class="form-group {{ $errors->has('username') ? 'has-error' : '' }}">
                <label for="username">{{ trans('cruds.user.fields.username') }}*</label>
                <input type="text" id="username" name="username" class="form-control"
                    value="{{ old('username', isset($user) ? $user->username : '') }}" required>
                @if($errors->has('username'))
                    <em class="invalid-feedback">{{ $errors->first('username') }}</em>
                @endif
            </div>

            <div class="form-group {{ $errors->has('password') ? 'has-error' : '' }}">
                <label for="password">{{ trans('cruds.user.fields.password') }}</label>
                <div class="input-group">
                    <input type="password" id="password" name="password" class="form-control" required>
                    <div class="input-group-append">
                        <button type="button" class="btn btn-outline-secondary" onclick="togglePassword()">
                            <i class="fas fa-eye" id="toggleIcon"></i>
                        </button>
                    </div>
                </div>
                @if($errors->has('password'))
                    <em class="invalid-feedback">{{ $errors->first('password') }}</em>
                @endif
            </div>

            <div class="form-group {{ $errors->has('roles') ? 'has-error' : '' }}">
                <label for="roles">{{ trans('cruds.user.fields.roles') }}*</label>
                <select name="roles" id="roles" class="form-control select2" required>
                    <option value="">Silakan Pilih</option>
                    @foreach($roles as $id => $role)
                        <option value="{{ $id }}" {{ old('roles') == $id ? 'selected' : '' }}>
                            {{ $role }}
                        </option>
                    @endforeach
                </select>
                @if($errors->has('roles'))
                    <em class="invalid-feedback">{{ $errors->first('roles') }}</em>
                @endif
            </div>

            <div id="extra-fields" style="display:none; margin-top:20px;">
                {{-- Kolom untuk Pelatih --}}
                <div id="employee-fields" style="display:none;">
                    <div class="form-group">
                        <label for="employee_phone">No Telepon (Pelatih)</label>
                        <input type="text" name="employee_phone" class="form-control">
                    </div>
                </div>

                {{-- Kolom untuk Murid --}}
                <div id="client-fields" style="display:none;">
                    <div class="form-group">
                        <label for="client_phone">No Telepon (Murid)</label>
                        <input type="text" name="client_phone" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="client_services">Paket Latihan (Services)</label>
                        <select name="client_services[]" id="client_services" class="form-control select2"
                            multiple="multiple" style="width: 100%;">
                            @foreach($serviceOptions as $id => $name)
                                <option value="{{ $id }}" {{ collect(old('client_services'))->contains($id) ? 'selected' : '' }}>
                                    {{ $name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="client_kuota">Kuota Awal</label>
                        <input type="number" name="client_kuota" class="form-control" min="0" value="0">
                    </div>
                    
                    <div class="form-group">
                        <label for="client_kuota_valid_until">Masa Berlaku Kuota</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                            </div>
                            <input type="text" name="client_kuota_valid_until" class="form-control date-picker-reusable date-picker-input" 
                                   placeholder="Pilih Tanggal" readonly value="{{ old('client_kuota_valid_until') }}">
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-end mt-4" style="gap: 10px;">
                <a class="btn btn-default px-4" href="{{ route('admin.users.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
                <button class="btn btn-success px-5" type="submit">
                    {{ trans('global.save') }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://npmcdn.com/flatpickr/dist/l10n/id.js"></script>

<script>
    function togglePassword() {
        const passwordInput = document.getElementById('password');
        const toggleIcon = document.getElementById('toggleIcon');
        const isPassword = passwordInput.type === 'password';
        passwordInput.type = isPassword ? 'text' : 'password';
        toggleIcon.classList.toggle('fa-eye');
        toggleIcon.classList.toggle('fa-eye-slash');
    }

    $(document).ready(function () {
        $('.select2').select2({
            placeholder: 'Pilih Opsi',
            width: '100%'
        });

        // 5. Inisialisasi Reusable Date Picker Component
        // Mode Single
        $(".date-picker-reusable").flatpickr({
            locale: "id",
            dateFormat: "Y-m-d",
            allowInput: true,
            disableMobile: "true"
        });

        // Mode Range (Jika nanti kamu pakai di halaman lain, tinggal kasih class ini)
        $(".date-range-reusable").flatpickr({
            locale: "id",
            mode: "range",
            dateFormat: "Y-m-d",
            disableMobile: "true"
        });

        function toggleExtraFields() {
            const selectedText = $('#roles option:selected').text().trim();
            const isEmployee = selectedText === 'Pelatih';
            const isClient = selectedText === 'Murid';

            $('#extra-fields').toggle(isEmployee || isClient);
            $('#employee-fields').toggle(isEmployee);
            $('#client-fields').toggle(isClient);
        }

        $('#roles').on('change', toggleExtraFields);
        toggleExtraFields();
    });
</script>
@endsection