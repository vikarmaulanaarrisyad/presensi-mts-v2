@props(['title' => 'Dashboard', 'subtitle' => ''])

<div class="top-header-panel mb-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
    <div>
        <h2 class="panel-title">{{ $title }}</h2>
        <span class="panel-subtitle">{{ $subtitle }}</span>
    </div>
    <div class="d-flex align-items-center gap-3">
        {{ $slot }}
        <div class="d-flex align-items-center gap-2 border-start ps-3">
            <div class="text-end">
                <div class="fw-bold text-dark lh-sm" style="font-size: 0.88rem;">{{ session('user_name', 'Admin') }}</div>
                <div class="text-muted font-mono-custom" style="font-size: 0.75rem;">{{ strtoupper(session('user_role', 'ADMIN')) }}</div>
            </div>
            <div class="bg-light p-2 rounded-3 text-secondary border">
                <i class="fa-solid fa-user-shield"></i>
            </div>
        </div>
    </div>
</div>
