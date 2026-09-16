@extends('layouts.admin')

@section('title', 'Tambah Klien')

@section('page-content')
    <div class="page-heading">
        <div class="page-heading-copy">
            <span class="page-icon"><i class="bi bi-building" aria-hidden="true"></i></span>
            <div>
                <p class="eyebrow mb-1">Master Data</p>
                <h1 class="h3 mb-1">Tambah PT / Klien</h1>
                <p class="text-muted mb-0">Isi data klien baru.</p>
            </div>
        </div>
    </div>

    <section class="panel mt-3">
        <form method="POST" action="{{ route('admin.clients.store') }}">
            @csrf
            @include('admin.clients._form')
        </form>
    </section>
@endsection
