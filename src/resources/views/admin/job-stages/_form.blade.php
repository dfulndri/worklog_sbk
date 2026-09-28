<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label" for="name">Nama Tahapan</label>
        <input class="form-control @error('name') is-invalid @enderror" id="name" name="name" type="text"
            value="{{ old('name', $stage->name ?? '') }}" required>
        @error('name')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-2">
        <label class="form-label" for="order_no">Urutan</label>
        <input class="form-control @error('order_no') is-invalid @enderror" id="order_no" name="order_no"
            type="number" min="0" value="{{ old('order_no', $stage->order_no ?? 0) }}">
        @error('order_no')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-2">
        <label class="form-label" for="weight">Bobot (%)</label>
        <input class="form-control @error('weight') is-invalid @enderror" id="weight" name="weight" type="number"
            min="0" max="100" value="{{ old('weight', $stage->weight ?? 0) }}" required>
        @error('weight')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-2">
        <label class="form-label" for="group">Grup</label>
        @php $currentGroup = old('group', $stage->group ?? 'draft'); @endphp
        <select class="form-select @error('group') is-invalid @enderror" id="group" name="group" required>
            @foreach (\App\Models\JobStage::GROUPS as $value => $label)
                <option value="{{ $value }}" @selected($currentGroup === $value)>{{ $label }}</option>
            @endforeach
        </select>
        @error('group')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-12">
        <div class="form-text">Bobot = progress kumulatif yang otomatis disarankan saat karyawan memilih tahapan ini di
            Daily Report.</div>
    </div>
</div>

<div class="d-flex justify-content-end gap-2 mt-4">
    <a href="{{ route('admin.job-stages.index') }}" class="btn btn-outline-secondary">Batal</a>
    <button class="btn btn-primary" type="submit"><i class="bi bi-save" aria-hidden="true"></i> Simpan</button>
</div>
