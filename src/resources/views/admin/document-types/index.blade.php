@extends('layouts.admin')

@section('title', 'Jenis Dokumen')

@section('page-content')
    <div class="page-heading">
        <div class="page-heading-copy">
            <span class="page-icon"><i class="bi bi-file-earmark-text" aria-hidden="true"></i></span>
            <div>
                <p class="eyebrow mb-1">Master Data</p>
                <h1 class="h3 mb-1">Jenis Dokumen</h1>
                <p class="text-muted mb-0">Kelola daftar jenis dokumen/perizinan (Pertek, UKL-UPL, dsb).</p>
            </div>
        </div>
        <div class="heading-actions">
            <a href="{{ route('admin.document-types.create') }}" class="btn btn-primary btn-sm">
                <i class="bi bi-plus-lg" aria-hidden="true"></i> Tambah Jenis Dokumen
            </a>
        </div>
    </div>

    <section class="panel mt-3">
        <div class="panel-header">
            <div>
                <h2 class="h5 mb-1 section-title"><i class="bi bi-file-earmark-text" aria-hidden="true"></i><span>Daftar
                        Jenis Dokumen</span></h2>
                <p class="text-muted mb-0">Total {{ $documentTypes->total() }} jenis dokumen.</p>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th scope="col">Nama Dokumen</th>
                        <th scope="col">Kategori</th>
                        <th scope="col" class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($documentTypes as $documentType)
                        <tr>
                            <td class="fw-semibold">{{ $documentType->name }}</td>
                            <td>{{ $documentType->category ?? '-' }}</td>
                            <td class="text-end">
                                <a class="btn btn-light btn-sm"
                                    href="{{ route('admin.document-types.edit', $documentType) }}">
                                    <i class="bi bi-pencil" aria-hidden="true"></i>
                                </a>
                                <form action="{{ route('admin.document-types.destroy', $documentType) }}" method="POST"
                                    class="d-inline"
                                    onsubmit="return confirm('Hapus jenis dokumen {{ $documentType->name }}?')">
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
                            <td colspan="3" class="text-center text-muted py-4">Belum ada jenis dokumen.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($documentTypes->hasPages())
            <div class="p-3">
                {{ $documentTypes->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </section>
@endsection
