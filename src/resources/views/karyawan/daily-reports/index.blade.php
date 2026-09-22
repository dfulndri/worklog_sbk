@extends('layouts.karyawan')

@section('title', 'Daily Report')
@section('search-placeholder', 'Cari daily report...')

@section('page-content')
    <div class="page-heading">
        <div class="page-heading-copy">
            <span class="page-icon"><i class="bi bi-journal-text" aria-hidden="true"></i></span>
            <div>
                <p class="eyebrow mb-1">Aktivitas Harian</p>
                <h1 class="h3 mb-1">Daily Report</h1>
                <p class="text-muted mb-0">Riwayat laporan aktivitas harian yang sudah kamu isi.</p>
            </div>
        </div>
        <div class="heading-actions">
            <a href="{{ route('karyawan.daily-reports.create') }}" class="btn btn-primary btn-sm">
                <i class="bi bi-plus-lg" aria-hidden="true"></i> Isi Daily Report
            </a>
        </div>
    </div>

    <section class="panel mt-3">
        <div class="panel-header">
            <div>
                <h2 class="h5 mb-1 section-title"><i class="bi bi-journal-text" aria-hidden="true"></i><span>Riwayat
                        Laporan</span></h2>
                <p class="text-muted mb-0">Total {{ $reports->total() }} laporan.</p>
            </div>
        </div>

        @forelse ($reports as $report)
            <div class="activity-item">
                <span class="activity-dot bg-primary"></span>
                <div class="d-flex justify-content-between w-100 gap-3">
                    <div>
                        <p class="mb-1 fw-semibold">
                            {{ $report->report_date->format('d M Y') }} —
                            {{ $report->jobTask->client->name ?? '-' }}
                            ({{ $report->jobTask->documentType->name ?? '-' }})
                            <span class="badge text-bg-secondary">{{ $report->progress }}%</span>
                        </p>
                        <p class="text-muted small mb-1">{{ $report->description }}</p>
                        @if ($report->obstacle)
                            <p class="text-danger small mb-1"><i class="bi bi-exclamation-triangle"></i> Kendala:
                                {{ $report->obstacle }}</p>
                        @endif
                        @if ($report->next_plan)
                            <p class="text-muted small mb-0"><i class="bi bi-arrow-right-circle"></i> Rencana
                                berikutnya:
                                {{ $report->next_plan }}</p>
                        @endif
                    </div>
                    <div class="d-flex align-items-start gap-1 flex-shrink-0">
                        <a class="btn btn-light btn-sm" href="{{ route('karyawan.daily-reports.edit', $report) }}">
                            <i class="bi bi-pencil" aria-hidden="true"></i>
                        </a>
                        <form action="{{ route('karyawan.daily-reports.destroy', $report) }}" method="POST"
                            onsubmit="return confirm('Hapus laporan tanggal {{ $report->report_date->format('d M Y') }}?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-light btn-sm text-danger">
                                <i class="bi bi-trash" aria-hidden="true"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <p class="text-muted text-center py-4 mb-0">Kamu belum pernah mengisi daily report.</p>
        @endforelse

        @if ($reports->hasPages())
            <div class="p-3">
                {{ $reports->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </section>
@endsection
