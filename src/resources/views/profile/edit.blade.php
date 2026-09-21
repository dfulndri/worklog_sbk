@extends($layout)

@section('title', 'Profile')

@section('page-content')
    <div class="page-heading">
        <div class="page-heading-copy">
            <span class="page-icon"><i class="bi bi-person-badge" aria-hidden="true"></i></span>
            <div>
                <p class="eyebrow mb-1">Account</p>
                <h1 class="h3 mb-1">Profile</h1>
                <p class="text-muted mb-0">Kelola foto profil dan data akun kamu.</p>
            </div>
        </div>
    </div>

    <section class="row g-3">
        <div class="col-12 col-xl-4">
            <div class="panel h-100 text-center profile-card p-0 overflow-hidden">
                <div class="cover-upload-wrapper">
                    <div class="profile-cover">
                        <img src="{{ $user->coverUrl() }}" alt="Cover {{ $user->name }}">
                    </div>

                    @if ($user->isAdmin())
                        <form method="POST" action="{{ route('profile.cover') }}" enctype="multipart/form-data"
                            id="coverForm">
                            @csrf
                            <input type="file" name="cover" id="coverInput" accept="image/*" class="d-none"
                                onchange="document.getElementById('coverForm').submit()">
                            <button type="button" class="cover-upload-btn"
                                onclick="document.getElementById('coverInput').click()">
                                <i class="bi bi-camera" aria-hidden="true"></i> Ganti Cover
                            </button>
                        </form>
                    @endif
                </div>

                <div class="px-3 pb-4">
                    <div class="photo-upload-wrapper">
                        <img class="avatar-img avatar-xl profile-photo" src="{{ $user->avatarUrl() }}"
                            alt="{{ $user->name }}">
                        <form method="POST" action="{{ route('profile.avatar') }}" enctype="multipart/form-data"
                            id="avatarForm">
                            @csrf
                            <input type="file" name="avatar" id="avatarInput" accept="image/*" class="d-none"
                                onchange="document.getElementById('avatarForm').submit()">
                            <button type="button" class="photo-upload-btn"
                                onclick="document.getElementById('avatarInput').click()">
                                <i class="bi bi-camera" aria-hidden="true"></i>
                            </button>
                        </form>
                    </div>
                    <h2 class="h5 mt-3 mb-1">{{ $user->name }}</h2>
                    <p class="text-muted mb-3">
                        {{ $user->isAdmin() ? 'Administrator' : $user->employee->position ?? 'Karyawan' }}</p>
                    <div class="d-flex justify-content-center gap-2">
                        <span class="badge text-bg-primary">{{ ucfirst($user->role) }}</span>
                    </div>
                    <div class="info-list mt-4 text-start">
                        <div><span>Email</span><strong>{{ $user->email }}</strong></div>
                        @if (!$user->isAdmin() && $user->employee)
                            <div><span>No. HP</span><strong>{{ $user->employee->phone ?? '-' }}</strong></div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-xl-8">
            <form class="panel" method="POST" action="{{ route('profile.update') }}">
                @csrf
                @method('PUT')
                <div class="panel-header">
                    <div>
                        <h2 class="h5 mb-1 section-title"><i class="bi bi-person-gear" aria-hidden="true"></i><span>Profile
                                Settings</span></h2>
                        <p class="text-muted mb-0">Perbarui nama dan email akun kamu.</p>
                    </div>
                </div>

                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label" for="name">Nama</label>
                        <input class="form-control @error('name') is-invalid @enderror" id="name" name="name"
                            type="text" value="{{ old('name', $user->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="email">Email</label>
                        <input class="form-control @error('email') is-invalid @enderror" id="email" name="email"
                            type="email" value="{{ old('email', $user->email) }}" required>
                        @error('email')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="d-flex justify-content-end mt-4">
                    <button class="btn btn-primary" type="submit">
                        <i class="bi bi-check2-circle" aria-hidden="true"></i> Save Profile
                    </button>
                </div>
            </form>
        </div>
    </section>
@endsection
