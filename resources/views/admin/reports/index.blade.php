@extends('layouts.app')

@section('title', 'Admin Reports – PAWsome Pets')

@section('content')
<section class="section">
    <div class="section-inner">
        <div class="dashboard-header">
            <div class="dashboard-welcome">
                <div class="dashboard-avatar" style="background:var(--coral);">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <div>
                    <h1>Admin Reports 📊</h1>
                    <p>{{ auth()->user()->name }} · {{ now()->format('F d, Y') }}</p>
                </div>
            </div>
            <div class="dashboard-header-actions">
                <a href="{{ route('admin.reports.export', 'csv') }}" class="btn-coral">Export CSV</a>
                <a href="{{ route('admin.reports.export', 'pdf') }}" class="btn-coral">Export PDF</a>
            </div>
        </div>

        <div class="dashboard-stats">
            <div class="dashboard-stat-card">
                <div class="dashboard-stat-icon">🐾</div>
                <div>
                    <div class="dashboard-stat-num">{{ $reportData['totalPets'] }}</div>
                    <div class="dashboard-stat-label">Total Pets</div>
                </div>
            </div>
            <div class="dashboard-stat-card">
                <div class="dashboard-stat-icon">✅</div>
                <div>
                    <div class="dashboard-stat-num">{{ $reportData['availablePets'] }}</div>
                    <div class="dashboard-stat-label">Available Pets</div>
                </div>
            </div>
            <div class="dashboard-stat-card">
                <div class="dashboard-stat-icon">🏡</div>
                <div>
                    <div class="dashboard-stat-num">{{ $reportData['adoptedPets'] }}</div>
                    <div class="dashboard-stat-label">Adopted Pets</div>
                </div>
            </div>
            <div class="dashboard-stat-card">
                <div class="dashboard-stat-icon">⏳</div>
                <div>
                    <div class="dashboard-stat-num">{{ $reportData['pendingRequests'] }}</div>
                    <div class="dashboard-stat-label">Pending Requests</div>
                </div>
            </div>
        </div>

        <div class="admin-grid">
            <div class="admin-main">
                <div class="admin-section-header">
                    <h2>Request Summary</h2>
                    <span class="admin-count">{{ array_sum($reportData['requestStatus']) }} total</span>
                </div>

                <div class="admin-table-wrap">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>Status</th>
                                <th>Count</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($reportData['requestStatus'] as $status => $count)
                            <tr>
                                <td>{{ ucfirst($status) }}</td>
                                <td>{{ $count }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="admin-section-header" style="margin-top:2.5rem;">
                    <h2>Pets by Species</h2>
                    <span class="admin-count">{{ array_sum($reportData['petsBySpecies']) }} total</span>
                </div>

                <div class="admin-table-wrap">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>Species</th>
                                <th>Count</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($reportData['petsBySpecies'] as $species => $count)
                            <tr>
                                <td>{{ ucfirst($species) }}</td>
                                <td>{{ $count }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="admin-section-header" style="margin-top:2.5rem;">
                    <h2>Latest Adoption Requests</h2>
                    <span class="admin-count">{{ $reportData['recentRequests']->count() }} recent</span>
                </div>

                <div class="admin-table-wrap">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>Pet</th>
                                <th>Applicant</th>
                                <th>Status</th>
                                <th>Submitted</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($reportData['recentRequests'] as $request)
                            <tr>
                                <td>{{ $request->pet->name }}</td>
                                <td>{{ $request->user->name }}</td>
                                <td>{{ ucfirst($request->status) }}</td>
                                <td>{{ $request->created_at->format('M d, Y') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="dashboard-side">
                <div class="dashboard-card">
                    <div class="dashboard-card-header">
                        <h3 class="dashboard-card-title">More Admin Metrics</h3>
                    </div>
                    <p><strong>Total users:</strong> {{ $reportData['totalUsers'] }}</p>
                    <p><strong>Average rating:</strong> {{ $reportData['averageRating'] }}</p>
                    <p>Use the buttons above to download this report as CSV or PDF.</p>
                    <p><a href="{{ route('admin.dashboard') }}" class="dashboard-section-link">Back to dashboard</a></p>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
