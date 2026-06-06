@extends('layouts.admin')

@section('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<style>
    /* Header Pop Out & Bold */
    .card-header {
        font-weight: 800 !important;
        font-size: 1.2rem;
        color: #2c3e50;
        border-bottom: 2px solid #f1f3f5;
    }

    /* Custom Date Picker Styling (Ngelangi Theme) */
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
    .date-picker-input {
        background-color: #fff !important;
        cursor: pointer;
    }
    
    /* Bikin input dan select nyatu, sama tinggi */
    .form-group input.form-control,
    .form-group select.form-control {
        height: 38px;
        font-size: 14px;
        padding: 6px 10px;
    }

    /* PERBAIKAN BUG MOBILE: Responsivitas Tombol Action Bar */
    .action-buttons {
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-top: 1px solid #e9ecef;
        padding-top: 1rem;
        margin-top: 1.5rem;
    }
    .action-buttons-right {
        display: flex;
        gap: 10px;
    }
    @media (max-width: 768px) {
        .action-buttons {
            flex-direction: column-reverse; /* Batalkan di bawah, grup Simpan/Kembali di atas */
            align-items: stretch;
            gap: 10px;
        }
        .action-buttons-left form {
            display: block !important;
        }
        .action-buttons-right {
            flex-direction: column-reverse; /* Kembali di bawah, Simpan di atas */
            width: 100%;
        }
        .w-100-mobile {
            width: 100% !important;
            margin-bottom: 8px;
        }
    }
</style>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            {{ trans('global.edit') }} {{ trans('cruds.appointment.title_singular') }}
        </div>

        <div class="card-body">
            @php
                $isAdmin = auth()->user()->roles()->pluck('title')->contains('Admin');
                $isEmployee = auth()->user()->roles()->pluck('title')->contains('Pelatih');
                $isClient = auth()->user()->roles()->pluck('title')->contains('Murid');

                $employee = \App\Employee::where('user_id', auth()->id())->first();
                $client = \App\Client::where('user_id', auth()->id())->first();

                $selectedServices = $appointment->services->pluck('id');
            @endphp

            <form action="{{ route('admin.appointments.update', [$appointment->id]) }}" method="POST"
                enctype="multipart/form-data" id="editForm">
                @csrf
                @method('PUT')

                {{-- Kolom Pelatih --}}
                <div class="form-group {{ $errors->has('employee_id') ? 'has-error' : '' }}">
                    <label for="employee_id">{{ trans('cruds.appointment.fields.employee') }}*</label>

                    <select name="employee_id" id="employee_id" class="form-control select2"
                        data-minimum-results-for-search="Infinity" {{ $isClient ? 'disabled' : 'required' }}>
                        @if ($isEmployee && $employee)
                            <option value="{{ $employee->id }}" selected>{{ $employee->name }}</option>
                        @else
                            <option value="">Pilih Pelatih</option>
                            @foreach ($employees as $id => $employeeName)
                                <option value="{{ $id }}" {{ $appointment->employee_id == $id ? 'selected' : '' }}>{{ $employeeName }}
                                </option>
                            @endforeach
                        @endif
                    </select>

                    @if($errors->has('employee_id'))
                        <em class="invalid-feedback">
                            {{ $errors->first('employee_id') }}
                        </em>
                    @endif
                </div>

                {{-- Kolom Murid --}}
                <div class="form-group {{ $errors->has('client_id') ? 'has-error' : '' }}">
                    <label for="client_id">{{ trans('cruds.appointment.fields.client') }}</label>

                    @if($isAdmin)
                        <select name="client_id" id="client_id" class="form-control select2">
                            <option value="">{{ trans('global.pleaseSelect') }}</option>
                            @foreach($clients as $id => $clientOption)
                                <option value="{{ $id }}" {{ $appointment->client_id == $id ? 'selected' : '' }}>
                                    {{ $clientOption }}
                                </option>
                            @endforeach
                        </select>
                    @elseif($isClient && $client)
                        <select name="client_id" id="client_id" class="form-control select2">
                            <option value="{{ $client->id }}" {{ $appointment->client_id == $client->id ? 'selected' : '' }}>
                                {{ $client->name }}
                            </option>
                        </select>
                    @else
                        <input type="text" class="form-control" value="{{ $appointment->client->name ?? '-' }}" readonly>
                    @endif

                    @if($errors->has('client_id'))
                        <em class="invalid-feedback">
                            {{ $errors->first('client_id') }}
                        </em>
                    @endif
                </div>

                <div class="form-group {{ $errors->has('location') ? 'has-error' : '' }}">
                    <label for="location">Lokasi*</label>
                    <select name="location" id="location" class="form-control select2" {{ !$isAdmin ? 'disabled' : 'required' }}>
                        <option value="">Pilih Lokasi</option>
                        @foreach($location_options as $key => $label)
                            <option value="{{ $key }}" {{ (old('location', $appointment->location) == $key) ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>

                    @if(!$isAdmin)
                        <input type="hidden" name="location" value="{{ $appointment->location }}">
                    @endif

                    @if($errors->has('location'))
                        <em class="invalid-feedback">
                            {{ $errors->first('location') }}
                        </em>
                    @endif
                </div>

                <div class="form-group {{ $errors->has('start_time') ? 'has-error' : '' }}">
                    <label for="start_time">{{ trans('cruds.appointment.fields.start_time') }}*</label>
                    <div style="display:flex; gap:10px;">
                        <div style="flex:1;">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                                </div>
                                <input type="text" id="edit_start_date" class="form-control date-picker-reusable date-picker-input"
                                    value="{{ old('start_time', isset($appointment) ? $appointment->start_time->format('Y-m-d') : '') }}" placeholder="Pilih Tanggal" readonly required>
                            </div>
                        </div>
                        <div style="flex:1;">
                            <select id="edit_start_hour" class="form-control" required>
                                <option value="">-- Pilih Jam --</option>
                            </select>
                        </div>
                    </div>
                    @if($errors->has('start_time'))
                        <em class="invalid-feedback">
                            {{ $errors->first('start_time') }}
                        </em>
                    @endif
                </div>

                <div class="form-group {{ $errors->has('finish_time') ? 'has-error' : '' }}">
                    <label for="finish_time">{{ trans('cruds.appointment.fields.finish_time') }}*</label>
                    <div style="display:flex; gap:10px;">
                        <div style="flex:1;">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                                </div>
                                <input type="text" id="edit_finish_date" class="form-control date-picker-reusable date-picker-input"
                                    value="{{ old('finish_time', isset($appointment) ? $appointment->finish_time->format('Y-m-d') : '') }}" placeholder="Pilih Tanggal" readonly required>
                            </div>
                        </div>
                        <div style="flex:1;">
                            <select id="edit_finish_hour" class="form-control" required>
                                <option value="">-- Pilih Jam --</option>
                            </select>
                        </div>
                    </div>
                    @if($errors->has('finish_time'))
                        <em class="invalid-feedback">
                            {{ $errors->first('finish_time') }}
                        </em>
                    @endif
                </div>

                {{-- Paket Latihan (Services) --}}
                <div class="form-group {{ $errors->has('services') ? 'has-error' : '' }}">
                    <label for="services">{{ trans('cruds.appointment.fields.services') }}*</label>
                    <select name="services[]" id="services" class="form-control select2" multiple="multiple" {{ !$isAdmin ? 'disabled' : 'required' }}>
                        @foreach($services as $service)
                            <option value="{{ $service->id }}" {{ $selectedServices->contains($service->id) ? 'selected' : '' }}>
                                {{ $service->category }}
                            </option>
                        @endforeach
                    </select>

                    @if(!$isAdmin)
                        @foreach($appointment->services as $service)
                            <input type="hidden" name="services[]" value="{{ $service->id }}">
                        @endforeach
                    @endif

                    @if($errors->has('services'))
                        <em class="invalid-feedback">
                            {{ $errors->first('services') }}
                        </em>
                    @endif
                </div>
            </form>

            <div class="action-buttons">
                <div class="action-buttons-left">
                    {{-- Tombol Batal Appointment (Kiri) --}}
                    @if(($isClient && $client && $appointment->client_id === $client->id) || $isAdmin)
                        <form action="{{ $isAdmin ? route('admin.appointments.adminCancel', $appointment->id) : route('admin.appointments.leave', $appointment->id) }}" 
                            method="POST" onsubmit="return confirm('Yakin ingin membatalkan appointment ini?');" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger w-100-mobile">
                                <i class="fas fa-times-circle mr-1"></i> Batalkan Appointment
                            </button>
                        </form>
                    @endif
                </div>

                {{-- Tombol Kembali & Simpan (Kanan) --}}
                <div class="action-buttons-right">
                    <a class="btn btn-default px-4 w-100-mobile" href="{{ route('admin.appointments.index') }}">
                        {{ trans('global.back_to_list') }}
                    </a>
                    <button class="btn btn-success px-5 w-100-mobile" type="button" onclick="document.getElementById('editForm').submit();">
                        {{ trans('global.save') }}
                    </button>
                </div>
            </div>

        </div>
    </div>
@endsection

@section('scripts')
@parent
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://npmcdn.com/flatpickr/dist/l10n/id.js"></script>

<script>
    $(document).ready(function() {
        // Inisialisasi Date Picker Flatpickr
        $(".date-picker-reusable").flatpickr({
            locale: "id",
            dateFormat: "Y-m-d",
            allowInput: true,
            disableMobile: "true",
            onChange: function(selectedDates, dateStr, instance) {
                if (instance.element.id === 'edit_start_date') updateStartHidden();
                if (instance.element.id === 'edit_finish_date') updateFinishHidden();
            }
        });
    });

    const startSlots = ["07:00", "08:00", "09:00", "10:00", "11:00", "12:00", "13:00", "14:00", "15:00", "16:00"];
    const finishSlots = ["08:00", "09:00", "10:00", "11:00", "12:00", "13:00", "14:00", "15:00", "16:00", "17:00"];

    const editStartHourSelect = document.getElementById('edit_start_hour');
    const editFinishHourSelect = document.getElementById('edit_finish_hour');

    // isi dropdown start
    startSlots.forEach(t => {
        const opt = document.createElement('option');
        opt.value = t;
        opt.textContent = t;
        if ("{{ $appointment->start_time->format('H:i') }}" === t) opt.selected = true;
        editStartHourSelect.appendChild(opt);
    });

    // isi dropdown finish
    finishSlots.forEach(t => {
        const opt = document.createElement('option');
        opt.value = t;
        opt.textContent = t;
        if ("{{ $appointment->finish_time->format('H:i') }}" === t) opt.selected = true;
        editFinishHourSelect.appendChild(opt);
    });

    // update hidden input untuk backend (Y-m-d H:i)
    function padZero(n) { return n.toString().padStart(2, '0'); }

    function updateStartHidden() {
        const date = document.getElementById('edit_start_date').value;
        const hour = editStartHourSelect.value;
        if (date && hour) {
            const hidden = document.getElementById('edit_start_time_hidden') || (() => {
                const i = document.createElement('input');
                i.type = 'hidden';
                i.name = 'start_time';
                i.id = 'edit_start_time_hidden';
                editStartHourSelect.parentNode.parentNode.appendChild(i);
                return i;
            })();
            hidden.value = `${date} ${hour}`;
        }
    }

    function updateFinishHidden() {
        const date = document.getElementById('edit_finish_date').value;
        const hour = editFinishHourSelect.value;
        if (date && hour) {
            const hidden = document.getElementById('edit_finish_time_hidden') || (() => {
                const i = document.createElement('input');
                i.type = 'hidden';
                i.name = 'finish_time';
                i.id = 'edit_finish_time_hidden';
                editFinishHourSelect.parentNode.parentNode.appendChild(i);
                return i;
            })();
            hidden.value = `${date} ${hour}`;
        }
    }

    document.getElementById('edit_start_date').addEventListener('change', updateStartHidden);
    editStartHourSelect.addEventListener('change', updateStartHidden);

    document.getElementById('edit_finish_date').addEventListener('change', updateFinishHidden);
    editFinishHourSelect.addEventListener('change', updateFinishHidden);

    // inisialisasi hidden input saat load
    updateStartHidden();
    updateFinishHidden();
</script>
@endsection