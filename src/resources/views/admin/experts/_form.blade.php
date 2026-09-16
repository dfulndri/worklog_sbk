<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label" for="name">Nama Tenaga Ahli</label>
        <input class="form-control @error('name') is-invalid @enderror" id="name" name="name" type="text"
            value="{{ old('name', $expert->name ?? '') }}" required>
        @error('name')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label class="form-label" for="field">Bidang Keahlian</label>
        <input class="form-control @error('field') is-invalid @enderror" id="field" name="field" type="text"
            value="{{ old('field', $expert->field ?? '') }}" placeholder="mis. Lingkungan, Struktur, dll.">
        @error('field')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>
</div>

<div class="d-flex justify-content-end gap-2 mt-4">
    <a href="{{ route('admin.experts.index') }}" class="btn btn-outline-secondary">Batal</a>
    <button class="btn btn-primary" type="submit">
        <i class="bi bi-save" aria-hidden="true"></i> Simpan
    </button>
</div>
