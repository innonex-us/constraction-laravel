<?php
/**
 * Temporary script to create storage symbolic link
 * Upload this to your public folder and run it once
 * Delete this file after running
 */

// Path to storage/app/public from public folder
$target = '../storage/app/public';
$link = 'storage';

// Remove existing storage folder/link if it exists
if (file_exists($link)) {
    if (is_link($link)) {
        unlink($link);
        echo "Removed existing symbolic link.\n";
    } elseif (is_dir($link)) {
        rmdir($link);
        echo "Removed existing directory.\n";
    }
}

// Create the symbolic link
if (symlink($target, $link)) {
    echo "✅ SUCCESS: Storage symbolic link created successfully!\n";
    echo "Link: public/storage -> ../storage/app/public\n";
    
    // Test if it works
    if (is_dir($link)) {
        echo "✅ VERIFIED: Link is working correctly.\n";
    } else {
        echo "❌ WARNING: Link created but not accessible.\n";
    }
} else {
    echo "❌ ERROR: Failed to create symbolic link.\n";
    echo "This might be due to server restrictions.\n";
    echo "Contact your hosting provider for assistance.\n";
}

// Show current directory structure for debugging
echo "\n=== Current public folder contents ===\n";
$files = scandir('.');
foreach ($files as $file) {
    if ($file !== '.' && $file !== '..') {
        $type = is_dir($file) ? '[DIR]' : '[FILE]';
        $link_info = is_link($file) ? ' -> ' . readlink($file) : '';
        echo "$type $file$link_info\n";
    }
}
?>
