@extends('layouts.admin')

@section('title', 'Tahapan Proses')
@section('search-placeholder', 'Cari tahapan...')

@section('page-content')
    <div class="page-heading">
        <div class="page-heading-copy">
            <span class="page-icon"><i class="bi bi-list-ol" aria-hidden="true"></i></span>
            <div>
                <p class="eyebrow mb-1">Master Data</p>
                <h1 class="h3 mb-1">Tahapan Proses</h1>
                <p class="text-muted mb-0">Tahapan tiap kategori, dikelompokkan Progress 1-4 (25% / 50% / 75% / 100%).</p>
            </div>
        </div>
        <div class="heading-actions">
            <a href="{{ route('admin.job-stages.create', ['kategori_id' => $kategoriId]) }}" class="btn btn-primary btn-sm">
                <i class="bi bi-plus-lg" aria-hidden="true"></i> Tambah
            </a>
        </div>
    </div>

    <section class="panel mt-3">
        @php $currentKategoriId = $kategoriId; @endphp
        @include('admin.job-stages._tabs')

        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Tahapan</th>
                        <th scope="col">Progress</th>
                        <th scope="col">Bobot</th>
                        <th scope="col" class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($stages as $stage)
                        <tr>
                            <td>{{ $stage->order_no }}</td>
                            <td class="fw-semibold">{{ $stage->name }}</td>
                            <td><span class="badge {{ $stage->phaseBadge() }}">{{ $stage->phaseLabel() }}</span></td>
                            <td>{{ $stage->weight }}%</td>
                            <td class="text-end">
                                <a class="btn btn-light btn-sm" href="{{ route('admin.job-stages.edit', $stage) }}">
                                    <i class="bi bi-pencil" aria-hidden="true"></i>
                                </a>
                                <form action="{{ route('admin.job-stages.destroy', $stage) }}" method="POST"
                                    class="d-inline"
                                    onsubmit="return confirm('Hapus tahapan {{ addslashes($stage->name) }}?')">
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
                            <td colspan="5" class="text-center text-muted py-4">Belum ada tahapan di kategori ini.
                                Jalankan JobStageSeeder atau tambah manual.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
@endsection
