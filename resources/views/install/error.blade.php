@extends('install.layout')

@section('title', 'Installation Error')
@section('step', 'Error')
@section('step_number', '!')

@section('content')
<div class="error-container">
    <div class="error-header">
        <div class="error-icon">
            <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="#dc3545" stroke-width="2">
                <circle cx="12" cy="12" r="10"></circle>
                <line x1="15" y1="9" x2="9" y2="15"></line>
                <line x1="9" y1="9" x2="15" y2="15"></line>
            </svg>
        </div>
        <h2>Installation Error</h2>
        <p class="error-message">{{ $error ?? 'An unexpected error occurred during installation.' }}</p>
    </div>

    <div class="troubleshooting-section">
        <h3>Common Solutions</h3>
        
        <div class="solution-card">
            <h4>1. Check System Requirements</h4>
            <p>Ensure your server meets all the minimum requirements:</p>
            <ul>
                <li>PHP 8.1 or higher</li>
                <li>Required PHP extensions (mbstring, openssl, pdo, etc.)</li>
                <li>Writable storage and bootstrap/cache directories</li>
            </ul>
            <a href="{{ route('install.requirements') }}" class="btn btn-outline">Check Requirements</a>
        </div>

        <div class="solution-card">
            <h4>2. Database Connection Issues</h4>
            <p>If you're experiencing database connection problems:</p>
            <ul>
                <li>Verify database credentials are correct</li>
                <li>Ensure the database server is running</li>
                <li>Check if the database exists</li>
                <li>Verify network connectivity to database server</li>
            </ul>
            <a href="{{ route('install.database') }}" class="btn btn-outline">Reconfigure Database</a>
        </div>

        <div class="solution-card">
            <h4>3. File Permissions</h4>
            <p>Set correct permissions for Laravel directories:</p>
            <div class="code-block">
                <code>
                    chmod -R 755 /path/to/your/project<br>
                    chmod -R 775 storage<br>
                    chmod -R 775 bootstrap/cache
                </code>
            </div>
        </div>

        <div class="solution-card">
            <h4>4. Missing PHP Extensions</h4>
            <p>Install required PHP extensions:</p>
            <div class="code-block">
                <code>
                    # For Ubuntu/Debian:<br>
                    sudo apt-get install php-mbstring php-xml php-curl<br><br>
                    # For CentOS/RHEL:<br>
                    sudo yum install php-mbstring php-xml php-curl<br><br>
                    # For cPanel: Enable extensions in PHP Selector
                </code>
            </div>
        </div>

        <div class="solution-card">
            <h4>5. Memory Limit Issues</h4>
            <p>Increase PHP memory limit in php.ini:</p>
            <div class="code-block">
                <code>memory_limit = 256M</code>
            </div>
        </div>
    </div>

    <div class="error-details" id="errorDetails" style="display: none;">
        <h3>Technical Details</h3>
        <div class="code-block">
            <pre>{{ $details ?? 'No additional details available.' }}</pre>
        </div>
    </div>

    <div class="action-buttons">
        <button type="button" class="btn btn-outline" onclick="toggleErrorDetails()">Show Technical Details</button>
        <a href="{{ route('install.welcome') }}" class="btn btn-secondary">Start Over</a>
        <button type="button" class="btn btn-primary" onclick="location.reload()">Try Again</button>
    </div>
</div>

<style>
.error-container {
    max-width: 800px;
    margin: 0 auto;
}

.error-header {
    text-align: center;
    margin-bottom: 2rem;
}

.error-icon {
    margin-bottom: 1rem;
}

.error-message {
    color: #dc3545;
    font-size: 1.1rem;
    margin-bottom: 0;
}

.troubleshooting-section {
    margin-bottom: 2rem;
}

.solution-card {
    background: #f8f9fa;
    border: 1px solid #dee2e6;
    border-radius: 8px;
    padding: 1.5rem;
    margin-bottom: 1rem;
}

.solution-card h4 {
    color: #495057;
    margin-bottom: 0.5rem;
}

.solution-card ul {
    margin: 0.5rem 0;
    padding-left: 1.5rem;
}

.solution-card li {
    margin-bottom: 0.25rem;
}

.code-block {
    background: #f1f3f4;
    border: 1px solid #d1d5db;
    border-radius: 4px;
    padding: 1rem;
    margin: 0.5rem 0;
    font-family: 'Courier New', monospace;
    font-size: 0.9rem;
    overflow-x: auto;
}

.code-block code {
    background: none;
    padding: 0;
    color: #1f2937;
}

.error-details {
    background: #fff5f5;
    border: 1px solid #fed7d7;
    border-radius: 8px;
    padding: 1.5rem;
    margin-bottom: 2rem;
}

.error-details pre {
    background: none;
    border: none;
    padding: 0;
    margin: 0;
    white-space: pre-wrap;
    word-wrap: break-word;
}

.action-buttons {
    display: flex;
    gap: 1rem;
    justify-content: center;
    flex-wrap: wrap;
}

.btn-outline {
    background: transparent;
    border: 2px solid #6c757d;
    color: #6c757d;
}

.btn-outline:hover {
    background: #6c757d;
    color: white;
}

@media (max-width: 768px) {
    .action-buttons {
        flex-direction: column;
    }
    
    .solution-card {
        padding: 1rem;
    }
}
</style>

<script>
function toggleErrorDetails() {
    const details = document.getElementById('errorDetails');
    const button = event.target;
    
    if (details.style.display === 'none') {
        details.style.display = 'block';
        button.textContent = 'Hide Technical Details';
    } else {
        details.style.display = 'none';
        button.textContent = 'Show Technical Details';
    }
}
</script>
@endsection