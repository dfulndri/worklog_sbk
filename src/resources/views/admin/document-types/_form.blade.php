<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label" for="name">Nama Jenis Dokumen</label>
        <input class="form-control @error('name') is-invalid @enderror" id="name" name="name" type="text"
            value="{{ old('name', $document_type->name ?? '') }}" placeholder="mis. Pertek Air, UKL-UPL" required>
        @error('name')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label class="form-label" for="category">Kategori</label>
        <input class="form-control @error('category') is-invalid @enderror" id="category" name="category"
            type="text" value="{{ old('category', $document_type->category ?? '') }}"
            placeholder="mis. Lingkungan, Bangunan">
        @error('category')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>
</div>

<div class="d-flex justify-content-end gap-2 mt-4">
    <a href="{{ route('admin.document-types.index') }}" class="btn btn-outline-secondary">Batal</a>
    <button class="btn btn-primary" type="submit">
        <i class="bi bi-save" aria-hidden="true"></i> Simpan
    </button>
</div>
