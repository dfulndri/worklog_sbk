@extends('layouts.app')

@section('title', 'Login')
@section('body-class', 'auth-body')

@section('content')
    <button class="icon-button theme-toggle auth-theme-toggle" type="button" data-theme-toggle aria-label="Switch color theme"
        title="Switch color theme">
        <i class="bi bi-moon-stars" data-theme-icon aria-hidden="true"></i>
    </button>

    <main class="auth-page">
        <section class="auth-card">
            <a class="auth-brand" href="{{ url('/') }}">
                <span class="brand-icon"><i class="bi bi-grid-1x2-fill" aria-hidden="true"></i></span>
                <span><strong>SBK Daily Report</strong><small>Sign in to your workspace.</small></span>
            </a>

            <div class="auth-visual">
                <img src="{{ asset('assets/images/png/dasher-ui-bootstrap-5.jpg') }}" alt="SBK Daily Report">
            </div>

            <form class="needs-validation" novalidate method="POST" action="{{ route('login') }}">
                @csrf

                <div class="mb-4">
                    <p class="eyebrow mb-1">Secure Access</p>
                    <h1 class="h3 mb-1">Login</h1>
                    <p class="text-muted mb-0">Masuk ke akun kamu untuk melanjutkan.</p>
                </div>

                @if ($errors->any())
                    <div class="alert alert-danger py-2">
                        {{ $errors->first() }}
                    </div>
                @endif

                <div class="mb-3">
                    <label class="form-label" for="loginEmail">Email address</label>
                    <input class="form-control @error('email') is-invalid @enderror" id="loginEmail" name="email"
                        type="email" value="{{ old('email') }}" required autofocus>
                    <div class="invalid-feedback">Masukkan email yang valid.</div>
                </div>

                <div class="mb-3">
                    <label class="form-label" for="loginPassword">Password</label>
                    <input class="form-control @error('password') is-invalid @enderror" id="loginPassword" name="password"
                        type="password" minlength="6" required>
                    <div class="invalid-feedback">Password minimal 6 karakter.</div>
                </div>

                <div class="form-check mb-4">
                    <input class="form-check-input" type="checkbox" id="rememberMe" name="remember">
                    <label class="form-check-label" for="rememberMe">Remember me</label>
                </div>

                <button class="btn btn-primary w-100" type="submit">
                    <i class="bi bi-box-arrow-in-right" aria-hidden="true"></i> Sign In
                </button>
            </form>
        </section>
    </main>
@endsection
