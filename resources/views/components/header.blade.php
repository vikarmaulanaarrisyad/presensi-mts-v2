@props(['title' => 'Dashboard', 'subtitle' => ''])

<div class="top-header">
    <div class="header-title-area">
        <h1>{{ $title }}</h1>
        <p>{{ $subtitle }}</p>
    </div>
    <div class="header-action-area" style="display: flex; align-items: center; gap: 20px;">
        {{ $slot }}
        
        <div class="profile-card">
            <div style="text-align: right;">
                <div style="font-size: 13px; font-weight: 700;">{{ session('user_name', 'User') }}</div>
                <div style="font-size: 11px; color: #10b981; font-weight: 600;">{{ strtoupper(session('user_role', 'USER')) }}</div>
            </div>
        </div>
    </div>
</div>
