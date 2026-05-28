@extends('backend.layouts.app')

@section('title', 'ข้อมูลระบบ')
@section('page-title', 'ข้อมูลระบบ')
@section('page-description', 'ข้อมูลระบบและสถิติการทำงานของ Laravel CMS')

@section('content')
<div class="main-content-area">
    <!-- Action Buttons -->
    <div class="flex flex-col sm:flex-row sm:justify-between space-y-2 sm:space-y-0 sm:space-x-2 mb-6">
        <button data-refresh class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 w-full sm:w-auto">
            <i class="fas fa-sync-alt mr-2"></i>
            รีเฟรชข้อมูล
        </button>
        <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-2">
            <a href="{{ route('backend.settings-systeminfo.export') }}" class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 w-full sm:w-auto">
                <i class="fas fa-download mr-2"></i>
                Export ข้อมูล
            </a>
        </div>
    </div>

    <!-- System Overview Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                    <i class="fas fa-server text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Server</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $systemInfo['server']['os'] }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-600">
                    <i class="fab fa-php text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">PHP Version</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $systemInfo['php']['version'] }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-red-100 text-red-600">
                    <i class="fas fa-fire text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Laravel Version</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $systemInfo['laravel']['version'] }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                    <i class="fas fa-database text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Database</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $systemInfo['database']['driver'] ?? 'N/A' }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- System Information Sections -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Server Information -->
        <div class="bg-white rounded-lg shadow-md">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">
                    <i class="fas fa-server mr-2"></i>
                    ข้อมูล Server
                </h3>
            </div>
            <div class="p-6">
                <dl class="grid grid-cols-1 gap-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Operating System</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $systemInfo['server']['os'] }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Server Software</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $systemInfo['server']['server_software'] }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Server Name</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $systemInfo['server']['server_name'] }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Server Port</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $systemInfo['server']['server_port'] }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Document Root</dt>
                        <dd class="mt-1 text-sm text-gray-900 font-mono">{{ $systemInfo['server']['document_root'] }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Memory Limit</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $systemInfo['server']['memory_limit'] }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Max Execution Time</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $systemInfo['server']['max_execution_time'] }} seconds</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Upload Max Filesize</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $systemInfo['server']['upload_max_filesize'] }}</dd>
                    </div>
                </dl>
            </div>
        </div>

        <!-- PHP Information -->
        <div class="bg-white rounded-lg shadow-md">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">
                    <i class="fab fa-php mr-2"></i>
                    ข้อมูล PHP
                </h3>
            </div>
            <div class="p-6">
                <dl class="grid grid-cols-1 gap-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Version</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $systemInfo['php']['version'] }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">SAPI</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $systemInfo['php']['sapi'] }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Zend Version</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $systemInfo['php']['zend_version'] }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Memory Usage</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $systemInfo['php']['memory_usage'] }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Memory Peak</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $systemInfo['php']['memory_peak'] }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Memory Limit</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $systemInfo['php']['memory_limit'] }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Loaded Extensions</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ count($systemInfo['php']['extensions']) }} extensions</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Error Reporting</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $systemInfo['php']['error_reporting'] }}</dd>
                    </div>
                </dl>
            </div>
        </div>
    </div>

    <!-- Laravel & Database Information -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Laravel Information -->
        <div class="bg-white rounded-lg shadow-md">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">
                    <i class="fas fa-fire mr-2"></i>
                    ข้อมูล Laravel
                </h3>
            </div>
            <div class="p-6">
                <dl class="grid grid-cols-1 gap-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Version</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $systemInfo['laravel']['version'] }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Environment</dt>
                        <dd class="mt-1">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $systemInfo['laravel']['environment'] === 'production' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                {{ $systemInfo['laravel']['environment'] }}
                            </span>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Debug Mode</dt>
                        <dd class="mt-1">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $systemInfo['laravel']['debug'] ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                                {{ $systemInfo['laravel']['debug'] ? 'Enabled' : 'Disabled' }}
                            </span>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">URL</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $systemInfo['laravel']['url'] }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Timezone</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $systemInfo['laravel']['timezone'] }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Locale</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $systemInfo['laravel']['locale'] }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">App Key</dt>
                        <dd class="mt-1">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $systemInfo['laravel']['key'] === 'Set' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $systemInfo['laravel']['key'] }}
                            </span>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Service Providers</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $systemInfo['laravel']['providers'] }} providers</dd>
                    </div>
                </dl>
            </div>
        </div>

        <!-- Database Information -->
        <div class="bg-white rounded-lg shadow-md">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">
                    <i class="fas fa-database mr-2"></i>
                    ข้อมูล Database
                </h3>
            </div>
            <div class="p-6">
                @if(isset($systemInfo['database']['error']))
                    <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-exclamation-triangle text-red-400"></i>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-red-800">Database Error</h3>
                                <div class="mt-2 text-sm text-red-700">
                                    <p>{{ $systemInfo['database']['error'] }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <dl class="grid grid-cols-1 gap-4">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Driver</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $systemInfo['database']['driver'] }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Version</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $systemInfo['database']['version'] }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Database Name</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $systemInfo['database']['database'] }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Host</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $systemInfo['database']['host'] }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Port</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $systemInfo['database']['port'] }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Charset</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $systemInfo['database']['charset'] }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Tables</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $systemInfo['database']['tables'] }} tables</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Migrations</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $systemInfo['database']['migrations']['total'] }} migrations</dd>
                        </div>
                    </dl>
                @endif
            </div>
        </div>
    </div>

    <!-- Cache & Storage Information -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Cache Information -->
        <div class="bg-white rounded-lg shadow-md">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">
                    <i class="fas fa-memory mr-2"></i>
                    ข้อมูล Cache
                </h3>
            </div>
            <div class="p-6">
                <dl class="grid grid-cols-1 gap-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Driver</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $systemInfo['cache']['driver'] }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Status</dt>
                        <dd class="mt-1">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $systemInfo['cache']['status'] === 'Working' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $systemInfo['cache']['status'] }}
                            </span>
                        </dd>
                    </div>
                </dl>
            </div>
        </div>

        <!-- Storage Information -->
        <div class="bg-white rounded-lg shadow-md">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">
                    <i class="fas fa-hdd mr-2"></i>
                    ข้อมูล Storage
                </h3>
            </div>
            <div class="p-6">
                <dl class="grid grid-cols-1 gap-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Storage Path</dt>
                        <dd class="mt-1 text-sm text-gray-900 font-mono">{{ $systemInfo['storage']['storage_path'] }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Storage Size</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $systemInfo['storage']['storage_size'] }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Public Size</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $systemInfo['storage']['public_size'] }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Logs Size</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $systemInfo['storage']['logs_size'] }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Cache Size</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $systemInfo['storage']['cache_size'] }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Sessions Size</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $systemInfo['storage']['sessions_size'] }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Views Size</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $systemInfo['storage']['views_size'] }}</dd>
                    </div>
                </dl>
            </div>
        </div>
    </div>

    <!-- Environment Information -->
    <div class="bg-white rounded-lg shadow-md">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">
                <i class="fas fa-cog mr-2"></i>
                ข้อมูล Environment
            </h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div>
                    <dt class="text-sm font-medium text-gray-500">App Environment</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $systemInfo['environment']['app_env'] }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">App Debug</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $systemInfo['environment']['app_debug'] ? 'true' : 'false' }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">App URL</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $systemInfo['environment']['app_url'] }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">App Timezone</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $systemInfo['environment']['app_timezone'] }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">App Locale</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $systemInfo['environment']['app_locale'] }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">DB Connection</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $systemInfo['environment']['db_connection'] }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Cache Driver</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $systemInfo['environment']['cache_driver'] }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Session Driver</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $systemInfo['environment']['session_driver'] }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Queue Driver</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $systemInfo['environment']['queue_driver'] }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Mail Driver</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $systemInfo['environment']['mail_driver'] }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Log Level</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $systemInfo['environment']['log_level'] }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Log Channel</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $systemInfo['environment']['log_channel'] }}</dd>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-refresh every 5 minutes (reduced frequency for better performance)
    setTimeout(function() {
        location.reload();
    }, 300000);
    
    // Add loading state for better UX
    const refreshButton = document.querySelector('[data-refresh]');
    if (refreshButton) {
        refreshButton.addEventListener('click', function() {
            this.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>กำลังโหลด...';
            this.disabled = true;
            // Reload the page
            setTimeout(() => {
                location.reload();
            }, 500);
        });
    }
});
</script>
@endpush
@endsection
