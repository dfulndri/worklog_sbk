@extends('layouts.admin')

@section('title', 'Edit Karyawan')

@section('page-content')
    <div class="page-heading">
        <div class="page-heading-copy">
            <span class="page-icon"><i class="bi bi-people" aria-hidden="true"></i></span>
            <div>
                <p class="eyebrow mb-1">Master Data</p>
                <h1 class="h3 mb-1">Edit Karyawan</h1>
                <p class="text-muted mb-0">Perbarui data {{ $employee->user->name }}.</p>
            </div>
        </div>
    </div>

    <section class="panel mt-3">
        <form method="POST" action="{{ route('admin.employees.update', $employee) }}">
            @csrf
            @method('PUT')

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label" for="name">Nama Lengkap</label>
                    <input class="form-control @error('name') is-invalid @enderror" id="name" name="name"
                        type="text" value="{{ old('name', $employee->user->name) }}" required>
                    @error('name')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label" for="email">Email (untuk login)</label>
                    <input class="form-control @error('email') is-invalid @enderror" id="email" name="email"
                        type="email" value="{{ old('email', $employee->user->email) }}" required>
                    @error('email')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label" for="password">Password Baru <span class="text-muted small">(kosongkan jika
                            tidak diganti)</span></label>
                    <input class="form-control @error('password') is-invalid @enderror" id="password" name="password"
                        type="password" minlength="6">
                    @error('password')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label" for="phone">No. HP</label>
                    <input class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone"
                        type="text" value="{{ old('phone', $employee->phone) }}">
                    @error('phone')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label" for="position">Jabatan</label>
                    <input class="form-control @error('position') is-invalid @enderror" id="position" name="position"
                        type="text" value="{{ old('position', $employee->position) }}">
                    @error('position')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2 mt-4">
                <a href="{{ route('admin.employees.index') }}" class="btn btn-outline-secondary">Batal</a>
                <button class="btn btn-primary" type="submit">
                    <i class="bi bi-save" aria-hidden="true"></i> Simpan
                </button>
            </div>
        </form>
    </section>
@endsection
