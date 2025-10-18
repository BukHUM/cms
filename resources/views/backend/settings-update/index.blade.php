@extends('backend.layouts.app')

@section('title', 'การอัพเดตระบบ')

@section('content')
<div class="main-content-area">
    <!-- Page Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold text-gray-900">
                <i class="fas fa-sync-alt mr-2"></i>
                การอัพเดตระบบ
            </h1>
            <p class="text-sm text-gray-600 mt-1 hidden sm:block">จัดการการอัพเดต Laravel Core, Packages และ Configuration</p>
        </div>
        <div class="flex space-x-3">
            <!-- ปุ่มตรวจสอบการอัพเดตถูกลบออกแล้ว -->
        </div>
    </div>

    <!-- Quick Update Actions -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        <!-- Laravel Core Update -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center mb-4">
                <div class="p-3 rounded-full bg-red-100 text-red-600">
                    <i class="fas fa-fire text-xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-900">Laravel Core</h3>
                    <p class="text-sm text-gray-600">อัพเดต Laravel Framework</p>
                </div>
            </div>
                    <div class="space-y-3">
                        <div class="text-sm">
                            <span class="text-gray-500">เวอร์ชันปัจจุบัน:</span>
                            <span class="font-medium text-gray-900">{{ app()->version() }}</span>
                        </div>
                        @if(isset($updateStatus['laravel']) && $updateStatus['laravel']['available'])
                            <div class="text-sm">
                                <span class="text-gray-500">เวอร์ชันใหม่:</span>
                                <span class="font-medium text-green-600">{{ $updateStatus['laravel']['latest_version'] }}</span>
                            </div>
                            <div class="bg-green-50 border border-green-200 rounded-lg p-3">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-check-circle text-green-400"></i>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm text-green-800">มีเวอร์ชันใหม่ให้อัพเดต</p>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-3">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-info-circle text-blue-400"></i>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm text-blue-800">ใช้เวอร์ชันล่าสุดแล้ว</p>
                                    </div>
                                </div>
                            </div>
                        @endif
                        
                        @if(isset($updateStatus['laravel']) && $updateStatus['laravel']['available'])
                            <form method="POST" action="{{ route('backend.settings-update.quick-update') }}" class="space-y-3">
                                @csrf
                                <input type="hidden" name="update_type" value="core">
                                <input type="hidden" name="component_name" value="Laravel Framework">
                                <input type="hidden" name="target_version" value="{{ $updateStatus['laravel']['latest_version'] }}">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">คำอธิบาย</label>
                                    <textarea name="description" rows="2" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" placeholder="อัพเดต Laravel Framework เป็นเวอร์ชันล่าสุด">{{ $updateStatus['laravel']['description'] ?? 'อัพเดต Laravel Framework เป็นเวอร์ชันล่าสุด' }}</textarea>
                                </div>
                                <button type="submit" class="w-full bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2" onclick="return confirmUpdate('Laravel Core')">
                                    <i class="fas fa-sync-alt mr-2"></i>
                                    อัพเดต Laravel Core
                                </button>
                            </form>
                        @else
                            <button disabled class="w-full bg-gray-400 text-white px-4 py-2 rounded-md cursor-not-allowed">
                                <i class="fas fa-ban mr-2"></i>
                                ไม่มีการอัพเดต
                            </button>
                        @endif
                    </div>
        </div>

        <!-- Composer Packages Update -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center mb-4">
                <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                    <i class="fas fa-box text-xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-900">Composer Packages</h3>
                    <p class="text-sm text-gray-600">อัพเดต Packages ทั้งหมด</p>
                </div>
            </div>
                    <div class="space-y-3">
                        <div class="text-sm">
                            <span class="text-gray-500">สถานะ:</span>
                            <span class="font-medium text-gray-900">พร้อมอัพเดต</span>
                        </div>
                        @if(isset($updateStatus['packages']) && $updateStatus['packages']['available'])
                            <div class="text-sm">
                                <span class="text-gray-500">Packages ที่อัพเดตได้:</span>
                                <span class="font-medium text-green-600">{{ $updateStatus['packages']['count'] }} packages</span>
                            </div>
                            <div class="bg-green-50 border border-green-200 rounded-lg p-3">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-check-circle text-green-400"></i>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm text-green-800">มี packages ใหม่ให้อัพเดต</p>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-3">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-info-circle text-blue-400"></i>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm text-blue-800">Packages ทั้งหมดเป็นเวอร์ชันล่าสุดแล้ว</p>
                                    </div>
                                </div>
                            </div>
                        @endif
                        
                        @if(isset($updateStatus['packages']) && $updateStatus['packages']['available'])
                            <form method="POST" action="{{ route('backend.settings-update.quick-update') }}" class="space-y-3">
                                @csrf
                                <input type="hidden" name="update_type" value="package">
                                <input type="hidden" name="component_name" value="Composer Packages">
                                <input type="hidden" name="target_version" value="latest">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">คำอธิบาย</label>
                                    <textarea name="description" rows="2" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" placeholder="อัพเดต Composer packages ทั้งหมด">{{ $updateStatus['packages']['description'] ?? 'อัพเดต Composer packages ทั้งหมด' }}</textarea>
                                </div>
                                <button type="submit" class="w-full bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2" onclick="return confirmUpdate('Composer Packages')">
                                    <i class="fas fa-sync-alt mr-2"></i>
                                    อัพเดต Packages
                                </button>
                            </form>
                        @else
                            <button disabled class="w-full bg-gray-400 text-white px-4 py-2 rounded-md cursor-not-allowed">
                                <i class="fas fa-ban mr-2"></i>
                                ไม่มีการอัพเดต
                            </button>
                        @endif
                    </div>
        </div>

        <!-- Configuration Update -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center mb-4">
                <div class="p-3 rounded-full bg-green-100 text-green-600">
                    <i class="fas fa-cog text-xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-900">Configuration</h3>
                    <p class="text-sm text-gray-600">อัพเดต Configuration</p>
                </div>
            </div>
            <div class="space-y-3">
                <div class="text-sm">
                    <span class="text-gray-500">สถานะ:</span>
                    <span class="font-medium text-gray-900">พร้อมอัพเดต</span>
                </div>
                @if(isset($updateStatus['config']) && $updateStatus['config']['available'])
                    <div class="text-sm">
                        <span class="text-gray-500">การอัพเดต:</span>
                        <span class="font-medium text-green-600">{{ $updateStatus['config']['changes'] }} การเปลี่ยนแปลง</span>
                    </div>
                    <div class="bg-green-50 border border-green-200 rounded-lg p-3">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-check-circle text-green-400"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-green-800">มี configuration ใหม่ให้อัพเดต</p>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-3">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-info-circle text-blue-400"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-blue-800">Configuration เป็นเวอร์ชันล่าสุดแล้ว</p>
                            </div>
                        </div>
                    </div>
                @endif
                
                @if(isset($updateStatus['config']) && $updateStatus['config']['available'])
                    <form method="POST" action="{{ route('backend.settings-update.quick-update') }}" class="space-y-3">
                        @csrf
                        <input type="hidden" name="update_type" value="config">
                        <input type="hidden" name="component_name" value="Configuration">
                        <input type="hidden" name="target_version" value="latest">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">คำอธิบาย</label>
                            <textarea name="description" rows="2" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" placeholder="อัพเดต Configuration files">{{ $updateStatus['config']['description'] ?? 'อัพเดต Configuration files' }}</textarea>
                        </div>
                        <button type="submit" class="w-full bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2" onclick="return confirmUpdate('Configuration')">
                            <i class="fas fa-sync-alt mr-2"></i>
                            อัพเดต Config
                        </button>
                    </form>
                @else
                    <button disabled class="w-full bg-gray-400 text-white px-4 py-2 rounded-md cursor-not-allowed">
                        <i class="fas fa-ban mr-2"></i>
                        ไม่มีการอัพเดต
                    </button>
                @endif
            </div>
        </div>
    </div>

    <!-- System Information -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Current System Status -->
        <div class="bg-white rounded-lg shadow-md">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">
                    <i class="fas fa-info-circle mr-2"></i>
                    สถานะระบบปัจจุบัน
                </h3>
            </div>
            <div class="p-6">
                <dl class="grid grid-cols-1 gap-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Laravel Version</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ app()->version() }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">PHP Version</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ PHP_VERSION }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Environment</dt>
                        <dd class="mt-1">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ app()->environment() === 'production' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                {{ app()->environment() }}
                            </span>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Debug Mode</dt>
                        <dd class="mt-1">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ config('app.debug') ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                                {{ config('app.debug') ? 'Enabled' : 'Disabled' }}
                            </span>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Database Driver</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ config('database.default') }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Cache Driver</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ config('cache.default') }}</dd>
                    </div>
                </dl>
            </div>
        </div>

        <!-- Update History -->
        <div class="bg-white rounded-lg shadow-md">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">
                    <i class="fas fa-history mr-2"></i>
                    ประวัติการอัพเดต
                </h3>
            </div>
            <div class="p-6">
                @if(isset($recentUpdates) && $recentUpdates->count() > 0)
                    <div class="space-y-4">
                        @foreach($recentUpdates as $update)
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                <div class="flex-1">
                                    <div class="flex items-center space-x-2">
                                        <span class="text-sm font-medium text-gray-900">{{ $update->component_name }}</span>
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $update->status_badge }}">{{ $update->status_text }}</span>
                                    </div>
                                    <div class="text-xs text-gray-500 mt-1">
                                        {{ $update->created_at->format('d/m/Y H:i') }}
                                    </div>
                                </div>
                                <div class="flex space-x-2">
                                    @if($update->canStart())
                                        <form method="POST" action="{{ route('backend.settings-update.start', $update) }}" class="inline">
                                            @csrf
                                            <button type="submit" class="text-green-600 hover:text-green-800" title="เริ่มการอัพเดต">
                                                <i class="fas fa-play"></i>
                                            </button>
                                        </form>
                                    @endif
                                    @if($update->canCancel())
                                        <form method="POST" action="{{ route('backend.settings-update.cancel', $update) }}" class="inline">
                                            @csrf
                                            <button type="submit" class="text-yellow-600 hover:text-yellow-800" title="ยกเลิก">
                                                <i class="fas fa-stop"></i>
                                            </button>
                                        </form>
                                    @endif
                                    @if($update->canRetry())
                                        <form method="POST" action="{{ route('backend.settings-update.retry', $update) }}" class="inline">
                                            @csrf
                                            <button type="submit" class="text-blue-600 hover:text-blue-800" title="ลองใหม่">
                                                <i class="fas fa-redo"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <i class="fas fa-history text-4xl text-gray-300 mb-4"></i>
                        <p class="text-gray-500">ยังไม่มีการอัพเดตใดๆ</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- System Maintenance Actions -->
    <div class="bg-white rounded-lg shadow-md">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">
                <i class="fas fa-tools mr-2"></i>
                การบำรุงรักษาระบบ
            </h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <form method="POST" action="{{ route('backend.settings-update.clear-cache') }}" class="inline">
                    @csrf
                    <button type="submit" class="w-full bg-yellow-600 text-white px-4 py-2 rounded-md hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2" onclick="return confirmMaintenance('ล้าง Cache')">
                        <i class="fas fa-broom mr-2"></i>
                        ล้าง Cache
                    </button>
                </form>
                
                <form method="POST" action="{{ route('backend.settings-update.optimize') }}" class="inline">
                    @csrf
                    <button type="submit" class="w-full bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2" onclick="return confirmMaintenance('Optimize ระบบ')">
                        <i class="fas fa-tachometer-alt mr-2"></i>
                        Optimize
                    </button>
                </form>
                
                <form method="POST" action="{{ route('backend.settings-update.migrate') }}" class="inline">
                    @csrf
                    <button type="submit" class="w-full bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2" onclick="return confirmMaintenance('Run Migrations')">
                        <i class="fas fa-database mr-2"></i>
                        Run Migrations
                    </button>
                </form>
                
                <form method="POST" action="{{ route('backend.settings-update.seed') }}" class="inline">
                    @csrf
                    <button type="submit" class="w-full bg-purple-600 text-white px-4 py-2 rounded-md hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2" onclick="return confirmMaintenance('Run Seeders')">
                        <i class="fas fa-seedling mr-2"></i>
                        Run Seeders
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-refresh every 30 seconds for in-progress updates
    const inProgressElements = document.querySelectorAll('.bg-purple-100');
    if (inProgressElements.length > 0) {
        setTimeout(function() {
            location.reload();
        }, 30000);
    }
});

