@extends('layouts.ki-admin')

@section('title', 'Notifications - EVC')
@section('page-title', 'Notifications')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card" style="border-radius: 16px; overflow: hidden;">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div>
                            <h4 class="mb-1">Notifications</h4>
                            <div class="text-muted small">
                                {{ $unreadCount }} non lue{{ $unreadCount > 1 ? 's' : '' }}
                            </div>
                        </div>
                        <button type="button" class="btn btn-sm btn-primary" id="markAllNotificationsReadBtn">
                            Tout marquer comme lu
                        </button>
                    </div>

                    <div class="list-group">
                        @forelse($notifications as $notification)
                            @php
                                $data = $notification->data ?? [];
                                $title = $data['title'] ?? 'Notification';
                                $message = $data['message'] ?? '';
                                $url = $data['url'] ?? null;
                                $isUnread = empty($notification->read_at);
                            @endphp

                            @if(!empty($url))
                                <a href="{{ $url }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-start" style="border-radius: 12px; margin-bottom: 10px; {{ $isUnread ? 'background: rgba(16,185,129,0.08);' : '' }}">
                                    <div class="me-3">
                                        <div class="fw-bold {{ $isUnread ? '' : 'text-muted' }}">{{ $title }}</div>
                                        <div class="small {{ $isUnread ? '' : 'text-muted' }}">{{ $message }}</div>
                                        <div class="small text-muted mt-1">{{ $notification->created_at ? $notification->created_at->format('d/m/Y H:i:s') : '' }}</div>
                                    </div>
                                    <div class="text-end">
                                        <span class="badge {{ $isUnread ? 'bg-success' : 'bg-secondary' }}">{{ $isUnread ? 'Nouveau' : 'Lu' }}</span>
                                    </div>
                                </a>
                            @else
                                <div class="list-group-item d-flex justify-content-between align-items-start" style="border-radius: 12px; margin-bottom: 10px; {{ $isUnread ? 'background: rgba(16,185,129,0.08);' : '' }}">
                                    <div class="me-3">
                                        <div class="fw-bold {{ $isUnread ? '' : 'text-muted' }}">{{ $title }}</div>
                                        <div class="small {{ $isUnread ? '' : 'text-muted' }}">{{ $message }}</div>
                                        <div class="small text-muted mt-1">{{ $notification->created_at ? $notification->created_at->format('d/m/Y H:i:s') : '' }}</div>
                                    </div>
                                    <div class="text-end">
                                        <span class="badge {{ $isUnread ? 'bg-success' : 'bg-secondary' }}">{{ $isUnread ? 'Nouveau' : 'Lu' }}</span>
                                    </div>
                                </div>
                            @endif
                        @empty
                            <div class="text-muted py-4 text-center">
                                Aucune notification pour le moment.
                            </div>
                        @endforelse
                    </div>

                    <div class="mt-3">
                        {{ $notifications->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const btn = document.getElementById('markAllNotificationsReadBtn');
        if (!btn) return;

        function getCsrfToken() {
            const meta = document.querySelector('meta[name="csrf-token"]');
            return meta ? meta.getAttribute('content') : '';
        }

        btn.addEventListener('click', async function() {
            btn.disabled = true;
            try {
                const res = await fetch("{{ route('dashboard.notifications.mark-read') }}", {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': getCsrfToken(),
                    },
                    credentials: 'same-origin',
                    body: JSON.stringify({})
                });
                if (res.ok) {
                    window.location.reload();
                } else {
                    btn.disabled = false;
                }
            } catch (e) {
                btn.disabled = false;
            }
        });
    });
</script>
@endpush
