@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        Edit Lokasi Latihan
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.locations.update", [$location->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label class="required" for="name">Nama Lokasi</label>
                <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name" id="name" value="{{ old('name', $location->name) }}" required>
                @if($errors->has('name'))
                    <div class="invalid-feedback">
                        {{ $errors->first('name') }}
                    </div>
                @endif
            </div>
            <div class="form-group">
                <label for="URL">URL Google Maps (Opsional)</label>
                <input class="form-control {{ $errors->has('URL') ? 'is-invalid' : '' }}" type="url" name="URL" id="URL" value="{{ old('URL', $location->URL) }}">
                @if($errors->has('URL'))
                    <div class="invalid-feedback">
                        {{ $errors->first('URL') }}
                    </div>
                @endif
            </div>
            <div class="form-group">
                <button class="btn btn-success" type="submit">
                    Perbarui
                </button>
                <a href="{{ route('admin.locations.index') }}" class="btn btn-default">Kembali</a>
            </div>
        </form>
    </div>
</div>
@endsection
