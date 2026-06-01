<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdoptionRequest;
use App\Models\Pet;
use App\Models\Rating;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index()
    {
        $reportData = $this->buildReportData();

        return view('admin.reports.index', compact('reportData'));
    }

    public function export(string $format)
    {
        $reportData = $this->buildReportData();
        $filename = 'pawsome-admin-report-' . now()->format('YmdHis');

        if ($format === 'csv') {
            $callback = function () use ($reportData) {
                $handle = fopen('php://output', 'w');

                fputcsv($handle, ['Metric', 'Value']);
                fputcsv($handle, ['Total pets', $reportData['totalPets']]);
                fputcsv($handle, ['Available pets', $reportData['availablePets']]);
                fputcsv($handle, ['Adopted pets', $reportData['adoptedPets']]);
                fputcsv($handle, ['Pending requests', $reportData['pendingRequests']]);
                fputcsv($handle, ['Total users', $reportData['totalUsers']]);
                fputcsv($handle, ['Average rating', $reportData['averageRating']]);
                fputcsv($handle, []);
                fputcsv($handle, ['Requests by status', 'Count']);

                foreach ($reportData['requestStatus'] as $status => $count) {
                    fputcsv($handle, [ucfirst($status), $count]);
                }

                fputcsv($handle, []);
                fputcsv($handle, ['Pets by species', 'Count']);

                foreach ($reportData['petsBySpecies'] as $species => $count) {
                    fputcsv($handle, [ucfirst($species), $count]);
                }

                fputcsv($handle, []);
                fputcsv($handle, ['Latest requests']);
                fputcsv($handle, ['Pet', 'Applicant', 'Status', 'Submitted']);

                foreach ($reportData['recentRequests'] as $request) {
                    fputcsv($handle, [
                        $request->pet->name,
                        $request->user->name,
                        ucfirst($request->status),
                        $request->created_at->format('Y-m-d H:i:s'),
                    ]);
                }

                fclose($handle);
            };

            return Response::streamDownload($callback, $filename . '.csv', [
                'Content-Type' => 'text/csv',
            ]);
        }

        if ($format === 'pdf') {
            if (! class_exists(Pdf::class)) {
                abort(503, 'PDF export is currently unavailable. Install the laravel-dompdf package to enable this feature.');
            }

            $pdf = Pdf::loadView('admin.reports.pdf', compact('reportData'));

            return $pdf->download($filename . '.pdf');
        }

        abort(404);
    }

    private function buildReportData(): array
    {
        $requestStatus = AdoptionRequest::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();

        $petsBySpecies = Pet::select('species', DB::raw('count(*) as total'))
            ->groupBy('species')
            ->pluck('total', 'species')
            ->toArray();

        return [
            'totalPets' => Pet::count(),
            'availablePets' => Pet::where('status', 'available')->count(),
            'adoptedPets' => Pet::where('status', 'adopted')->count(),
            'pendingRequests' => AdoptionRequest::where('status', 'pending')->count(),
            'totalUsers' => User::count(),
            'averageRating' => round(Rating::avg('rating') ?? 0, 1),
            'requestStatus' => $requestStatus,
            'petsBySpecies' => $petsBySpecies,
            'recentRequests' => AdoptionRequest::with(['pet', 'user'])
                ->latest()
                ->take(20)
                ->get(),
        ];
    }
}
