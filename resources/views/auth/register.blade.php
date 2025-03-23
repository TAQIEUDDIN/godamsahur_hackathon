@extends('layouts.auth')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="logo-section">
                <h1>MusafirBuddy</h1>
                <p>Join Our Community</p>
            </div>

            <div class="card auth-card">
                <div class="card-header">{{ __('Create New Account') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="row mb-4">
                            <label for="name" class="col-md-4 col-form-label text-md-end">{{ __('Full Name') }}</label>
                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>
                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-4">
                            <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Email Address') }}</label>
                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-4">
                            <label for="password" class="col-md-4 col-form-label text-md-end">{{ __('Password') }}</label>
                            <div class="col-md-6">
                                <div class="input-group">
                                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">
                                    <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                        <i class="bi bi-eye-slash" id="togglePasswordIcon"></i>
                                    </button>
                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-end">{{ __('Confirm Password') }}</label>
                            <div class="col-md-6">
                                <div class="input-group">
                                    <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                                    <button class="btn btn-outline-secondary" type="button" id="toggleConfirmPassword">
                                        <i class="bi bi-eye-slash" id="toggleConfirmPasswordIcon"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Register') }}
                                </button>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-md-8 offset-md-4 auth-links">
                                <p>Already have an account? <a href="{{ route('login') }}">Login here</a></p>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // For password field
    const togglePassword = document.querySelector('#togglePassword');
    const password = document.querySelector('#password');
    const toggleIcon = document.querySelector('#togglePasswordIcon');

    togglePassword.addEventListener('click', function () {
        const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
        password.setAttribute('type', type);
        toggleIcon.classList.toggle('bi-eye');
        toggleIcon.classList.toggle('bi-eye-slash');
    });

    // For confirm password field
    const toggleConfirmPassword = document.querySelector('#toggleConfirmPassword');
    const confirmPassword = document.querySelector('#password-confirm');
    const toggleConfirmIcon = document.querySelector('#toggleConfirmPasswordIcon');

    toggleConfirmPassword.addEventListener('click', function () {
        const type = confirmPassword.getAttribute('type') === 'password' ? 'text' : 'password';
        confirmPassword.setAttribute('type', type);
        toggleConfirmIcon.classList.toggle('bi-eye');
        toggleConfirmIcon.classList.toggle('bi-eye-slash');
    });
});
</script>

<style>
.input-group .btn-outline-secondary {
    border-color: #ced4da;
    color: #6c757d;
}

.input-group .btn-outline-secondary:hover {
    background-color: #f8f9fa;
    color: #6c757d;
}

.input-group .form-control:focus + .btn-outline-secondary {
    border-color: #86b7fe;
}

.input-group .btn-outline-secondary:focus {
    box-shadow: none;
}

.input-group {
    position: relative;
}

.invalid-feedback {
    display: block;
    width: 100%;
    margin-top: 0.25rem;
}
</style>
@endsection
