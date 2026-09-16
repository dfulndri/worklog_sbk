@extends('layouts.admin')

@section('title', 'Tambah Jenis Dokumen')

@section('page-content')
    <div class="page-heading">
        <div class="page-heading-copy">
            <span class="page-icon"><i class="bi bi-file-earmark-text" aria-hidden="true"></i></span>
            <div>
                <p class="eyebrow mb-1">Master Data</p>
                <h1 class="h3 mb-1">Tambah Jenis Dokumen</h1>
            </div>
        </div>
    </div>

    <section class="panel mt-3">
        <form method="POST" action="{{ route('admin.document-types.store') }}">
            @csrf
            @include('admin.document-types._form')
        </form>
    </section>
@endsection
