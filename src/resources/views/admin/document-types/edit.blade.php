@extends('layouts.admin')

@section('title', 'Edit Jenis Dokumen')

@section('page-content')
    <div class="page-heading">
        <div class="page-heading-copy">
            <span class="page-icon"><i class="bi bi-file-earmark-text" aria-hidden="true"></i></span>
            <div>
                <p class="eyebrow mb-1">Master Data</p>
                <h1 class="h3 mb-1">Edit Jenis Dokumen</h1>
                <p class="text-muted mb-0">Perbarui data {{ $document_type->name }}.</p>
            </div>
        </div>
    </div>

    <section class="panel mt-3">
        <form method="POST" action="{{ route('admin.document-types.update', $document_type) }}">
            @csrf
            @method('PUT')
            @include('admin.document-types._form')
        </form>
    </section>
@endsection
