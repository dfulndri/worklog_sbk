@extends('layouts.app')

@section('content')
    <div class="admin-shell">
        <div class="sidebar-backdrop" data-sidebar-close></div>

        <aside class="admin-sidebar" id="adminSidebar" aria-label="Main navigation">
            <div class="sidebar-header">
                <a class="brand-mark" href="{{ route('admin.dashboard') }}" aria-label="SBK Daily Report">
                    <span class="brand-icon"><i class="bi bi-grid-1x2-fill" aria-hidden="true"></i></span>
                    <span class="brand-copy">
                        <span class="brand-title">SBK Report</span>
                        <span class="brand-subtitle">Admin Panel</span>
                    </span>
                </a>
            </div>

            <nav class="sidebar-nav">
                <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
                    href="{{ route('admin.dashboard') }}">
                    <span class="nav-icon"><i class="bi bi-speedometer2" aria-hidden="true"></i></span>
                    <span class="nav-text">Dashboard</span>
                </a>
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
            </nav>

            <div class="sidebar-user">
                <img class="avatar-img avatar-md sidebar-user-avatar" src="{{ asset('assets/images/avatar/avatar.jpg') }}"
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

                    <div class="navbar-actions ms-auto">
                        <button class="icon-button theme-toggle" type="button" data-theme-toggle
                            aria-label="Switch color theme" title="Switch color theme">
                            <i class="bi bi-moon-stars" data-theme-icon aria-hidden="true"></i>
                        </button>

                        <div class="dropdown">
                            <button class="profile-button dropdown-toggle" type="button" data-bs-toggle="dropdown"
                                aria-expanded="false">
                                <img class="avatar-img avatar-sm" src="{{ asset('assets/images/avatar/avatar.jpg') }}"
                                    alt="{{ auth()->user()->name }}">
                                <span class="profile-name d-none d-sm-inline">{{ auth()->user()->name }}</span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
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
