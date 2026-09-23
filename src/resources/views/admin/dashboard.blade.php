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
        <div class="heading-actions d-flex flex-wrap gap-2 align-items-center">
            @include('partials.period-filter')
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

    <section class="mt-3">
        <h2 class="h6 text-muted text-uppercase mb-2" style="letter-spacing: 0.04em;">
            Aktivitas Periode Terpilih
        </h2>
        <div class="row g-3">
            <div class="col-12 col-sm-4">
                <article class="metric-card metric-primary">
                    <div class="metric-top">
                        <span class="metric-label">Laporan Masuk</span>
                        <span class="metric-icon"><i class="bi bi-journal-text" aria-hidden="true"></i></span>
                    </div>
                    <div class="metric-value">{{ $periodStats['reports_in'] }}</div>
                    <div class="metric-meta"><span>daily report periode ini</span></div>
                </article>
            </div>
            <div class="col-12 col-sm-4">
                <article class="metric-card metric-success">
                    <div class="metric-top">
                        <span class="metric-label">Pekerjaan Baru</span>
                        <span class="metric-icon"><i class="bi bi-kanban" aria-hidden="true"></i></span>
                    </div>
                    <div class="metric-value">{{ $periodStats['jobs_created'] }}</div>
                    <div class="metric-meta"><span>dibuat pada periode ini</span></div>
                </article>
            </div>
            <div class="col-12 col-sm-4">
                <article class="metric-card metric-warning">
                    <div class="metric-top">
                        <span class="metric-label">Rata-rata Progress</span>
                        <span class="metric-icon"><i class="bi bi-graph-up-arrow" aria-hidden="true"></i></span>
                    </div>
                    <div class="metric-value">{{ $periodStats['avg_progress'] }}%</div>
                    <div class="metric-meta"><span>dari laporan periode ini</span></div>
                </article>
            </div>
        </div>

        <div class="panel mt-3">
            <h2 class="h5 mb-3 section-title"><i class="bi bi-bar-chart-line" aria-hidden="true"></i><span>Aktivitas
                    Laporan per Karyawan</span></h2>
            @if (count($employeeDatasets) > 0)
                <div style="height: 320px;">
                    <canvas id="employeeActivityChart"></canvas>
                </div>
            @else
                <p class="text-muted text-center py-4 mb-0">Belum ada laporan pada periode ini.</p>
            @endif
        </div>
    </section>

    <section class="row g-3 mt-3">
        <div class="col-12 col-xl-4">
            <div class="panel h-100">
                <h2 class="h5 mb-3 section-title"><i class="bi bi-pie-chart" aria-hidden="true"></i><span>Pekerjaan per
                        Tahapan</span></h2>
                @php
                    $stages = ['draft' => 'Draft', 'revisi' => 'Revisi', 'sidang' => 'Sidang', 'final' => 'Final'];
                    $stageColor = [
                        'draft' => 'secondary',
                        'revisi' => 'warning',
                        'sidang' => 'info',
                        'final' => 'success',
                    ];
                @endphp
                @foreach ($stages as $key => $label)
                    @php $count = $stageBreakdown[$key] ?? 0; @endphp
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="badge text-bg-{{ $stageColor[$key] }}">{{ $label }}</span>
                        <strong>{{ $count }}</strong>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="col-12 col-xl-8">
            <div class="panel h-100">
                <div class="panel-header">
                    <div>
                        <h2 class="h5 mb-1 section-title"><i class="bi bi-alarm" aria-hidden="true"></i><span>Deadline
                                Mendekat (7 hari)</span></h2>
                        <p class="text-muted mb-0">Pekerjaan yang belum final dan deadline-nya sudah dekat.</p>
                    </div>
                </div>
                @forelse ($upcomingDeadlines as $job)
                    <div class="activity-item">
                        <span class="activity-dot bg-danger"></span>
                        <div class="d-flex justify-content-between w-100">
                            <div>
                                <p class="mb-1 fw-semibold">{{ $job->client->name ?? '-' }} —
                                    {{ $job->employee->user->name ?? '-' }}</p>
                                <p class="text-muted small mb-0">Tahapan: {{ ucfirst($job->stage) }}</p>
                            </div>
                            <span
                                class="badge text-bg-danger align-self-center">{{ $job->deadline->format('d M Y') }}</span>
                        </div>
                    </div>
                @empty
                    <p class="text-muted text-center py-3 mb-0">Tidak ada pekerjaan dengan deadline dekat.</p>
                @endforelse
            </div>
        </div>
    </section>

    <section class="panel mt-3">
        <div class="panel-header">
            <div>
                <h2 class="h5 mb-1 section-title"><i class="bi bi-activity" aria-hidden="true"></i><span>Aktivitas Daily
                        Report</span></h2>
                <p class="text-muted mb-0">8 laporan terbaru pada periode terpilih.</p>
            </div>
            <a class="btn btn-outline-secondary btn-sm" href="{{ route('admin.reports.index') }}">Lihat Semua Laporan</a>
        </div>
        @forelse ($recentReports as $report)
            <div class="activity-item">
                <span class="activity-dot bg-primary"></span>
                <div>
                    <p class="mb-1 fw-semibold">{{ $report->user->name ?? '-' }} —
                        {{ $report->jobTask->client->name ?? '-' }}</p>
                    <p class="text-muted small mb-0">{{ $report->report_date->format('d M Y') }} —
                        {{ Str::limit($report->description, 100) }}</p>
                </div>
            </div>
        @empty
            <p class="text-muted text-center py-3 mb-0">Belum ada aktivitas daily report.</p>
        @endforelse
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

@push('scripts')
    <script src="{{ asset('assets/vendors/chartjs/chart.umd.min.js') }}"></script>
    <script>
        const employeeChartCanvas = document.getElementById('employeeActivityChart');
        if (employeeChartCanvas) {
            const employeeDatasets = @json($employeeDatasets);
            const bucketLabels = @json($bucketLabels);

            new Chart(employeeChartCanvas, {
                type: 'bar',
                data: {
                    labels: bucketLabels,
                    datasets: employeeDatasets,
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: {
                        mode: 'index',
                        intersect: false
                    },
                    plugins: {
                        legend: {
                            display: true,
                            position: 'bottom',
                            labels: {
                                usePointStyle: true,
                                boxWidth: 8,
                                boxHeight: 8
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: (ctx) => `${ctx.dataset.label}: ${ctx.parsed.y} laporan`
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: {
                                display: false
                            }
                        },
                        y: {
                            beginAtZero: true,
                            ticks: {
                                precision: 0
                            }
                        }
                    }
                }
            });
        }
    </script>
@endpush
