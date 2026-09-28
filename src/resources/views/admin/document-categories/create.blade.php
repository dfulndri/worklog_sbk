@extends('layouts.admin')

@section('title', 'Tambah Kategori Dokumen')

@section('page-content')
    <div class="page-heading">
        <div class="page-heading-copy">
            <span class="page-icon"><i class="bi bi-diagram-3" aria-hidden="true"></i></span>
            <div>
                <p class="eyebrow mb-1">Master Data</p>
                <h1 class="h3 mb-1">Tambah Kategori Dokumen</h1>
            </div>
        </div>
    </div>

    <section class="panel mt-3">
        <form method="POST" action="{{ route('admin.document-categories.store') }}">
            @csrf
            @include('admin.document-categories._form')
        </form>
    </section>
@endsection
