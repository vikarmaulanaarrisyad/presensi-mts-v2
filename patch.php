<?php
$content = file_get_contents('resources/views/dashboard_admin.blade.php');
$content = str_replace('<a href="{{ url(\'/siswa\') }}">', '<a href="{{ route(\'attendance.schedule\') }}">🕒 Pengaturan Jadwal</a>' . PHP_EOL . '            <a href="{{ url(\'/siswa\') }}">', $content);
file_put_contents('resources/views/dashboard_admin.blade.php', $content);
echo "dashboard_admin patched\n";

$content2 = file_get_contents('resources/views/components/sidebar-admin.blade.php');
$content2 = str_replace('<a class="nav-link-custom {{ Request::is(\'devices*\')', '<a class="nav-link-custom {{ Request::is(\'pengaturan-jadwal*\') ? \'active\' : \'\' }}" href="{{ route(\'attendance.schedule\') }}">' . PHP_EOL . '            <i class="fa-solid fa-clock"></i> Pengaturan Jadwal' . PHP_EOL . '        </a>' . PHP_EOL . '        <a class="nav-link-custom {{ Request::is(\'devices*\')', $content2);
file_put_contents('resources/views/components/sidebar-admin.blade.php', $content2);
echo "sidebar-admin patched\n";
