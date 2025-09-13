<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Config;
use App\Models\User;
use App\Services\SystemRequirementsChecker;
use Exception;

class InstallController extends Controller
{
    /**
     * Check if installation is already completed
     */
    private function isInstalled()
    {
        return File::exists(storage_path('app/installed.lock'));
    }

    /**
     * Show installation wizard home
     */
    public function index()
    {
        if ($this->isInstalled()) {
            return redirect('/')->with('message', 'Application is already installed.');
        }

        return view('install.welcome');
    }

    /**
     * Show system requirements check
     */
    public function requirements()
    {
        if ($this->isInstalled()) {
            return redirect('/');
        }

        $checker = new SystemRequirementsChecker();
        $requirements = $checker->check();
        
        return view('install.requirements', compact('requirements'));
    }

    /**
     * Show database configuration
     */
    public function database()
    {
        if ($this->isInstalled()) {
            return redirect('/');
        }

        return view('install.database');
    }

    /**
     * Store database configuration
     */
    public function storeDatabase(Request $request)
    {
        if ($this->isInstalled()) {
            return redirect('/');
        }
        
        try {
            $request->validate([
                'db_connection' => 'required|in:mysql,pgsql,sqlite',
                'db_host' => 'required_unless:db_connection,sqlite',
                'db_port' => 'nullable|numeric',
                'db_database' => 'required_unless:db_connection,sqlite',
                'db_username' => 'required_unless:db_connection,sqlite',
                'db_password' => 'nullable',
                'app_name' => 'required|string|max:255',
                'app_url' => 'required|url',
                'app_env' => 'required|in:production,staging,local',
            ]);
            
            // Test database connection first
            $config = [
                'driver' => $request->db_connection,
                'host' => $request->db_host,
                'port' => $request->db_port,
                'database' => $request->db_connection === 'sqlite' ? database_path($request->db_database_sqlite ?? 'database.sqlite') : $request->db_database,
                'username' => $request->db_username,
                'password' => $request->db_password,
            ];
            
            config(['database.connections.install' => $config]);
            DB::connection('install')->getPdo();
            
            // Update .env file
            $this->updateEnvFile([
                'APP_NAME' => $request->app_name,
                'APP_URL' => $request->app_url,
                'APP_ENV' => $request->app_env,
                'APP_DEBUG' => $request->has('app_debug') ? 'true' : 'false',
                'DB_CONNECTION' => $request->db_connection,
                'DB_HOST' => $request->db_host,
                'DB_PORT' => $request->db_port,
                'DB_DATABASE' => $request->db_connection === 'sqlite' ? $request->db_database_sqlite ?? 'database.sqlite' : $request->db_database,
                'DB_USERNAME' => $request->db_username,
                'DB_PASSWORD' => $request->db_password,
            ]);
            
            // Store configuration in session for next step
            session(['install_config' => $request->all()]);
            
            return redirect()->route('install.admin')->with('success', 'Database configuration saved successfully!');
            
        } catch (\Exception $e) {
            return redirect()->route('install.error')
                ->with('error', 'Database configuration failed: ' . $e->getMessage())
                ->with('details', 'Please check your database credentials and try again.');
        }
    }

