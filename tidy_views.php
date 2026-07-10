<?php
function tidyView($filePath) {
    if (!file_exists($filePath)) return;
    $content = file_get_contents($filePath);
    
    // First, let's split the file by lines
    $lines = explode("\n", $content);
    $newLines = [];
    $inMainContent = false;
    
    foreach ($lines as $line) {
        $trimmed = trim($line);
        if (str_starts_with($trimmed, '<x-layout') || str_starts_with($trimmed, '</x-layout')) {
            $newLines[] = $trimmed;
            continue;
        }
        
        if (str_starts_with($trimmed, '<x-slot')) {
            $newLines[] = '    ' . $trimmed;
            continue;
        }
        
        if (str_starts_with($trimmed, '</x-slot>')) {
            $newLines[] = '    ' . $trimmed;
            continue;
        }

        // If it's a slot content for simple text (like title)
        if (preg_match('/^\s*<x-slot.*?>.*?<\/x-slot>\s*$/', $line)) {
             $newLines[] = '    ' . $trimmed;
             continue;
        }
        
        // Let's just indent everything that is inside the layout with 4 spaces.
        // For existing lines, if they already have indentation, we add 4 spaces, but only if they are not <x-slot
        if ($trimmed !== '') {
            // Find current indentation
            preg_match('/^(\s*)/', $line, $matches);
            $currentSpaces = $matches[1];
            // If the line is already indented, we might not need to add more, but let's ensure it has AT LEAST 4 spaces.
            if (strlen($currentSpaces) < 4) {
                $newLines[] = '    ' . ltrim($line);
            } else {
                // If it is from the original file, it might need 4 spaces extra to align inside x-layout
                // Let's just add 4 spaces to everything that doesn't start with x-slot or x-layout
                $newLines[] = '    ' . rtrim($line, "\r");
            }
        } else {
            $newLines[] = '';
        }
    }
    
    // Clean up multiple empty lines
    $finalText = implode("\n", $newLines);
    $finalText = preg_replace("/\n{3,}/", "\n\n", $finalText);
    
    file_put_contents($filePath, $finalText);
    echo "Tidied: $filePath\n";
}

tidyView('resources/views/data-siswa.blade.php');
tidyView('resources/views/data-guru.blade.php');
tidyView('resources/views/data-kelas.blade.php');
tidyView('resources/views/monitoring.blade.php');
