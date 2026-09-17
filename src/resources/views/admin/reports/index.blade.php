@extends('layouts.admin')

@section('title', 'Laporan')

@section('page-content')
    <div class="page-heading">
        <div class="page-heading-copy">
            <span class="page-icon"><i class="bi bi-bar-chart-line" aria-hidden="true"></i></span>
            <div>
                <p class="eyebrow mb-1">Laporan</p>
                <h1 class="h3 mb-1">Laporan Aktivitas Harian</h1>
                <p class="text-muted mb-0">Rekap seluruh daily report, bisa difilter dan diekspor.</p>
            </div>
        </div>
        <div class="heading-actions">
            <a href="{{ route('admin.reports.export-csv', request()->query()) }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-file-earmark-spreadsheet" aria-hidden="true"></i> Export Excel (CSV)
            </a>
            <a href="{{ route('admin.reports.export-pdf', request()->query()) }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-file-earmark-pdf" aria-hidden="true"></i> Export PDF
            </a>
        </div>
    </div>

    <section class="panel mt-3">
        <div class="panel-header">
            <div>
                <h2 class="h5 mb-1 section-title"><i class="bi bi-funnel" aria-hidden="true"></i><span>Filter</span></h2>
            </div>
        </div>

        <form method="GET" class="row g-2 px-3 pb-3">
            <div class="col-auto">
                <select name="client_id" class="form-select form-select-sm">
                    <option value="">Semua Klien</option>
                    @foreach ($clients as $client)
                        <option value="{{ $client->id }}" @selected(request('client_id') == $client->id)>{{ $client->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-auto">
                <select name="employee_id" class="form-select form-select-sm">
                    <option value="">Semua Karyawan</option>
                    @foreach ($employees as $employee)
                        <option value="{{ $employee->id }}" @selected(request('employee_id') == $employee->id)>{{ $employee->user->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-auto">
                <input type="date" name="date_from" class="form-control form-control-sm"
                    value="{{ request('date_from') }}" placeholder="Dari tanggal">
            </div>
            <div class="col-auto">
                <input type="date" name="date_to" class="form-control form-control-sm" value="{{ request('date_to') }}"
                    placeholder="Sampai tanggal">
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-primary btn-sm">Terapkan</button>
                <a href="{{ route('admin.reports.index') }}" class="btn btn-outline-secondary btn-sm">Reset</a>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th scope="col">Tanggal</th>
                        <th scope="col">Karyawan</th>
                        <th scope="col">Klien</th>
                        <th scope="col">Jenis Dokumen</th>
                        <th scope="col">Progress</th>
                        <th scope="col">Uraian</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($reports as $report)
                        <tr>
                            <td>{{ $report->report_date->format('d M Y') }}</td>
                            <td>{{ $report->user->name ?? '-' }}</td>
                            <td>{{ $report->jobTask->client->name ?? '-' }}</td>
                            <td>{{ $report->jobTask->documentType->name ?? '-' }}</td>
                            <td>{{ $report->progress }}%</td>
                            <td class="text-truncate" style="max-width: 320px;">{{ $report->description }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">Tidak ada data untuk filter ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($reports->hasPages())
            <div class="p-3">
                {{ $reports->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </section>
@endsection
