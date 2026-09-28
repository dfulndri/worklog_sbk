@extends('layouts.admin')

@section('title', 'Edit Tahapan')

@section('page-content')
    <div class="page-heading">
        <div class="page-heading-copy">
            <span class="page-icon"><i class="bi bi-list-ol" aria-hidden="true"></i></span>
            <div>
                <p class="eyebrow mb-1">Master Data</p>
                <h1 class="h3 mb-1">Edit Tahapan</h1>
                <p class="text-muted mb-0">Perbarui {{ $stage->name }}.</p>
            </div>
        </div>
    </div>

    <section class="panel mt-3">
        <form method="POST" action="{{ route('admin.job-stages.update', $stage) }}">
            @csrf
            @method('PUT')
            @include('admin.job-stages._form')
        </form>
    </section>
@endsection
