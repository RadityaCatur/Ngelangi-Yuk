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
</style>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            {{ trans('global.edit') }} {{ trans('cruds.client.title_singular') }}
        </div>

        <div class="card-body">
            <form action="{{ route("admin.clients.update", [$client->id]) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                    <label for="name">{{ trans('cruds.client.fields.name') }}</label>
                    <input type="text" id="name" name="name" class="form-control"
                        value="{{ old('name', isset($client) ? $client->user->name : '') }}">
                    @if($errors->has('name'))
                        <em class="invalid-feedback">
                            {{ $errors->first('name') }}
                        </em>
                    @endif
                    <p class="helper-block">
                        {{ trans('cruds.client.fields.name_helper') }}
                    </p>
                </div>
                <div class="form-group {{ $errors->has('phone') ? 'has-error' : '' }}">
                    <label for="phone">{{ trans('cruds.client.fields.phone') }}</label>
                    <input type="text" id="phone" name="phone" class="form-control"
                        value="{{ old('phone', isset($client) ? $client->phone : '') }}">
                    @if($errors->has('phone'))
                        <em class="invalid-feedback">
                            {{ $errors->first('phone') }}
                        </em>
                    @endif
                    <p class="helper-block">
                        {{ trans('cruds.client.fields.phone_helper') }}
                    </p>
                </div>
                <div class="form-group {{ $errors->has('username') ? 'has-error' : '' }}">
                    <label for="username">{{ trans('cruds.client.fields.username') }}</label>
                    <input type="username" id="username" name="username" class="form-control"
                        value="{{ old('username', isset($client) ? $client->user->username : '') }}">
                    @if($errors->has('username'))
                        <em class="invalid-feedback">
                            {{ $errors->first('username') }}
                        </em>
                    @endif
                    <p class="helper-block">
                        {{ trans('cruds.client.fields.username_helper') }}
                    </p>
                </div>
                <div class="form-group {{ $errors->has('services') ? 'has-error' : '' }}">
                    <label for="services">{{ trans('cruds.client.fields.services') }}
                        <span class="btn btn-info btn-xs select-all">{{ trans('global.select_all') }}</span>
                        <span class="btn btn-info btn-xs deselect-all">{{ trans('global.deselect_all') }}</span></label>
                    <select name="services[]" id="services" class="form-control select2" multiple="multiple">
                        @foreach($services as $id => $services)
                            <option value="{{ $id }}" {{ (in_array($id, old('services', [])) || isset($client) && $client->services->contains($id)) ? 'selected' : '' }}>{{ $services }}</option>
                        @endforeach
                    </select>
                    @if($errors->has('services'))
                        <em class="invalid-feedback">
                            {{ $errors->first('services') }}
                        </em>
                    @endif
                    <p class="helper-block">
                        {{ trans('cruds.client.fields.services_helper') }}
                    </p>
                </div>
                <div class="form-group {{ $errors->has('kuota') ? 'has-error' : '' }}">
                    <label for="kuota">{{ trans('cruds.client.fields.kuota') }}</label>
                    <input type="number" id="kuota" name="kuota" class="form-control"
                        value="{{ old('kuota', isset($client) ? $client->kuota : '') }}">
                    @if($errors->has('kuota'))
                        <em class="invalid-feedback">
                            {{ $errors->first('kuota') }}
                        </em>
                    @endif
                    <p class="helper-block">
                        {{ trans('cruds.client.fields.kuota_helper') }}
                    </p>
                </div>
                <div class="form-group {{ $errors->has('kuota_valid_until') ? 'has-error' : '' }}">
                    <label for="kuota_valid_until">Masa Berlaku Kuota</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                        </div>
                        <input type="text" id="kuota_valid_until" name="kuota_valid_until" class="form-control date-picker-reusable date-picker-input"
                            value="{{ old('kuota_valid_until', isset($client) && $client->kuota_valid_until ? \Carbon\Carbon::parse($client->kuota_valid_until)->format('Y-m-d') : '') }}" placeholder="Pilih Tanggal" readonly>
                    </div>
                    @if($errors->has('kuota_valid_until'))
                        <em class="invalid-feedback">
                            {{ $errors->first('kuota_valid_until') }}
                        </em>
                    @endif
                    <p class="helper-block" style="font-size: 0.85rem; color: #6c757d; margin-top: 5px;">
                        Kosongkan jika kuota tidak memiliki masa kadaluwarsa.
                    </p>
                </div>

                <div class="d-flex justify-content-end mt-4" style="gap: 10px;">
                    <a class="btn btn-default px-4" href="{{ route('admin.clients.index') }}">
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
    $(document).ready(function () {
        // Inisialisasi Reusable Date Picker Component
        $(".date-picker-reusable").flatpickr({
            locale: "id",
            dateFormat: "Y-m-d",
            allowInput: true,
            disableMobile: "true"
        });
    });
</script>
@endsection