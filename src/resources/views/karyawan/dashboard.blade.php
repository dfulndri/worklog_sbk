@extends('layouts.karyawan')

@section('title', 'Progress Saya')

@section('page-content')
    <div class="page-heading">
        <div class="page-heading-copy">
            <span class="page-icon"><i class="bi bi-speedometer2" aria-hidden="true"></i></span>
            <div>
                <p class="eyebrow mb-1">Overview</p>
                <h1 class="h3 mb-1">Progress Pekerjaan Saya</h1>
                <p class="text-muted mb-0">Daftar pekerjaan yang menjadi tanggung jawab kamu.</p>
            </div>
        </div>
        <div class="heading-actions">
            <a href="{{ route('karyawan.daily-reports.create') }}" class="btn btn-primary btn-sm">
                <i class="bi bi-plus-lg" aria-hidden="true"></i> Isi Daily Report
            </a>
        </div>
    </div>

    <section class="row g-3 mt-1" aria-label="Ringkasan progress">
        <div class="col-12 col-sm-6 col-xl-3">
            <article class="metric-card metric-primary">
                <div class="metric-top">
                    <span class="metric-label">Total Pekerjaan</span>
                    <span class="metric-icon"><i class="bi bi-kanban" aria-hidden="true"></i></span>
                </div>
                <div class="metric-value">{{ $stats['total'] }}</div>
                <div class="metric-meta"><span>pekerjaan ditugaskan</span></div>
            </article>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <article class="metric-card metric-success">
                <div class="metric-top">
                    <span class="metric-label">Selesai (Final)</span>
                    <span class="metric-icon"><i class="bi bi-check2-circle" aria-hidden="true"></i></span>
                </div>
                <div class="metric-value">{{ $stats['final'] }}</div>
                <div class="metric-meta"><span>pekerjaan final</span></div>
            </article>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <article class="metric-card metric-warning">
                <div class="metric-top">
                    <span class="metric-label">Rata-rata Progress</span>
                    <span class="metric-icon"><i class="bi bi-graph-up" aria-hidden="true"></i></span>
                </div>
                <div class="metric-value">{{ $stats['avg_progress'] }}%</div>
                <div class="metric-meta"><span>dari semua pekerjaan</span></div>
            </article>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <article class="metric-card metric-danger">
                <div class="metric-top">
                    <span class="metric-label">Deadline Mendekat</span>
                    <span class="metric-icon"><i class="bi bi-alarm" aria-hidden="true"></i></span>
                </div>
                <div class="metric-value">{{ $stats['near_deadline'] }}</div>
                <div class="metric-meta"><span>&le; 7 hari lagi</span></div>
            </article>
        </div>
    </section>

    <section class="row g-3 mt-1">
        <div class="col-12 col-xl-4">
            <div class="panel h-100 text-center">
                <h2 class="h5 mb-3 section-title"><i class="bi bi-pie-chart" aria-hidden="true"></i><span>Rata-rata
                        Progress</span></h2>
                <div class="donut-chart mx-auto"
                    style="background: conic-gradient(var(--admin-primary) 0 {{ $stats['avg_progress'] }}%, var(--admin-border, #e2e8f0) {{ $stats['avg_progress'] }}% 100%);">
                    <span>{{ $stats['avg_progress'] }}%</span>
                </div>
                <p class="text-muted small mt-3 mb-0">Rata-rata dari {{ $stats['total'] }} pekerjaan</p>
            </div>
        </div>

        <div class="col-12 col-xl-8">
            <div class="panel h-100">
                <div class="panel-header">
                    <div>
                        <h2 class="h5 mb-1 section-title"><i class="bi bi-bar-chart-line"
                                aria-hidden="true"></i><span>Progress per Pekerjaan</span></h2>
                        <p class="text-muted mb-0">6 pekerjaan terbaru.</p>
                    </div>
                </div>
                @if ($progressChart->isNotEmpty())
                    <div class="chart-bars" aria-label="Progress per pekerjaan"
                        style="grid-template-columns: repeat({{ $progressChart->count() }}, minmax(38px, 1fr));">
                        @foreach ($progressChart as $item)
                            <div class="chart-column" style="--bar-size: {{ $item['value'] }}%">
                                <span></span>
                                <small>{{ $item['label'] }}</small>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-muted text-center py-4 mb-0">Belum ada pekerjaan untuk ditampilkan.</p>
                @endif
            </div>
        </div>
    </section>

    <section class="panel mt-3">
        <div class="panel-header">
            <div>
                <h2 class="h5 mb-1 section-title"><i class="bi bi-kanban" aria-hidden="true"></i><span>Pekerjaan Saya</span>
                </h2>
                <p class="text-muted mb-0">Status dan progres tiap pekerjaan.</p>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th scope="col">Klien</th>
                        <th scope="col">Jenis Dokumen</th>
                        <th scope="col">Tahapan</th>
                        <th scope="col">Progress</th>
                        <th scope="col">Deadline</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($jobs as $job)
                        <tr>
                            <td>{{ $job->client->name ?? '-' }}</td>
                            <td>{{ $job->documentType->name ?? '-' }}</td>
                            <td><span class="badge text-bg-secondary">{{ ucfirst($job->stage) }}</span></td>
                            <td>
                                <div class="progress" style="height: 6px; min-width: 100px;">
                                    <div class="progress-bar" role="progressbar" style="width: {{ $job->progress }}%">
                                    </div>
                                </div>
                                <small class="text-muted">{{ $job->progress }}%</small>
                            </td>
                            <td>{{ $job->deadline?->format('d M Y') ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">Belum ada pekerjaan yang ditugaskan ke
                                kamu.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
@endsection
