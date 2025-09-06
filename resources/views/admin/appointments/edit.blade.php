@extends('layouts.admin')
@section('content')

    <div class="card">
        <div class="card-header">
            {{ trans('global.edit') }} {{ trans('cruds.appointment.title_singular') }}
        </div>

        <div class="card-body">
            <form action="{{ route('admin.appointments.update', [$appointment->id]) }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                @method('PUT')

                @php
                    $isAdmin = auth()->user()->roles()->pluck('title')->contains('Admin');
                    $isEmployee = auth()->user()->roles()->pluck('title')->contains('Pelatih');
                    $isClient = auth()->user()->roles()->pluck('title')->contains('Murid');

                    $employee = \App\Employee::where('user_id', auth()->id())->first();
                    $client = \App\Client::where('user_id', auth()->id())->first();
                @endphp

                {{-- Kolom Pelatih --}}
                <div class="form-group {{ $errors->has('employee_id') ? 'has-error' : '' }}">
                    <label for="employee">{{ trans('cruds.appointment.fields.employee') }}</label>

                    <select name="employee_id" id="employee_id" class="form-control select2"
                        data-minimum-results-for-search="Infinity" {{ $isClient ? 'disabled' : '' }} {{-- hanya admin &
                        employee yang bisa ubah --}}>
                        @if ($isEmployee && $employee)
                            <option value="{{ $employee->id }}" selected>{{ $employee->name }}</option>
                        @else
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

                <!-- Start Date Time -->
                <div class="form-group {{ $errors->has('start_time') ? 'has-error' : '' }}">
                    <label for="start_time">{{ trans('cruds.appointment.fields.start_time') }}*</label>
                    <div style="display:flex; gap:10px;">
                        <div style="flex:1;">
                            <input type="date" id="edit_start_date" class="form-control"
                                value="{{ old('start_time', isset($appointment) ? $appointment->start_time->format('Y-m-d') : '') }}">
                        </div>
                        <div style="flex:1;">
                            <select id="edit_start_hour" class="form-control">
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


                <!-- Finish Date Time -->
                <div class="form-group {{ $errors->has('finish_time') ? 'has-error' : '' }}">
                    <label for="finish_time">{{ trans('cruds.appointment.fields.finish_time') }}*</label>
                    <div style="display:flex; gap:10px;">
                        <div style="flex:1;">
                            <input type="date" id="edit_finish_date" class="form-control"
                                value="{{ old('finish_time', isset($appointment) ? $appointment->finish_time->format('Y-m-d') : '') }}">
                        </div>
                        <div style="flex:1;">
                            <select id="edit_finish_hour" class="form-control">
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
                    <label for="services">{{ trans('cruds.appointment.fields.services') }}</label>

                    <select name="services[]" id="services" class="form-control select2" multiple="multiple" {{ !$isAdmin ? 'disabled' : '' }}>
                        @foreach($services as $service)
                            <option value="{{ $service->id }}">
                                {{ $service->category }}
                            </option>
                        @endforeach
                    </select>

                    {{-- Jika bukan admin, tambahkan hidden input supaya tetap submit services --}}
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

                {{-- Tombol Simpan --}}
                <div>
                    <input class="btn btn-success" type="submit" value="{{ trans('global.save') }}">
                </div>
            </form>
            {{-- Tombol Batal --}}
            @if($isClient && $client && $appointment->client_id === $client->id)
                <form action="{{ route('admin.appointments.leave', $appointment->id) }}" method="POST"
                    onsubmit="return confirm('Yakin ingin batal dari appointment ini?');" style="margin-top: 5px">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        Batalkan Pendaftaran
                    </button>
                </form>
            @endif
        </div>

        <script>
            const startSlots = ["07:00", "08:00", "09:00", "10:00", "11:00", "12:00", "13:00", "14:00", "15:00", "16:00"];
            const finishSlots = ["08:00", "09:00", "10:00", "11:00", "12:00", "13:00", "14:00", "15:00", "16:00", "17:00"];

            const editStartHourSelect = document.getElementById('edit_start_hour');
            const editFinishHourSelect = document.getElementById('edit_finish_hour');

            // isi dropdown start
            startSlots.forEach(t => {
                const opt = document.createElement('option');
                opt.value = t;
                opt.textContent = t;
                // set default selected sesuai appointment
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