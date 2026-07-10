<?php
function refactorView($filePath, $title, $subtitle) {
    if (!file_exists($filePath)) return;
    $content = file_get_contents($filePath);
    
    // Find the end of top-header-panel
    $headerEndPos = strpos($content, '</div>', strpos($content, '<div class="top-header-panel'));
    // Wait, top-header-panel has nested divs. 
    // It's better to find the first @if(session('success')) or the next element after the header.
    $alertsPos = strpos($content, '@if(session(');
    $activeDemosPos = strpos($content, '@php'); 
    $rowPos = strpos($content, '<div class="row');
    
    $startBody = min(array_filter([$alertsPos, $activeDemosPos, $rowPos], fn($val) => $val !== false));
    if (!$startBody) $startBody = strpos($content, '<div class="row'); // fallback

    // In data-siswa, we have alerts. Let's find the content AFTER alerts because layout-admin handles alerts!
    // But what about activeDemos? That should be in the body.
    $activeDemosPos = strpos($content, '@php 
            $activeDemos');
    if ($activeDemosPos !== false && $activeDemosPos > strpos($content, '<div class="main-content">')) {
        $startContent = $activeDemosPos;
    } else {
        $startContent = strpos($content, '<div class="row');
        if ($startContent === false) {
             $startContent = strpos($content, '<div class="table-container-card');
        }
    }

    $modalsEnd = strpos($content, '<script');
    if ($modalsEnd === false) {
        $modalsEnd = strpos($content, '</body>');
    }

    $mainContent = substr($content, $startContent, $modalsEnd - $startContent);
    
    // Extract actions from header
    preg_match('/<h2 class="panel-title">.*?<\/h2>.*?<span class="panel-subtitle">.*?<\/span>.*?<\/div>\s*<div class="d-flex align-items-center gap-3">(.*?)<div class="d-flex align-items-center gap-2 border-start ps-3">/is', $content, $headerActionsMatch);
    
    $headerActions = isset($headerActionsMatch[1]) ? trim($headerActionsMatch[1]) : '';

    // Extract Scripts
    $scriptsPos = strpos($content, '<script');
    $scripts = '';
    if ($scriptsPos !== false) {
        $scripts = substr($content, $scriptsPos);
        $scripts = str_replace(['</body>', '</html>'], '', $scripts);
    }

    $newContent = "<x-layout-admin>
    <x-slot name=\"title\">{$title}</x-slot>
    <x-slot name=\"headerTitle\">{$title}</x-slot>
    <x-slot name=\"headerSubtitle\">{$subtitle}</x-slot>
    
    <x-slot name=\"headerActions\">
        {$headerActions}
    </x-slot>

{$mainContent}

    <x-slot name=\"scripts\">
{$scripts}
    </x-slot>
</x-layout-admin>";

    file_put_contents($filePath, $newContent);
    echo "Refactored: $filePath\n";
}

refactorView('resources/views/data-siswa.blade.php', 'Data Induk Siswa', 'Manajemen Biodata dan Registrasi Biometrik Fingerprint Terintegrasi');
refactorView('resources/views/data-guru.blade.php', 'Data Guru', 'Manajemen Data Pengajar dan Hak Akses Sistem');
refactorView('resources/views/data-kelas.blade.php', 'Data Kelas', 'Manajemen Rombongan Belajar dan Penugasan Wali Kelas');

