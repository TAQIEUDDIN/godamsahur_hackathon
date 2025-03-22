<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <title>Register</title>
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
        .register-btn {
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
        .register-btn:hover {
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
        @media (max-width: 768px) {
            .card-body {
                padding: 1.5rem !important;
            }
            .card {
                margin: 0 15px;
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
                                <h2 class="text-uppercase text-center mb-4">Create an account</h2>
                                <form id="registerForm" method="POST" action="{{ route('register') }}">
                                    @csrf
                                    <div class="form-outline">
                                        <input type="text" id="name" class="form-control" name="name" required autofocus>
                                        <label class="form-label" for="name">Your Name</label>
                                    </div>
                                    <div class="form-outline">
                                        <input type="email" id="email" class="form-control" name="email" required>
                                        <label class="form-label" for="email">Your Email</label>
                                    </div>
                                    <div class="form-outline position-relative">
                                        <input type="password" id="password" class="form-control" name="password" required>
                                        <label class="form-label" for="password">Password</label>
                                        <i class="bi bi-eye-slash toggle-icon" id="togglePassword"></i>
                                    </div>
                                    <div class="form-outline position-relative">
                                        <input type="password" id="password_confirmation" class="form-control" name="password_confirmation" required>
                                        <label class="form-label" for="password_confirmation">Repeat your password</label>
                                        <i class="bi bi-eye-slash toggle-icon" id="toggleConfirmPassword"></i>
                                        <p class="text-danger mt-1 d-none" id="passwordError">Passwords do not match!</p>
                                    </div>
                                    <div class="d-flex justify-content-center mt-4">
                                        <button type="submit" class="btn register-btn">Register</button>
                                    </div>
                                    <p class="text-center text-muted mt-4">Already have an account? <a href="{{ route('login') }}" class="text-success fw-bold">Login here</a></p>
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
            togglePasswordVisibility("password_confirmation", "toggleConfirmPassword");

            const passwordInput = document.getElementById("password");
            const confirmPasswordInput = document.getElementById("password_confirmation");
            const passwordError = document.getElementById("passwordError");
            const registerForm = document.getElementById("registerForm");

            function checkPasswordMatch() {
                if (passwordInput.value !== confirmPasswordInput.value) {
                    passwordError.classList.remove("d-none");
                } else {
                    passwordError.classList.add("d-none");
                }
            }

            confirmPasswordInput.addEventListener("input", checkPasswordMatch);

            registerForm.addEventListener("submit", function (event) {
                if (passwordInput.value !== confirmPasswordInput.value) {
                    event.preventDefault();
                    alert("Passwords do not match. Please check again.");
                }
            });
        });
    </script>
</body>
</html>