    /**
     * Test database connection
     */
    public function testDatabase(Request $request)
    {
        try {
            $request->validate([
                'db_connection' => 'required|in:mysql,pgsql,sqlite',
                'db_host' => 'required_unless:db_connection,sqlite',
                'db_port' => 'nullable|numeric',
                'db_database' => 'required_unless:db_connection,sqlite',
                'db_username' => 'required_unless:db_connection,sqlite',
                'db_password' => 'nullable',
                'db_database_sqlite' => 'required_if:db_connection,sqlite',
            ]);
            
            $config = [
                'driver' => $request->db_connection,
                'host' => $request->db_host,
                'port' => $request->db_port,
                'database' => $request->db_connection === 'sqlite' ? database_path($request->db_database_sqlite) : $request->db_database,
                'username' => $request->db_username,
                'password' => $request->db_password,
            ];
            
            // Test the connection
            config(['database.connections.test' => $config]);
            $pdo = DB::connection('test')->getPdo();
            
            return response()->json([
                'success' => true,
                'message' => 'Database connection successful',
                'database' => $request->db_connection === 'sqlite' ? $request->db_database_sqlite : $request->db_database
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Save database configuration
     */
    public function saveDatabase(Request $request)
    {
        try {
            $request->validate([
                'db_host' => 'required|string',
                'db_port' => 'required|numeric',
                'db_name' => 'required|string',
                'db_username' => 'required|string',
                'db_password' => 'nullable|string',
            ]);

            // Update .env file
            $this->updateEnvFile([
                'DB_HOST' => $request->db_host,
                'DB_PORT' => $request->db_port,
                'DB_DATABASE' => $request->db_name,
                'DB_USERNAME' => $request->db_username,
                'DB_PASSWORD' => $request->db_password,
            ]);

            // Clear config cache
            Artisan::call('config:clear');

            return response()->json([
                'success' => true,
                'message' => 'Database configuration saved successfully!'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to save database configuration: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show admin account creation
     */
    public function admin()
    {
        if ($this->isInstalled()) {
            return redirect('/');
        }

        return view('install.admin');
    }

    /**
     * Create admin account and run migrations
     */
    public function createAdmin(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255',
                'password' => 'required|string|min:8|confirmed',
            ]);

            // Run migrations
            Artisan::call('migrate:fresh --seed');

            // Create admin user
            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'email_verified_at' => now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Admin account created successfully!'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create admin account: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store admin account
     */
    public function storeAdmin(Request $request)
    {
        if ($this->isInstalled()) {
            return redirect('/');
        }
        
        try {
            $request->validate([
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'password' => 'required|string|min:8|confirmed',
                'phone' => 'nullable|string|max:20',
                'company' => 'nullable|string|max:255',
                'position' => 'nullable|string|max:255',
            ]);
            
            // Run migrations
            Artisan::call('migrate', ['--force' => true]);
            
            // Create admin user (assuming User model exists)
            $userData = [
                'name' => $request->first_name . ' ' . $request->last_name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'email_verified_at' => now(),
            ];
            
            // Add optional fields if they exist in the users table
            if ($request->phone) $userData['phone'] = $request->phone;
            if ($request->company) $userData['company'] = $request->company;
            if ($request->position) $userData['position'] = $request->position;
            
            $user = User::create($userData);
            
            // Store installation data for completion page
            $installConfig = session('install_config', []);
            $installationData = [
                'app_name' => $installConfig['app_name'] ?? config('app.name'),
                'app_url' => $installConfig['app_url'] ?? config('app.url'),
                'app_env' => $installConfig['app_env'] ?? config('app.env'),
                'admin_name' => $user->name,
                'admin_email' => $user->email,
            ];
            
            session(['installation_data' => $installationData]);
            
            return redirect()->route('install.complete');
            
        } catch (\Exception $e) {
            return redirect()->route('install.error')
                ->with('error', 'Admin account creation failed: ' . $e->getMessage())
                ->with('details', 'Please check your information and try again.');
        }
    }

    /**
     * Complete installation
     */
    public function complete()
    {
        if ($this->isInstalled()) {
            return redirect('/');
        }

        try {
            // Generate application key if not exists
            if (empty(config('app.key'))) {
                Artisan::call('key:generate');
            }

            // Create storage link
            Artisan::call('storage:link');

            // Optimize for production
            Artisan::call('config:cache');
            Artisan::call('route:cache');
            Artisan::call('view:cache');

            // Create installation lock file
            File::put(storage_path('app/installed.lock'), now()->toDateTimeString());

            $installationData = session('installation_data', []);
            
            // Clear installation sessions
            session()->forget(['install_config', 'installation_data']);

            return view('install.complete', compact('installationData'));
        } catch (Exception $e) {
            return redirect()->route('install.error')
                ->with('error', 'Installation completion failed: ' . $e->getMessage())
                ->with('details', 'The installation process encountered an error during finalization.');
        }
    }

    /**
     * Show installation error page
     */
    public function error(Request $request)
    {
        $error = session('error', $request->get('error', 'An unexpected error occurred during installation.'));
        $details = session('details', $request->get('details', ''));
        
        // Clear error messages from session after displaying
        session()->forget(['error', 'details']);
        
        return view('install.error', compact('error', 'details'));
    }



    /**
     * Update .env file
     */
    private function updateEnvFile($data)
    {
        $envFile = base_path('.env');
        $envContent = File::get($envFile);

        foreach ($data as $key => $value) {
            $pattern = "/^{$key}=.*/m";
            $replacement = "{$key}={$value}";
            
            if (preg_match($pattern, $envContent)) {
                $envContent = preg_replace($pattern, $replacement, $envContent);
            } else {
                $envContent .= "\n{$replacement}";
            }
        }

        File::put($envFile, $envContent);
    }
}