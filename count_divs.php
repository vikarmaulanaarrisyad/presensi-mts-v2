<?php
$content = file_get_contents('resources/views/data-siswa.blade.php');
$divs = substr_count($content, '<div');
$endDivs = substr_count($content, '</div');
echo 'Divs: ' . $divs . ' End Divs: ' . $endDivs . "\n";
