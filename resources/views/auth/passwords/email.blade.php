@extends('layouts.app')
@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card-group">
                <div class="card p-4">
                    <div class="card-body">
                        <form method="POST" action="{{ route('password.username') }}">
                            {{ csrf_field() }}
                            <h1>
                                <div class="login-logo">
                                    <a href="#">
                                        {{ trans('panel.site_title') }}
                                    </a>
                                </div>
                            </h1>
                            <p class="text-muted"></p>
                            <div>
                                {{ csrf_field() }}
                                <div class="form-group has-feedback">
                                    <input type="username" name="username" class="form-control" required="autofocus"
                                        placeholder="{{ trans('global.login_username') }}">
                                    @if($errors->has('username'))
                                        <em class="invalid-feedback">
                                            {{ $errors->first('username') }}
                                        </em>
                                    @endif
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 text-right">
                                    <button type="submit" class="btn btn-primary btn-block btn-flat">
                                        {{ trans('global.reset_password') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection