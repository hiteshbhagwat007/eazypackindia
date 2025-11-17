<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'ERP CRM')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="@yield('body-class', 'bg-gray-100 min-h-screen font-sans text-gray-900')">
    @yield('content')

    @stack('scripts')
</body>
</html>
