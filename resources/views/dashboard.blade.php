@extends('layouts.app')

@section('title', 'My Dashboard – PAWsome Pets')

@section('content')

<section class="section">
    <div class="section-inner">

        <div class="dashboard-header">
            <div class="dashboard-welcome">
                <div class="dashboard-avatar">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <div>
                    <h1>Welcome back, {{ explode(' ', auth()->user()->name)[0] }}! 🐾</h1>
                    <p>{{ auth()->user()->email }}</p>
                </div>
            </div>
            <a href="{{ url('/pets') }}" class="btn-coral">Browse More Pets →</a>
        </div>

        <div class="dashboard-stats">
            <div class="dashboard-stat-card">
                <div class="dashboard-stat-icon">📋</div>
                <div>
                    <div class="dashboard-stat-num">{{ collect($requests)->count() }}</div>
                    <div class="dashboard-stat-label">Total Applications</div>
                </div>
            </div>
            <div class="dashboard-stat-card">
                <div class="dashboard-stat-icon">⏳</div>
                <div>
                    <div class="dashboard-stat-num">{{ collect($requests)->where('status', 'pending')->count() }}</div>
                    <div class="dashboard-stat-label">Pending</div>
                </div>
            </div>
            <div class="dashboard-stat-card">
                <div class="dashboard-stat-icon">✅</div>
                <div>
                    <div class="dashboard-stat-num">{{ collect($requests)->where('status', 'approved')->count() }}</div>
                    <div class="dashboard-stat-label">Approved</div>
                </div>
            </div>
            <div class="dashboard-stat-card">
                <div class="dashboard-stat-icon">🔔</div>
                <div>
                    <div class="dashboard-stat-num">{{ $unreadNotifications ?? 0 }}</div>
                    <div class="dashboard-stat-label">Unread Notifications</div>
                </div>
            </div>
        </div>

        <div class="dashboard-grid">

            <div class="dashboard-main">
                <div class="dashboard-section-header">
                    <h2>My Adoption Applications</h2>
                    <a href="{{ url('/pets') }}" class="dashboard-section-link">+ Apply for Another</a>
                </div>

                @forelse($requests ?? [] as $request)
                <div class="request-card">
                    <div class="request-pet-img">
                        @if($request->pet->photo)
                            <img src="{{ asset('storage/' . $request->pet->photo) }}"
                                 alt="{{ $request->pet->name }}">
                        @else
                            <div class="request-pet-placeholder">
                                <span>{{ $request->pet->species === 'dog' ? '🐶' : ($request->pet->species === 'cat' ? '🐱' : '🐹') }}</span>
                            </div>
                        @endif
                    </div>
                    <div class="request-info">
                        <div class="request-info-top">
                            <div>
                                <h3>{{ $request->pet->name }}</h3>
                                <p class="request-meta">
                                    {{ ucfirst($request->pet->breed ?? $request->pet->species) }} ·
                                    {{ ucfirst($request->pet->age_group) }} ·
                                    {{ ucfirst($request->pet->gender) }}
                                </p>
                            </div>
                            <span class="request-status request-status--{{ $request->status }}">
                                @switch($request->status)
                                    @case('pending')   ⏳ Pending   @break
                                    @case('approved')  ✅ Approved  @break
                                    @case('rejected')  ❌ Rejected  @break
                                    @case('completed') 🏡 Completed @break
                                @endswitch
                            </span>
                        </div>
                        <p class="request-reason">
                            "{{ Str::limit($request->reason, 100) }}"
                        </p>
                        <div class="request-footer">
                            <span class="request-date">
                                Applied {{ $request->created_at->diffForHumans() }}
                            </span>
                            <a href="{{ url('/pets/' . $request->pet->id) }}"
                               class="request-view-link">View Pet →</a>
                        </div>
                    </div>
                </div>
                @empty
                <div class="dashboard-empty">
                    <span>🐾</span>
                    <h3>No applications yet</h3>
                    <p>You haven't applied to adopt any pets yet. Browse our gallery and find your perfect match!</p>
                </div>
                @endforelse
            </div>

            <div class="dashboard-side">

                <div class="dashboard-card">
                    <h3 class="dashboard-card-title">👤 My Profile</h3>
                    <div class="profile-info">
                        <div class="profile-row">
                            <span class="profile-label">Name</span>
                            <span class="profile-value">{{ auth()->user()->name }}</span>
                        </div>
                        <div class="profile-row">
                            <span class="profile-label">Email</span>
                            <span class="profile-value">{{ auth()->user()->email }}</span>
                        </div>
                        <div class="profile-row">
                            <span class="profile-label">Phone</span>
                            <span class="profile-value">{{ auth()->user()->phone ?? '—' }}</span>
                        </div>
                        <div class="profile-row">
                            <span class="profile-label">Address</span>
                            <span class="profile-value">{{ auth()->user()->address ?? '—' }}</span>
                        </div>
                        <div class="profile-row">
                            <span class="profile-label">Member Since</span>
                            <span class="profile-value">{{ auth()->user()->created_at->format('M d, Y') }}</span>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>

@endsection