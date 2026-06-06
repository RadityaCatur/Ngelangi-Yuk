@extends('layouts.app')
@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card-group">
                <div class="card p-4">
                    <div class="card-body">
                        @if(\Session::has('message'))
                            <p class="alert alert-info">
                                {{ \Session::get('message') }}
                            </p>
                        @endif
                        <form method="POST" action="{{ route('login') }}">
                            {{ csrf_field() }}
                            <h1>{{ trans('panel.site_title') }}</h1>
                            <p class="text-muted">{{ trans('global.login') }}</p>

                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="fa fa-user"></i>
                                    </span>
                                </div>
                                <input name="username" type="text"
                                    class="form-control{{ $errors->has('username') ? ' is-invalid' : '' }}" required
                                    autofocus placeholder="{{ trans('global.login_username') }}"
                                    value="{{ old('username', null) }}">
                                @if($errors->has('username'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('username') }}
                                    </div>
                                @endif
                            </div>

                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fa fa-lock"></i></span>
                                </div>
                                <input name="password" type="password"
                                    class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" required
                                    placeholder="{{ trans('global.login_password') }}">
                                @if($errors->has('password'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('password') }}
                                    </div>
                                @endif
                            </div>

                            <div class="row align-items-center">
                                <div class="col-6">
                                    <button type="submit" class="btn btn-primary px-4">
                                        {{ trans('global.login') }}
                                    </button>
                                </div>
                                <div class="col-6 text-right">
                                    <a href="#" id="forgotPasswordBtn" class="btn btn-link px-0" style="color: #019db2; text-decoration: none;">
                                        Lupa Password?
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.getElementById('forgotPasswordBtn').addEventListener('click', function (e) {
                e.preventDefault();
                
                const waNumber = '{{ config('app.admin_whatsapp') }}';
                // PERBAIKAN: Memperbaiki format teks dan emoji yang pecah
                const waText = `Halo Admin NgelangiYuk, aku lupa nih password akun buat masuk ke Website NgelangiYuk 😓%0A` +
                               `Bisa bantu aku? dataku sebagai berikut yaa:%0A%0A` +
                               `Nama: %0A` +
                               `Username: %0A` +
                               `Password Baru: %0A` +
                               `Konfirmasi Password Baru: %0A%0A` +
                               `Terima kasih Admin!`;
                
                const waLink = `https://wa.me/${waNumber}?text=${waText}`;

                Swal.fire({
                    icon: 'info',
                    title: 'Lupa Password?',
                    text: 'Sistem akan mengarahkanmu ke WhatsApp Admin Ngelangi Yuk untuk melakukan reset password.',
                    showCancelButton: true,
                    confirmButtonColor: '#25D366',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: '<i class="fab fa-whatsapp" style="margin-right: 5px;"></i> Hubungi Admin di WA',
                    cancelButtonText: 'Batal',
                    backdrop: `rgba(0,0,0,0.5)`
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.open(waLink, '_blank');
                    }
                });
            });
        });
    </script>
@endsection