@extends('layouts.admin')

@section('title', 'Detail Pekerjaan')

@section('page-content')
    <div class="page-heading">
        <div class="page-heading-copy">
            <span class="page-icon"><i class="bi bi-kanban" aria-hidden="true"></i></span>
            <div>
                <p class="eyebrow mb-1">Monitoring</p>
                <h1 class="h3 mb-1">{{ $job->client->name ?? '-' }} — {{ $job->documentType->name ?? '-' }}</h1>
                <p class="text-muted mb-0">{{ $job->title ?? 'Detail pekerjaan dan histori aktivitas harian.' }}</p>
            </div>
        </div>
        <div class="heading-actions">
            <a href="{{ route('admin.jobs.edit', $job) }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-pencil" aria-hidden="true"></i> Edit
            </a>
            <a href="{{ route('admin.jobs.index') }}" class="btn btn-light btn-sm">Kembali</a>
        </div>
    </div>

    <section class="row g-3 mt-1">
        <div class="col-12 col-xl-4">
            <div class="panel h-100">
                <h2 class="h5 mb-3 section-title"><i class="bi bi-info-circle" aria-hidden="true"></i><span>Informasi
                        Pekerjaan</span></h2>
                <dl class="row mb-0">
                    <dt class="col-5 text-muted">PIC</dt>
                    <dd class="col-7">{{ $job->employee->user->name ?? '-' }}</dd>

                    <dt class="col-5 text-muted">Tenaga Ahli</dt>
                    <dd class="col-7">{{ $job->expert->name ?? '-' }}</dd>

                    <dt class="col-5 text-muted">Tahapan</dt>
                    <dd class="col-7"><span class="badge text-bg-secondary">{{ ucfirst($job->stage) }}</span></dd>

                    <dt class="col-5 text-muted">Progress</dt>
                    <dd class="col-7">
                        <div class="progress" style="height: 6px;">
                            <div class="progress-bar" role="progressbar" style="width: {{ $job->progress }}%"></div>
                        </div>
                        <small class="text-muted">{{ $job->progress }}%</small>
                    </dd>

                    <dt class="col-5 text-muted">Deadline</dt>
                    <dd class="col-7">{{ $job->deadline?->format('d M Y') ?? '-' }}</dd>
                </dl>
            </div>
        </div>

        <div class="col-12 col-xl-8">
            <div class="panel h-100">
                <div class="panel-header">
                    <div>
                        <h2 class="h5 mb-1 section-title"><i class="bi bi-journal-text" aria-hidden="true"></i><span>Histori
                                Daily Report</span></h2>
                        <p class="text-muted mb-0">Aktivitas harian yang dilaporkan PIC untuk pekerjaan ini.</p>
                    </div>
                </div>

                @forelse ($job->dailyReports as $report)
                    <div class="activity-item">
                        <span class="activity-dot bg-primary"></span>
                        <div>
                            <p class="mb-1 fw-semibold">{{ $report->report_date->format('d M Y') }} — Progress
                                {{ $report->progress }}%</p>
                            <p class="text-muted small mb-1">{{ $report->description }}</p>
                            @if ($report->obstacle)
                                <p class="text-danger small mb-1"><i class="bi bi-exclamation-triangle"></i> Kendala:
                                    {{ $report->obstacle }}</p>
                            @endif
                            @if ($report->next_plan)
                                <p class="text-muted small mb-0"><i class="bi bi-arrow-right-circle"></i> Rencana
                                    berikutnya: {{ $report->next_plan }}</p>
                            @endif
                        </div>
                    </div>
                @empty
                    <p class="text-muted text-center py-4 mb-0">Belum ada daily report untuk pekerjaan ini.</p>
                @endforelse
            </div>
        </div>
    </section>
@endsection
