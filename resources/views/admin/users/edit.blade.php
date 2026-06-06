@extends('layouts.admin')

@section('styles')
<style>
    /* Header Pop Out & Bold (Konsisten dengan Create) */
    .card-header {
        font-weight: 800 !important;
        font-size: 1.2rem;
        color: #2c3e50;
        border-bottom: 2px solid #f1f3f5;
    }

    /* Styling agar select2 single terlihat lebih proporsional */
    .select2-container--default .select2-selection--single {
        height: calc(1.5em + .75rem + 2px) !important;
    }
</style>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            {{ trans('global.edit') }} {{ trans('cruds.user.title_singular') }}
        </div>

        <div class="card-body">
            <form action="{{ route("admin.users.update", [$user->id]) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                    <label for="name">{{ trans('cruds.user.fields.name') }}*</label>
                    <input type="text" id="name" name="name" class="form-control"
                        value="{{ old('name', isset($user) ? $user->name : '') }}" required>
                    @if($errors->has('name'))
                        <em class="invalid-feedback">
                            {{ $errors->first('name') }}
                        </em>
                    @endif
                    <p class="helper-block">
                        {{ trans('cruds.user.fields.name_helper') }}
                    </p>
                </div>
                
                <div class="form-group {{ $errors->has('username') ? 'has-error' : '' }}">
                    <label for="username">{{ trans('cruds.user.fields.username') }}*</label>
                    <input type="text" id="username" name="username" class="form-control"
                        value="{{ old('username', isset($user) ? $user->username : '') }}" required>
                    @if($errors->has('username'))
                        <em class="invalid-feedback">
                            {{ $errors->first('username') }}
                        </em>
                    @endif
                    <p class="helper-block">
                        {{ trans('cruds.user.fields.username_helper') }}
                    </p>
                </div>
                
                <div class="form-group {{ $errors->has('password') ? 'has-error' : '' }}">
                    <label for="password">{{ trans('cruds.user.fields.password') }}</label>
                    <div class="input-group">
                        <input type="password" id="password" name="password" class="form-control">
                        <div class="input-group-append">
                            <button type="button" class="btn btn-outline-secondary" onclick="togglePassword()">
                                <i class="fas fa-eye" id="toggleIcon"></i>
                            </button>
                        </div>
                    </div>
                    @if($errors->has('password'))
                        <em class="invalid-feedback">
                            {{ $errors->first('password') }}
                        </em>
                    @endif
                    <p class="helper-block" style="font-size: 0.85rem; color: #6c757d; margin-top: 5px;">
                        Biarkan kosong jika tidak ingin mengubah password.
                    </p>
                </div>
                
                <div class="form-group {{ $errors->has('roles') ? 'has-error' : '' }}">
                    <label for="roles">{{ trans('cruds.user.fields.roles') }}*</label>
                    <select name="roles" id="roles" class="form-control select2" required>
                        <option value="">Silakan Pilih</option>
                        @foreach($roles as $id => $role)
                            <option value="{{ $id }}" {{ old('roles') == $id || (isset($user) && $user->roles->pluck('id')->contains($id)) ? 'selected' : '' }}>
                                {{ $role }}
                            </option>
                        @endforeach
                    </select>
                    @if($errors->has('roles'))
                        <em class="invalid-feedback">
                            {{ $errors->first('roles') }}
                        </em>
                    @endif
                    <p class="helper-block">
                        {{ trans('cruds.user.fields.roles_helper') }}
                    </p>
                </div>

                <div class="d-flex justify-content-end mt-4" style="gap: 10px;">
                    <a class="btn btn-default px-4" href="{{ route('admin.users.index') }}">
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
    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.getElementById('toggleIcon');
            const isPassword = passwordInput.type === 'password';
            passwordInput.type = isPassword ? 'text' : 'password';
            toggleIcon.classList.toggle('fa-eye');
            toggleIcon.classList.toggle('fa-eye-slash');
        }

        $(document).ready(function () {
            $('.select2').select2({
                placeholder: 'Pilih Peran',
                width: '100%',
                minimumResultsForSearch: Infinity
            });
        });
    </script>
@endsection