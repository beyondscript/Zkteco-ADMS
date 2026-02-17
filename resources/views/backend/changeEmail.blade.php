@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    @include('backend.partials.headMenu')
                </div>

                <div class="card-body">
                    @include('backend.partials.navMenu')

                    <hr>

                    <div class="manage_supervisors">
                        <h4 class="header" style="margin-bottom: 0;">Change email</h4>

                        <div style="border: 1px solid red; padding: 1.25rem;">
                            <form method="POST" action="{{ route('updateEmail') }}">
                                @csrf

                                <div class="row mb-3">
                                    <label for="current_password" class="col-md-4 col-form-label text-md-end">Current password</label>

                                    <div class="col-md-6">
                                        <input id="current_password" type="password" class="form-control @error('current_password') is-invalid @enderror" name="current_password" autocomplete="current-password" autofocus>

                                        @error('current_password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="new_email" class="col-md-4 col-form-label text-md-end">New email</label>

                                    <div class="col-md-6">
                                        <input id="new_email" type="text" class="form-control @error('new_email') is-invalid @enderror" name="new_email" value="{{ old('new_email') }}" autocomplete="email">

                                        @error('new_email')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-6 offset-md-4">
                                        <div class="form-check" style="display: flex; align-items: center;">
                                            <input class="form-check-input" style="cursor: pointer; margin-top: 1px;" type="checkbox" name="show-password" id="show-password" onclick="showPassword()">

                                            <label class="form-check-label" style="cursor: pointer;" for="show-password">
                                                Show password
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mb-0">
                                    <div class="col-md-8 offset-md-4">
                                        <button type="submit" class="btn btn-primary">
                                            Change
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
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
                document.getElementById('current_password').type = 'text';
            }
            else if(checkBox.checked === false){
                document.getElementById('current_password').type = 'password';
            }
        }
    </script>
@endsection
