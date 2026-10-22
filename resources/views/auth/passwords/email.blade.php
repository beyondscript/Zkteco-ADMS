
@extends('layouts.app')

@section('content')
<div class="container auth_page">
    <div class="row justify-content-center">
        <div class="col-12 auth_column">
            @include('auth.partials.visualPanel')

            <div class="card auth_card">
                <div class="card-header">
                    <h1 class="page_title">{{ __('Reset password') }}</h1>
                    <p class="page_description">We'll send a reset link to your email.</p>
                </div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="status_message" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('password.email') }}">
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

                        <div class="row mb-0">
                            <div class="col-12 form_actions">
                                <button type="submit" class="btn btn-primary">
                                    Send link
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
