<div class="row g-3">
    @isset($parents)
        <div class="col-12">
            <label class="form-label" for="parent_id">Induk</label>
            <select class="form-select @error('parent_id') is-invalid @enderror" id="parent_id" name="parent_id">
                <option value="">— Tanpa induk (Jenis Kegiatan baru) —</option>
                @foreach ($parents as $parent)
                    <option value="{{ $parent['id'] }}" @selected(old('parent_id', $selectedParent) == $parent['id'])>
                        {{ $parent['label'] }}</option>
                @endforeach
            </select>
            @error('parent_id')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
            <div class="form-text">Level otomatis mengikuti induk yang dipilih.</div>
        </div>
    @else
        <div class="col-12">
            <label class="form-label">Level</label>
            <input class="form-control" type="text" value="{{ $category->levelLabel() }}" disabled>
        </div>
    @endisset

    <div class="col-md-8">
        <label class="form-label" for="name">Nama</label>
        <input class="form-control @error('name') is-invalid @enderror" id="name" name="name" type="text"
            value="{{ old('name', $category->name ?? '') }}" required>
        @error('name')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-4">
        <label class="form-label" for="sort_order">Urutan</label>
        <input class="form-control @error('sort_order') is-invalid @enderror" id="sort_order" name="sort_order"
            type="number" min="0" value="{{ old('sort_order', $category->sort_order ?? 0) }}">
        @error('sort_order')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-12">
        <div class="form-check">
            <input type="hidden" name="requires_authority" value="0">
            <input class="form-check-input" type="checkbox" id="requires_authority" name="requires_authority"
                value="1" @checked(old('requires_authority', $category->requires_authority ?? false))>
            <label class="form-check-label" for="requires_authority">
                Butuh Status Kewenangan (Kab/Kota, Provinsi, Kementerian)
            </label>
        </div>
    </div>
</div>

<div class="d-flex justify-content-end gap-2 mt-4">
    <a href="{{ route('admin.document-categories.index') }}" class="btn btn-outline-secondary">Batal</a>
    <button class="btn btn-primary" type="submit"><i class="bi bi-save" aria-hidden="true"></i> Simpan</button>
</div>
