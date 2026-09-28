<div class="row g-3">
    <div class="col-md-8">
        <label class="form-label" for="name">Nama</label>
        <input class="form-control @error('name') is-invalid @enderror" id="name" name="name" type="text"
            value="{{ old('name', $authority->name ?? '') }}" placeholder="mis. Provinsi" required>
        @error('name')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-4">
        <label class="form-label" for="sort_order">Urutan</label>
        <input class="form-control @error('sort_order') is-invalid @enderror" id="sort_order" name="sort_order"
            type="number" min="0" value="{{ old('sort_order', $authority->sort_order ?? 0) }}">
        @error('sort_order')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>
</div>

<div class="d-flex justify-content-end gap-2 mt-4">
    <a href="{{ route('admin.authorities.index') }}" class="btn btn-outline-secondary">Batal</a>
    <button class="btn btn-primary" type="submit"><i class="bi bi-save" aria-hidden="true"></i> Simpan</button>
</div>
