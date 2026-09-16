@extends('layouts.admin')

@section('title', 'Tambah Tenaga Ahli')

@section('page-content')
    <div class="page-heading">
        <div class="page-heading-copy">
            <span class="page-icon"><i class="bi bi-person-badge" aria-hidden="true"></i></span>
            <div>
                <p class="eyebrow mb-1">Master Data</p>
                <h1 class="h3 mb-1">Tambah Tenaga Ahli</h1>
            </div>
        </div>
    </div>

    <section class="panel mt-3">
        <form method="POST" action="{{ route('admin.experts.store') }}">
            @csrf
            @include('admin.experts._form')
        </form>
    </section>
@endsection
