@extends('layouts.auth')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="logo-section">
                <h1>MusafirBuddy</h1>
                <p>Email Verification Required</p>
            </div>

            <div class="card auth-card">
                <div class="card-header">{{ __('Verify Your Email Address') }}</div>

                <div class="card-body">
                    @if (session('resent'))
                        <div class="alert alert-success" role="alert">
                            {{ __('A fresh verification link has been sent to your email address.') }}
                        </div>
                    @endif

                    <p class="mb-4">{{ __('Before proceeding, please check your email for a verification link.') }}</p>
                    
                    <p class="mb-4">{{ __('If you did not receive the email') }},
                        <form class="d-inline" method="POST" action="{{ route('verification.resend') }}">
                            @csrf
                            <button type="submit" class="btn btn-link p-0 m-0 align-baseline">{{ __('click here to request another') }}</button>.
                        </form>
                    </p>

                    <div class="text-center mt-4">
                        <a href="{{ route('login') }}" class="btn btn-outline-primary">
                            <i class="bi bi-arrow-left"></i> Back to Login
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.btn-link {
    color: #0d6efd;
    text-decoration: none;
    padding: 0;
    vertical-align: baseline;
}

.btn-link:hover {
    color: #0a58ca;
    text-decoration: underline;
}

.alert-success {
    background-color: rgba(25, 135, 84, 0.1);
    border-color: rgba(25, 135, 84, 0.2);
    color: #198754;
    border-radius: 8px;
}

.btn-outline-primary {
    border: 2px solid #0d6efd;
    padding: 0.5rem 1.5rem;
    transition: all 0.3s ease;
}

.btn-outline-primary:hover {
    background-color: #0d6efd;
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(13, 110, 253, 0.3);
}
</style>
@endsection
