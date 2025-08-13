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

                {{-- Start Time --}}
                <div class="form-group {{ $errors->has('start_time') ? 'has-error' : '' }}">
                    <label for="start_time">{{ trans('cruds.appointment.fields.start_time') }}*</label>
                    <input type="text" id="start_time" name="start_time" class="form-control datetime"
                        value="{{ old('start_time', $appointment->start_time) }}" {{ !$isAdmin ? 'readonly' : '' }}
                        required>
                </div>

                {{-- Finish Time --}}
                <div class="form-group {{ $errors->has('finish_time') ? 'has-error' : '' }}">
                    <label for="finish_time">{{ trans('cruds.appointment.fields.finish_time') }}*</label>
                    <input type="text" id="finish_time" name="finish_time" class="form-control datetime"
                        value="{{ old('finish_time', $appointment->finish_time) }}" {{ !$isAdmin ? 'readonly' : '' }}
                        required>
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
@endsection