@extends('layouts.admin')

@section('title', 'Status Kewenangan')
@section('search-placeholder', 'Cari status kewenangan...')

@section('page-content')
    <div class="page-heading">
        <div class="page-heading-copy">
            <span class="page-icon"><i class="bi bi-bank" aria-hidden="true"></i></span>
            <div>
                <p class="eyebrow mb-1">Master Data</p>
                <h1 class="h3 mb-1">Status Kewenangan</h1>
                <p class="text-muted mb-0">Tingkat pemerintahan yang berwenang menerbitkan dokumen lingkungan.</p>
            </div>
        </div>
        <div class="heading-actions">
            <a href="{{ route('admin.authorities.create') }}" class="btn btn-primary btn-sm">
                <i class="bi bi-plus-lg" aria-hidden="true"></i> Tambah
            </a>
        </div>
    </div>

    <section class="panel mt-3">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th scope="col">Nama</th>
                        <th scope="col">Urutan</th>
                        <th scope="col" class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($authorities as $authority)
                        <tr>
                            <td class="fw-semibold">{{ $authority->name }}</td>
                            <td>{{ $authority->sort_order }}</td>
                            <td class="text-end">
                                <a class="btn btn-light btn-sm" href="{{ route('admin.authorities.edit', $authority) }}">
                                    <i class="bi bi-pencil" aria-hidden="true"></i>
                                </a>
                                <form action="{{ route('admin.authorities.destroy', $authority) }}" method="POST"
                                    class="d-inline" onsubmit="return confirm('Hapus {{ addslashes($authority->name) }}?')">
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
                            <td colspan="3" class="text-center text-muted py-4">Belum ada data.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($authorities->hasPages())
            <div class="p-3">{{ $authorities->links('pagination::bootstrap-5') }}</div>
        @endif
    </section>
@endsection
