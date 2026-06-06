@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        Tambah Lokasi Latihan
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.locations.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label class="required" for="name">Nama Lokasi</label>
                <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name" id="name" value="{{ old('name', '') }}" required>
                @if($errors->has('name'))
                    <div class="invalid-feedback">
                        {{ $errors->first('name') }}
                    </div>
                @endif
                <span class="help-block">Contoh: Kolam Renang A</span>
            </div>
            <div class="form-group">
                <label for="URL">URL Google Maps (Opsional)</label>
                <input class="form-control {{ $errors->has('URL') ? 'is-invalid' : '' }}" type="url" name="URL" id="URL" value="{{ old('URL', '') }}">
                @if($errors->has('URL'))
                    <div class="invalid-feedback">
                        {{ $errors->first('URL') }}
                    </div>
                @endif
                <span class="help-block">Contoh: https://maps.app.goo.gl/...</span>
            </div>
            <div class="form-group">
                <button class="btn btn-success" type="submit">
                    Simpan
                </button>
                <a href="{{ route('admin.locations.index') }}" class="btn btn-default">Kembali</a>
            </div>
        </form>
    </div>
</div>
@endsection
