@extends('layouts.admin')

@section('title', 'Kategori Dokumen')
@section('search-placeholder', 'Cari kategori/jenis dokumen...')

@section('page-content')
    <div class="page-heading">
        <div class="page-heading-copy">
            <span class="page-icon"><i class="bi bi-diagram-3" aria-hidden="true"></i></span>
            <div>
                <p class="eyebrow mb-1">Master Data</p>
                <h1 class="h3 mb-1">Kategori Dokumen</h1>
                <p class="text-muted mb-0">Struktur berjenjang: Kegiatan › Kategori › Sub Kategori › Jenis Dokumen.</p>
            </div>
        </div>
        <div class="heading-actions">
            <a href="{{ route('admin.document-categories.create') }}" class="btn btn-primary btn-sm">
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
                        <th scope="col">Level</th>
                        <th scope="col">Butuh Kewenangan</th>
                        <th scope="col">Urutan</th>
                        <th scope="col" class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($rows as $row)
                        @php $category = $row['category']; @endphp
                        <tr>
                            <td>
                                @if ($search !== '')
                                    <span class="fw-semibold">{{ $row['path'] }}</span>
                                @else
                                    <span style="display:inline-block; padding-left: {{ $row['depth'] * 1.5 }}rem;"
                                        class="{{ $row['depth'] < 2 ? 'fw-semibold' : '' }}">
                                        @if ($row['depth'] > 0)
                                            <i class="bi bi-arrow-return-right text-muted" aria-hidden="true"></i>
                                        @endif
                                        {{ $category->name }}
                                    </span>
                                @endif
                            </td>
                            <td><span class="badge text-bg-light border">{{ $category->levelLabel() }}</span></td>
                            <td>
                                @if ($category->requires_authority)
                                    <span class="badge text-bg-warning">Ya</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>{{ $category->sort_order }}</td>
                            <td class="text-end text-nowrap">
                                @if ($category->childLevel())
                                    <a class="btn btn-light btn-sm"
                                        href="{{ route('admin.document-categories.create', ['parent_id' => $category->id]) }}"
                                        title="Tambah {{ \App\Models\DocumentCategory::LEVEL_LABELS[$category->childLevel()] }}">
                                        <i class="bi bi-plus-lg" aria-hidden="true"></i>
                                    </a>
                                @endif
                                <a class="btn btn-light btn-sm"
                                    href="{{ route('admin.document-categories.edit', $category) }}">
                                    <i class="bi bi-pencil" aria-hidden="true"></i>
                                </a>
                                <form action="{{ route('admin.document-categories.destroy', $category) }}" method="POST"
                                    class="d-inline" onsubmit="return confirm('Hapus {{ addslashes($category->name) }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-light btn-sm text-danger"
                                        @disabled($row['has_children'])>
                                        <i class="bi bi-trash" aria-hidden="true"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">Belum ada data. Jalankan seeder atau
                                tambah
                                manual.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
@endsection
