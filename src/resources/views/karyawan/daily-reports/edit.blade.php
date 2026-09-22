@extends('layouts.karyawan')

@section('title', 'Edit Daily Report')

@section('page-content')
    <div class="page-heading">
        <div class="page-heading-copy">
            <span class="page-icon"><i class="bi bi-journal-text" aria-hidden="true"></i></span>
            <div>
                <p class="eyebrow mb-1">Aktivitas Harian</p>
                <h1 class="h3 mb-1">Edit Daily Report</h1>
                <p class="text-muted mb-0">Perbarui laporan tanggal {{ $report->report_date->format('d M Y') }}.</p>
            </div>
        </div>
    </div>

    <section class="panel mt-3">
        @if ($jobs->isEmpty())
            <p class="text-muted text-center py-4 mb-0">
                Kamu belum memiliki pekerjaan yang ditugaskan. Hubungi admin untuk penugasan pekerjaan.
            </p>
        @else
            <form method="POST" action="{{ route('karyawan.daily-reports.update', $report) }}">
                @csrf
                @method('PUT')
                @include('karyawan.daily-reports._form')
            </form>
        @endif
    </section>
@endsection
