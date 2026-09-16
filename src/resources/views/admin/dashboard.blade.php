@extends('layouts.admin')

@section('title', 'Dashboard Admin')

@section('page-content')
    <div class="page-heading">
        <div class="page-heading-copy">
            <span class="page-icon"><i class="bi bi-speedometer2" aria-hidden="true"></i></span>
            <div>
                <p class="eyebrow mb-1">Overview</p>
                <h1 class="h3 mb-1">Dashboard</h1>
                <p class="text-muted mb-0">Ringkasan data dan pekerjaan yang sedang berjalan.</p>
            </div>
        </div>
        <div class="heading-actions">
            <a href="{{ route('admin.jobs.create') }}" class="btn btn-primary btn-sm">
                <i class="bi bi-plus-lg" aria-hidden="true"></i> Tambah Pekerjaan
            </a>
        </div>
    </div>

    <section class="row g-3 mt-1" aria-label="Dashboard metrics">
        <div class="col-12 col-sm-6 col-xl-3">
            <article class="metric-card metric-primary">
                <div class="metric-top">
                    <span class="metric-label">PT / Klien</span>
                    <span class="metric-icon"><i class="bi bi-building" aria-hidden="true"></i></span>
                </div>
                <div class="metric-value">{{ $stats['clients'] }}</div>
                <div class="metric-meta"><span>total klien terdaftar</span></div>
            </article>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <article class="metric-card metric-success">
                <div class="metric-top">
                    <span class="metric-label">Tenaga Ahli</span>
                    <span class="metric-icon"><i class="bi bi-person-badge" aria-hidden="true"></i></span>
                </div>
                <div class="metric-value">{{ $stats['experts'] }}</div>
                <div class="metric-meta"><span>tenaga ahli aktif</span></div>
            </article>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <article class="metric-card metric-warning">
                <div class="metric-top">
                    <span class="metric-label">Karyawan</span>
                    <span class="metric-icon"><i class="bi bi-people" aria-hidden="true"></i></span>
                </div>
                <div class="metric-value">{{ $stats['employees'] }}</div>
                <div class="metric-meta"><span>karyawan / PIC</span></div>
            </article>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <article class="metric-card metric-danger">
                <div class="metric-top">
                    <span class="metric-label">Pekerjaan</span>
                    <span class="metric-icon"><i class="bi bi-kanban" aria-hidden="true"></i></span>
                </div>
                <div class="metric-value">{{ $stats['jobs'] }}</div>
                <div class="metric-meta"><span>total pekerjaan berjalan</span></div>
            </article>
        </div>
    </section>

    <section class="panel mt-3">
        <div class="panel-header">
            <div>
                <h2 class="h5 mb-1 section-title"><i class="bi bi-kanban" aria-hidden="true"></i><span>Pekerjaan
                        Terbaru</span></h2>
                <p class="text-muted mb-0">5 pekerjaan yang terakhir dibuat.</p>
            </div>
            <a class="btn btn-outline-secondary btn-sm" href="{{ route('admin.jobs.index') }}">Lihat Semua</a>
        </div>
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th scope="col">Klien</th>
                        <th scope="col">PIC</th>
                        <th scope="col">Tahapan</th>
                        <th scope="col">Progress</th>
                        <th scope="col">Deadline</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($latestJobs as $job)
                        <tr>
                            <td>{{ $job->client->name ?? '-' }}</td>
                            <td>{{ $job->employee->user->name ?? '-' }}</td>
                            <td><span class="badge text-bg-secondary">{{ ucfirst($job->stage) }}</span></td>
                            <td>{{ $job->progress }}%</td>
                            <td>{{ $job->deadline?->format('d M Y') ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">Belum ada data pekerjaan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
@endsection
