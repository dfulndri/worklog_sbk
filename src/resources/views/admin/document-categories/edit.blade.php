@extends('layouts.admin')

@section('title', 'Edit Kategori Dokumen')

@section('page-content')
    <div class="page-heading">
        <div class="page-heading-copy">
            <span class="page-icon"><i class="bi bi-diagram-3" aria-hidden="true"></i></span>
            <div>
                <p class="eyebrow mb-1">Master Data</p>
                <h1 class="h3 mb-1">Edit Kategori Dokumen</h1>
                <p class="text-muted mb-0">{{ $category->pathLabel() }}</p>
            </div>
        </div>
    </div>

    <section class="panel mt-3">
        <form method="POST" action="{{ route('admin.document-categories.update', $category) }}">
            @csrf
            @method('PUT')
            @include('admin.document-categories._form')
        </form>
    </section>
@endsection
