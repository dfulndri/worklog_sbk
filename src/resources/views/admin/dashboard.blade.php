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

    <section class="panel mt-3">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
            <h2 class="h5 mb-0 section-title"><i class="bi bi-bar-chart-line" aria-hidden="true"></i><span>Pekerjaan
                    per Progress</span></h2>
            <div id="progressCategoryFilter" class="btn-group btn-group-sm" role="group" aria-label="Filter kategori">
            </div>
        </div>
        <div id="progressBars" class="d-flex flex-column gap-2"></div>
        <div id="progressList" class="mt-3 pt-3 border-top"></div>
    </section>

    <section class="row g-3 mt-3">
        <div class="col-12">
            <div class="panel h-100">
                <div class="panel-header">
                    <div>
                        <h2 class="h5 mb-1 section-title"><i class="bi bi-alarm" aria-hidden="true"></i><span>Deadline
                                Mendekat (7 hari)</span></h2>
                        <p class="text-muted mb-0">Pekerjaan yang belum selesai (progress di bawah 100%) dan deadline-nya
                            sudah dekat.</p>
                    </div>
                </div>
                @forelse ($upcomingDeadlines as $job)
                    <div class="activity-item">
                        <span class="activity-dot bg-danger"></span>
                        <div class="d-flex justify-content-between w-100">
                            <div>
                                <p class="mb-1 fw-semibold">{{ $job->client->name ?? '-' }} —
                                    {{ $job->employee->user->name ?? '-' }}</p>
                                <p class="text-muted small mb-0">Tahapan: {{ $job->stage->name ?? '-' }}</p>
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
                            <td>
                                @if ($job->stage)
                                    <span
                                        class="badge {{ $job->stage->phaseBadge() }}">{{ $job->stage->phaseLabel() }}</span>
                                    <small class="text-muted">{{ $job->stage->name }}</small>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
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

@push('scripts')
    <script>
        (function() {
            const data = @json($progressData);
            const MAX_ROWS = 50;
            let cat = 'all';
            let phase = 1;

            const esc = (v) => String(v === null || v === undefined ? '' : v).replace(/[&<>"']/g, (c) => ({
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#39;'
            } [c]));

            const catName = (id) => (data.categories.find((c) => String(c.id) === String(id)) || {}).name || '';
            const inCat = (categoryId) => cat === 'all' || String(categoryId) === cat;

            function countFor(p) {
                let total = 0;
                data.categories.forEach(function(c) {
                    if (inCat(c.id) && data.counts[c.id] && data.counts[c.id][p]) {
                        total += data.counts[c.id][p];
                    }
                });
                return total;
            }

            function render() {
                const filterEl = document.getElementById('progressCategoryFilter');
                const options = [{
                    id: 'all',
                    name: 'Semua'
                }].concat(data.categories);
                filterEl.innerHTML = options.map(function(o) {
                    const active = String(o.id) === cat;
                    return '<button type="button" class="btn ' + (active ? 'btn-primary' :
                            'btn-outline-secondary') +
                        '" data-cat="' + esc(o.id) + '">' + esc(o.name) + '</button>';
                }).join('');

                const counts = [1, 2, 3, 4].map(countFor);
                const max = Math.max(1, Math.max.apply(null, counts));

                document.getElementById('progressBars').innerHTML = counts.map(function(n, i) {
                    const p = i + 1;
                    const selected = phase === p;
                    const width = n === 0 ? 0 : Math.max(3, Math.round(n / max * 100));
                    return '<button type="button" data-phase="' + p + '" class="btn text-start w-100 p-2" ' +
                        'style="display:grid; grid-template-columns:110px minmax(0,1fr) 36px; align-items:center; gap:12px; ' +
                        'border:1px solid ' + (selected ? 'var(--admin-primary, #2563eb)' :
                            'var(--admin-border, #e2e8f0)') + ';' +
                        (selected ?
                            ' background: color-mix(in srgb, var(--admin-primary, #2563eb) 8%, transparent);' :
                            '') + '">' +
                        '<span><strong class="d-block">Progress ' + p + '</strong><small class="text-muted">' +
                        (p * 25) + '%</small></span>' +
                        '<span class="progress" style="height:16px;"><span class="progress-bar" style="width:' +
                        width + '%"></span></span>' +
                        '<strong class="text-end">' + n + '</strong></button>';
                }).join('');

                const rows = data.jobs.filter((j) => j.phase === phase && inCat(j.category_id));
                const total = countFor(phase);
                const byClient = {};
                rows.slice(0, MAX_ROWS).forEach(function(j) {
                    (byClient[j.client] = byClient[j.client] || []).push(j);
                });

                let out = '<div class="d-flex justify-content-between align-items-center mb-2">' +
                    '<strong>Progress ' + phase + ' &middot; ' + total + ' pekerjaan</strong>' +
                    '<a class="small" href="' + esc(data.indexUrl) + '?phase=' + phase +
                    (cat !== 'all' ? '&kategori_id=' + encodeURIComponent(cat) : '') +
                    '">Lihat semua di Pekerjaan</a></div>';

                if (rows.length === 0) {
                    out += '<p class="text-muted mb-0">Tidak ada pekerjaan di progress ini.</p>';
                }

                Object.keys(byClient).sort().forEach(function(client) {
                    out += '<div class="small text-muted mt-3 mb-1">' + esc(client) + ' &middot; ' + byClient[
                        client].length + '</div>';
                    byClient[client].forEach(function(j) {
                        out += '<a href="' + esc(j.url) +
                            '" class="d-flex justify-content-between gap-3 py-2 border-top text-decoration-none" style="color:inherit;">' +
                            '<span><span class="d-block">' + esc(j.path) + '</span>' +
                            '<small class="text-muted">' + esc(j.stage) + ' &middot; PIC ' + esc(j
                            .pic) + '</small></span>' +
                            '<span class="text-end text-nowrap"><strong class="d-block">' + esc(j
                                .progress) + '%</strong>' +
                            '<small class="text-muted">' + esc(catName(j.category_id)) +
                            '</small></span></a>';
                    });
                });

                document.getElementById('progressList').innerHTML = out;

                filterEl.querySelectorAll('button').forEach(function(b) {
                    b.onclick = function() {
                        cat = b.dataset.cat;
                        render();
                    };
                });
                document.querySelectorAll('#progressBars button').forEach(function(b) {
                    b.onclick = function() {
                        phase = parseInt(b.dataset.phase, 10);
                        render();
                    };
                });
            }

            render();
        })();
    </script>
@endpush
