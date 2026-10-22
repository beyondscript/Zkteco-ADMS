@extends('layouts.app')

@section('content')
<div class="container auth_page">
    <div class="row justify-content-center">
        <div class="col-12 auth_column">
            @include('auth.partials.visualPanel')

            <div class="card auth_card">
                <div class="card-header">
                    <h1 class="page_title">Reset password</h1>
                    <p class="page_description">Choose a new password for your workspace.</p>
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('password.update') }}">
                        @csrf

                        <input type="hidden" name="token" value="{{ $request->token }}">

                        <div class="row mb-3">
                            <label for="email" class="col-12 col-form-label">Email</label>

                            <div class="col-12">
                                <input id="email" type="text" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ $request->email ?? old('email') }}" autocomplete="email" autofocus>

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
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" autocomplete="new-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="password-confirm" class="col-12 col-form-label">Confirm password</label>

                            <div class="col-12">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" autocomplete="new-password">
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
                                    Reset
                                </button>

                                <a href="{{ route('index') }}" class="btn btn-danger">Cancel</a>
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

                document.getElementById('password-confirm').type = 'text';
            }
            else if(checkBox.checked === false){
                document.getElementById('password').type = 'password';

                document.getElementById('password-confirm').type = 'password';
            }
        }
    </script>
@endsection
