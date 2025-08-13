@extends('layouts.admin')
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
                <div>
                    <input class="btn btn-danger" type="submit" value="{{ trans('global.save') }}">
                </div>
            </form>


        </div>
    </div>
@endsection