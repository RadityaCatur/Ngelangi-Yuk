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
</style>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            {{ trans('global.create') }} {{ trans('cruds.appointment.title_singular') }}
        </div>

        <div class="card-body">
            <form action="{{ route("admin.appointments.store") }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group {{ $errors->has('employee_id') ? 'has-error' : '' }}">
                    <label for="employee">{{ trans('cruds.appointment.fields.employee') }}*</label>
                    <select name="employee_id[]" id="employee" class="form-control select2" multiple="multiple" required>
                        @foreach($employees as $id => $employee)
                            <option value="{{ $id }}" {{ (collect(old('employee_id', []))->contains($id)) ? 'selected' : '' }}>
                                {{ $employee }}
                            </option>
                        @endforeach
                    </select>
                    @if($errors->has('employee_id'))
                        <em class="invalid-feedback">
                            {{ $errors->first('employee_id') }}
                        </em>
                    @endif
                </div>
                
                <div class="form-group {{ $errors->has('client_id') ? 'has-error' : '' }}">
                    <label for="client">{{ trans('cruds.appointment.fields.client') }}</label>
                    <select name="client_id" id="client" class="form-control select2" data-placeholder="-- Pilih Murid --" data-allow-clear="true">
                        <option value=""></option>
                        @foreach($clients as $id => $client)
                            <option value="{{ $id }}" {{ (isset($appointment) && $appointment->client ? $appointment->client->id : old('client_id')) == $id ? 'selected' : '' }}>{{ $client }}</option>
                        @endforeach
                    </select>
                    @if($errors->has('client_id'))
                        <em class="invalid-feedback">
                            {{ $errors->first('client_id') }}
                        </em>
                    @endif
                </div>
                
                <div class="form-group {{ $errors->has('location_id') ? 'has-error' : '' }}">
                    <label for="location_id">Lokasi*</label>
                    <select name="location_id" id="location_id" class="form-control select2" required>
                        @foreach($locations as $id => $name)
                            <option value="{{ $id }}" {{ old('location_id', 1) == $id ? 'selected' : '' }}>{{ $name }}</option>
                        @endforeach
                    </select>
                    @if($errors->has('location_id'))
                        <em class="invalid-feedback">
                            {{ $errors->first('location_id') }}
                        </em>
                    @endif
                </div>
                
                <div class="form-group {{ $errors->has('start_time') ? 'has-error' : '' }}">
                    <label for="start_time">{{ trans('cruds.appointment.fields.start_time') }}*</label>
                    <div style="display: flex; gap: 10px;">
                        <div style="flex: 1;">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                                </div>
                                <input type="text" id="start_date" class="form-control date-picker-reusable date-picker-input"
                                    value="{{ old('start_date', $defaultDate) }}" placeholder="Pilih Tanggal" readonly required>
                            </div>
                        </div>
                        <div style="flex: 1;">
                            <select id="start_hour" class="form-control" required>
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
                    <input type="text" id="finish_time" name="finish_time" class="form-control" readonly
                        value="{{ old('finish_time', isset($appointment) ? $appointment->finish_time->format('Y-m-d H:i') : '') }}" required>
                    @if($errors->has('finish_time'))
                        <em class="invalid-feedback">
                            {{ $errors->first('finish_time') }}
                        </em>
                    @endif
                </div>
                
                <div class="form-group {{ $errors->has('services') ? 'has-error' : '' }}">
                    <label for="services">{{ trans('cruds.appointment.fields.services') }}*</label>
                    <select name="services[]" id="services" class="form-control select2" multiple="multiple" required>
                        @foreach($services as $service)
                            <option value="{{ $service->id }}">
                                {{ $service->category }}
                            </option>
                        @endforeach
                    </select>
                    @if($errors->has('services'))
                        <em class="invalid-feedback">
                            {{ $errors->first('services') }}
                        </em>
                    @endif
                    <p class="helper-block">
                        {{ trans('cruds.appointment.fields.services_helper') }}
                    </p>
                </div>
                
                <div class="d-flex justify-content-end mt-4" style="gap: 10px;">
                    <a class="btn btn-default px-4" href="{{ route('admin.appointments.index') }}">
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
                updateTimes();
            }
        });
    });

    const timeSlots = ["07:00", "08:00", "09:00", "10:00", "11:00", "12:00", "13:00", "14:00", "15:00", "16:00"];
    const startHourSelect = document.getElementById('start_hour');
    const startDateInput = document.getElementById('start_date');
    const finishInput = document.getElementById('finish_time');

    // isi dropdown jam
    timeSlots.forEach(t => {
        const opt = document.createElement('option');
        opt.value = t;
        opt.textContent = t;
        startHourSelect.appendChild(opt);
    });

    function padZero(n) { return n.toString().padStart(2, '0'); }

    function updateTimes() {
        const date = startDateInput.value;
        const hour = startHourSelect.value;
        if (date && hour) {
            const [h, m] = hour.split(':').map(Number);
            const start = new Date(date);
            start.setHours(h, m);
            const finish = new Date(start);
            finish.setHours(finish.getHours() + 1);

            const startVal = `${start.getFullYear()}-${padZero(start.getMonth() + 1)}-${padZero(start.getDate())} ${padZero(start.getHours())}:${padZero(start.getMinutes())}`;
            const finishVal = `${finish.getFullYear()}-${padZero(finish.getMonth() + 1)}-${padZero(finish.getDate())} ${padZero(finish.getHours())}:${padZero(finish.getMinutes())}`;

            finishInput.value = finishVal;

            // hidden input untuk backend
            let hiddenInput = document.getElementById('start_time_hidden');
            if (!hiddenInput) {
                hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.name = 'start_time';
                hiddenInput.id = 'start_time_hidden';
                startDateInput.parentNode.appendChild(hiddenInput);
            }
            hiddenInput.value = startVal;
        }
    }

    startDateInput.addEventListener('change', updateTimes);
    startHourSelect.addEventListener('change', updateTimes);
</script>
@endsection