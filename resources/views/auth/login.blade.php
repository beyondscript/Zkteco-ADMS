@extends('layouts.app')

@section('content')
<div class="container auth_page">
    <div class="row justify-content-center">
        <div class="col-12 auth_column">
            @include('auth.partials.visualPanel')

            <div class="card auth_card">
                <div class="card-header">
                    <h1 class="page_title">Login</h1>
                    <p class="page_description">Sign in to your workspace.</p>
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="row mb-3">
                            <label for="email" class="col-12 col-form-label">Email</label>

                            <div class="col-12">
                                <input id="email" type="text" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" autocomplete="email" autofocus>

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="password" class="col-12 col-form-label">Password</label>

                            <div class="col-12">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" autocomplete="current-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-12">
                                <div class="form-check" style="display: flex; align-items: center;">
                                    <input class="form-check-input" style="cursor: pointer; margin-top: -1px;" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                    <label class="form-check-label" style="cursor: pointer;" for="remember">
                                        Remember me
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-12">
                                <div class="form-check" style="display: flex; align-items: center;">
                                    <input class="form-check-input" style="cursor: pointer; margin-top: -1px;" type="checkbox" name="show-password" id="show-password" onclick="showPassword()">

                                    <label class="form-check-label" style="cursor: pointer;" for="show-password">
                                        Show password
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-12 form_actions">
                                <button type="submit" class="btn btn-primary">
                                    Login
                                </button>

                                @if(Route::has('password.request'))
                                    <a id="reset_password_button" class="btn btn-link" href="{{ route('password.request') }}">
                                        Forgot your password?
                                    </a>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
    <script>
        function showPassword() {
            checkBox = document.getElementById('show-password');

            if(checkBox.checked === true){
                document.getElementById('password').type = 'text';
            }
            else if(checkBox.checked === false){
                document.getElementById('password').type = 'password';
            }
        }
    </script>
@endsection
