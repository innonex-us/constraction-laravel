@extends('install.layout')

@section('title', 'Welcome to Installation')

@section('content')
<div class="text-center">
    <div class="mb-4">
        <i class="fas fa-rocket text-primary" style="font-size: 4rem;"></i>
    </div>
    
    <h2 class="mb-4">Welcome to the Installation Wizard</h2>
    
    <p class="lead text-muted mb-4">
        This wizard will guide you through the installation process step by step.
        We'll help you configure your database, create an admin account, and get your application ready to use.
    </p>
    
    <div class="row g-4 mb-5">
        <div class="col-md-4">
            <div class="text-center p-3">
                <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                    <i class="fas fa-check-circle text-success" style="font-size: 1.5rem;"></i>
                </div>
                <h5>System Check</h5>
                <p class="text-muted small mb-0">Verify server requirements and PHP extensions</p>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="text-center p-3">
                <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                    <i class="fas fa-database text-info" style="font-size: 1.5rem;"></i>
                </div>
                <h5>Database Setup</h5>
                <p class="text-muted small mb-0">Configure your database connection settings</p>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="text-center p-3">
                <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                    <i class="fas fa-user-shield text-warning" style="font-size: 1.5rem;"></i>
                </div>
                <h5>Admin Account</h5>
                <p class="text-muted small mb-0">Create your administrator account</p>
            </div>
        </div>
    </div>
    
    <div class="alert alert-info alert-custom mb-4">
        <i class="fas fa-info-circle me-2"></i>
        <strong>Before you begin:</strong> Make sure you have your database credentials ready. 
        You'll need the database host, name, username, and password.
    </div>
    
    <div class="alert alert-warning alert-custom mb-4">
        <i class="fas fa-exclamation-triangle me-2"></i>
        <strong>Important:</strong> This installation wizard should only be run once. 
        After completion, it will be automatically disabled for security.
    </div>
    
    <div class="d-grid gap-2 d-md-flex justify-content-md-center">
        <a href="{{ route('install.requirements') }}" class="btn btn-install btn-lg">
            <i class="fas fa-arrow-right me-2"></i>
            Start Installation
        </a>
    </div>
    
    <div class="mt-4">
        <p class="text-muted small">
            <i class="fas fa-clock me-1"></i>
            Estimated time: 3-5 minutes
        </p>
    </div>
</div>

<style>
.col-md-4:hover .bg-light {
    background-color: #f8f9fa !important;
    transform: translateY(-2px);
    transition: all 0.3s ease;
}
</style>
@endsection