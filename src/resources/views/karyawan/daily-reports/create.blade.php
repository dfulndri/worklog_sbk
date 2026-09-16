@extends('layouts.karyawan')

@section('title', 'Isi Daily Report')

@section('page-content')
    <div class="page-heading">
        <div class="page-heading-copy">
            <span class="page-icon"><i class="bi bi-journal-text" aria-hidden="true"></i></span>
            <div>
                <p class="eyebrow mb-1">Aktivitas Harian</p>
                <h1 class="h3 mb-1">Isi Daily Report</h1>
                <p class="text-muted mb-0">Catat aktivitas, progres, dan kendala pekerjaanmu hari ini.</p>
            </div>
        </div>
    </div>

    <section class="panel mt-3">
        @if ($jobs->isEmpty())
            <p class="text-muted text-center py-4 mb-0">
                Kamu belum memiliki pekerjaan yang ditugaskan. Hubungi admin untuk penugasan pekerjaan.
            </p>
        @else
            <form method="POST" action="{{ route('karyawan.daily-reports.store') }}">
                @csrf

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label" for="job_task_id">Pekerjaan</label>
                        <select class="form-select @error('job_task_id') is-invalid @enderror" id="job_task_id"
                            name="job_task_id" required>
                            <option value="">Pilih Pekerjaan</option>
                            @foreach ($jobs as $job)
                                <option value="{{ $job->id }}" data-progress="{{ $job->progress }}"
                                    @selected(old('job_task_id') == $job->id)>
                                    {{ $job->client->name ?? '-' }} — {{ $job->documentType->name ?? '-' }}
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
                        <input class="form-control @error('report_date') is-invalid @enderror" id="report_date"
                            name="report_date" type="date" value="{{ old('report_date', now()->format('Y-m-d')) }}"
                            required>
                        @error('report_date')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-3">
                        <label class="form-label" for="progress">Progress Terbaru (%)</label>
                        <input class="form-control @error('progress') is-invalid @enderror" id="progress" name="progress"
                            type="number" min="0" max="100" value="{{ old('progress', 0) }}" required>
                        @error('progress')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12">
                        <label class="form-label" for="description">Uraian Pekerjaan</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description"
                            rows="4" placeholder="Ceritakan aktivitas yang kamu kerjakan hari ini" required>{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label" for="obstacle">Kendala <span
                                class="text-muted small">(opsional)</span></label>
                        <textarea class="form-control @error('obstacle') is-invalid @enderror" id="obstacle" name="obstacle" rows="3">{{ old('obstacle') }}</textarea>
                        @error('obstacle')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label" for="next_plan">Rencana Berikutnya <span
                                class="text-muted small">(opsional)</span></label>
                        <textarea class="form-control @error('next_plan') is-invalid @enderror" id="next_plan" name="next_plan" rows="3">{{ old('next_plan') }}</textarea>
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
            </form>
        @endif
    </section>
@endsection

@push('scripts')
    <script>
        // Isi otomatis progress dengan nilai progress pekerjaan saat ini, saat pekerjaan dipilih.
        document.getElementById('job_task_id')?.addEventListener('change', function(e) {
            const selected = e.target.options[e.target.selectedIndex];
            const currentProgress = selected.getAttribute('data-progress');
            if (currentProgress !== null) {
                document.getElementById('progress').value = currentProgress;
            }
        });
    </script>
@endpush
