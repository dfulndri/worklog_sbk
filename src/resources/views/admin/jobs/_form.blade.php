@php
    $selectedClient = old('client_id', $job->client_id ?? '');
    $selectedExpert = old('expert_id', $job->expert_id ?? '');
    $selectedEmployee = old('employee_id', $job->employee_id ?? '');
    $selectedDocType = old('document_type_id', $job->document_type_id ?? '');
    $selectedStage = old('stage', $job->stage ?? 'draft');
    $currentProgress = old('progress', $job->progress ?? 0);
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
        <label class="form-label" for="document_type_id">Jenis Dokumen</label>
        <select class="form-select @error('document_type_id') is-invalid @enderror" id="document_type_id"
            name="document_type_id" required>
            <option value="">Pilih Jenis Dokumen</option>
            @foreach ($documentTypes as $documentType)
                <option value="{{ $documentType->id }}" @selected($selectedDocType == $documentType->id)>{{ $documentType->name }}</option>
            @endforeach
        </select>
        @error('document_type_id')
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

    <div class="col-12">
        <label class="form-label" for="title">Judul / Catatan Pekerjaan <span
                class="text-muted small">(opsional)</span></label>
        <input class="form-control @error('title') is-invalid @enderror" id="title" name="title" type="text"
            value="{{ old('title', $job->title ?? '') }}" placeholder="mis. Pertek Air PT Mekar Tahap 1">
        @error('title')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-4">
        <label class="form-label" for="stage">Tahapan</label>
        <select class="form-select @error('stage') is-invalid @enderror" id="stage" name="stage" required>
            <option value="draft" @selected($selectedStage === 'draft')>Draft</option>
            <option value="revisi" @selected($selectedStage === 'revisi')>Revisi</option>
            <option value="sidang" @selected($selectedStage === 'sidang')>Sidang</option>
            <option value="final" @selected($selectedStage === 'final')>Final</option>
        </select>
        @error('stage')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-4">
        <label class="form-label" for="progress">Progress (%)</label>
        <input class="form-control @error('progress') is-invalid @enderror" id="progress" name="progress"
            type="number" min="0" max="100" value="{{ $currentProgress }}" required>
        @error('progress')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-4">
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
