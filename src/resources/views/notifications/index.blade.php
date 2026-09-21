@extends($layout)

@section('title', 'Notifikasi')

@section('page-content')
    <div class="page-heading">
        <div class="page-heading-copy">
            <span class="page-icon"><i class="bi bi-bell" aria-hidden="true"></i></span>
            <div>
                <p class="eyebrow mb-1">Notifikasi</p>
                <h1 class="h3 mb-1">Semua Notifikasi</h1>
            </div>
        </div>
        <div class="heading-actions">
            <form method="POST" action="{{ route('notifications.mark-all-read') }}">
                @csrf
                <button type="submit" class="btn btn-outline-secondary btn-sm">Tandai semua dibaca</button>
            </form>
        </div>
    </div>

    <section class="panel mt-3">
        @forelse ($notifications as $notification)
            <a href="{{ route('notifications.read', $notification) }}"
                class="activity-item d-block text-decoration-none {{ $notification->isRead() ? '' : 'fw-semibold' }}">
                <span class="activity-dot {{ $notification->isRead() ? 'bg-secondary' : 'bg-primary' }}"></span>
                <div>
                    <p class="mb-1">{{ $notification->title }}</p>
                    @if ($notification->message)
                        <p class="text-muted small mb-1">{{ $notification->message }}</p>
                    @endif
                    <p class="text-muted small mb-0">{{ $notification->created_at->diffForHumans() }}</p>
                </div>
            </a>
        @empty
            <p class="text-muted text-center py-4 mb-0">Belum ada notifikasi.</p>
        @endforelse

        @if ($notifications->hasPages())
            <div class="p-3">
                {{ $notifications->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </section>
@endsection
