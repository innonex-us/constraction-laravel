@extends('install.layout')

@section('title', 'Installation Complete')

@section('content')
@php
    $showProgress = true;
    $currentStep = 5;
@endphp

<div class="text-center">
    <div class="mb-4">
        <div class="bg-success rounded-circle d-inline-flex align-items-center justify-content-center mb-3" 
             style="width: 80px; height: 80px; animation: pulse 2s infinite;">
            <i class="fas fa-check text-white" style="font-size: 2.5rem;"></i>
        </div>
        
        <h2 class="text-success mb-3">🎉 Installation Complete!</h2>
        <p class="lead text-muted mb-4">
            Congratulations! Your application has been successfully installed and configured.
        </p>
    </div>
    
    <div class="row g-4 mb-5">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center p-4">
                    <div class="bg-primary bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" 
                         style="width: 60px; height: 60px;">
                        <i class="fas fa-database text-primary" style="font-size: 1.5rem;"></i>
                    </div>
                    <h5 class="card-title">Database</h5>
                    <p class="card-text text-muted small">✓ Connected and configured<br>✓ Tables created successfully</p>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center p-4">
                    <div class="bg-success bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" 
                         style="width: 60px; height: 60px;">
                        <i class="fas fa-user-shield text-success" style="font-size: 1.5rem;"></i>
                    </div>
                    <h5 class="card-title">Admin Account</h5>
                    <p class="card-text text-muted small">✓ Administrator created<br>✓ Ready to login</p>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center p-4">
                    <div class="bg-info bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" 
                         style="width: 60px; height: 60px;">
                        <i class="fas fa-cogs text-info" style="font-size: 1.5rem;"></i>
                    </div>
                    <h5 class="card-title">Configuration</h5>
                    <p class="card-text text-muted small">✓ Environment configured<br>✓ Security keys generated</p>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row mb-5">
        <div class="col-lg-8 mx-auto">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Installation Summary</h5>
                </div>
                <div class="card-body">
                    <div class="row text-start">
                        <div class="col-md-6">
                            <h6 class="text-muted mb-3">Application Details</h6>
                            <ul class="list-unstyled">
                                <li class="mb-2">
                                    <strong>Name:</strong> {{ $installationData['app_name'] ?? 'Laravel Application' }}
                                </li>
                                <li class="mb-2">
                                    <strong>URL:</strong> 
                                    <a href="{{ $installationData['app_url'] ?? url('/') }}" target="_blank">
                                        {{ $installationData['app_url'] ?? url('/') }}
                                    </a>
                                </li>
                                <li class="mb-2">
                                    <strong>Environment:</strong> 
                                    <span class="badge bg-{{ ($installationData['app_env'] ?? 'production') === 'production' ? 'success' : 'warning' }}">
                                        {{ ucfirst($installationData['app_env'] ?? 'production') }}
                                    </span>
                                </li>
                                <li class="mb-2">
                                    <strong>Installation Date:</strong> {{ now()->format('M d, Y H:i:s') }}
                                </li>
                            </ul>
                        </div>
                        
                        <div class="col-md-6">
                            <h6 class="text-muted mb-3">Admin Account</h6>
                            <ul class="list-unstyled">
                                <li class="mb-2">
                                    <strong>Name:</strong> {{ $installationData['admin_name'] ?? 'Administrator' }}
                                </li>
                                <li class="mb-2">
                                    <strong>Email:</strong> {{ $installationData['admin_email'] ?? 'admin@example.com' }}
                                </li>
                                <li class="mb-2">
                                    <strong>Role:</strong> 
                                    <span class="badge bg-primary">Super Administrator</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="alert alert-success alert-custom mb-4">
        <i class="fas fa-shield-alt me-2"></i>
        <strong>Security Notice:</strong> The installation wizard has been automatically disabled for security. 
        You can only run it again by manually deleting the installation lock file.
    </div>
    
    <div class="alert alert-info alert-custom mb-4">
        <i class="fas fa-lightbulb me-2"></i>
        <strong>Next Steps:</strong> 
        <ul class="mb-0 mt-2 text-start">
            <li>Login to your admin panel to configure additional settings</li>
            <li>Update your profile and change default passwords if needed</li>
            <li>Configure email settings for notifications</li>
            <li>Set up backups and security measures</li>
        </ul>
    </div>
    
    <div class="d-grid gap-2 d-md-flex justify-content-md-center">
        <a href="{{ url('/') }}" class="btn btn-outline-primary btn-lg me-md-2">
            <i class="fas fa-home me-2"></i>
            Visit Website
        </a>
        
        <a href="{{ url('/admin') }}" class="btn btn-install btn-lg">
            <i class="fas fa-tachometer-alt me-2"></i>
            Go to Admin Panel
        </a>
    </div>
    
    <div class="mt-4">
        <p class="text-muted small">
            <i class="fas fa-heart text-danger me-1"></i>
            Thank you for choosing our application!
        </p>
    </div>
</div>

<style>
@keyframes pulse {
    0% {
        transform: scale(1);
        box-shadow: 0 0 0 0 rgba(40, 167, 69, 0.7);
    }
    70% {
        transform: scale(1.05);
        box-shadow: 0 0 0 10px rgba(40, 167, 69, 0);
    }
    100% {
        transform: scale(1);
        box-shadow: 0 0 0 0 rgba(40, 167, 69, 0);
    }
}

.card:hover {
    transform: translateY(-2px);
    transition: all 0.3s ease;
}
</style>

@push('scripts')
<script>
$(document).ready(function() {
    // Confetti animation (optional)
    if (typeof confetti !== 'undefined') {
        confetti({
            particleCount: 100,
            spread: 70,
            origin: { y: 0.6 }
        });
    }
    
    // Auto-redirect after 30 seconds (optional)
    let countdown = 30;
    const redirectTimer = setInterval(function() {
        countdown--;
        if (countdown <= 0) {
            clearInterval(redirectTimer);
            window.location.href = '{{ url("/") }}';
        }
    }, 1000);
    
    // Stop auto-redirect if user interacts with the page
    $(document).on('click keypress mousemove', function() {
        clearInterval(redirectTimer);
    });
});
</script>
@endpush
@endsection