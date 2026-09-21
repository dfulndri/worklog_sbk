@extends('layouts.admin')

@section('title', 'Data Karyawan')
@section('search-placeholder', 'Cari nama karyawan...')

@section('page-content')
    <div class="page-heading">
        <div class="page-heading-copy">
            <span class="page-icon"><i class="bi bi-people" aria-hidden="true"></i></span>
            <div>
                <p class="eyebrow mb-1">Master Data</p>
                <h1 class="h3 mb-1">Data Karyawan / PIC</h1>
                <p class="text-muted mb-0">Kelola akun dan data karyawan yang akan mengisi daily report.</p>
            </div>
        </div>
        <div class="heading-actions">
            <a href="{{ route('admin.employees.create') }}" class="btn btn-primary btn-sm">
                <i class="bi bi-plus-lg" aria-hidden="true"></i> Tambah Karyawan
            </a>
        </div>
    </div>

    <section class="panel mt-3">
        <div class="panel-header">
            <div>
                <h2 class="h5 mb-1 section-title"><i class="bi bi-people" aria-hidden="true"></i><span>Daftar
                        Karyawan</span></h2>
                <p class="text-muted mb-0">Total {{ $employees->total() }} karyawan.</p>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th scope="col">Nama</th>
                        <th scope="col">Email</th>
                        <th scope="col">Jabatan</th>
                        <th scope="col">No. HP</th>
                        <th scope="col" class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($employees as $employee)
                        <tr>
                            <td class="d-flex align-items-center gap-2">
                                <img class="avatar-img avatar-sm" src="{{ asset('assets/images/avatar/avatar.jpg') }}"
                                    alt="{{ $employee->user->name }}">
                                <span class="fw-semibold">{{ $employee->user->name }}</span>
                            </td>
                            <td>{{ $employee->user->email }}</td>
                            <td>{{ $employee->position ?? '-' }}</td>
                            <td>{{ $employee->phone ?? '-' }}</td>
                            <td class="text-end">
                                <a class="btn btn-light btn-sm" href="{{ route('admin.employees.edit', $employee) }}">
                                    <i class="bi bi-pencil" aria-hidden="true"></i>
                                </a>
                                <form action="{{ route('admin.employees.destroy', $employee) }}" method="POST"
                                    class="d-inline"
                                    onsubmit="return confirm('Hapus karyawan {{ $employee->user->name }}? Akun login juga akan dihapus.')">
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
                            <td colspan="5" class="text-center text-muted py-4">Belum ada data karyawan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($employees->hasPages())
            <div class="p-3">
                {{ $employees->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </section>
@endsection
