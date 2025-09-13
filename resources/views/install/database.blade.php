@extends('install.layout')

@section('title', 'Database Configuration')

@section('content')
@php
    $showProgress = true;
    $currentStep = 3;
@endphp

<div class="mb-4">
    <h3><i class="fas fa-database me-2 text-primary"></i>Database Configuration</h3>
    <p class="text-muted">Configure your database connection settings. Make sure your database exists and credentials are correct.</p>
</div>

<form id="databaseForm" method="POST" action="{{ route('install.database.store') }}">
    @csrf
    
    <div class="row">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="fas fa-server me-2"></i>Database Connection</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="db_connection" class="form-label">Database Type</label>
                            <select class="form-select @error('db_connection') is-invalid @enderror" 
                                    id="db_connection" name="db_connection" required>
                                <option value="mysql" {{ old('db_connection', 'mysql') == 'mysql' ? 'selected' : '' }}>MySQL</option>
                                <option value="pgsql" {{ old('db_connection') == 'pgsql' ? 'selected' : '' }}>PostgreSQL</option>
                                <option value="sqlite" {{ old('db_connection') == 'sqlite' ? 'selected' : '' }}>SQLite</option>
                            </select>
                            @error('db_connection')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3" id="db_host_group">
                            <label for="db_host" class="form-label">Database Host</label>
                            <input type="text" class="form-control @error('db_host') is-invalid @enderror" 
                                   id="db_host" name="db_host" value="{{ old('db_host', 'localhost') }}" 
                                   placeholder="localhost" required>
                            @error('db_host')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Usually 'localhost' for shared hosting</div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3" id="db_port_group">
                            <label for="db_port" class="form-label">Database Port</label>
                            <input type="number" class="form-control @error('db_port') is-invalid @enderror" 
                                   id="db_port" name="db_port" value="{{ old('db_port', '3306') }}" 
                                   placeholder="3306">
                            @error('db_port')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Default: 3306 for MySQL, 5432 for PostgreSQL</div>
                        </div>
                        
                        <div class="col-md-6 mb-3" id="db_name_group">
                            <label for="db_database" class="form-label">Database Name</label>
                            <input type="text" class="form-control @error('db_database') is-invalid @enderror" 
                                   id="db_database" name="db_database" value="{{ old('db_database') }}" 
                                   placeholder="your_database_name" required>
                            @error('db_database')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">The database must already exist</div>
                        </div>
                    </div>
                    
                    <div class="row" id="db_credentials_group">
                        <div class="col-md-6 mb-3">
                            <label for="db_username" class="form-label">Database Username</label>
                            <input type="text" class="form-control @error('db_username') is-invalid @enderror" 
                                   id="db_username" name="db_username" value="{{ old('db_username') }}" 
                                   placeholder="database_user" required>
                            @error('db_username')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="db_password" class="form-label">Database Password</label>
                            <div class="input-group">
                                <input type="password" class="form-control @error('db_password') is-invalid @enderror" 
                                       id="db_password" name="db_password" value="{{ old('db_password') }}" 
                                       placeholder="database_password">
                                <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            @error('db_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div id="sqlite_group" style="display: none;">
                        <div class="mb-3">
                            <label for="db_database_sqlite" class="form-label">SQLite Database Path</label>
                            <input type="text" class="form-control" id="db_database_sqlite" 
                                   name="db_database_sqlite" value="{{ old('db_database_sqlite', 'database/database.sqlite') }}" 
                                   placeholder="database/database.sqlite">
                            <div class="form-text">Relative to your project root directory</div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="fas fa-cogs me-2"></i>Advanced Settings</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="app_name" class="form-label">Application Name</label>
                            <input type="text" class="form-control @error('app_name') is-invalid @enderror" 
                                   id="app_name" name="app_name" value="{{ old('app_name', 'Laravel Application') }}" 
                                   placeholder="My Application" required>
                            @error('app_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="app_url" class="form-label">Application URL</label>
                            <input type="url" class="form-control @error('app_url') is-invalid @enderror" 
                                   id="app_url" name="app_url" value="{{ old('app_url', request()->getSchemeAndHttpHost()) }}" 
                                   placeholder="https://yourdomain.com" required>
                            @error('app_url')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="app_env" class="form-label">Environment</label>
                            <select class="form-select @error('app_env') is-invalid @enderror" 
                                    id="app_env" name="app_env" required>
                                <option value="production" {{ old('app_env', 'production') == 'production' ? 'selected' : '' }}>Production</option>
                                <option value="staging" {{ old('app_env') == 'staging' ? 'selected' : '' }}>Staging</option>
                                <option value="local" {{ old('app_env') == 'local' ? 'selected' : '' }}>Local</option>
                            </select>
                            @error('app_env')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <div class="form-check form-switch mt-4">
                                <input class="form-check-input" type="checkbox" id="app_debug" 
                                       name="app_debug" value="1" {{ old('app_debug') ? 'checked' : '' }}>
                                <label class="form-check-label" for="app_debug">
                                    Enable Debug Mode
                                </label>
                                <div class="form-text">Only enable for development/testing</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Connection Test</h5>
                </div>
                <div class="card-body text-center">
                    <div id="connectionStatus" class="mb-3">
                        <i class="fas fa-question-circle text-muted" style="font-size: 2rem;"></i>
                        <p class="mt-2 mb-0 text-muted">Click "Test Connection" to verify your settings</p>
                    </div>
                    
                    <button type="button" id="testConnection" class="btn btn-outline-primary">
                        <span class="loading-spinner spinner-border spinner-border-sm me-2" role="status"></span>
                        <span class="btn-text">
                            <i class="fas fa-plug me-2"></i>Test Connection
                        </span>
                    </button>
                </div>
            </div>
            
            <div class="alert alert-info alert-custom">
                <i class="fas fa-lightbulb me-2"></i>
                <strong>Tip:</strong> For cPanel hosting, your database credentials are usually found in the "MySQL Databases" section.
            </div>
            
            <div class="alert alert-warning alert-custom">
                <i class="fas fa-exclamation-triangle me-2"></i>
                <strong>Important:</strong> Make sure your database exists before proceeding. The installer will create tables but not the database itself.
            </div>
        </div>
    </div>
    
    <div class="d-flex justify-content-between">
        <a href="{{ route('install.requirements') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Back
        </a>
        
        <button type="submit" id="submitForm" class="btn btn-install" disabled>
            <span class="loading-spinner spinner-border spinner-border-sm me-2" role="status"></span>
            <span class="btn-text">
                Continue to Admin Setup
                <i class="fas fa-arrow-right ms-2"></i>
            </span>
        </button>
    </div>
</form>

@push('scripts')
<script>
$(document).ready(function() {
    // Handle database type change
    $('#db_connection').change(function() {
        const dbType = $(this).val();
        
        if (dbType === 'sqlite') {
            $('#db_host_group, #db_port_group, #db_name_group, #db_credentials_group').hide();
            $('#sqlite_group').show();
            $('#db_port').val('');
        } else {
            $('#db_host_group, #db_port_group, #db_name_group, #db_credentials_group').show();
            $('#sqlite_group').hide();
            
            // Set default ports
            if (dbType === 'mysql') {
                $('#db_port').val('3306');
            } else if (dbType === 'pgsql') {
                $('#db_port').val('5432');
            }
        }
    });
    
    // Toggle password visibility
    $('#togglePassword').click(function() {
        const passwordField = $('#db_password');
        const icon = $(this).find('i');
        
        if (passwordField.attr('type') === 'password') {
            passwordField.attr('type', 'text');
            icon.removeClass('fa-eye').addClass('fa-eye-slash');
        } else {
            passwordField.attr('type', 'password');
            icon.removeClass('fa-eye-slash').addClass('fa-eye');
        }
    });
    
    // Test database connection
    $('#testConnection').click(function() {
        const button = $(this);
        const form = $('#databaseForm');
        
        InstallHelper.showLoading(button);
        
        $.ajax({
            url: '{{ route("install.database.test") }}',
            method: 'POST',
            data: form.serialize(),
            success: function(response) {
                if (response.success) {
                    $('#connectionStatus').html(`
                        <i class="fas fa-check-circle text-success" style="font-size: 2rem;"></i>
                        <p class="mt-2 mb-0 text-success">Connection successful!</p>
                        <small class="text-muted">Database: ${response.database}</small>
                    `);
                    $('#submitForm').prop('disabled', false);
                } else {
                    $('#connectionStatus').html(`
                        <i class="fas fa-times-circle text-danger" style="font-size: 2rem;"></i>
                        <p class="mt-2 mb-0 text-danger">Connection failed</p>
                        <small class="text-muted">${response.message}</small>
                    `);
                    $('#submitForm').prop('disabled', true);
                }
            },
            error: function(xhr) {
                const response = xhr.responseJSON;
                $('#connectionStatus').html(`
                    <i class="fas fa-times-circle text-danger" style="font-size: 2rem;"></i>
                    <p class="mt-2 mb-0 text-danger">Connection failed</p>
                    <small class="text-muted">${response?.message || 'Unknown error occurred'}</small>
                `);
                $('#submitForm').prop('disabled', true);
            },
            complete: function() {
                InstallHelper.hideLoading(button);
            }
        });
    });
    
    // Form submission
    $('#databaseForm').submit(function(e) {
        const submitButton = $('#submitForm');
        InstallHelper.showLoading(submitButton);
    });
    
    // Auto-fill app URL
    if (!$('#app_url').val()) {
        $('#app_url').val(window.location.origin);
    }
});
</script>
@endpush
@endsection