@extends('layouts.admin')

@section('title', 'Tambah Kategori Dokumen')

@section('page-content')
    @php
        $levelLabels = ['kategori' => 'Kategori', 'sub_kategori' => 'Sub Kategori', 'dokumen' => 'Jenis Dokumen'];
    @endphp
    <div class="page-heading">
        <div class="page-heading-copy">
            <span class="page-icon"><i class="bi bi-diagram-3" aria-hidden="true"></i></span>
            <div>
                <p class="eyebrow mb-1">Master Data</p>
                <h1 class="h3 mb-1">Tambah {{ $levelLabels[$level] }}</h1>
            </div>
        </div>
    </div>

    <section class="panel mt-3">
        @include('admin.document-categories._tabs')

        <form method="POST" action="{{ route('admin.document-categories.store') }}">
            @csrf
            <input type="hidden" name="level" value="{{ $level }}">

            <div class="row g-3">
                @if ($level === 'sub_kategori' || $level === 'dokumen')
                    <div class="col-md-6">
                        <label class="form-label" for="kategori_id">Kategori</label>
                        <select class="form-select @error('kategori_id') is-invalid @enderror" id="kategori_id"
                            name="kategori_id" required>
                            <option value="">Pilih Kategori</option>
                            @foreach ($kategoriList as $k)
                                <option value="{{ $k->id }}" @selected(old('kategori_id') == $k->id)>{{ $k->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('kategori_id')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                @endif

                @if ($level === 'dokumen')
                    <div class="col-md-6">
                        <label class="form-label" for="sub_kategori_id">Sub Kategori</label>
                        <select class="form-select @error('sub_kategori_id') is-invalid @enderror" id="sub_kategori_id"
                            name="sub_kategori_id" required>
                            <option value="">Pilih Kategori dulu</option>
                        </select>
                        @error('sub_kategori_id')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                @endif

                <div class="col-md-8">
                    <label class="form-label" for="name">Nama</label>
                    <input class="form-control @error('name') is-invalid @enderror" id="name" name="name"
                        type="text" value="{{ old('name') }}" required>
                    @error('name')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label" for="sort_order">Urutan</label>
                    <input class="form-control @error('sort_order') is-invalid @enderror" id="sort_order" name="sort_order"
                        type="number" min="0" value="{{ old('sort_order', 0) }}">
                    @error('sort_order')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                @if ($level === 'dokumen')
                    <div class="col-12">
                        <div class="form-check">
                            <input type="hidden" name="requires_authority" value="0">
                            <input class="form-check-input" type="checkbox" id="requires_authority"
                                name="requires_authority" value="1" @checked(old('requires_authority'))>
                            <label class="form-check-label" for="requires_authority">
                                Butuh Status Kewenangan (Kab/Kota, Provinsi, Kementerian)
                            </label>
                        </div>
                    </div>
                @endif
            </div>

            <div class="d-flex justify-content-end gap-2 mt-4">
                <a href="{{ route('admin.document-categories.index', ['tab' => $level]) }}"
                    class="btn btn-outline-secondary">Batal</a>
                <button class="btn btn-primary" type="submit"><i class="bi bi-save" aria-hidden="true"></i>
                    Simpan</button>
            </div>
        </form>
    </section>
@endsection

@if ($level === 'dokumen')
    @push('scripts')
        <script>
            const subKategoriOptions = @json($subKategoriOptionsJs);
            const kategoriSelect = document.getElementById('kategori_id');
            const subKategoriSelect = document.getElementById('sub_kategori_id');

            kategoriSelect?.addEventListener('change', function(e) {
                subKategoriSelect.innerHTML = '<option value="">Pilih Sub Kategori</option>';
                subKategoriOptions
                    .filter((s) => String(s.parent_id) === e.target.value)
                    .forEach((s) => {
                        const opt = document.createElement('option');
                        opt.value = s.id;
                        opt.textContent = s.name;
                        subKategoriSelect.appendChild(opt);
                    });
            });
        </script>
    @endpush
@endif
