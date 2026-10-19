<div class="row g-3">
    @isset($kategoriList)
        <div class="col-md-4">
            <label class="form-label" for="category_id">Kategori</label>
            <select class="form-select @error('category_id') is-invalid @enderror" id="category_id" name="category_id"
                required>
                <option value="">Pilih Kategori</option>
                @foreach ($kategoriList as $k)
                    <option value="{{ $k->id }}" @selected(old('category_id', $kategoriId) == $k->id)>{{ $k->name }}</option>
                @endforeach
            </select>
            @error('category_id')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>
    @else
        <div class="col-md-4">
            <label class="form-label">Kategori</label>
            <input class="form-control" type="text" value="{{ $stage->category->name ?? '-' }}" disabled>
        </div>
    @endisset

    <div class="col-md-8">
        <label class="form-label" for="name">Nama Tahapan</label>
        <input class="form-control @error('name') is-invalid @enderror" id="name" name="name" type="text"
            value="{{ old('name', $stage->name ?? '') }}" required>
        @error('name')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-4">
        <label class="form-label" for="phase">Progress</label>
        @php $currentPhase = old('phase', $stage->phase ?? 1); @endphp
        <select class="form-select @error('phase') is-invalid @enderror" id="phase" name="phase" required>
            @foreach (\App\Models\JobStage::PHASES as $value => $label)
                <option value="{{ $value }}" @selected((int) $currentPhase === $value)>{{ $label }}
                    ({{ $value * 25 }}%)</option>
            @endforeach
        </select>
        @error('phase')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-4">
        <label class="form-label" for="order_no">Urutan</label>
        <input class="form-control @error('order_no') is-invalid @enderror" id="order_no" name="order_no"
            type="number" min="0" value="{{ old('order_no', $stage->order_no ?? 0) }}">
        @error('order_no')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-12">
        <div class="form-text">Bobot otomatis mengikuti Progress (Progress 1 = 25%, Progress 4 = 100%) dan menjadi
            saran progress saat tahapan ini dipilih.</div>
    </div>
</div>

<div class="d-flex justify-content-end gap-2 mt-4">
    <a href="{{ route('admin.job-stages.index', ['kategori_id' => $kategoriId ?? ($stage->category_id ?? null)]) }}"
        class="btn btn-outline-secondary">Batal</a>
    <button class="btn btn-primary" type="submit"><i class="bi bi-save" aria-hidden="true"></i> Simpan</button>
</div>
