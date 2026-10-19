@extends('layouts.admin')

@section('title', 'Pekerjaan')
@section('search-placeholder', 'Cari klien/PIC/dokumen...')

@section('page-content')
    <div class="page-heading">
        <div class="page-heading-copy">
            <span class="page-icon"><i class="bi bi-kanban" aria-hidden="true"></i></span>
            <div>
                <p class="eyebrow mb-1">Monitoring</p>
                <h1 class="h3 mb-1">Pekerjaan</h1>
                <p class="text-muted mb-0">Kelola dan pantau progres seluruh pekerjaan.</p>
            </div>
        </div>
        <div class="heading-actions">
            <a href="{{ route('admin.jobs.create') }}" class="btn btn-primary btn-sm">
                <i class="bi bi-plus-lg" aria-hidden="true"></i> Tambah Pekerjaan
            </a>
        </div>
    </div>

    <section class="panel mt-3">
        <div class="panel-header">
            <div>
                <h2 class="h5 mb-1 section-title"><i class="bi bi-kanban" aria-hidden="true"></i><span>Daftar
                        Pekerjaan</span></h2>
                <p class="text-muted mb-0">Total {{ $jobs->total() }} pekerjaan.</p>
            </div>
        </div>

        <form method="GET" class="row g-2 px-3 pb-3">
            <div class="col-auto">
                <select name="client_id" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="">Semua Klien</option>
                    @foreach ($clients as $client)
                        <option value="{{ $client->id }}" @selected(request('client_id') == $client->id)>{{ $client->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-auto">
                <select name="kategori_id" class="form-select form-select-sm"
                    onchange="this.form.bidang_id.value=''; this.form.submit()">
                    <option value="">Semua Kategori</option>
                    @foreach ($kategoriList as $k)
                        <option value="{{ $k->id }}" @selected(request('kategori_id') == $k->id)>{{ $k->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-auto">
                <select name="bidang_id" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="">Semua Bidang</option>
                    @foreach ($bidangList as $b)
                        <option value="{{ $b->id }}" @selected(request('bidang_id') == $b->id)>{{ $b->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-auto">
                <select name="phase" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="">Semua Progress</option>
                    @foreach (\App\Models\JobStage::PHASES as $value => $label)
                        <option value="{{ $value }}" @selected(request('phase') == $value)>{{ $label }}
                            ({{ $value * 25 }}%)</option>
                    @endforeach
                </select>
            </div>
            @if (request('client_id') || request('kategori_id') || request('bidang_id') || request('phase'))
                <div class="col-auto">
                    <a href="{{ route('admin.jobs.index') }}" class="btn btn-outline-secondary btn-sm">Reset Filter</a>
                </div>
            @endif
        </form>

        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th scope="col">Klien</th>
                        <th scope="col">Jenis Dokumen</th>
                        <th scope="col">PIC</th>
                        <th scope="col">Tahapan</th>
                        <th scope="col">Progress</th>
                        <th scope="col">Deadline</th>
                        <th scope="col" class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($jobs as $job)
                        <tr>
                            <td class="fw-semibold">{{ $job->client->name ?? '-' }}</td>
                            <td>
                                {{ $job->documentCategory->name ?? '-' }}
                                @if ($job->documentCategory)
                                    <br><small class="text-muted">{{ $job->documentCategory->pathLabel() }}</small>
                                @endif
                            </td>
                            <td>{{ $job->employee->user->name ?? '-' }}</td>
                            <td>
                                @if ($job->stage)
                                    <span
                                        class="badge {{ $job->stage->phaseBadge() }}">{{ $job->stage->phaseLabel() }}</span>
                                    <br><small class="text-muted">{{ $job->stage->name }}</small>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                <div class="progress" style="height: 6px; min-width: 100px;">
                                    <div class="progress-bar" role="progressbar" style="width: {{ $job->progress }}%">
                                    </div>
                                </div>
                                <small class="text-muted">{{ $job->progress }}%</small>
                            </td>
                            <td>{{ $job->deadline?->format('d M Y') ?? '-' }}</td>
                            <td class="text-end">
                                <a class="btn btn-light btn-sm" href="{{ route('admin.jobs.show', $job) }}">
                                    <i class="bi bi-eye" aria-hidden="true"></i>
                                </a>
                                <a class="btn btn-light btn-sm" href="{{ route('admin.jobs.edit', $job) }}">
                                    <i class="bi bi-pencil" aria-hidden="true"></i>
                                </a>
                                <form action="{{ route('admin.jobs.destroy', $job) }}" method="POST" class="d-inline"
                                    onsubmit="return confirm('Hapus pekerjaan ini? Semua daily report terkait juga akan terhapus.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-light btn-sm text-danger">
                                        <i class="bi bi-trash" aria-hidden="true"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">Belum ada data pekerjaan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($jobs->hasPages())
            <div class="p-3">
                {{ $jobs->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </section>
@endsection
