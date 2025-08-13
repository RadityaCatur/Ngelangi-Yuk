@extends('layouts.admin')
@section('content')

    <div class="card">
        <div class="card-header">
            {{ trans('global.create') }} {{ trans('cruds.user.title_singular') }}
        </div>

        <div class="card-body">
            <form action="{{ route("admin.users.store") }}" method="POST" enctype="multipart/form-data">
                @csrf
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
                    <input type="username" id="username" name="username" class="form-control"
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
                        <input type="password" id="password" name="password" class="form-control" required>
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
                    <p class="helper-block">
                        {{ trans('cruds.user.fields.password_helper') }}
                    </p>
                </div>
                <div class="form-group {{ $errors->has('roles') ? 'has-error' : '' }}">
                    <label for="roles">{{ trans('cruds.user.fields.roles') }}*</label>
                    <select name="roles" id="roles" class="form-control select2" required>
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

                <div id="extra-fields" style="display:none; margin-top:20px;">

                    {{-- Kolom untuk Pelatih --}}
                    <div id="employee-fields" style="display:none;">
                        <div class="form-group">
                            <label for="employee_phone">No Telepon (Pelatih)</label>
                            <input type="text" name="employee_phone" class="form-control">
                        </div>
                    </div>

                    {{-- Kolom untuk Murid --}}
                    <div id="client-fields" style="display:none;">
                        <div class="form-group">
                            <label for="client_phone">No Telepon (Murid)</label>
                            <input type="text" name="client_phone" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="client_services">Paket Latihan (Services)</label>
                            <select name="client_services[]" id="client_services" class="form-control select2"
                                multiple="multiple" style="width: 100%;">
                                @foreach($serviceOptions as $id => $name)
                                    <option value="{{ $id }}" {{ collect(old('client_services'))->contains($id) ? 'selected' : '' }}>
                                        {{ $name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="client_kuota">Kuota Awal</label>
                            <input type="number" name="client_kuota" class="form-control" min="0" value="0">
                        </div>
                    </div>
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

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

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

            function toggleExtraFields() {
                const selectedValue = $('#roles').val();
                const selectedText = $('#roles option:selected').text().trim();

                const isEmployee = selectedText === 'Pelatih' || selectedValue === '2';
                const isClient = selectedText === 'Murid' || selectedValue === '3';

                $('#extra-fields').toggle(isEmployee || isClient);
                $('#employee-fields').toggle(isEmployee);
                $('#client-fields').toggle(isClient);
            }

            $('#roles').on('change select2:select', toggleExtraFields);

            toggleExtraFields(); // langsung jalankan saat pertama kali
        });
    </script>



@endsection