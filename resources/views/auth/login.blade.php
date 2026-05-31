@extends('layouts.app')

@section('title', 'Sign In – PAWsome Pets')

@section('content')

    <section class="auth-section">
        <div class="auth-container">

            <div class="auth-panel-left" style="background: linear-gradient(145deg, rgba(30, 77, 74, 0.82) 0%, rgba(45, 107, 103, 0.78) 100%), url('{{ asset('images/dog-kitty.jpg') }}') center center / cover no-repeat;">
                <div class="auth-panel-brand">
                    <div class="auth-panel-icon">🐾</div>
                    <h2>Welcome Back!</h2>
                    <p>Sign in to check your adoption requests, track notifications, and find your next furry friend.</p>
                </div>
            </div>

            <div class="auth-panel-right">
                <div class="auth-form-header">
                    <h1>Sign In</h1>
                    <p>Don't have an account? <a href="{{ route('register') }}">Register here</a></p>
                </div>

                <form method="POST" action="{{ route('login') }}" class="auth-form">
                    @csrf

                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="you@example.com"
                            required autofocus>
                        @error('email')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" placeholder="Enter your password" required>
                        @error('password')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-check">
                        <input type="checkbox" id="remember" name="remember">
                        <label for="remember">Keep me signed in</label>
                    </div>

                    <button type="submit" class="btn-primary btn-full">
                        Sign In →
                    </button>
                </form>

                <div class="auth-divider"><span>or</span></div>
                    <a href="{{ url('/pets') }}" class="btn-outline btn-full" style="text-align:center;">
                        Browse Pets Without Signing In
                    </a>
                </div>
        </div>
    </section>
@endsection