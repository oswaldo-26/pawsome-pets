@extends('layouts.app')

@section('title', 'Admin Dashboard – PAWsome Pets')

@section('content')

<section class="section">
    <div class="section-inner">

        <div class="dashboard-header">
            <div class="dashboard-welcome">
                <div class="dashboard-avatar" style="background:var(--coral);">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <div>
                    <h1>Admin Panel 🛠️</h1>
                    <p>{{ auth()->user()->name }} · {{ now()->format('F d, Y') }}</p>
                </div>
            </div>
            <div class="dashboard-header-actions">
                <a href="{{ route('admin.reports.index') }}" class="btn-coral">View Reports</a>
                <a href="{{ route('admin.pets.create') }}" class="btn-coral">+ Add New Pet</a>
            </div>
        </div>

        <div class="dashboard-stats">
            <div class="dashboard-stat-card">
                <div class="dashboard-stat-icon">🐾</div>
                <div>
                    <div class="dashboard-stat-num">{{ $totalPets ?? 0 }}</div>
                    <div class="dashboard-stat-label">Total Pets</div>
                </div>
            </div>
            <div class="dashboard-stat-card">
                <div class="dashboard-stat-icon">✅</div>
                <div>
                    <div class="dashboard-stat-num">{{ $availablePets ?? 0 }}</div>
                    <div class="dashboard-stat-label">Available</div>
                </div>
            </div>
            <div class="dashboard-stat-card">
                <div class="dashboard-stat-icon">⏳</div>
                <div>
                    <div class="dashboard-stat-num">{{ $pendingRequests ?? 0 }}</div>
                    <div class="dashboard-stat-label">Pending Requests</div>
                </div>
            </div>
            <div class="dashboard-stat-card">
                <div class="dashboard-stat-icon">🏡</div>
                <div>
                    <div class="dashboard-stat-num">{{ $adoptedPets ?? 0 }}</div>
                    <div class="dashboard-stat-label">Adopted</div>
                </div>
            </div>
        </div>

        <div class="admin-grid">

            <div class="admin-main">

                <div class="admin-section-header">
                    <h2>⏳ Pending Requests</h2>
                    <span class="admin-count">{{ ($pendingRequestsList ?? collect())->count() }} pending</span>
                </div>

                @forelse($pendingRequestsList ?? [] as $request)
                <div class="admin-request-card">
                    <div class="admin-request-pet-img">
                        @if($request->pet->photo)
                            <img src="{{ asset('storage/' . $request->pet->photo) }}"
                                 alt="{{ $request->pet->name }}">
                        @else
                            <div class="request-pet-placeholder">
                                <span>{{ $request->pet->species === 'dog' ? '🐶' : ($request->pet->species === 'cat' ? '🐱' : '🐹') }}</span>
                            </div>
                        @endif
                    </div>
                    <div class="admin-request-info">
                        <div class="admin-request-top">
                            <div>
                                <h3>{{ $request->pet->name }}</h3>
                                <p class="request-meta">
                                    {{ ucfirst($request->pet->breed ?? $request->pet->species) }} ·
                                    {{ ucfirst($request->pet->age_group) }}
                                </p>
                            </div>
                            <span class="request-status request-status--pending">⏳ Pending</span>
                        </div>

                        {{-- Applicant Info --}}
                        <div class="admin-applicant">
                            <div class="admin-applicant-avatar">
                                {{ strtoupper(substr($request->user->name, 0, 1)) }}
                            </div>
                            <div>
                                <p class="admin-applicant-name">{{ $request->user->name }}</p>
                                <p class="admin-applicant-meta">
                                    {{ $request->user->email }} ·
                                    {{ $request->home_type ? ucfirst($request->home_type) : 'Home type N/A' }}
                                    {{ $request->has_yard ? '· Has yard' : '' }}
                                    {{ $request->has_children ? '· Has children' : '' }}
                                    {{ $request->has_other_pets ? '· Has other pets' : '' }}
                                </p>
                            </div>
                        </div>

                        @if($request->reason)
                        <p class="admin-request-reason">
                            "{{ Str::limit($request->reason, 120) }}"
                        </p>
                        @endif

                        <div class="admin-request-footer">
                            <span class="request-date">{{ $request->created_at->diffForHumans() }}</span>
                            <div class="admin-actions">
                                {{-- Approve --}}
                                <form method="POST"
                                      action="{{ url('/admin/adoption/' . $request->id . '/approve') }}"
                                      style="display:inline;">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn-approve">✅ Approve</button>
                                </form>
                                {{-- Reject --}}
                                <form method="POST"
                                      action="{{ url('/admin/adoption/' . $request->id . '/reject') }}"
                                      style="display:inline;">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn-reject">❌ Reject</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="dashboard-empty">
                    <span>🎉</span>
                    <h3>All caught up!</h3>
                    <p>No pending adoption requests right now.</p>
                </div>
                @endforelse

                {{-- All Requests History --}}
                @if(($allRequests ?? collect())->count() > 0)
                <div class="admin-section-header" style="margin-top:2.5rem;">
                    <h2>📋 All Requests</h2>
                    <span class="admin-count">{{ ($allRequests ?? collect())->count() }} total</span>
                </div>

                <div class="admin-table-wrap">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>Pet</th>
                                <th>Applicant</th>
                                <th>Status</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($allRequests as $req)
                            <tr>
                                <td>
                                    <strong>{{ $req->pet->name }}</strong>
                                    <span class="table-meta">{{ ucfirst($req->pet->species) }}</span>
                                </td>
                                <td>
                                    <strong>{{ $req->user->name }}</strong>
                                    <span class="table-meta">{{ $req->user->email }}</span>
                                </td>
                                <td>
                                    <span class="request-status request-status--{{ $req->status }}">
                                        @switch($req->status)
                                            @case('pending')   ⏳ Pending   @break
                                            @case('approved')  ✅ Approved  @break
                                            @case('rejected')  ❌ Rejected  @break
                                            @case('completed') 🏡 Completed @break
                                        @endswitch
                                    </span>
                                </td>
                                <td class="table-meta">{{ $req->created_at->format('M d, Y') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif

            </div>

            <div class="dashboard-side">

                <div class="dashboard-card">
                    <div class="dashboard-card-header">
                        <h3 class="dashboard-card-title">🐾 Manage Pets</h3>
                        <a href="{{ url('/admin/pets/create') }}" class="dashboard-section-link">+ Add Pet</a>
                    </div>

                    @forelse($pets ?? [] as $pet)
                    <div class="admin-pet-row">
                        <div class="admin-pet-row-img">
                            @if($pet->photo)
                                <img src="{{ asset('storage/' . $pet->photo) }}" alt="{{ $pet->name }}">
                            @else
                                <span>{{ $pet->species === 'dog' ? '🐶' : ($pet->species === 'cat' ? '🐱' : '🐹') }}</span>
                            @endif
                        </div>
                        <div class="admin-pet-row-info">
                            <p class="admin-pet-row-name">{{ $pet->name }}</p>
                            <p class="admin-pet-row-meta">{{ ucfirst($pet->species) }} · {{ ucfirst($pet->status) }}</p>
                        </div>
                        <div class="admin-pet-row-actions">
                            <a href="{{ route('admin.pets.edit', $pet) }}"
                               class="admin-icon-btn admin-icon-btn--edit" title="Edit">✏️</a>
                            <form method="POST"
                                  action="{{ route('admin.pets.destroy', $pet) }}"
                                  style="display:inline;"
                                  onsubmit="return confirm('Remove {{ $pet->name }}?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="admin-icon-btn admin-icon-btn--delete"
                                        title="Delete">🗑️</button>
                            </form>
                        </div>
                    </div>
                    @empty
                    <div class="dashboard-empty" style="padding:1.5rem 0;">
                        <span>🐾</span>
                        <p style="margin-top:0.5rem;">No pets added yet.</p>
                    </div>
                    @endforelse
                </div>

                <div class="dashboard-card">
                    <div class="dashboard-card-header">
                        <h3 class="dashboard-card-title">🔔 Recent Notifications Sent</h3>
                    </div>

                    @forelse($recentNotifications ?? [] as $notif)
                    <div class="notif-item notif-item--{{ $notif->type }}">
                        <div class="notif-icon notif-icon--{{ $notif->type }}">
                            @switch($notif->type)
                                @case('approved')  ✅ @break
                                @case('rejected')  ❌ @break
                                @case('pending')   ⏳ @break
                                @case('completed') 🏡 @break
                            @endswitch
                        </div>
                        <div class="notif-body">
                            <p class="notif-title">{{ $notif->title }}</p>
                            <p class="notif-message">To: {{ $notif->user->name }}</p>
                            <span class="notif-time">{{ $notif->created_at->diffForHumans() }}</span>
                        </div>
                    </div>
                    @empty
                    <div class="dashboard-empty" style="padding:1.5rem 0;">
                        <span>🔔</span>
                        <p style="margin-top:0.5rem;">No notifications sent yet.</p>
                    </div>
                    @endforelse
                </div>

            </div>
        </div>
    </div>
</section>

@endsection