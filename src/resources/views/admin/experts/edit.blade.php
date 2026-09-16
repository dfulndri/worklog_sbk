@extends('layouts.admin')

@section('title', 'Edit Tenaga Ahli')

@section('page-content')
    <div class="page-heading">
        <div class="page-heading-copy">
            <span class="page-icon"><i class="bi bi-person-badge" aria-hidden="true"></i></span>
            <div>
                <p class="eyebrow mb-1">Master Data</p>
                <h1 class="h3 mb-1">Edit Tenaga Ahli</h1>
                <p class="text-muted mb-0">Perbarui data {{ $expert->name }}.</p>
            </div>
        </div>
    </div>

    <section class="panel mt-3">
        <form method="POST" action="{{ route('admin.experts.update', $expert) }}">
            @csrf
            @method('PUT')
            @include('admin.experts._form')
        </form>
    </section>
@endsection
