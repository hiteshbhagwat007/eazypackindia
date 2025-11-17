<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'ERP CRM')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="@yield('body-class', 'min-h-screen bg-gray-50 font-sans text-gray-900')">
    @yield('body')
</body>
</html>
