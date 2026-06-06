@extends('layouts.admin')

@section('styles')
<style>
    /* Header Pop Out & Bold (Konsisten dengan form User) */
    .card-header {
        font-weight: 800 !important;
        font-size: 1.2rem;
        color: #2c3e50;
        border-bottom: 2px solid #f1f3f5;
    }
</style>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            {{ trans('global.create') }} {{ trans('cruds.service.title_singular') }}
        </div>

        <div class="card-body">
            <form action="{{ route("admin.services.store") }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                    <label for="name">{{ trans('cruds.service.fields.name') }}*</label>
                    <input type="text" id="name" name="name" class="form-control"
                        value="{{ old('name', isset($service) ? $service->name : '') }}" required>
                    @if($errors->has('name'))
                        <em class="invalid-feedback">
                            {{ $errors->first('name') }}
                        </em>
                    @endif
                    <p class="helper-block">
                        {{ trans('cruds.service.fields.name_helper') }}
                    </p>
                </div>
                
                <div class="form-group {{ $errors->has('kuota') ? 'has-error' : '' }}">
                    <label for="kuota">{{ trans('cruds.service.fields.kuota') }}*</label>
                    <input type="number" id="kuota" name="kuota" class="form-control"
                        value="{{ old('kuota', isset($service) ? $service->kuota : '') }}" required>
                    @if($errors->has('kuota'))
                        <em class="invalid-feedback">
                            {{ $errors->first('kuota') }}
                        </em>
                    @endif 
                    <p class="helper-block">
                        {{ trans('cruds.service.fields.kuota_helper') }}
                    </p>
                </div>
                
                <div class="form-group {{ $errors->has('price') ? 'has-error' : '' }}">
                    <label for="price">{{ trans('cruds.service.fields.price') }}*</label>
                    <input type="number" id="price" name="price" class="form-control"
                        value="{{ old('price', isset($service) ? $service->price : '') }}" step="0.01" required>
                    @if($errors->has('price'))
                        <em class="invalid-feedback">
                            {{ $errors->first('price') }}
                        </em>
                    @endif
                    <p class="helper-block">
                        {{ trans('cruds.service.fields.price_helper') }}
                    </p>
                </div>

                <div class="d-flex justify-content-end mt-4" style="gap: 10px;">
                    <a class="btn btn-default px-4" href="{{ route('admin.services.index') }}">
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