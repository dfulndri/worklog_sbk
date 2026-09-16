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
                                    <div class="progress-bar" role="progressbar" style="width: {{ $job->progress }}%"></div>
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
