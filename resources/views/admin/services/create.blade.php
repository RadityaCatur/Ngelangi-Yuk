@extends('layouts.admin')
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
                    </em> @endif <p class="helper-block">
                        {{ trans('cruds.service.fields.kuota_helper') }}
                    </p>
                </div>
                <div class="form-group {{ $errors->has('price') ? 'has-error' : '' }}">
                    <label for="price">{{ trans('cruds.service.fields.price') }}</label>
                    <input type="number" id="price" name="price" class="form-control"
                        value="{{ old('price', isset($service) ? $service->price : '') }}" step="0.01">
                    @if($errors->has('price'))
                        <em class="invalid-feedback">
                            {{ $errors->first('price') }}
                        </em>
                    @endif
                    <p class="helper-block">
                        {{ trans('cruds.service.fields.price_helper') }}
                    </p>
                </div>
                <div>
                    <input class="btn btn-danger" type="submit" value="{{ trans('global.save') }}">
                </div>
            </form>
            <a style="margin-top:20px;" class="btn btn-default" href="{{ url()->previous() }}">
                {{ trans('global.back_to_list') }}
            </a>
        </div>
    </div>
@endsection