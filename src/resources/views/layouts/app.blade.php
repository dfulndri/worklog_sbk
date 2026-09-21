<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Daily Report') | SBK</title>

    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/bootstrap-icons/bootstrap-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/custom.css') }}">
    @stack('styles')
</head>

<body class="@yield('body-class')">
    @yield('content')

    <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
    @auth
        <script>
            // Override data demo bawaan template (lihat main.js: initUserProfile)
            // supaya nama & foto yang tampil selalu data user yang sedang login.
            window.adminHMDUser = {
                name: @json(auth()->user()->name),
                workspace: @json(auth()->user()->isAdmin() ? 'Administrator' : auth()->user()->employee->position ?? 'Karyawan'),
                avatar: @json(auth()->user()->avatarUrl()),
            };
        </script>
    @endauth
    <script src="{{ asset('assets/js/main.js') }}"></script>
    @stack('scripts')
</body>

</html>
