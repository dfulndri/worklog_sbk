@extends('layouts.admin')

@section('title', 'Edit Kategori Dokumen')

@section('page-content')
    <div class="page-heading">
        <div class="page-heading-copy">
            <span class="page-icon"><i class="bi bi-diagram-3" aria-hidden="true"></i></span>
            <div>
                <p class="eyebrow mb-1">Master Data</p>
                <h1 class="h3 mb-1">Edit {{ $category->levelLabel() }}</h1>
                <p class="text-muted mb-0">{{ $category->pathLabel() }}</p>
            </div>
        </div>
    </div>

    <section class="panel mt-3">
        @include('admin.document-categories._tabs')

        <form method="POST" action="{{ route('admin.document-categories.update', $category) }}">
            @csrf
            @method('PUT')

            <div class="row g-3">
                <div class="col-md-8">
                    <label class="form-label" for="name">Nama</label>
                    <input class="form-control @error('name') is-invalid @enderror" id="name" name="name"
                        type="text" value="{{ old('name', $category->name) }}" required>
                    @error('name')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label" for="sort_order">Urutan</label>
                    <input class="form-control @error('sort_order') is-invalid @enderror" id="sort_order" name="sort_order"
                        type="number" min="0" value="{{ old('sort_order', $category->sort_order) }}">
                    @error('sort_order')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                @if ($category->level === 'dokumen')
                    <div class="col-12">
                        <div class="form-check">
                            <input type="hidden" name="requires_authority" value="0">
                            <input class="form-check-input" type="checkbox" id="requires_authority"
                                name="requires_authority" value="1" @checked(old('requires_authority', $category->requires_authority))>
                            <label class="form-check-label" for="requires_authority">
                                Butuh Status Kewenangan (Kab/Kota, Provinsi, Kementerian)
                            </label>
                        </div>
                    </div>
                @endif
            </div>

            <div class="d-flex justify-content-end gap-2 mt-4">
                <a href="{{ route('admin.document-categories.index', ['tab' => $category->level]) }}"
                    class="btn btn-outline-secondary">Batal</a>
                <button class="btn btn-primary" type="submit"><i class="bi bi-save" aria-hidden="true"></i>
                    Simpan</button>
            </div>
        </form>
    </section>
@endsection
