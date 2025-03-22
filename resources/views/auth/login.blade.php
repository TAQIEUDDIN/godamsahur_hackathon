<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <title>Login</title>
    <style>
        .bg-image {
            background-image: url('https://mdbcdn.b-cdn.net/img/Photos/new-templates/search-box/img4.webp');
            background-size: cover;
            background-position: center;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .gradient-custom-3 {
            background: rgba(0, 0, 0, 0.6);
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .card {
            border-radius: 15px;
            background: white;
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
            max-width: 500px;
            margin: 0 auto;
            width: 100%;
        }
        .form-outline {
            position: relative;
            margin-bottom: 1.5rem;
        }
        .form-control {
            border: 1px solid #38a169;
            border-radius: 5px;
            padding: 12px;
            height: auto;
            width: 100%;
        }
        .form-label {
            position: absolute;
            top: -10px;
            left: 10px;
            background: white;
            padding: 0 5px;
            font-size: 0.85rem;
            color: #38a169;
        }
        .login-btn {
            background-color: #38a169;
            color: white;
            padding: 10px 30px;
            border-radius: 5px;
            transition: 0.3s;
            font-size: 16px;
            border: none;
            width: 100%;
            max-width: 200px;
        }
        .login-btn:hover {
            background-color: #2f855a;
            transform: translateY(-2px);
        }
        .toggle-icon {
            position: absolute;
            right: 15px;
            top: 12px;
            cursor: pointer;
            font-size: 18px;
            color: #2f855a;
        }
        .custom-checkbox {
            display: flex;
            align-items: center;
        }
        .custom-checkbox input {
            margin-right: 8px;
            accent-color: #38a169;
        }
        .custom-checkbox span {
            color: #4a5568;
            font-size: 14px;
        }
        .form-links {
            color: #38a169;
            text-decoration: none;
            font-weight: 500;
            transition: 0.3s;
        }
        .form-links:hover {
            color: #2f855a;
            text-decoration: underline;
        }
        @media (max-width: 768px) {
            .card-body {
                padding: 1.5rem !important;
            }
            .card {
                margin: 0 15px;
            }
            .button-container {
                flex-direction: column;
                gap: 15px;
            }
            .login-btn {
                width: 100%;
                max-width: 100%;
            }
        }
    </style>
</head>
<body>
    <section class="bg-image">
        <div class="gradient-custom-3">
            <div class="container py-5">
                <div class="row justify-content-center">
                    <div class="col-12 col-md-10 col-lg-8 col-xl-7">
                        <div class="card">
                            <div class="card-body p-4 p-md-5">
                                <h2 class="text-center mb-4">Log in to your account</h2>
                                <form method="POST" action="{{ route('login') }}">
                                    @csrf
                                    <div class="form-outline">
                                        <input type="email" id="email" class="form-control" name="email" value="{{ old('email') }}" required autofocus autocomplete="username">
                                        <label class="form-label" for="email">Your Email</label>
                                    </div>
                                    
                                    <div class="form-outline position-relative">
                                        <input type="password" id="password" class="form-control" name="password" required autocomplete="current-password">
                                        <label class="form-label" for="password">Password</label>
                                        <i class="bi bi-eye-slash toggle-icon" id="togglePassword"></i>
                                    </div>

                                    <div class="mb-3">
                                        <label for="remember_me" class="custom-checkbox">
                                            <input id="remember_me" type="checkbox" name="remember">
                                            <span>{{ __('Remember me') }}</span>
                                        </label>
                                    </div>

                                    <div class="d-flex justify-content-between align-items-center mb-4 button-container">
                                        @if (Route::has('password.request'))
                                            <a class="form-links" href="{{ route('password.request') }}">
                                                {{ __('Forgot your password?') }}
                                            </a>
                                        @endif
                                        <div class="d-flex justify-content-center">
                                            <button type="submit" class="btn login-btn">
                                                {{ __('Log in') }}
                                            </button>
                                        </div>
                                    </div>

                                    <p class="text-center text-muted mt-4">Don't have an account? <a href="{{ route('register') }}" class="form-links fw-bold">Register here</a></p>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
    document.addEventListener("DOMContentLoaded", function () {
        function togglePasswordVisibility(inputId, toggleId) {
            const passwordInput = document.getElementById(inputId);
            const toggleIcon = document.getElementById(toggleId);

            toggleIcon.addEventListener("click", function () {
                const type = passwordInput.type === "password" ? "text" : "password";
                passwordInput.type = type;
                this.classList.toggle("bi-eye");
                this.classList.toggle("bi-eye-slash");
            });
        }

        togglePasswordVisibility("password", "togglePassword");
    });
    </script>
</body>
</html>