@php
    $selectedJob = old('job_task_id', $report->job_task_id ?? '');
    $selectedStage = old('stage_id', $report->stage_id ?? '');
    $stageWeightsJs = $stages->pluck('weight', 'id');
@endphp

<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label" for="job_task_id">Pekerjaan</label>
        <select class="form-select @error('job_task_id') is-invalid @enderror" id="job_task_id" name="job_task_id"
            required>
            <option value="">Pilih Pekerjaan</option>
            @foreach ($jobs as $job)
                <option value="{{ $job->id }}" data-progress="{{ $job->progress }}" data-stage="{{ $job->stage_id }}"
                    @selected($selectedJob == $job->id)>
                    {{ $job->client->name ?? '-' }} — {{ $job->documentCategory->name ?? '-' }}
                    ({{ $job->progress }}%)
                </option>
            @endforeach
        </select>
        @error('job_task_id')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-3">
        <label class="form-label" for="report_date">Tanggal</label>
        <input class="form-control @error('report_date') is-invalid @enderror" id="report_date" name="report_date"
            type="date"
            value="{{ old('report_date', isset($report) ? $report->report_date->format('Y-m-d') : now()->format('Y-m-d')) }}"
            required>
        @error('report_date')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-3">
        <label class="form-label" for="stage_id">Tahapan</label>
        <select class="form-select @error('stage_id') is-invalid @enderror" id="stage_id" name="stage_id" required>
            <option value="">Pilih Tahapan</option>
            @foreach ($stages as $stage)
                <option value="{{ $stage->id }}" @selected($selectedStage == $stage->id)>{{ $stage->name }}</option>
            @endforeach
        </select>
        @error('stage_id')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-3">
        <label class="form-label" for="progress">Progress Terbaru (%)</label>
        <input class="form-control @error('progress') is-invalid @enderror" id="progress" name="progress"
            type="number" min="0" max="100" value="{{ old('progress', $report->progress ?? 0) }}"
            required>
        <div class="form-text">Otomatis ke-isi dari bobot tahapan, tetap bisa diubah manual.</div>
        @error('progress')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-12">
        <label class="form-label" for="description">Uraian Pekerjaan</label>
        <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description"
            rows="4" placeholder="Ceritakan aktivitas yang kamu kerjakan hari ini" required>{{ old('description', $report->description ?? '') }}</textarea>
        @error('description')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label class="form-label" for="obstacle">Kendala <span class="text-muted small">(opsional)</span></label>
        <textarea class="form-control @error('obstacle') is-invalid @enderror" id="obstacle" name="obstacle" rows="3">{{ old('obstacle', $report->obstacle ?? '') }}</textarea>
        @error('obstacle')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label class="form-label" for="next_plan">Rencana Berikutnya <span
                class="text-muted small">(opsional)</span></label>
        <textarea class="form-control @error('next_plan') is-invalid @enderror" id="next_plan" name="next_plan" rows="3">{{ old('next_plan', $report->next_plan ?? '') }}</textarea>
        @error('next_plan')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>
</div>

<div class="d-flex justify-content-end gap-2 mt-4">
    <a href="{{ route('karyawan.daily-reports.index') }}" class="btn btn-outline-secondary">Batal</a>
    <button class="btn btn-primary" type="submit">
        <i class="bi bi-save" aria-hidden="true"></i> Simpan Laporan
    </button>
</div>

@push('scripts')
    <script>
        (function() {
            const stageWeights = @json($stageWeightsJs);
            const jobSelect = document.getElementById('job_task_id');
            const stageSelect = document.getElementById('stage_id');
            const progressInput = document.getElementById('progress');

            // Pilih pekerjaan -> tahapan ikut ke tahapan terakhir pekerjaan itu (kalau ada).
            jobSelect?.addEventListener('change', function(e) {
                const selected = e.target.options[e.target.selectedIndex];
                const stageId = selected.getAttribute('data-stage');
                if (stageId && stageId !== '') {
                    stageSelect.value = stageId;
                } else {
                    const currentProgress = selected.getAttribute('data-progress');
                    if (currentProgress !== null) {
                        progressInput.value = currentProgress;
                    }
                    return;
                }
                const weight = stageWeights[stageSelect.value];
                if (weight !== undefined) {
                    progressInput.value = weight;
                }
            });

            // Pilih tahapan -> progress otomatis ikut bobotnya (tetap bisa diubah manual).
            stageSelect?.addEventListener('change', function(e) {
                const weight = stageWeights[e.target.value];
                if (weight !== undefined) {
                    progressInput.value = weight;
                }
            });
        })();
    </script>
@endpush
