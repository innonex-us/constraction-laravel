@extends('install.layout')

@section('title', 'System Requirements')

@section('content')
@php
    $showProgress = true;
    $currentStep = 2;
@endphp

<div class="mb-4">
    <h3><i class="fas fa-server me-2 text-primary"></i>System Requirements Check</h3>
    <p class="text-muted">We're checking if your server meets all the requirements to run this application.</p>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-light">
                <h5 class="mb-0"><i class="fas fa-cog me-2"></i>PHP Requirements</h5>
            </div>
            <div class="card-body p-0">
                @foreach($requirements['php'] as $requirement => $status)
                <div class="requirement-item">
                    <div class="requirement-status {{ $status['status'] ? 'status-success' : 'status-error' }}">
                        <i class="fas {{ $status['status'] ? 'fa-check' : 'fa-times' }}"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="fw-medium">{{ $requirement }}</div>
                        @if(isset($status['current']))
                            <small class="text-muted">Current: {{ $status['current'] }}</small>
                        @endif
                        @if(isset($status['required']))
                            <small class="text-muted">Required: {{ $status['required'] }}</small>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-light">
                <h5 class="mb-0"><i class="fas fa-puzzle-piece me-2"></i>PHP Extensions</h5>
            </div>
            <div class="card-body p-0">
                @foreach($requirements['extensions'] as $extension => $status)
                <div class="requirement-item">
                    <div class="requirement-status {{ $status['status'] ? 'status-success' : 'status-error' }}">
                        <i class="fas {{ $status['status'] ? 'fa-check' : 'fa-times' }}"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="fw-medium">{{ $extension }}</div>
                        <small class="text-muted">{{ $status['description'] }}</small>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-light">
                <h5 class="mb-0"><i class="fas fa-folder me-2"></i>Directory Permissions</h5>
            </div>
            <div class="card-body p-0">
                @foreach($requirements['permissions'] as $directory => $status)
                <div class="requirement-item">
                    <div class="requirement-status {{ $status['status'] ? 'status-success' : 'status-error' }}">
                        <i class="fas {{ $status['status'] ? 'fa-check' : 'fa-times' }}"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="fw-medium">{{ $directory }}</div>
                        <small class="text-muted">{{ $status['permission'] }} ({{ $status['status'] ? 'Writable' : 'Not Writable' }})</small>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-light">
                <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Status Summary</h5>
            </div>
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <div class="status-success me-3">
                        <i class="fas fa-check"></i>
                    </div>
                    <div>
                        <div class="fw-medium text-success">{{ $requirements['summary']['passed'] }} Passed</div>
                        <small class="text-muted">Requirements met</small>
                    </div>
                </div>
                
                @if($requirements['summary']['failed'] > 0)
                <div class="d-flex align-items-center mb-3">
                    <div class="status-error me-3">
                        <i class="fas fa-times"></i>
                    </div>
                    <div>
                        <div class="fw-medium text-danger">{{ $requirements['summary']['failed'] }} Failed</div>
                        <small class="text-muted">Need attention</small>
                    </div>
                </div>
                @endif
                
                @if($requirements['summary']['warnings'] > 0)
                <div class="d-flex align-items-center mb-3">
                    <div class="status-warning me-3">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <div>
                        <div class="fw-medium text-warning">{{ $requirements['summary']['warnings'] }} Warnings</div>
                        <small class="text-muted">Recommended fixes</small>
                    </div>
                </div>
                @endif
            </div>
        </div>
        
        @if($requirements['summary']['failed'] > 0)
        <div class="alert alert-danger alert-custom">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <strong>Action Required:</strong> Please fix the failed requirements before proceeding.
        </div>
        @endif
        
        @if($requirements['summary']['warnings'] > 0 && $requirements['summary']['failed'] == 0)
        <div class="alert alert-warning alert-custom">
            <i class="fas fa-info-circle me-2"></i>
            <strong>Notice:</strong> Some optional requirements are not met. You can continue, but some features may not work optimally.
        </div>
        @endif
        
        @if($requirements['summary']['failed'] == 0)
        <div class="alert alert-success alert-custom">
            <i class="fas fa-check-circle me-2"></i>
            <strong>Great!</strong> Your server meets all the requirements.
        </div>
        @endif
    </div>
</div>

@if($requirements['summary']['failed'] > 0)
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-danger text-white">
        <h5 class="mb-0"><i class="fas fa-tools me-2"></i>Troubleshooting Guide</h5>
    </div>
    <div class="card-body">
        <h6>Common Solutions:</h6>
        <ul class="mb-3">
            <li><strong>Missing PHP Extensions:</strong> Contact your hosting provider or install via cPanel PHP Extensions</li>
            <li><strong>Permission Issues:</strong> Set directory permissions to 755 and file permissions to 644</li>
            <li><strong>PHP Version:</strong> Upgrade to PHP 8.1 or higher through cPanel or hosting control panel</li>
        </ul>
        
        <div class="alert alert-info mb-0">
            <i class="fas fa-lightbulb me-2"></i>
            <strong>Need Help?</strong> Check the <code>DEPLOYMENT-TROUBLESHOOTING.md</code> file in your project root for detailed solutions.
        </div>
    </div>
</div>
@endif

<div class="d-flex justify-content-between">
    <a href="{{ route('install.welcome') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-2"></i>Back
    </a>
    
    @if($requirements['summary']['failed'] == 0)
        <a href="{{ route('install.database') }}" class="btn btn-install">
            Continue to Database Setup
            <i class="fas fa-arrow-right ms-2"></i>
        </a>
    @else
        <button type="button" class="btn btn-install" disabled title="Fix requirements first">
            Fix Requirements First
            <i class="fas fa-lock ms-2"></i>
        </button>
    @endif
</div>

@push('scripts')
@if(isset($requirements['summary']['failed']) && $requirements['summary']['failed'] > 0)
<script>
$(document).ready(function() {
    // Auto-refresh requirements check every 30 seconds if there are failures
    let refreshInterval = setInterval(function() {
        window.location.reload();
    }, 30000);
    
    // Stop auto-refresh when user interacts with the page
    $(document).on('click keypress', function() {
        clearInterval(refreshInterval);
    });
});
</script>
@endif
@endpush
@endsection