@extends('layouts.app')

@section('title', 'Create Account – PAWsome Pets')

@section('content')

    <section class="auth-section">
        <div class="auth-container">

            <div class="auth-panel-left" style="background: linear-gradient(145deg, rgba(30, 77, 74, 0.82) 0%, rgba(45, 107, 103, 0.78) 100%), url('{{ asset('images/dog-kitty.jpg') }}') center center / cover no-repeat;">
                <div class="auth-panel-brand">
                    <div class="auth-panel-icon">🐾</div>
                    <h2>Join PAWsome Pets!</h2>
                    <p>Create a free account to apply for adoptions, save your favorite pets, and get notified on your
                        requests.</p>
                </div>
                <div class="auth-panel-steps">
                    <div class="auth-step">
                        <div class="auth-step-num">1</div>
                        <div>
                            <strong>Create your account</strong>
                            <p>Just your name, email, and password.</p>
                        </div>
                    </div>
                    <div class="auth-step">
                        <div class="auth-step-num">2</div>
                        <div>
                            <strong>Browse & apply</strong>
                            <p>Find a pet and submit an adoption request.</p>
                        </div>
                    </div>
                    <div class="auth-step">
                        <div class="auth-step-num">3</div>
                        <div>
                            <strong>Get notified</strong>
                            <p>We'll let you know when you're approved!</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="auth-panel-right">
                <div class="auth-form-header">
                    <h1>Create Account</h1>
                    <p>Already have an account? <a href="{{ route('login') }}">Sign in here</a></p>
                </div>

                <form method="POST" action="{{ route('register') }}" class="auth-form">
                    @csrf

                    <div class="form-group">
                        <label for="name">Full Name</label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}" placeholder="Juan dela Cruz"
                            required autofocus>
                        @error('name')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="you@example.com"
                            required>
                        @error('email')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="phone">Phone Number <span class="form-optional">(optional)</span></label>
                        <input type="tel" id="phone" name="phone" value="{{ old('phone') }}" placeholder="09XX XXX XXXX">
                        @error('phone')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="address">Home Address</label>
                        <input type="text" id="address" name="address" value="{{ old('address') }}"
                        placeholder="Street, City, Province" required>
                        @error('address')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Occupation --}}
                    <div class="form-group">
                        <label for="occupation">Occupation <span class="form-optional">(optional)</span></label>
                        <input type="text" id="occupation" name="occupation" value="{{ old('occupation') }}"
                            placeholder="e.g. Teacher, Engineer, Student">
                        @error('occupation')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Home Type --}}
                    <div class="form-group">
                        <label for="home_type">Home Type <span class="form-optional">(optional)</span></label>
                        <select id="home_type" name="home_type">
                            <option value="">Select home type...</option>
                            <option value="house" {{ old('home_type') == 'house' ? 'selected' : '' }}>🏠 House</option>
                            <option value="apartment" {{ old('home_type') == 'apartment' ? 'selected' : '' }}>🏢 Apartment</option>
                            <option value="condo" {{ old('home_type') == 'condo' ? 'selected' : '' }}>🏙️ Condo</option>
                            <option value="other" {{ old('home_type') == 'other' ? 'selected' : '' }}>🏡 Other</option>
                        </select>
                        @error('home_type')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" placeholder="At least 8 characters" required>
                        @error('password')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password_confirmation">Confirm Password</label>
                        <input type="password" id="password_confirmation" name="password_confirmation"
                            placeholder="Repeat your password" required>
                    </div>

                    <button type="submit" class="btn-coral btn-full">
                        Create My Account →
                    </button>

                    <p class="auth-terms">
                        By registering, you agree to our
                        <a href="{{ url('/terms') }}">Terms of Use</a> and
                        <a href="{{ url('/privacy') }}">Privacy Policy</a>.
                    </p>
                </form>
            </div>
        </div>
    </section>
@endsection