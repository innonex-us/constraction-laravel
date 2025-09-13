@extends('install.layout')

@section('title', 'Create Admin Account')

@section('content')
@php
    $showProgress = true;
    $currentStep = 4;
@endphp

<div class="mb-4">
    <h3><i class="fas fa-user-shield me-2 text-primary"></i>Create Admin Account</h3>
    <p class="text-muted">Create your administrator account to manage the application. This will be your main login credentials.</p>
</div>

<form id="adminForm" method="POST" action="{{ route('install.admin.store') }}">
    @csrf
    
    <div class="row">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="fas fa-user me-2"></i>Personal Information</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="first_name" class="form-label">First Name</label>
                            <input type="text" class="form-control @error('first_name') is-invalid @enderror" 
                                   id="first_name" name="first_name" value="{{ old('first_name') }}" 
                                   placeholder="John" required>
                            @error('first_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="last_name" class="form-label">Last Name</label>
                            <input type="text" class="form-control @error('last_name') is-invalid @enderror" 
                                   id="last_name" name="last_name" value="{{ old('last_name') }}" 
                                   placeholder="Doe" required>
                            @error('last_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="email" class="form-label">Email Address</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" 
                               id="email" name="email" value="{{ old('email') }}" 
                               placeholder="admin@example.com" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">This will be your login email address</div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="phone" class="form-label">Phone Number <span class="text-muted">(Optional)</span></label>
                        <input type="tel" class="form-control @error('phone') is-invalid @enderror" 
                               id="phone" name="phone" value="{{ old('phone') }}" 
                               placeholder="+1 (555) 123-4567">
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="fas fa-lock me-2"></i>Security Credentials</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <div class="input-group">
                            <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                   id="password" name="password" placeholder="Enter a strong password" required>
                            <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Minimum 8 characters with letters, numbers, and symbols</div>
                        
                        <!-- Password strength indicator -->
                        <div class="mt-2">
                            <div class="progress" style="height: 5px;">
                                <div id="passwordStrength" class="progress-bar" role="progressbar" style="width: 0%"></div>
                            </div>
                            <small id="passwordStrengthText" class="text-muted">Password strength: <span>Not set</span></small>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">Confirm Password</label>
                        <div class="input-group">
                            <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror" 
                                   id="password_confirmation" name="password_confirmation" 
                                   placeholder="Confirm your password" required>
                            <button class="btn btn-outline-secondary" type="button" id="togglePasswordConfirm">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        @error('password_confirmation')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div id="passwordMatch" class="form-text"></div>
                    </div>
                </div>
            </div>
            
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="fas fa-building me-2"></i>Organization Details <span class="text-muted">(Optional)</span></h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="company" class="form-label">Company/Organization</label>
                            <input type="text" class="form-control @error('company') is-invalid @enderror" 
                                   id="company" name="company" value="{{ old('company') }}" 
                                   placeholder="Your Company Name">
                            @error('company')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="position" class="form-label">Position/Title</label>
                            <input type="text" class="form-control @error('position') is-invalid @enderror" 
                                   id="position" name="position" value="{{ old('position') }}" 
                                   placeholder="Administrator">
                            @error('position')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="fas fa-shield-alt me-2"></i>Security Tips</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-start mb-3">
                        <div class="text-success me-3 mt-1">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div>
                            <strong>Strong Password</strong>
                            <p class="small text-muted mb-0">Use at least 8 characters with a mix of letters, numbers, and symbols</p>
                        </div>
                    </div>
                    
                    <div class="d-flex align-items-start mb-3">
                        <div class="text-info me-3 mt-1">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <div>
                            <strong>Valid Email</strong>
                            <p class="small text-muted mb-0">Use a real email address for password recovery and notifications</p>
                        </div>
                    </div>
                    
                    <div class="d-flex align-items-start mb-3">
                        <div class="text-warning me-3 mt-1">
                            <i class="fas fa-key"></i>
                        </div>
                        <div>
                            <strong>Remember Credentials</strong>
                            <p class="small text-muted mb-0">Save your login details securely - you'll need them to access the admin panel</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="alert alert-info alert-custom">
                <i class="fas fa-info-circle me-2"></i>
                <strong>Admin Privileges:</strong> This account will have full access to manage the application, users, and settings.
            </div>
            
            <div class="alert alert-warning alert-custom">
                <i class="fas fa-exclamation-triangle me-2"></i>
                <strong>Important:</strong> Keep these credentials safe and don't share them with unauthorized users.
            </div>
        </div>
    </div>
    
    <div class="d-flex justify-content-between">
        <a href="{{ route('install.database') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Back
        </a>
        
        <button type="submit" id="submitForm" class="btn btn-install">
            <span class="loading-spinner spinner-border spinner-border-sm me-2" role="status"></span>
            <span class="btn-text">
                Create Admin Account
                <i class="fas fa-arrow-right ms-2"></i>
            </span>
        </button>
    </div>
</form>

@push('scripts')
<script>
$(document).ready(function() {
    // Toggle password visibility
    $('#togglePassword').click(function() {
        const passwordField = $('#password');
        const icon = $(this).find('i');
        
        if (passwordField.attr('type') === 'password') {
            passwordField.attr('type', 'text');
            icon.removeClass('fa-eye').addClass('fa-eye-slash');
        } else {
            passwordField.attr('type', 'password');
            icon.removeClass('fa-eye-slash').addClass('fa-eye');
        }
    });
    
    $('#togglePasswordConfirm').click(function() {
        const passwordField = $('#password_confirmation');
        const icon = $(this).find('i');
        
        if (passwordField.attr('type') === 'password') {
            passwordField.attr('type', 'text');
            icon.removeClass('fa-eye').addClass('fa-eye-slash');
        } else {
            passwordField.attr('type', 'password');
            icon.removeClass('fa-eye-slash').addClass('fa-eye');
        }
    });
    
    // Password strength checker
    $('#password').on('input', function() {
        const password = $(this).val();
        const strength = checkPasswordStrength(password);
        
        $('#passwordStrength').removeClass('bg-danger bg-warning bg-success')
                             .addClass(strength.class)
                             .css('width', strength.percentage + '%');
        
        $('#passwordStrengthText span').text(strength.text);
    });
    
    // Password confirmation checker
    $('#password_confirmation').on('input', function() {
        const password = $('#password').val();
        const confirmation = $(this).val();
        const matchDiv = $('#passwordMatch');
        
        if (confirmation === '') {
            matchDiv.text('').removeClass('text-success text-danger');
        } else if (password === confirmation) {
            matchDiv.text('✓ Passwords match').removeClass('text-danger').addClass('text-success');
        } else {
            matchDiv.text('✗ Passwords do not match').removeClass('text-success').addClass('text-danger');
        }
    });
    
    // Form submission
    $('#adminForm').submit(function(e) {
        const password = $('#password').val();
        const confirmation = $('#password_confirmation').val();
        
        if (password !== confirmation) {
            e.preventDefault();
            InstallHelper.showAlert('error', 'Passwords do not match. Please check and try again.');
            return false;
        }
        
        const submitButton = $('#submitForm');
        InstallHelper.showLoading(submitButton);
    });
    
    // Password strength function
    function checkPasswordStrength(password) {
        let score = 0;
        let feedback = [];
        
        if (password.length >= 8) score += 1;
        else feedback.push('at least 8 characters');
        
        if (/[a-z]/.test(password)) score += 1;
        else feedback.push('lowercase letters');
        
        if (/[A-Z]/.test(password)) score += 1;
        else feedback.push('uppercase letters');
        
        if (/[0-9]/.test(password)) score += 1;
        else feedback.push('numbers');
        
        if (/[^A-Za-z0-9]/.test(password)) score += 1;
        else feedback.push('special characters');
        
        switch (score) {
            case 0:
            case 1:
                return { class: 'bg-danger', percentage: 20, text: 'Very Weak' };
            case 2:
                return { class: 'bg-danger', percentage: 40, text: 'Weak' };
            case 3:
                return { class: 'bg-warning', percentage: 60, text: 'Fair' };
            case 4:
                return { class: 'bg-warning', percentage: 80, text: 'Good' };
            case 5:
                return { class: 'bg-success', percentage: 100, text: 'Strong' };
            default:
                return { class: 'bg-danger', percentage: 0, text: 'Not set' };
        }
    }
});
</script>
@endpush
@endsection