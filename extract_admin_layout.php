<?php
$content = file_get_contents('resources/views/data-siswa.blade.php');

// Extract head and style
preg_match('/(<!DOCTYPE html>.*?<head>.*?)<\/head>/is', $content, $headMatch);
$head = $headMatch[1] . "
    {{ \$styles ?? '' }}
</head>";

// Extract sidebar
preg_match('/(<div class="sidebar">.*?)<div class="main-content">/is', $content, $sidebarMatch);
$sidebarContent = trim($sidebarMatch[1]);
file_put_contents('resources/views/components/sidebar-admin.blade.php', $sidebarContent);

// Extract header
preg_match('/(<div class="top-header-panel.*?<\/div>\s*<\/div>\s*<\/div>)/is', $content, $headerMatch);
$headerContent = trim($headerMatch[1]);
// We should parameterize title and subtitle
$headerContent = preg_replace('/<h2 class="panel-title">.*?<\/h2>/is', '<h2 class="panel-title">{{ $title }}</h2>', $headerContent);
$headerContent = preg_replace('/<span class="panel-subtitle">.*?<\/span>/is', '<span class="panel-subtitle">{{ $subtitle }}</span>', $headerContent);

// Action slot
$headerContent = preg_replace('/<div class="d-flex align-items-center gap-3">.*?<div class="d-flex align-items-center gap-2 border-start ps-3">/is', '<div class="d-flex align-items-center gap-3">
                {{ $slot }}
                <div class="d-flex align-items-center gap-2 border-start ps-3">', $headerContent);

// Add @props
$headerComponent = "@props(['title' => 'Dashboard', 'subtitle' => ''])\n\n" . $headerContent;
file_put_contents('resources/views/components/header-admin.blade.php', $headerComponent);

// Create Layout Component
$layoutContent = $head . "
<body>
    <x-sidebar-admin />
    
    <div class=\"main-content\">
        <x-header-admin :title=\"\$headerTitle ?? 'Dashboard'\" :subtitle=\"\$headerSubtitle ?? ''\">
            {{ \$headerActions ?? '' }}
        </x-header-admin>

        @if(session('success'))
        <div class=\"alert alert-success alert-dismissible fade show rounded-3 mb-4 shadow-sm\" role=\"alert\">
            <i class=\"fa-solid fa-circle-check me-2\"></i> <strong>Sukses!</strong> {{ session('success') }}
            <button type=\"button\" class=\"btn-close\" data-bs-dismiss=\"alert\" aria-label=\"Close\"></button>
        </div>
        @endif

        @if(session('warning'))
        <div class=\"alert alert-warning alert-dismissible fade show rounded-3 mb-4 shadow-sm\" role=\"alert\">
            <i class=\"fa-solid fa-triangle-exclamation me-2\"></i> <strong>Peringatan!</strong> {{ session('warning') }}
            <button type=\"button\" class=\"btn-close\" data-bs-dismiss=\"alert\" aria-label=\"Close\"></button>
        </div>
        @endif

        @if(session('error'))
        <div class=\"alert alert-danger alert-dismissible fade show rounded-3 mb-4 shadow-sm\" role=\"alert\">
            <i class=\"fa-solid fa-circle-xmark me-2\"></i> <strong>Gagal!</strong> {{ session('error') }}
            <button type=\"button\" class=\"btn-close\" data-bs-dismiss=\"alert\" aria-label=\"Close\"></button>
        </div>
        @endif

        {{ \$slot }}
    </div>

    {{ \$scripts ?? '' }}
</body>
</html>";

file_put_contents('resources/views/components/layout-admin.blade.php', $layoutContent);
echo "Admin components created.";
