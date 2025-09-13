<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Installation Wizard') - {{ config('app.name', 'Laravel') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .install-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        .install-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            overflow: hidden;
        }
        .install-header {
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .install-body {
            padding: 40px;
        }
        .progress-container {
            margin-bottom: 30px;
        }
        .step-indicator {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        .step {
            flex: 1;
            text-align: center;
            position: relative;
        }
        .step:not(:last-child)::after {
            content: '';
            position: absolute;
            top: 15px;
            right: -50%;
            width: 100%;
            height: 2px;
            background: #e5e7eb;
            z-index: 1;
        }
        .step.active:not(:last-child)::after,
        .step.completed:not(:last-child)::after {
            background: #10b981;
        }
        .step-circle {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background: #e5e7eb;
            color: #6b7280;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 8px;
            position: relative;
            z-index: 2;
            font-size: 12px;
            font-weight: bold;
        }
        .step.active .step-circle {
            background: #3b82f6;
            color: white;
        }
        .step.completed .step-circle {
            background: #10b981;
            color: white;
        }
        .step-label {
            font-size: 12px;
            color: #6b7280;
            font-weight: 500;
        }
        .step.active .step-label {
            color: #3b82f6;
            font-weight: 600;
        }
        .step.completed .step-label {
            color: #10b981;
            font-weight: 600;
        }
        .btn-install {
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            border: none;
            padding: 12px 30px;
            border-radius: 8px;
            color: white;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        .btn-install:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(79, 70, 229, 0.4);
            color: white;
        }
        .btn-install:disabled {
            opacity: 0.6;
            transform: none;
            box-shadow: none;
        }
        .requirement-item {
            display: flex;
            align-items: center;
            padding: 12px 0;
            border-bottom: 1px solid #f3f4f6;
        }
        .requirement-item:last-child {
            border-bottom: none;
        }
        .requirement-status {
            width: 24px;
            height: 24px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            font-size: 12px;
        }
        .status-success {
            background: #dcfce7;
            color: #16a34a;
        }
        .status-error {
            background: #fef2f2;
            color: #dc2626;
        }
        .status-warning {
            background: #fef3c7;
            color: #d97706;
        }
        .alert-custom {
            border: none;
            border-radius: 10px;
            padding: 15px 20px;
        }
        .loading-spinner {
            display: none;
        }
        .loading .loading-spinner {
            display: inline-block;
        }
        .loading .btn-text {
            display: none;
        }
    </style>
    @stack('styles')
</head>
<body>
    <div class="install-container">
        <div class="install-card">
            <div class="install-header">
                <h1><i class="fas fa-cogs me-2"></i>Installation Wizard</h1>
                <p class="mb-0">Let's get your application up and running!</p>
            </div>
            
            @if(isset($showProgress) && $showProgress)
            <div class="progress-container px-4 pt-4">
                <div class="step-indicator">
                    <div class="step {{ $currentStep >= 1 ? ($currentStep == 1 ? 'active' : 'completed') : '' }}">
                        <div class="step-circle">{{ $currentStep > 1 ? '✓' : '1' }}</div>
                        <div class="step-label">Welcome</div>
                    </div>
                    <div class="step {{ $currentStep >= 2 ? ($currentStep == 2 ? 'active' : 'completed') : '' }}">
                        <div class="step-circle">{{ $currentStep > 2 ? '✓' : '2' }}</div>
                        <div class="step-label">Requirements</div>
                    </div>
                    <div class="step {{ $currentStep >= 3 ? ($currentStep == 3 ? 'active' : 'completed') : '' }}">
                        <div class="step-circle">{{ $currentStep > 3 ? '✓' : '3' }}</div>
                        <div class="step-label">Database</div>
                    </div>
                    <div class="step {{ $currentStep >= 4 ? ($currentStep == 4 ? 'active' : 'completed') : '' }}">
                        <div class="step-circle">{{ $currentStep > 4 ? '✓' : '4' }}</div>
                        <div class="step-label">Admin</div>
                    </div>
                    <div class="step {{ $currentStep >= 5 ? ($currentStep == 5 ? 'active' : 'completed') : '' }}">
                        <div class="step-circle">{{ $currentStep > 5 ? '✓' : '5' }}</div>
                        <div class="step-label">Complete</div>
                    </div>
                </div>
            </div>
            @endif
            
            <div class="install-body">
                @if(session('error'))
                    <div class="alert alert-danger alert-custom mb-4">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        {{ session('error') }}
                    </div>
                @endif
                
                @if(session('success'))
                    <div class="alert alert-success alert-custom mb-4">
                        <i class="fas fa-check-circle me-2"></i>
                        {{ session('success') }}
                    </div>
                @endif
                
                @yield('content')
            </div>
        </div>
        
        <div class="text-center mt-4">
            <p class="text-white-50 mb-0">
                <i class="fas fa-shield-alt me-1"></i>
                Secure Installation Process
            </p>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script>
        // Global installation helper functions
        window.InstallHelper = {
            showLoading: function(button) {
                $(button).addClass('loading').prop('disabled', true);
            },
            hideLoading: function(button) {
                $(button).removeClass('loading').prop('disabled', false);
            },
            showAlert: function(type, message, container = '.install-body') {
                const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
                const icon = type === 'success' ? 'fa-check-circle' : 'fa-exclamation-triangle';
                const alert = `
                    <div class="alert ${alertClass} alert-custom mb-4">
                        <i class="fas ${icon} me-2"></i>
                        ${message}
                    </div>
                `;
                $(container).prepend(alert);
                
                // Auto remove after 5 seconds
                setTimeout(() => {
                    $('.alert').fadeOut();
                }, 5000);
            }
        };
    </script>
    @stack('scripts')
</body>
</html>