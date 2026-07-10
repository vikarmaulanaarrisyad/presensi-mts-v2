<?php
$directory = new RecursiveDirectoryIterator(__DIR__ . '/resources/views');
$iterator = new RecursiveIteratorIterator($directory);
$files = [];

foreach ($iterator as $info) {
    if (pathinfo($info->getFilename(), PATHINFO_EXTENSION) === 'php' && strpos($info->getFilename(), '.blade.php') !== false) {
        $files[] = $info->getPathname();
    }
}

$sweetAlertPartial = "resources/views/partials/sweetalerts.blade.php";
if (!file_exists(dirname($sweetAlertPartial))) {
    mkdir(dirname($sweetAlertPartial), 0777, true);
}
file_put_contents($sweetAlertPartial, <<<'HTML'
@if(session('success') || session('error') || session('warning'))
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: "{{ session('success') }}",
            confirmButtonColor: '#10b981',
            timer: 3000,
            timerProgressBar: true
        });
        @endif

        @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            text: "{{ session('error') }}",
            confirmButtonColor: '#ef4444'
        });
        @endif

        @if(session('warning'))
        Swal.fire({
            icon: 'warning',
            title: 'Peringatan!',
            text: "{{ session('warning') }}",
            confirmButtonColor: '#f59e0b'
        });
        @endif
    });
</script>
@endif
HTML
);

foreach ($files as $file) {
    if (basename($file) === 'sweetalerts.blade.php') continue;
    
    $content = file_get_contents($file);
    $original = $content;

    // Remove inline @if(session(...)) ... @endif
    // We will do a generic regex that removes @if(session('success')) ... @endif blocks.
    // We need to be careful not to remove nested @ifs if there are any, but usually these are simple alerts.
    
    // Pattern to match @if(session('key')) ... @endif
    $pattern = '/\s*@if\(\s*session\(\s*[\'"](?:success|error|warning)[\'"]\s*\)\s*\).*?@endif\s*/s';
    
    // Some pages like data-kelas have inline script alerts: @if(session('success')) <script>alert("{{ session('success') }}");</script> @endif
    $content = preg_replace($pattern, "\n", $content);

    // If the file changed, we should inject @include('partials.sweetalerts') right before </body> or </x-layout-admin> or </x-layout>
    // Wait, it's better to inject it everywhere IF the file has a </body> or </x-layout or </x-layout-admin>.
    // But maybe we only inject if the file had alerts? No, any page could receive a redirect with a flash message.
    
    if (strpos($content, "@include('partials.sweetalerts')") === false) {
        if (strpos($content, '</body>') !== false) {
            $content = str_replace('</body>', "    @include('partials.sweetalerts')\n</body>", $content);
        } elseif (strpos($content, '</x-layout-admin>') !== false) {
            $content = str_replace('</x-layout-admin>', "    @include('partials.sweetalerts')\n</x-layout-admin>", $content);
        } elseif (strpos($content, '</x-layout>') !== false) {
            $content = str_replace('</x-layout>', "    @include('partials.sweetalerts')\n</x-layout>", $content);
        }
    }

    if ($content !== $original) {
        file_put_contents($file, $content);
        echo "Updated: " . basename($file) . "\n";
    }
}
echo "Done!\n";
