<?php
function refactorAdminView($filePath, $title, $subtitle) {
    if (!file_exists($filePath)) return;
    $content = file_get_contents($filePath);
    
    // Extract header actions
    preg_match('/<div class="d-flex align-items-center gap-3">\s*(.*?)<div class="d-flex align-items-center gap-2 border-start ps-3">/is', $content, $headerActionsMatch);
    $headerActions = isset($headerActionsMatch[1]) ? trim($headerActionsMatch[1]) : '';

    // Find where the real content starts (skipping the layout alerts which are now in layout-admin)
    // The last alert is session('error')
    $lastAlertEnd = strpos($content, "@if(session('error'))");
    if ($lastAlertEnd !== false) {
        $lastAlertEnd = strpos($content, "@endif", $lastAlertEnd) + 6;
        $splitPoint = $lastAlertEnd;
    } else {
        $splitPoint = strpos($content, '@php');
        if ($splitPoint === false) $splitPoint = strpos($content, '<div class="row');
        if ($splitPoint === false) $splitPoint = strpos($content, '<div class="table-container-card');
    }

    $bodyAndModalsAndScripts = substr($content, $splitPoint);
    
    // Find the script block. We know all modals are followed by the closing </div> of .main-content, then <script>
    // Let's use preg_match to find the last </div> before the <script> tags begin.
    // Actually, it's safer to find `<script` and then remove the immediate preceding `</div>`
    $scriptPos = strpos($bodyAndModalsAndScripts, '<script');
    if ($scriptPos !== false) {
        $bodyAndModals = substr($bodyAndModalsAndScripts, 0, $scriptPos);
        $scripts = substr($bodyAndModalsAndScripts, $scriptPos);
        
        // Trim body and remove the last </div> which belongs to main-content
        $bodyAndModals = rtrim($bodyAndModals);
        if (substr($bodyAndModals, -6) === '</div>') {
            $bodyAndModals = substr($bodyAndModals, 0, -6);
        }
        
        $scripts = str_replace(['</body>', '</html>'], '', $scripts);
    } else {
        $bodyAndModals = $bodyAndModalsAndScripts;
        $scripts = '';
    }

    $newContent = "<x-layout-admin>
    <x-slot name=\"title\">{$title}</x-slot>
    <x-slot name=\"headerTitle\">{$title}</x-slot>
    <x-slot name=\"headerSubtitle\">{$subtitle}</x-slot>
    
    <x-slot name=\"headerActions\">
        {$headerActions}
    </x-slot>

" . trim($bodyAndModals) . "

    <x-slot name=\"scripts\">
" . trim($scripts) . "
    </x-slot>
</x-layout-admin>";

    file_put_contents($filePath, $newContent);
    echo "Refactored $filePath\n";
}

refactorAdminView('resources/views/data-siswa.blade.php', 'Data Induk Siswa', 'Manajemen Biodata dan Registrasi Biometrik Fingerprint Terintegrasi');
refactorAdminView('resources/views/data-guru.blade.php', 'Data Guru', 'Manajemen Data Pengajar dan Hak Akses Sistem');
refactorAdminView('resources/views/data-kelas.blade.php', 'Data Kelas', 'Manajemen Rombongan Belajar dan Penugasan Wali Kelas');
