<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\DailyReport;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $reports = $this->filteredQuery($request)->paginate(15)->withQueryString();

        $clients = Client::orderBy('name')->get();
        $employees = Employee::with('user')->get();

        return view('admin.reports.index', compact('reports', 'clients', 'employees'));
    }

    public function exportCsv(Request $request): StreamedResponse
    {
        $reports = $this->filteredQuery($request)->get();

        $filename = 'daily-reports-' . now()->format('Ymd-His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($reports) {
            $handle = fopen('php://output', 'w');

            // BOM supaya karakter dibaca benar saat dibuka di Excel.
            fwrite($handle, "\xEF\xBB\xBF");

            fputcsv($handle, [
                'Tanggal',
                'Karyawan',
                'Klien',
                'Jenis Dokumen',
                'Progress (%)',
                'Uraian',
                'Kendala',
                'Rencana Berikutnya',
            ]);

            foreach ($reports as $report) {
                fputcsv($handle, [
                    $report->report_date->format('d-m-Y'),
                    $report->user->name ?? '-',
                    $report->jobTask->client->name ?? '-',
                    $report->jobTask->documentType->name ?? '-',
                    $report->progress,
                    $report->description,
                    $report->obstacle,
                    $report->next_plan,
                ]);
            }

            fclose($handle);
        };

        return Response::stream($callback, 200, $headers);
    }

    public function exportPdf(Request $request)
    {
        $reports = $this->filteredQuery($request)->get();

        // Membutuhkan package: composer require barryvdh/laravel-dompdf
        $pdf = app('dompdf.wrapper')
            ->loadView('admin.reports.pdf', compact('reports'))
            ->setPaper('a4', 'landscape');

        return $pdf->download('daily-reports-' . now()->format('Ymd-His') . '.pdf');
    }

    private function filteredQuery(Request $request)
    {
        return DailyReport::with(['user', 'jobTask.client', 'jobTask.documentType'])
            ->when($request->filled('client_id'), function ($q) use ($request) {
                $q->whereHas('jobTask', fn($j) => $j->where('client_id', $request->client_id));
            })
            ->when($request->filled('employee_id'), function ($q) use ($request) {
                $q->whereHas('jobTask', fn($j) => $j->where('employee_id', $request->employee_id));
            })
            ->when($request->filled('date_from'), fn($q) => $q->whereDate('report_date', '>=', $request->date_from))
            ->when($request->filled('date_to'), fn($q) => $q->whereDate('report_date', '<=', $request->date_to))
            ->latest('report_date');
    }
}