// SweetAlert functions
function confirmUpdate(type) {
    return Swal.fire({
        title: 'ยืนยันการอัพเดต',
        text: `คุณแน่ใจหรือไม่ที่จะอัพเดต ${type}?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'ใช่, อัพเดตเลย!',
        cancelButtonText: 'ยกเลิก'
    }).then((result) => {
        if (result.isConfirmed) {
            // Show loading
            Swal.fire({
                title: 'กำลังอัพเดต...',
                text: 'กรุณารอสักครู่',
                icon: 'info',
                allowOutsideClick: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            // Submit the form
            event.target.closest('form').submit();
        }
        return result.isConfirmed;
    });
}

function confirmMaintenance(action) {
    return Swal.fire({
        title: 'ยืนยันการดำเนินการ',
        text: `คุณแน่ใจหรือไม่ที่จะ${action}?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'ใช่, ดำเนินการเลย!',
        cancelButtonText: 'ยกเลิก'
    }).then((result) => {
        if (result.isConfirmed) {
            // Show loading
            Swal.fire({
                title: 'กำลังดำเนินการ...',
                text: 'กรุณารอสักครู่',
                icon: 'info',
                allowOutsideClick: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            // Submit the form
            event.target.closest('form').submit();
        }
        return result.isConfirmed;
    });
}
</script>
@endpush
@endsection