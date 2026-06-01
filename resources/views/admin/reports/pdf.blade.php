<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>PAWsome Pets Admin Report</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #333; }
        h1, h2 { margin: 0 0 0.5rem 0; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 1rem; }
        th, td { padding: 0.5rem; border: 1px solid #ccc; text-align: left; }
        th { background: #f7f7f7; }
        .summary-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 0.75rem; margin-bottom: 1rem; }
        .summary-card { padding: 0.75rem; border: 1px solid #ddd; border-radius: 6px; }
        .summary-label { font-size: 0.85rem; color: #666; }
        .summary-value { font-size: 1.4rem; font-weight: 700; margin-top: 0.25rem; }
    </style>
</head>
<body>
    <h1>PAWsome Pets Admin Report</h1>
    <p>Generated: {{ now()->format('F d, Y H:i') }}</p>

    <div class="summary-grid">
        <div class="summary-card">
            <div class="summary-label">Total Pets</div>
            <div class="summary-value">{{ $reportData['totalPets'] }}</div>
        </div>
        <div class="summary-card">
            <div class="summary-label">Available Pets</div>
            <div class="summary-value">{{ $reportData['availablePets'] }}</div>
        </div>
        <div class="summary-card">
            <div class="summary-label">Adopted Pets</div>
            <div class="summary-value">{{ $reportData['adoptedPets'] }}</div>
        </div>
        <div class="summary-card">
            <div class="summary-label">Pending Requests</div>
            <div class="summary-value">{{ $reportData['pendingRequests'] }}</div>
        </div>
        <div class="summary-card">
            <div class="summary-label">Total Users</div>
            <div class="summary-value">{{ $reportData['totalUsers'] }}</div>
        </div>
        <div class="summary-card">
            <div class="summary-label">Average Rating</div>
            <div class="summary-value">{{ $reportData['averageRating'] }}</div>
        </div>
    </div>

    <h2>Request Status</h2>
    <table>
        <thead>
            <tr><th>Status</th><th>Count</th></tr>
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

    <h2>Pets by Species</h2>
    <table>
        <thead>
            <tr><th>Species</th><th>Count</th></tr>
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

    <h2>Latest Adoption Requests</h2>
    <table>
        <thead>
            <tr><th>Pet</th><th>Applicant</th><th>Status</th><th>Submitted</th></tr>
        </thead>
        <tbody>
            @foreach($reportData['recentRequests'] as $request)
            <tr>
                <td>{{ $request->pet->name }}</td>
                <td>{{ $request->user->name }}</td>
                <td>{{ ucfirst($request->status) }}</td>
                <td>{{ $request->created_at->format('Y-m-d') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
