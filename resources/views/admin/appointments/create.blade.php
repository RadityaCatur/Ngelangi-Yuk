@extends('layouts.admin')
@section('content')

    <div class="card">
        <div class="card-header">
            {{ trans('global.create') }} {{ trans('cruds.appointment.title_singular') }}
        </div>

        <div class="card-body">
            <form action="{{ route("admin.appointments.store") }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group {{ $errors->has('employee_id') ? 'has-error' : '' }}">
                    <label for="employee">{{ trans('cruds.appointment.fields.employee') }}</label>
                    <select name="employee_id[]" id="employee" class="form-control select2" multiple="multiple">
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
                    <select name="client_id" id="client" class="form-control select2">
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
                <div class="form-group {{ $errors->has('start_time') ? 'has-error' : '' }}">
                    <label for="start_time">{{ trans('cruds.appointment.fields.start_time') }}*</label>
                    <div style="display: flex; gap: 10px;">
                        <!-- Tanggal separo -->
                        <div style="flex: 1;">
                            <input type="date" id="start_date" class="form-control"
                                value="{{ old('start_time', isset($appointment) ? $appointment->start_time->format('Y-m-d') : '') }}">
                        </div>
                        <!-- Jam separo -->
                        <div style="flex: 1;">
                            <select id="start_hour" class="form-control">
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
                        value="{{ old('finish_time', isset($appointment) ? $appointment->finish_time->format('Y-m-d H:i') : '') }}">
                    @if($errors->has('finish_time'))
                        <em class="invalid-feedback">
                            {{ $errors->first('finish_time') }}
                        </em>
                    @endif
                </div>
                <div class="form-group {{ $errors->has('services') ? 'has-error' : '' }}">
                    <label for="services">{{ trans('cruds.appointment.fields.services') }}
                        <span class="btn btn-info btn-xs select-all">{{ trans('global.select_all') }}</span>
                        <span class="btn btn-info btn-xs deselect-all">{{ trans('global.deselect_all') }}</span></label>
                    <select name="services[]" id="services" class="form-control select2" multiple="multiple">
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
                <div>
                    <input class="btn btn-danger" type="submit" value="{{ trans('global.save') }}">
                </div>
            </form>


        </div>
    </div>

    <script>
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

    <style>
        /* bikin input dan select nyatu, sama tinggi */
        .form-group input.form-control,
        .form-group select.form-control {
            height: 38px;
            font-size: 14px;
            padding: 6px 10px;
        }
    </style>
@endsection