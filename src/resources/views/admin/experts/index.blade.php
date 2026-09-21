@extends('layouts.admin')

@section('title', 'Tenaga Ahli')
@section('search-placeholder', 'Cari nama tenaga ahli...')

@section('page-content')
    <div class="page-heading">
        <div class="page-heading-copy">
            <span class="page-icon"><i class="bi bi-person-badge" aria-hidden="true"></i></span>
            <div>
                <p class="eyebrow mb-1">Master Data</p>
                <h1 class="h3 mb-1">Tenaga Ahli</h1>
                <p class="text-muted mb-0">Kelola daftar tenaga ahli yang terlibat dalam pekerjaan.</p>
            </div>
        </div>
        <div class="heading-actions">
            <a href="{{ route('admin.experts.create') }}" class="btn btn-primary btn-sm">
                <i class="bi bi-plus-lg" aria-hidden="true"></i> Tambah Tenaga Ahli
            </a>
        </div>
    </div>

    <section class="panel mt-3">
        <div class="panel-header">
            <div>
                <h2 class="h5 mb-1 section-title"><i class="bi bi-person-badge" aria-hidden="true"></i><span>Daftar Tenaga
                        Ahli</span></h2>
                <p class="text-muted mb-0">Total {{ $experts->total() }} tenaga ahli.</p>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th scope="col">Nama</th>
                        <th scope="col">Bidang Keahlian</th>
                        <th scope="col" class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($experts as $expert)
                        <tr>
                            <td class="fw-semibold">{{ $expert->name }}</td>
                            <td>{{ $expert->field ?? '-' }}</td>
                            <td class="text-end">
                                <a class="btn btn-light btn-sm" href="{{ route('admin.experts.edit', $expert) }}">
                                    <i class="bi bi-pencil" aria-hidden="true"></i>
                                </a>
                                <form action="{{ route('admin.experts.destroy', $expert) }}" method="POST" class="d-inline"
                                    onsubmit="return confirm('Hapus data {{ $expert->name }}?')">
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
                            <td colspan="3" class="text-center text-muted py-4">Belum ada data tenaga ahli.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($experts->hasPages())
            <div class="p-3">
                {{ $experts->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </section>
@endsection
