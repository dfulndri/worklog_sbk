@extends('layouts.app')

@section('content')
    <div class="admin-shell">
        <div class="sidebar-backdrop" data-sidebar-close></div>

        <aside class="admin-sidebar" id="adminSidebar" aria-label="Main navigation">
            <div class="sidebar-header">
                <a class="brand-mark" href="{{ route('admin.dashboard') }}" aria-label="SBK Daily Report">
                    <span class="brand-icon" style="background: transparent; box-shadow: none;">
                        <img src="{{ asset('assets/images/logo/Logo_Sabhika.png') }}" alt="SBK Logo"
                            style="width: 36px; height: 36px; object-fit: contain;">
                    </span>
                    <span class="brand-copy">
                        <span class="brand-title">PT Sastra Worklog</span>
                        <span class="brand-subtitle">Admin Panel</span>
                    </span>
                </a>
            </div>

            <nav class="sidebar-nav">
                <div class="sidebar-nav-group">
                    <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
                        href="{{ route('admin.dashboard') }}">
                        <span class="nav-icon"><i class="bi bi-speedometer2" aria-hidden="true"></i></span>
                        <span class="nav-text">Dashboard</span>
                    </a>
                </div>

                <hr class="sidebar-nav-divider">
                <div class="sidebar-nav-group">
                    <span class="sidebar-nav-label">Data Master</span>
                    <a class="nav-link {{ request()->routeIs('admin.clients.*') ? 'active' : '' }}"
                        href="{{ route('admin.clients.index') }}">
                        <span class="nav-icon"><i class="bi bi-building" aria-hidden="true"></i></span>
                        <span class="nav-text">Data PT / Klien</span>
                    </a>
                    <a class="nav-link {{ request()->routeIs('admin.experts.*') ? 'active' : '' }}"
                        href="{{ route('admin.experts.index') }}">
                        <span class="nav-icon"><i class="bi bi-person-badge" aria-hidden="true"></i></span>
                        <span class="nav-text">Tenaga Ahli</span>
                    </a>
                    <a class="nav-link {{ request()->routeIs('admin.employees.*') ? 'active' : '' }}"
                        href="{{ route('admin.employees.index') }}">
                        <span class="nav-icon"><i class="bi bi-people" aria-hidden="true"></i></span>
                        <span class="nav-text">Karyawan</span>
                    </a>
                    <a class="nav-link {{ request()->routeIs('admin.document-types.*') ? 'active' : '' }}"
                        href="{{ route('admin.document-types.index') }}">
                        <span class="nav-icon"><i class="bi bi-file-earmark-text" aria-hidden="true"></i></span>
                        <span class="nav-text">Jenis Dokumen</span>
                    </a>
                </div>

                <hr class="sidebar-nav-divider">
                <div class="sidebar-nav-group">
                    <span class="sidebar-nav-label">Operasional</span>
                    <a class="nav-link {{ request()->routeIs('admin.jobs.*') ? 'active' : '' }}"
                        href="{{ route('admin.jobs.index') }}">
                        <span class="nav-icon"><i class="bi bi-kanban" aria-hidden="true"></i></span>
                        <span class="nav-text">Pekerjaan</span>
                    </a>
                    <a class="nav-link {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}"
                        href="{{ route('admin.reports.index') }}">
                        <span class="nav-icon"><i class="bi bi-bar-chart-line" aria-hidden="true"></i></span>
                        <span class="nav-text">Laporan</span>
                    </a>
                </div>

                <hr class="sidebar-nav-divider">
                <div class="sidebar-nav-group">
                    <span class="sidebar-nav-label">Settings</span>
                    <a class="nav-link {{ request()->routeIs('profile.edit') ? 'active' : '' }}"
                        href="{{ route('profile.edit') }}">
                        <span class="nav-icon"><i class="bi bi-person-badge" aria-hidden="true"></i></span>
                        <span class="nav-text">Profile</span>
                    </a>
                    <a class="nav-link {{ request()->routeIs('account-settings.edit') ? 'active' : '' }}"
                        href="{{ route('account-settings.edit') }}">
                        <span class="nav-icon"><i class="bi bi-gear" aria-hidden="true"></i></span>
                        <span class="nav-text">Account Settings</span>
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="nav-link w-100 text-start border-0 bg-transparent">
                            <span class="nav-icon"><i class="bi bi-box-arrow-right" aria-hidden="true"></i></span>
                            <span class="nav-text">Logout</span>
                        </button>
                    </form>
                </div>
            </nav>

            <div class="sidebar-user">
                <img class="avatar-img avatar-md sidebar-user-avatar" src="{{ auth()->user()->avatarUrl() }}"
                    alt="{{ auth()->user()->name }}">
                <strong>{{ auth()->user()->name }}</strong>
                <small>Administrator</small>
            </div>

            <div class="sidebar-footer">
                <span class="status-dot"></span>
                <span class="sidebar-footer-text">System running smoothly</span>
            </div>
        </aside>

        <div class="admin-main">
            <nav class="navbar admin-navbar navbar-expand bg-white">
                <div class="container-fluid px-3 px-lg-4">
                    <button class="sidebar-toggle" type="button" data-sidebar-toggle aria-controls="adminSidebar"
                        aria-expanded="true" aria-label="Toggle sidebar">
                        <span></span><span></span><span></span>
                    </button>

                    <form method="GET" action="{{ request()->url() }}" class="d-none d-md-flex ms-3 flex-grow-1"
                        role="search">
                        <input class="form-control search-input" type="search" name="search"
                            value="{{ request('search') }}" placeholder="@yield('search-placeholder', 'Cari...')">
                    </form>

                    <div class="navbar-actions ms-auto">
                        <button class="icon-button theme-toggle" type="button" data-theme-toggle
                            aria-label="Switch color theme" title="Switch color theme">
                            <i class="bi bi-moon-stars" data-theme-icon aria-hidden="true"></i>
                        </button>

                        <div class="dropdown">
                            <button class="icon-button" type="button" data-bs-toggle="dropdown" aria-expanded="false"
                                aria-label="Notifications">
                                @if ($navUnreadCount > 0)
                                    <span
                                        class="notification-count-badge">{{ $navUnreadCount > 9 ? '9+' : $navUnreadCount }}</span>
                                @endif
                                <i class="bi bi-bell" aria-hidden="true"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end notification-menu">
                                <div
                                    class="dropdown-header fw-bold text-body d-flex justify-content-between align-items-center">
                                    <span>Notifications</span>
                                    @if ($navUnreadCount > 0)
                                        <form method="POST" action="{{ route('notifications.mark-all-read') }}"
                                            class="m-0">
                                            @csrf
                                            <button type="submit" class="btn btn-link btn-sm p-0">Tandai semua</button>
                                        </form>
                                    @endif
                                </div>
                                @forelse ($navRecentNotifications as $notification)
                                    <a class="dropdown-item {{ $notification->isRead() ? '' : 'unread' }}"
                                        href="{{ route('notifications.read', $notification) }}">
                                        <span class="notification-title">{{ $notification->title }}</span>
                                        <span
                                            class="notification-time">{{ $notification->created_at->diffForHumans() }}</span>
                                    </a>
                                @empty
                                    <span class="dropdown-item text-muted">Belum ada notifikasi</span>
                                @endforelse
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item text-center" href="{{ route('notifications.index') }}">Lihat
                                    semua notifikasi</a>
                            </div>
                        </div>

                        <div class="dropdown">
                            <button class="profile-button dropdown-toggle" type="button" data-bs-toggle="dropdown"
                                aria-expanded="false">
                                <img class="avatar-img avatar-sm" src="{{ auth()->user()->avatarUrl() }}"
                                    alt="{{ auth()->user()->name }}">
                                <span class="profile-name d-none d-sm-inline">{{ auth()->user()->name }}</span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="{{ route('profile.edit') }}">Profile</a></li>
                                <li><a class="dropdown-item" href="{{ route('account-settings.edit') }}">Account
                                        settings</a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item">Sign out</button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </nav>

            <main class="dashboard-content">
                <div class="container-fluid px-3 px-lg-4 py-4">
                    @if (session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    @yield('page-content')
                </div>
            </main>

            <footer class="admin-footer">
                <div class="container-fluid px-3 px-lg-4">
                    <span>SBK Daily Report System &copy; {{ date('Y') }}</span>
                </div>
            </footer>
        </div>
    </div>
@endsection
