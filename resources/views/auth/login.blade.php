@extends('layouts.auth')

@section('title', 'Login - ERP CRM')

@section('body-class', 'min-h-screen flex items-center justify-center bg-gradient-to-br from-indigo-500 via-purple-500 to-pink-500 p-4 font-sans')

@section('body')
    <div class="w-full max-w-md p-8 bg-white rounded-2xl shadow-xl">
        <div class="flex justify-center mb-6">
            <div class="relative">
                <svg width="72" height="72" viewBox="0 0 192 192" xmlns="http://www.w3.org/2000/svg">
                    <rect width="192" height="192" fill="#4f46e5" rx="16" />
                    <rect x="48" y="36" width="60" height="24" fill="white" />
                    <circle cx="136" cy="48" r="16" fill="#9333ea" />
                    <path d="M48 84 H108 V108 H72 V132 H108 V156 H48 Z" fill="white" />
                </svg>
            </div>
        </div>

        <div class="text-center mb-6">
            <h1 class="text-3xl font-bold text-gray-800 mb-2">Welcome Back</h1>
            <p class="text-gray-600">Sign in to your account</p>
        </div>

        @if (session('status'))
            <div class="mb-4 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
                {{ session('status') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-4 rounded-lg border-l-4 border-red-500 bg-red-50 px-4 py-3 text-sm text-red-700">
                {{ $errors->first() }}
            </div>
        @endif

        <form class="space-y-5" method="POST" action="{{ route('login') }}">
            @csrf

            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    value="{{ old('email') }}"
                    required autofocus
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition"
                    placeholder="you@example.com"
                >
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    required
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition"
                    placeholder="Enter your password"
                >
            </div>

            <div class="flex items-center justify-between text-sm text-gray-600">
                <label class="flex items-center gap-2">
                    <input type="checkbox" id="remember" name="remember" class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                    Remember me
                </label>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="text-indigo-600 hover:text-indigo-500 font-medium">Forgot password?</a>
                @endif
            </div>

            <button
                type="submit"
                class="w-full py-3 px-4 bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-600 hover:to-purple-700 text-white font-semibold rounded-lg shadow-md transition duration-200 transform hover:scale-[1.02]"
            >
                Sign In
            </button>
        </form>

        <div class="mt-6 text-center text-sm text-gray-600">
            Don't have an account?
            <a href="{{ route('register') }}" class="font-medium text-indigo-600 hover:text-indigo-500">Sign up</a>
        </div>

        <div class="mt-8 rounded-xl bg-gray-50 p-5 text-sm text-gray-600 border border-gray-200">
            <h3 class="text-gray-800 font-semibold mb-3">Test Credentials</h3>
            <ul class="space-y-2">
                <li><strong>Super Admin:</strong> superadmin@example.com / 123123</li>
                <li><strong>Admin:</strong> admin@example.com / 123123</li>
                <li><strong>Employee:</strong> employee@example.com / 123123</li>
            </ul>
        </div>
    </div>
@endsection