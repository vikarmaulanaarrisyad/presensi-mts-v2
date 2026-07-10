<?php
$content = file_get_contents('resources/views/monitoring.blade.php');

// Extract body content starting from <div class="stats-grid"> up to the end of main-wrapper
$bodyStart = strpos($content, '<div class="stats-grid">');
$bodyEnd = strpos($content, '    <div class="modal-overlay" id="modalIzin">'); // where modals start
$modalsEnd = strpos($content, '<script>'); // where scripts start

$mainContent = substr($content, $bodyStart, $modalsEnd - $bodyStart);

// Extract header actions (e.g., the PDF button)
preg_match('/<div class="header-action-area"[^>]*>(.*?)<div class="profile-card">/is', $content, $headerActionsMatch);
$headerActions = trim($headerActionsMatch[1]);

// Extract Scripts
$scripts = substr($content, strpos($content, '<script>'));
// Remove </body> and </html>
$scripts = str_replace(['</body>', '</html>'], '', $scripts);

$newContent = "<x-layout>
    <x-slot name=\"title\">Dashboard Monitoring Presensi Real-Time</x-slot>
    <x-slot name=\"headerTitle\">Sistem Monitoring Absening</x-slot>
    <x-slot name=\"headerSubtitle\">Data Sinkronisasi Otomatis Mesin Fingerprint Cloud</x-slot>
    
    <x-slot name=\"headerActions\">
        {$headerActions}
    </x-slot>

{$mainContent}

    <x-slot name=\"scripts\">
{$scripts}
    </x-slot>
</x-layout>";

file_put_contents('resources/views/monitoring.blade.php', $newContent);
echo "Monitoring view updated.";
