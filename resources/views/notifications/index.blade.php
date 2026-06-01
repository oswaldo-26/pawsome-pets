@extends('layouts.app')

@section('title', 'Notifications – PAWsome Pets')

@section('content')
<section class="section">
    <div class="section-inner">
        <div class="dashboard-header">
            <div>
                <h1>Your Notifications</h1>
                <p>All your updates are here in one place. Mark them read or review the latest adoption status messages.</p>
            </div>
            <div style="display:flex; gap:1rem; flex-wrap:wrap; align-items:center;">
                <a href="{{ url('/dashboard') }}" class="btn-outline">Back to dashboard</a>
                @if(($notifications ?? collect())->where('is_read', false)->count() > 0)
                    <form method="POST" action="{{ route('notifications.readAll') }}" style="margin:0;">
                        @csrf
                        <button type="submit" class="btn-coral">Mark all read</button>
                    </form>
                @endif
            </div>
        </div>

        <div class="dashboard-card">
            <div class="dashboard-card-header">
                <h3 class="dashboard-card-title">{{ ($notifications ?? collect())->where('is_read', false)->count() }} Unread</h3>
                <span class="section-note">Showing your latest notifications first.</span>
            </div>

            @forelse($notifications ?? [] as $notif)
                <div class="notif-item {{ $notif->is_read ? '' : 'notif-item--unread' }}" style="margin-bottom:1rem;">
                    <div class="notif-icon notif-icon--{{ $notif->type }}">
                        @switch($notif->type)
                            @case('approved')  ✅ @break
                            @case('rejected')  ❌ @break
                            @case('pending')   ⏳ @break
                            @case('completed') 🏡 @break
                            @default          🔔 @break
                        @endswitch
                    </div>
                    <div class="notif-body">
                        <p class="notif-title">{{ $notif->title }}</p>
                        <p class="notif-message">{{ $notif->message }}</p>
                        <span class="notif-time">{{ $notif->created_at->diffForHumans() }}</span>
                    </div>
                    @if(!$notif->is_read)
                        <div class="notif-dot"></div>
                    @endif
                </div>
            @empty
                <div class="dashboard-empty" style="padding:2rem 0; text-align:center;">
                    <span>🔔</span>
                    <h3 style="margin-top:0.75rem;">No notifications yet</h3>
                    <p style="margin-top:0.5rem;">When something changes, you'll see it here.</p>
                </div>
            @endforelse
        </div>
    </div>
</section>
@endsection
