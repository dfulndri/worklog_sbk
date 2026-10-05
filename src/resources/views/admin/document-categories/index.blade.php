@extends('layouts.admin')

@section('title', 'Kategori Dokumen')
@section('search-placeholder', 'Cari di tab ini...')

@section('page-content')
    <div class="page-heading">
        <div class="page-heading-copy">
            <span class="page-icon"><i class="bi bi-diagram-3" aria-hidden="true"></i></span>
            <div>
                <p class="eyebrow mb-1">Master Data</p>
                <h1 class="h3 mb-1">Kategori Dokumen</h1>
                <p class="text-muted mb-0">Kelola Kategori, Sub Kategori, Jenis Dokumen, dan Status Kewenangan.</p>
            </div>
        </div>
        <div class="heading-actions">
            <a href="{{ route('admin.document-categories.create', ['level' => $tab]) }}" class="btn btn-primary btn-sm">
                <i class="bi bi-plus-lg" aria-hidden="true"></i> Tambah Data
            </a>
        </div>
    </div>

    <section class="panel mt-3">
        @include('admin.document-categories._tabs')

        @if ($tab !== 'kategori')
            <form method="GET" class="row g-2 mb-3">
                <input type="hidden" name="tab" value="{{ $tab }}">
                <div class="col-auto">
                    <select name="kategori_id" class="form-select form-select-sm" style="min-width: 220px;"
                        onchange="this.form.submit()">
                        <option value="">-- semua kategori --</option>
                        @foreach ($kategoriList as $k)
                            <option value="{{ $k->id }}" @selected($kategoriId == $k->id)>{{ $k->name }}</option>
                        @endforeach
                    </select>
                </div>
                @if ($tab === 'dokumen')
                    <div class="col-auto">
                        <select name="sub_kategori_id" class="form-select form-select-sm" style="min-width: 240px;"
                            onchange="this.form.submit()">
                            <option value="">-- semua sub kategori --</option>
                            @foreach ($subKategoriFiltered as $s)
                                <option value="{{ $s->id }}" @selected($subKategoriId == $s->id)>{{ $s->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                @endif
                @if ($kategoriId || $subKategoriId)
                    <div class="col-auto">
                        <a href="{{ route('admin.document-categories.index', ['tab' => $tab]) }}"
                            class="btn btn-outline-secondary btn-sm">Reset Filter</a>
                    </div>
                @endif
            </form>
        @endif

        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        @if ($tab === 'sub_kategori')
                            <th scope="col">Kategori</th>
                        @endif
                        @if ($tab === 'dokumen')
                            <th scope="col">Kategori</th>
                            <th scope="col">Sub Kategori</th>
                        @endif
                        <th scope="col">Nama</th>
                        @if ($tab === 'dokumen')
                            <th scope="col">Butuh Kewenangan</th>
                        @endif
                        <th scope="col">Urutan</th>
                        <th scope="col" class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($rows as $category)
                        <tr>
                            @if ($tab === 'sub_kategori')
                                <td class="text-muted">{{ $category->parent->name ?? '-' }}</td>
                            @endif
                            @if ($tab === 'dokumen')
                                <td class="text-muted">{{ $category->parent->parent->name ?? '-' }}</td>
                                <td class="text-muted">{{ $category->parent->name ?? '-' }}</td>
                            @endif
                            <td class="fw-semibold">{{ $category->name }}</td>
                            @if ($tab === 'dokumen')
                                <td>
                                    @if ($category->requires_authority)
                                        <span class="badge text-bg-warning">Ya</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                            @endif
                            <td>{{ $category->sort_order }}</td>
                            <td class="text-end text-nowrap">
                                <a class="btn btn-light btn-sm"
                                    href="{{ route('admin.document-categories.edit', $category) }}">
                                    <i class="bi bi-pencil" aria-hidden="true"></i>
                                </a>
                                <form action="{{ route('admin.document-categories.destroy', $category) }}" method="POST"
                                    class="d-inline" onsubmit="return confirm('Hapus {{ addslashes($category->name) }}?')">
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
                            <td colspan="6" class="text-center text-muted py-4">Belum ada data pada tab ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
@endsection
