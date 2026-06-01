<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'PAWsome Pets – Find Your Forever Friend')</title>
    <link rel="icon" type="image/png" href="{{ asset('pawsome-pets-logo.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=DM+Sans:wght@400;500;600&display=swap"
        rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @yield('styles')
</head>

<body>
    <nav class="main-nav">
        <a href="{{ url('/') }}" class="nav-logo">
            <div class="nav-logo-icon">
                <img src="{{ asset('pawsome-pets-logo.png') }}" alt="PAWsome Pets Logo">
            </div>
            <span class="nav-logo-text">PAWsome <span>Pets</span></span>
        </a>

        <ul class="nav-links">
            <x-nav-link href="{{ url('/pets') }}" :active="request()->is('pets*')">
                Adopt a Pet
            </x-nav-link>
            <x-nav-link href="{{ url('/#how-it-works') }}" :active="false">
                How It Works
            </x-nav-link>
            <x-nav-link href="{{ url('/gallery') }}" :active="request()->is('gallery*')">
                Pet Gallery
            </x-nav-link>
            <x-nav-link href="{{ url('/about') }}" :active="request()->is('about*')">
                About Us
            </x-nav-link>
            @if(! auth()->check() || auth()->user()->role !== 'admin')
                <x-nav-link href="{{ url('/rate') }}" :active="request()->is('rate*')">
                    Rate Us
                </x-nav-link>
            @endif
        </ul>

        <div class="nav-actions">
            @auth
                @if(auth()->user()->role === 'adopter')
                    @php
                        $unread = \Illuminate\Support\Facades\DB::table('notifs')
                            ->where('user_id', auth()->id())
                            ->where('is_read', false)
                            ->count();
                    @endphp
                    <a href="{{ route('notifications.index') }}" class="nav-notif-btn" title="Notifications">
                        🔔
                        @if($unread > 0)
                            <span class="nav-notif-badge">{{ $unread }}</span>
                        @endif
                    </a>
                @endif

                <a href="{{ auth()->user()->role === 'admin' ? url('/admin/dashboard') : url('/dashboard') }}"
                    class="nav-account-link">
                    {{ auth()->user()->name }}
                </a>

                <form method="POST" action="{{ route('logout') }}" style="display:inline;">
                    @csrf
                    <button type="submit" class="btn-logout">Sign Out</button>
                </form>

                <a href="{{ auth()->user()->role === 'admin' ? url('/admin/dashboard') : url('/dashboard') }}"
                    class="btn-outline">My Dashboard
                </a>
            @else
                <a href="{{ route('login') }}" class="nav-account-link">Sign In</a>
                <a href="{{ url('/register') }}" class="btn-register">Register</a>
            @endauth
        </div>
    </nav>

    @if(session('success'))
        <div class="flash flash-success">
            ✅ {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="flash flash-error">
            ❌ {{ session('error') }}
        </div>
    @endif

    @yield('content')

    <footer class="main-footer">
        <div class="footer-inner">
            <div class="footer-top">
                <div class="footer-brand">
                    <div class="footer-logo">
                        <div class="footer-logo-icon">
                            <img src="{{ asset('pawsome-pets-logo.png') }}" alt="PAWsome Pets Logo">
                        </div>
                        <span class="footer-logo-text">PAWsome <span>Pets</span></span>
                    </div>
                    <p class="footer-motto">
                        "Every pet deserves a loving home,<br>
                        and every home deserves a loving pet."
                    </p>
                </div>

                <div class="footer-col">
                    <h4>Quick Links</h4>
                    <ul>
                        <li><a href="{{ url('/pets') }}">Adopt a Pet</a></li>
                        <li><a href="{{ url('/#how-it-works') }}">How It Works</a></li>
                    </ul>
                </div>

                <div class="footer-col">
                    <h4>Support</h4>
                    <ul>
                        <li><a href="{{ url('/about') }}">About Us</a></li>
                        <li><a href="{{ url('/contact') }}">Contact</a></li>
                        <li><a href="{{ url('/faq') }}">FAQ</a></li>
                        @auth
                            <li>
                                <a
                                    href="{{ auth()->user()->role === 'admin' ? url('/admin/dashboard') : url('/dashboard') }}">
                                    My Dashboard
                                </a>
                            </li>
                        @else
                            <li><a href="{{ route('login') }}">Sign In</a></li>
                            <li><a href="{{ route('register') }}">Register</a></li>
                        @endauth
                    </ul>
                </div>
            </div>

            <div class="footer-bottom">
                <p class="footer-copy">
                    © {{ date('Y') }} <a href="{{ url('/') }}">PAWsome Pets</a>.
                    Made with <span class="footer-heart">love</span> for animals everywhere.
                </p>
            </div>
        </div>
    </footer>
    @yield('scripts')
</body>

</html>