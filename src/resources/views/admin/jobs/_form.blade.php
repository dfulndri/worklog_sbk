@php
    $selectedClient = old('client_id', $job->client_id ?? '');
    $selectedExpert = old('expert_id', $job->expert_id ?? '');
    $selectedEmployee = old('employee_id', $job->employee_id ?? '');
    $selectedStage = old('stage_id', $job->stage_id ?? '');
    $selectedAuthority = old('authority_id', $job->authority_id ?? '');
    $currentProgress = old('progress', $job->progress ?? 0);

    $sel = $selected ?? ['kategori_id' => old('_kategori_id'), 'sub_kategori_id' => null, 'dokumen_id' => null];
    $currentCategoryId = old('document_category_id', $job->document_category_id ?? '');
    $stageWeightsJs = $stages->pluck('weight', 'id');
@endphp

<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label" for="client_id">PT / Klien</label>
        <select class="form-select @error('client_id') is-invalid @enderror" id="client_id" name="client_id" required>
            <option value="">Pilih Klien</option>
            @foreach ($clients as $client)
                <option value="{{ $client->id }}" @selected($selectedClient == $client->id)>{{ $client->name }}</option>
            @endforeach
        </select>
        @error('client_id')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label class="form-label" for="employee_id">PIC (Karyawan)</label>
        <select class="form-select @error('employee_id') is-invalid @enderror" id="employee_id" name="employee_id"
            required>
            <option value="">Pilih PIC</option>
            @foreach ($employees as $employee)
                <option value="{{ $employee->id }}" @selected($selectedEmployee == $employee->id)>{{ $employee->user->name }}</option>
            @endforeach
        </select>
        @error('employee_id')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label class="form-label" for="expert_id">Tenaga Ahli <span class="text-muted small">(opsional)</span></label>
        <select class="form-select @error('expert_id') is-invalid @enderror" id="expert_id" name="expert_id">
            <option value="">Tidak ada</option>
            @foreach ($experts as $expert)
                <option value="{{ $expert->id }}" @selected($selectedExpert == $expert->id)>{{ $expert->name }}</option>
            @endforeach
        </select>
        @error('expert_id')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label class="form-label" for="stage_id">Tahapan</label>
        <select class="form-select @error('stage_id') is-invalid @enderror" id="stage_id" name="stage_id" required>
            <option value="">Pilih Tahapan</option>
            @foreach ($stages as $stage)
                <option value="{{ $stage->id }}" @selected($selectedStage == $stage->id)>
                    {{ $stage->name }} ({{ $stage->groupLabel() }})</option>
            @endforeach
        </select>
        @error('stage_id')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>
</div>

<hr class="my-4">
<h2 class="h6 section-title mb-3"><i class="bi bi-diagram-3" aria-hidden="true"></i><span>Jenis Dokumen</span></h2>

<div class="row g-3">
    <div class="col-md-4">
        <label class="form-label" for="kategori_select">Kategori</label>
        <select class="form-select" id="kategori_select">
            <option value="">Pilih Kategori</option>
            @foreach ($kategoriList as $k)
                <option value="{{ $k->id }}" @selected($sel['kategori_id'] == $k->id)>{{ $k->name }}</option>
            @endforeach
        </select>
    </div>

    <div class="col-md-4">
        <label class="form-label" for="sub_kategori_select">Sub Kategori</label>
        <select class="form-select" id="sub_kategori_select" disabled>
            <option value="">Pilih Kategori dulu</option>
        </select>
    </div>

    <div class="col-md-4" id="dokumen_wrapper" style="display:none;">
        <label class="form-label" for="dokumen_select">Jenis Dokumen</label>
        <select class="form-select" id="dokumen_select">
            <option value="">Pilih Sub Kategori dulu</option>
        </select>
    </div>

    <input type="hidden" name="document_category_id" id="document_category_id_input" value="{{ $currentCategoryId }}">
    @error('document_category_id')
        <div class="col-12">
            <div class="text-danger small">{{ $message }}</div>
        </div>
    @enderror

    <div class="col-md-6" id="authority_wrapper" style="display:none;">
        <label class="form-label" for="authority_id">Status Kewenangan</label>
        <select class="form-select @error('authority_id') is-invalid @enderror" id="authority_id" name="authority_id">
            <option value="">Pilih Status Kewenangan</option>
            @foreach ($authorities as $authority)
                <option value="{{ $authority->id }}" @selected($selectedAuthority == $authority->id)>{{ $authority->name }}</option>
            @endforeach
        </select>
        @error('authority_id')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
        <div class="form-text">Wajib diisi untuk jenis dokumen lingkungan ini.</div>
    </div>
</div>

<hr class="my-4">

