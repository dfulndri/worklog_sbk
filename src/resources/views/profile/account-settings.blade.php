@extends($layout)

@section('title', 'Account Settings')

@section('page-content')
    <div class="page-heading">
        <div class="page-heading-copy">
            <span class="page-icon"><i class="bi bi-gear" aria-hidden="true"></i></span>
            <div>
                <p class="eyebrow mb-1">Account</p>
                <h1 class="h3 mb-1">Account Settings</h1>
                <p class="text-muted mb-0">Kelola keamanan akun kamu.</p>
            </div>
        </div>
    </div>

    <section class="panel" style="max-width: 640px;">
        <div class="panel-header">
            <div>
                <h2 class="h5 mb-1 section-title"><i class="bi bi-shield-lock" aria-hidden="true"></i><span>Ganti
                        Password</span></h2>
                <p class="text-muted mb-0">Gunakan password yang kuat dan tidak dipakai di tempat lain.</p>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form method="POST" action="{{ route('account-settings.update') }}">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label" for="current_password">Password Saat Ini</label>
                <input class="form-control @error('current_password') is-invalid @enderror" id="current_password"
                    name="current_password" type="password" required>
                @error('current_password')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label" for="password">Password Baru</label>
                <input class="form-control @error('password') is-invalid @enderror" id="password" name="password"
                    type="password" minlength="6" required>
                @error('password')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label" for="password_confirmation">Konfirmasi Password Baru</label>
                <input class="form-control" id="password_confirmation" name="password_confirmation" type="password"
                    minlength="6" required>
            </div>

            <div class="d-flex justify-content-end mt-4">
                <button class="btn btn-primary" type="submit">
                    <i class="bi bi-check2-circle" aria-hidden="true"></i> Update Password
                </button>
            </div>
        </form>
    </section>
@endsection
