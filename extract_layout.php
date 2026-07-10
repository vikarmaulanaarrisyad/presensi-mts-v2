<?php
$content = file_get_contents('resources/views/monitoring.blade.php');
preg_match('/(<!DOCTYPE html>.*?<head>.*?)<\/head>/is', $content, $headMatch);

$layout = $headMatch[1] . "
    {{ \$styles ?? '' }}
</head>
<body>
    <x-sidebar />
    <div class=\"main-wrapper\">
        <x-header :title=\"\$title ?? 'Dashboard'\" :subtitle=\"\$subtitle ?? ''\">
            {{ \$headerActions ?? '' }}
        </x-header>

        @if(session('success'))
            <div class=\"alert-premium\">
                <i class=\"fa-solid fa-circle-check\" style=\"font-size: 18px; color: #10b981;\"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        {{ \$slot }}
    </div>

    {{ \$scripts ?? '' }}
</body>
</html>";

file_put_contents('resources/views/components/layout.blade.php', $layout);
echo "Layout created.";