<div class="row g-3">
    <div class="col-12">
        <label class="form-label" for="title">Judul / Catatan Pekerjaan <span
                class="text-muted small">(opsional)</span></label>
        <input class="form-control @error('title') is-invalid @enderror" id="title" name="title" type="text"
            value="{{ old('title', $job->title ?? '') }}" placeholder="mis. Pertek Air PT Mekar Tahap 1">
        @error('title')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label class="form-label" for="progress">Progress (%)</label>
        <input class="form-control @error('progress') is-invalid @enderror" id="progress" name="progress"
            type="number" min="0" max="100" value="{{ $currentProgress }}" required>
        @error('progress')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label class="form-label" for="deadline">Deadline</label>
        <input class="form-control @error('deadline') is-invalid @enderror" id="deadline" name="deadline"
            type="date"
            value="{{ old('deadline', isset($job) && $job->deadline ? $job->deadline->format('Y-m-d') : '') }}">
        @error('deadline')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>
</div>

<div class="d-flex justify-content-end gap-2 mt-4">
    <a href="{{ route('admin.jobs.index') }}" class="btn btn-outline-secondary">Batal</a>
    <button class="btn btn-primary" type="submit">
        <i class="bi bi-save" aria-hidden="true"></i> Simpan
    </button>
</div>

@push('scripts')
    <script>
        (function() {
            const subKategoriOptions = @json($subKategoriOptionsJs);
            const dokumenOptions = @json($dokumenOptionsJs);
            const initialSelection = @json($sel);
            const stageWeights = @json($stageWeightsJs);

            const kategoriSelect = document.getElementById('kategori_select');
            const subKategoriSelect = document.getElementById('sub_kategori_select');
            const dokumenSelect = document.getElementById('dokumen_select');
            const dokumenWrapper = document.getElementById('dokumen_wrapper');
            const authorityWrapper = document.getElementById('authority_wrapper');
            const authoritySelect = document.getElementById('authority_id');
            const hiddenInput = document.getElementById('document_category_id_input');

            function fillSelect(select, options, placeholder, selectedId) {
                select.innerHTML = '<option value="">' + placeholder + '</option>';
                options.forEach(function(opt) {
                    const el = document.createElement('option');
                    el.value = opt.id;
                    el.textContent = opt.name;
                    if (selectedId && String(opt.id) === String(selectedId)) {
                        el.selected = true;
                    }
                    select.appendChild(el);
                });
            }

            function toggleAuthority(requiresAuthority) {
                authorityWrapper.style.display = requiresAuthority ? '' : 'none';
                if (!requiresAuthority) {
                    authoritySelect.value = '';
                }
            }

            function onSubKategoriResolved(subKategoriId, keepDokumenId) {
                const subItem = subKategoriOptions.find((s) => String(s.id) === String(subKategoriId));

                if (subItem && subItem.has_children) {
                    const children = dokumenOptions.filter((d) => String(d.parent_id) === String(subKategoriId));
                    fillSelect(dokumenSelect, children, 'Pilih Jenis Dokumen', keepDokumenId);
                    dokumenWrapper.style.display = '';
                    dokumenSelect.disabled = false;

                    if (keepDokumenId && children.some((c) => String(c.id) === String(keepDokumenId))) {
                        hiddenInput.value = keepDokumenId;
                        const docItem = dokumenOptions.find((d) => String(d.id) === String(keepDokumenId));
                        toggleAuthority(!!(docItem && docItem.requires_authority));
                    } else {
                        hiddenInput.value = '';
                        toggleAuthority(false);
                    }
                } else {
                    dokumenWrapper.style.display = 'none';
                    dokumenSelect.innerHTML = '';
                    hiddenInput.value = subKategoriId || '';
                    toggleAuthority(!!(subItem && subItem.requires_authority));
                }
            }

            kategoriSelect.addEventListener('change', function(e) {
                const children = subKategoriOptions.filter((s) => String(s.parent_id) === e.target.value);
                fillSelect(subKategoriSelect, children, 'Pilih Sub Kategori', null);
                subKategoriSelect.disabled = children.length === 0;
                dokumenWrapper.style.display = 'none';
                hiddenInput.value = '';
                toggleAuthority(false);
            });

            subKategoriSelect.addEventListener('change', function(e) {
                onSubKategoriResolved(e.target.value, null);
            });

            dokumenSelect.addEventListener('change', function(e) {
                hiddenInput.value = e.target.value;
                const docItem = dokumenOptions.find((d) => String(d.id) === e.target.value);
                toggleAuthority(!!(docItem && docItem.requires_authority));
            });

            // Pre-fill saat edit.
            if (initialSelection.kategori_id) {
                const subChildren = subKategoriOptions.filter((s) => String(s.parent_id) === String(initialSelection
                    .kategori_id));
                fillSelect(subKategoriSelect, subChildren, 'Pilih Sub Kategori', initialSelection.sub_kategori_id);
                subKategoriSelect.disabled = false;

                if (initialSelection.sub_kategori_id) {
                    onSubKategoriResolved(initialSelection.sub_kategori_id, initialSelection.dokumen_id);
                }
            }

            // Isi otomatis progress dengan bobot tahapan saat tahapan dipilih (tetap bisa diubah manual).
            const stageSelect = document.getElementById('stage_id');
            stageSelect?.addEventListener('change', function(e) {
                const weight = stageWeights[e.target.value];
                if (weight !== undefined) {
                    document.getElementById('progress').value = weight;
                }
            });
        })();
    </script>
@endpush
