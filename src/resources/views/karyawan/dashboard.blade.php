@extends('layouts.karyawan')

@section('title', 'Dashboard')

@section('page-content')
    <div class="page-heading">
        <div class="page-heading-copy">
            <span class="page-icon"><i class="bi bi-speedometer2" aria-hidden="true"></i></span>
            <div>
                <p class="eyebrow mb-1">Overview</p>
                <h1 class="h3 mb-1">Dashboard</h1>
                <p class="text-muted mb-0">Ringkasan aktivitas laporan harianmu.</p>
            </div>
        </div>
        <div class="heading-actions">
            @include('partials.period-filter')
        </div>
    </div>

    <section class="row g-3 mt-1" aria-label="Dashboard metrics">
        <div class="col-12 col-sm-6 col-xl-3">
            <article class="metric-card metric-primary">
                <div class="metric-top">
                    <span class="metric-label">Total Laporan</span>
                    <span class="metric-icon"><i class="bi bi-journal-text" aria-hidden="true"></i></span>
                </div>
                <div class="metric-value">{{ $stats['total_reports'] }}</div>
                <div class="metric-meta"><span>laporan pada periode ini</span></div>
            </article>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <article class="metric-card metric-success">
                <div class="metric-top">
                    <span class="metric-label">Rata-rata Progress</span>
                    <span class="metric-icon"><i class="bi bi-graph-up-arrow" aria-hidden="true"></i></span>
                </div>
                <div class="metric-value">{{ $stats['avg_progress'] }}%</div>
                <div class="metric-meta"><span>progress yang dilaporkan</span></div>
            </article>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <article class="metric-card metric-warning">
                <div class="metric-top">
                    <span class="metric-label">Pekerjaan Ditangani</span>
                    <span class="metric-icon"><i class="bi bi-kanban" aria-hidden="true"></i></span>
                </div>
                <div class="metric-value">{{ $stats['jobs_touched'] }}</div>
                <div class="metric-meta"><span>pekerjaan disentuh periode ini</span></div>
            </article>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <article class="metric-card metric-danger">
                <div class="metric-top">
                    <span class="metric-label">Ada Kendala</span>
                    <span class="metric-icon"><i class="bi bi-exclamation-triangle" aria-hidden="true"></i></span>
                </div>
                <div class="metric-value">{{ $stats['with_obstacle'] }}</div>
                <div class="metric-meta"><span>laporan dengan kendala</span></div>
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
                <p class="text-muted mt-3 mb-0">Dari {{ $stats['total_reports'] }} laporan pada periode ini</p>
            </div>
        </div>

        <div class="col-12 col-xl-8">
            <div class="panel h-100">
                <h2 class="h5 mb-3 section-title"><i class="bi bi-bar-chart-line" aria-hidden="true"></i><span>Tren
                        Aktivitas Laporan</span></h2>
                @if ($chartHasData)
                    <div style="height: 280px;">
                        <canvas id="activityChart"></canvas>
                    </div>
                @else
                    <p class="text-muted text-center py-4 mb-0">Belum ada laporan pada periode ini.</p>
                @endif
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script src="{{ asset('assets/vendors/chartjs/chart.umd.min.js') }}"></script>
    <script>
        const activityChartCanvas = document.getElementById('activityChart');
        if (activityChartCanvas) {
            const chartLabels = @json($chartLabels);
            const chartValues = @json($chartValues);

            new Chart(activityChartCanvas, {
                type: 'bar',
                data: {
                    labels: chartLabels,
                    datasets: [{
                        label: 'Jumlah Laporan',
                        data: chartValues,
                        backgroundColor: '#dc2626',
                        borderRadius: 6,
                        maxBarThickness: 36,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: (ctx) => `${ctx.parsed.y} laporan`
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
