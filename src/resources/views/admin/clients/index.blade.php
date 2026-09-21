@extends('layouts.admin')

@section('title', 'Data PT / Klien')
@section('search-placeholder', 'Cari nama PT/Klien...')

@section('page-content')
    <div class="page-heading">
        <div class="page-heading-copy">
            <span class="page-icon"><i class="bi bi-building" aria-hidden="true"></i></span>
            <div>
                <p class="eyebrow mb-1">Master Data</p>
                <h1 class="h3 mb-1">Data PT / Klien</h1>
                <p class="text-muted mb-0">Kelola daftar perusahaan/klien yang ditangani.</p>
            </div>
        </div>
        <div class="heading-actions">
            <a href="{{ route('admin.clients.create') }}" class="btn btn-primary btn-sm">
                <i class="bi bi-plus-lg" aria-hidden="true"></i> Tambah Klien
            </a>
        </div>
    </div>

    <section class="panel mt-3">
        <div class="panel-header">
            <div>
                <h2 class="h5 mb-1 section-title"><i class="bi bi-building" aria-hidden="true"></i><span>Daftar Klien</span>
                </h2>
                <p class="text-muted mb-0">Total {{ $clients->total() }} klien terdaftar.</p>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th scope="col">Nama Klien</th>
                        <th scope="col">Alamat</th>
                        <th scope="col">Kontak</th>
                        <th scope="col">Status</th>
                        <th scope="col" class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($clients as $client)
                        <tr>
                            <td class="fw-semibold">{{ $client->name }}</td>
                            <td>{{ $client->address ?? '-' }}</td>
                            <td>{{ $client->contact ?? '-' }}</td>
                            <td>
                                @if ($client->status === 'aktif')
                                    <span class="badge text-bg-success">Aktif</span>
                                @else
                                    <span class="badge text-bg-secondary">Nonaktif</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <a class="btn btn-light btn-sm" href="{{ route('admin.clients.edit', $client) }}">
                                    <i class="bi bi-pencil" aria-hidden="true"></i>
                                </a>
                                <form action="{{ route('admin.clients.destroy', $client) }}" method="POST" class="d-inline"
                                    onsubmit="return confirm('Hapus data klien {{ $client->name }}?')">
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
                            <td colspan="5" class="text-center text-muted py-4">Belum ada data klien.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($clients->hasPages())
            <div class="p-3">
                {{ $clients->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </section>
@endsection
