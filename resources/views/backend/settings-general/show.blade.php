@extends('backend.layouts.app')

@section('title', 'รายละเอียดการตั้งค่า')

@section('content')
<div class="main-content-area">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-6">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">
                <i class="fas fa-eye mr-2"></i>
                รายละเอียดการตั้งค่า
            </h1>
            <p class="text-sm sm:text-base text-gray-600 mt-1">
                ข้อมูลการตั้งค่า: <span class="font-medium">{{ $setting->key }}</span>
            </p>
        </div>
        <div class="mt-4 sm:mt-0 flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-2">
            <a href="{{ route('backend.settings.edit', $setting) }}" 
               class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 w-full sm:w-auto">
                <i class="fas fa-edit mr-2"></i>
                แก้ไข
            </a>
            <a href="{{ route('backend.settings.index') }}" 
               class="bg-gray-600 text-white px-4 py-2 rounded-md hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 w-full sm:w-auto">
                <i class="fas fa-arrow-left mr-2"></i>
                กลับ
            </a>
        </div>
    </div>

    <!-- Setting Details -->
    <div class="bg-white rounded-lg shadow">
        <div class="p-6">
            <div class="flex flex-col sm:flex-row items-start sm:items-center space-y-4 sm:space-y-0 sm:space-x-6">
                <div class="w-20 h-20 bg-blue-500 rounded-full flex items-center justify-center flex-shrink-0">
                    <i class="{{ $setting->type_icon }} text-white text-2xl"></i>
                </div>
                
                <div class="flex-1 min-w-0">
                    <h2 class="text-xl sm:text-2xl font-bold text-gray-900 truncate">{{ $setting->key }}</h2>
                    @if($setting->description)
                        <p class="text-base sm:text-lg text-gray-600 mt-1">{{ $setting->description }}</p>
                    @endif
                    
                    <div class="flex flex-wrap items-center gap-2 mt-3">
                        @if($setting->is_public)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                <i class="fas fa-check-circle mr-1"></i>
                                ใช้งาน
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                <i class="fas fa-times-circle mr-1"></i>
                                ไม่ใช้งาน
                            </span>
                        @endif
                        
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $setting->group_color }}">
                            {{ ucfirst($setting->group) }}
                        </span>
                        
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $setting->type_color }}">
                            <i class="{{ $setting->type_icon }} mr-1"></i>
                            {{ ucfirst($setting->type) }}
                        </span>
                    </div>
                </div>
                
                <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-2 w-full sm:w-auto">
                    @if($setting->canBeDeleted())
                        <button onclick="deleteSetting({{ $setting->id }})" 
                                class="bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 w-full sm:w-auto">
                            <i class="fas fa-trash mr-2"></i>
                            ลบ
                        </button>
                    @endif
                    
                    @if(!$setting->isSystemSetting())
                        <button onclick="toggleStatus({{ $setting->id }}, {{ $setting->is_public ? 'false' : 'true' }})" 
                                class="bg-gray-600 text-white px-4 py-2 rounded-md hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 w-full sm:w-auto">
                            <i class="fas fa-toggle-{{ $setting->is_public ? 'on' : 'off' }} mr-2"></i>
                            {{ $setting->is_public ? 'ปิดใช้งาน' : 'เปิดใช้งาน' }}
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Setting Information -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-6">
        <!-- Basic Information -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">
                    <i class="fas fa-info-circle mr-2"></i>
                    ข้อมูลพื้นฐาน
                </h3>
            </div>
            <div class="p-6">
                <dl class="space-y-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">คีย์</dt>
                        <dd class="mt-1 text-sm text-gray-900 font-mono bg-gray-50 px-3 py-2 rounded border">{{ $setting->key }}</dd>
                    </div>
                    
                    <div>
                        <dt class="text-sm font-medium text-gray-500">ค่า</dt>
                        <dd class="mt-1 text-sm text-gray-900">
                            @if($setting->type === 'boolean')
                                @if($setting->value)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <i class="fas fa-check mr-1"></i>
                                        เปิด ({{ $setting->value }})
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        <i class="fas fa-times mr-1"></i>
                                        ปิด ({{ $setting->value }})
                                    </span>
                                @endif
                            @elseif($setting->type === 'json')
                                <pre class="bg-gray-50 p-3 rounded border text-xs overflow-x-auto">{{ json_encode(json_decode($setting->value), JSON_PRETTY_PRINT) }}</pre>
                            @else
                                <span class="font-mono bg-gray-50 px-3 py-2 rounded border block">{{ $setting->value }}</span>
                            @endif
                        </dd>
                    </div>
                    
                    <div>
                        <dt class="text-sm font-medium text-gray-500">ประเภท</dt>
                        <dd class="mt-1">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $setting->type_color }}">
                                <i class="{{ $setting->type_icon }} mr-1"></i>
                                {{ ucfirst($setting->type) }}
                            </span>
                        </dd>
                    </div>
                    
                    <div>
                        <dt class="text-sm font-medium text-gray-500">กลุ่ม</dt>
                        <dd class="mt-1">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $setting->group_color }}">
                                {{ ucfirst($setting->group) }}
                            </span>
                        </dd>
                    </div>
                </dl>
            </div>
        </div>

        <!-- Additional Information -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">
                    <i class="fas fa-clock mr-2"></i>
                    ข้อมูลเพิ่มเติม
                </h3>
            </div>
            <div class="p-6">
                <dl class="space-y-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">สถานะ</dt>
                        <dd class="mt-1">
                            @if($setting->is_public)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <i class="fas fa-check-circle mr-1"></i>
                                    ใช้งาน
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    <i class="fas fa-times-circle mr-1"></i>
                                    ไม่ใช้งาน
                                </span>
                            @endif
                        </dd>
                    </div>
                    
                    <div>
                        <dt class="text-sm font-medium text-gray-500">ประเภทการตั้งค่า</dt>
                        <dd class="mt-1">
                            @if($setting->isSystemSetting())
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                    <i class="fas fa-cog mr-1"></i>
                                    ระบบ
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    <i class="fas fa-user-cog mr-1"></i>
                                    กำหนดเอง
                                </span>
                            @endif
                        </dd>
                    </div>
                    
                    <div>
                        <dt class="text-sm font-medium text-gray-500">สร้างเมื่อ</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $setting->created_at->format('d/m/Y H:i') }}</dd>
                    </div>
                    
                    <div>
                        <dt class="text-sm font-medium text-gray-500">อัปเดตล่าสุด</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $setting->updated_at->format('d/m/Y H:i') }}</dd>
                    </div>
                </dl>
            </div>
        </div>
    </div>

    <!-- Description -->
    @if($setting->description)
        <div class="bg-white rounded-lg shadow mt-6">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">
                    <i class="fas fa-align-left mr-2"></i>
                    คำอธิบาย
                </h3>
            </div>
            <div class="p-6">
                <p class="text-gray-700 leading-relaxed">{{ $setting->description }}</p>
            </div>
        </div>
    @endif

    <!-- Delete Form -->
    <form id="delete-form" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>

    <!-- Toggle Status Form -->
    <form id="toggle-status-form" method="POST" style="display: none;">
        @csrf
        @method('PATCH')
    </form>
@endsection

@push('scripts')
<script>
    // Delete setting function
    function deleteSetting(settingId) {
        Swal.fire({
            title: 'คุณแน่ใจหรือไม่?',
            text: "คุณจะไม่สามารถย้อนกลับได้!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'ใช่, ลบเลย!',
            cancelButtonText: 'ยกเลิก'
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.getElementById('delete-form');
                form.action = `/backend/settings/${settingId}`;
                form.submit();
            }
        });
    }

    // Toggle status function
    function toggleStatus(settingId, currentStatus) {
        Swal.fire({
            title: 'คุณแน่ใจหรือไม่?',
            text: `คุณต้องการ${currentStatus ? 'เปิดใช้งาน' : 'ปิดใช้งาน'}การตั้งค่านี้หรือไม่?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'ใช่, เปลี่ยนสถานะ!',
            cancelButtonText: 'ยกเลิก'
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.getElementById('toggle-status-form');
                form.action = `/backend/settings/${settingId}/toggle-status`;
                form.submit();
            }
        });
    }
</script>
@endpush
