@extends('layouts.admin')

@section('title', 'Edit Status Kewenangan')

@section('page-content')
    <div class="page-heading">
        <div class="page-heading-copy">
            <span class="page-icon"><i class="bi bi-bank" aria-hidden="true"></i></span>
            <div>
                <p class="eyebrow mb-1">Master Data</p>
                <h1 class="h3 mb-1">Edit Status Kewenangan</h1>
                <p class="text-muted mb-0">Perbarui {{ $authority->name }}.</p>
            </div>
        </div>
    </div>

    <section class="panel mt-3">
        <form method="POST" action="{{ route('admin.authorities.update', $authority) }}">
            @csrf
            @method('PUT')
            @include('admin.authorities._form')
        </form>
    </section>
@endsection
