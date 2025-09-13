<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Symfony\Component\HttpFoundation\Response;

class CheckInstallation
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Skip check for installation routes
        if ($request->is('install*')) {
            return $next($request);
        }
        
        // Skip check for API routes, assets, and other non-web routes
        if ($request->is('api/*') || $request->is('storage/*') || $request->is('_debugbar/*')) {
            return $next($request);
        }
        
        // Check if installation is complete
        $installationLockFile = storage_path('app/installed.lock');
        
        if (!File::exists($installationLockFile)) {
            // Installation not complete, redirect to installation wizard
            return redirect()->route('install.welcome');
        }
        
        return $next($request);
    }
}