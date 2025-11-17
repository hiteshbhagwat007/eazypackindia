@extends('layouts.auth')

@section('title', 'Register - ERP CRM')

@section('body-class', 'min-h-screen flex items-center justify-center bg-gradient-to-br from-green-50 to-orange-50 p-4 font-sans')

@section('body')
  <div class="w-full max-w-md p-8 bg-white rounded-2xl shadow-xl">
    <div class="flex justify-center mb-8">
      <div class="relative">
        <svg width="80" height="80" viewBox="0 0 192 192" xmlns="http://www.w3.org/2000/svg">
          <rect width="192" height="192" fill="#6BBF59" rx="16" />
          <rect x="48" y="36" width="60" height="24" fill="white" />
          <circle cx="136" cy="48" r="16" fill="#FF9933" />
          <path d="M48 84 H108 V108 H72 V132 H108 V156 H48 Z" fill="white" />
        </svg>
      </div>
    </div>

    <div class="text-center mb-6">
      <h1 class="text-3xl font-bold text-gray-800 mb-2">Create an Account</h1>
      <p class="text-gray-600">Register a new account for ERP CRM</p>
    </div>

    @if (session('status'))
      <div class="mb-4 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
        {{ session('status') }}
      </div>
    @endif

    @if ($errors->any())
      <div class="mb-4 rounded-lg border-l-4 border-red-500 bg-red-50 px-4 py-3 text-sm text-red-700">
        <ul class="list-disc ml-5 space-y-1">
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <form class="space-y-6" method="POST" action="{{ route('register') }}">
      @csrf

      <div>
        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Full Name</label>
        <input
          type="text"
          id="name"
          name="name"
          value="{{ old('name') }}"
          required autofocus
          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-green focus:border-transparent transition"
          placeholder="John Doe"
        />
      </div>

      <div>
        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
        <input
          type="email"
          id="email"
          name="email"
          value="{{ old('email') }}"
          required
          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-green focus:border-transparent transition"
          placeholder="you@example.com"
        />
      </div>

      <div>
        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
        <input
          type="password"
          id="password"
          name="password"
          required
          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-green focus:border-transparent transition"
          placeholder="Create a password"
        />
      </div>

      <div>
        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Confirm Password</label>
        <input
          type="password"
          id="password_confirmation"
          name="password_confirmation"
          required
          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-green focus:border-transparent transition"
          placeholder="Re-enter your password"
        />
      </div>

      <div>
        <label for="role" class="block text-sm font-medium text-gray-700 mb-2">Select Role</label>
        <select
          id="role"
          name="role"
          required
          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-green focus:border-transparent transition bg-white"
        >
          <option value="" disabled {{ old('role') ? '' : 'selected' }}>Choose role</option>
          <option value="super_admin" {{ old('role') === 'super_admin' ? 'selected' : '' }}>Super Admin</option>
          <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Admin</option>
          <option value="manager" {{ old('role') === 'manager' ? 'selected' : '' }}>Manager</option>
          <option value="employee" {{ old('role') === 'employee' ? 'selected' : '' }}>Employee</option>
        </select>
      </div>

      <div class="flex items-center">
        <input type="checkbox" id="terms" name="terms" class="w-4 h-4 text-brand-green border-gray-300 rounded focus:ring-brand-green" required />
        <label for="terms" class="ml-2 text-sm text-gray-600">
          I agree to the <a href="#" class="text-brand-orange hover:text-orange-700">Terms &amp; Conditions</a>
        </label>
      </div>

      <button
        type="submit"
        class="w-full py-3 px-4 bg-gradient-to-r from-brand-green to-emerald-600 hover:from-emerald-600 hover:to-emerald-700 text-white font-semibold rounded-lg shadow-md transition duration-200 transform hover:scale-[1.02]"
      >
        Create Account
      </button>
    </form>

    <p class="text-center text-sm text-gray-600 mt-6">
      Already have an account?
      <a href="{{ route('login') }}" class="font-medium text-brand-orange hover:text-orange-700">Sign in</a>
    </p>
  </div>
@endsection