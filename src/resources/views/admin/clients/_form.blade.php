<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label" for="name">Nama Klien / PT</label>
        <input class="form-control @error('name') is-invalid @enderror" id="name" name="name" type="text"
            value="{{ old('name', $client->name ?? '') }}" required>
        @error('name')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label class="form-label" for="contact">Kontak (telepon/email)</label>
        <input class="form-control @error('contact') is-invalid @enderror" id="contact" name="contact" type="text"
            value="{{ old('contact', $client->contact ?? '') }}">
        @error('contact')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label class="form-label" for="status">Status</label>
        <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
            @php $currentStatus = old('status', $client->status ?? 'aktif'); @endphp
            <option value="aktif" @selected($currentStatus === 'aktif')>Aktif</option>
            <option value="nonaktif" @selected($currentStatus === 'nonaktif')>Nonaktif</option>
        </select>
        @error('status')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-12">
        <label class="form-label" for="address">Alamat</label>
        <textarea class="form-control @error('address') is-invalid @enderror" id="address" name="address" rows="3">{{ old('address', $client->address ?? '') }}</textarea>
        @error('address')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>
</div>

<div class="d-flex justify-content-end gap-2 mt-4">
    <a href="{{ route('admin.clients.index') }}" class="btn btn-outline-secondary">Batal</a>
    <button class="btn btn-primary" type="submit">
        <i class="bi bi-save" aria-hidden="true"></i> Simpan
    </button>
</div>
