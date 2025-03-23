<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'MusafirBuddy') }}</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com" rel="preconnect">
    <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,300;1,400;1,500;1,600;1,700;1,800&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Jost:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-image: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url('/assets/img/bg/masjidsendayan.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            min-height: 100vh;
            display: flex;
            align-items: center;
        }

        .auth-card {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border: none;
            border-radius: 15px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
        }

        .auth-card .card-header {
            background: transparent;
            border-bottom: 2px solid rgba(0, 0, 0, 0.1);
            font-size: 1.5rem;
            font-weight: 600;
            color: #333;
            padding: 1.5rem;
        }

        .auth-card .card-body {
            padding: 2rem;
        }

        .form-control {
            border-radius: 8px;
            padding: 0.75rem 1rem;
            border: 1px solid rgba(0, 0, 0, 0.1);
            background: rgba(255, 255, 255, 0.9);
        }

        .form-control:focus {
            box-shadow: 0 0 0 3px rgba(13, 110, 253, 0.15);
        }

        .btn-primary {
            padding: 0.75rem 2rem;
            border-radius: 8px;
            font-weight: 500;
            background-color: #0d6efd;
            border: none;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(13, 110, 253, 0.3);
        }

        .auth-links {
            margin-top: 1rem;
        }

        .auth-links a {
            color: #0d6efd;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .auth-links a:hover {
            color: #0a58ca;
            text-decoration: underline;
        }

        .logo-section {
            text-align: center;
            margin-bottom: 2rem;
        }

        .logo-section h1 {
            color: white;
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
        }

        .logo-section p {
            color: rgba(255, 255, 255, 0.9);
            font-size: 1.1rem;
        }
    </style>
</head>
<body>
    @yield('content')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 