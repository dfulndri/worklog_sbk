@extends('layouts.admin')

@section('title', 'Edit Pekerjaan')

@section('page-content')
    <div class="page-heading">
        <div class="page-heading-copy">
            <span class="page-icon"><i class="bi bi-kanban" aria-hidden="true"></i></span>
            <div>
                <p class="eyebrow mb-1">Monitoring</p>
                <h1 class="h3 mb-1">Edit Pekerjaan</h1>
                <p class="text-muted mb-0">Perbarui data pekerjaan {{ $job->client->name ?? '' }}.</p>
            </div>
        </div>
    </div>

    <section class="panel mt-3">
        <form method="POST" action="{{ route('admin.jobs.update', $job) }}">
            @csrf
            @method('PUT')
            @include('admin.jobs._form')
        </form>
    </section>
@endsection
