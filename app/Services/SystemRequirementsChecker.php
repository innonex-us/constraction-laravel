<?php

namespace App\Services;

use Illuminate\Support\Facades\File;

class SystemRequirementsChecker
{
    /**
     * Check all system requirements
     */
    public function check(): array
    {
        return [
            'php' => $this->checkPhpRequirements(),
            'extensions' => $this->checkPhpExtensions(),
            'permissions' => $this->checkDirectoryPermissions(),
            'summary' => $this->getSummary()
        ];
    }
    
    /**
     * Check PHP version requirements
     */
    private function checkPhpRequirements(): array
    {
        $requirements = [
            'php_version' => [
                'name' => 'PHP Version',
                'required' => '8.1.0',
                'current' => PHP_VERSION,
                'status' => version_compare(PHP_VERSION, '8.1.0', '>=')
            ],
            'memory_limit' => [
                'name' => 'Memory Limit',
                'required' => '128M',
                'current' => ini_get('memory_limit'),
                'status' => $this->checkMemoryLimit()
            ],
            'max_execution_time' => [
                'name' => 'Max Execution Time',
                'required' => '300 seconds',
                'current' => ini_get('max_execution_time') . ' seconds',
                'status' => (int)ini_get('max_execution_time') >= 300 || ini_get('max_execution_time') == 0
            ]
        ];
        
        return $requirements;
    }
    
    /**
     * Check required PHP extensions
     */
    private function checkPhpExtensions(): array
    {
        $requiredExtensions = [
            'mbstring' => 'Multibyte String',
            'openssl' => 'OpenSSL',
            'pdo' => 'PDO',
            'tokenizer' => 'Tokenizer',
            'xml' => 'XML',
            'ctype' => 'Ctype',
            'json' => 'JSON',
            'bcmath' => 'BCMath',
            'fileinfo' => 'Fileinfo',
            'gd' => 'GD Library',
            'curl' => 'cURL'
        ];
        
        $extensions = [];
        
        foreach ($requiredExtensions as $extension => $name) {
            $extensions[$extension] = [
                'name' => $name,
                'status' => extension_loaded($extension),
                'required' => true
            ];
        }
        
        return $extensions;
    }
    
    /**
     * Check directory permissions
     */
    private function checkDirectoryPermissions(): array
    {
        $directories = [
            'storage' => storage_path(),
            'bootstrap_cache' => base_path('bootstrap/cache'),
            'config_cache' => base_path('bootstrap/cache'),
        ];
        
        $permissions = [];
        
        foreach ($directories as $key => $path) {
            $permissions[$key] = [
                'name' => ucfirst(str_replace('_', ' ', $key)),
                'path' => $path,
                'status' => is_writable($path),
                'required' => true
            ];
        }
        
        return $permissions;
    }
    
    /**
     * Get summary of all checks
     */
    private function getSummary(): array
    {
        $php = $this->checkPhpRequirements();
        $extensions = $this->checkPhpExtensions();
        $permissions = $this->checkDirectoryPermissions();
        
        $total = count($php) + count($extensions) + count($permissions);
        $passed = 0;
        $failed = 0;
        
        // Count PHP requirements
        foreach ($php as $requirement) {
            if ($requirement['status']) {
                $passed++;
            } else {
                $failed++;
            }
        }
        
        // Count extensions
        foreach ($extensions as $extension) {
            if ($extension['status']) {
                $passed++;
            } else {
                $failed++;
            }
        }
        
        // Count permissions
        foreach ($permissions as $permission) {
            if ($permission['status']) {
                $passed++;
            } else {
                $failed++;
            }
        }
        
        return [
            'total' => $total,
            'passed' => $passed,
            'failed' => $failed,
            'all_passed' => $failed === 0
        ];
    }
    
    /**
     * Check memory limit
     */
    private function checkMemoryLimit(): bool
    {
        $memoryLimit = ini_get('memory_limit');
        
        if ($memoryLimit == -1) {
            return true; // No limit
        }
        
        $memoryLimitBytes = $this->convertToBytes($memoryLimit);
        $requiredBytes = $this->convertToBytes('128M');
        
        return $memoryLimitBytes >= $requiredBytes;
    }
    
    /**
     * Convert memory limit to bytes
     */
    private function convertToBytes(string $value): int
    {
        $value = trim($value);
        $last = strtolower($value[strlen($value) - 1]);
        $value = (int) $value;
        
        switch ($last) {
            case 'g':
                $value *= 1024;
            case 'm':
                $value *= 1024;
            case 'k':
                $value *= 1024;
        }
        
        return $value;
    }
}