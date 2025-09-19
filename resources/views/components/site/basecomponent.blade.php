<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ config('app.app_title') }}</title>
    <link rel="shortcut icon" href="{{ asset('assets/img/logo/logo-white.png') }}" type="image/x-icon">

    <link href="{{ asset('assets/bootstrap/css/bootstrap_custom.css') }}" rel="stylesheet">
    <script src="{{ asset('assets/bootstrap/js/bootstrap.bundle.js') }}"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
        integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <style>
        @keyframes marquee {
            0% {
                transform: translateX(100%);
            }

            100% {
                transform: translateX(-100%);
            }
        }

        .marquee-text {
            animation: marquee 16s linear infinite;
        }

        .dashboard-card {
            border: 1px solid var(--bs-gray-300);
            border-radius: 1rem;
            background-color: white;
            padding: 1.5rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

    </style>
</head>

<body>
    {{ $slot }}
</body>

</html>