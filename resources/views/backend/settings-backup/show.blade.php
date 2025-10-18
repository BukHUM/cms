@extends('backend.layouts.app')

@section('title', 'รายละเอียดการตั้งค่าการสำรองข้อมูล')
@section('page-title', 'รายละเอียดการตั้งค่าการสำรองข้อมูล')
@section('page-description', 'ดูรายละเอียดการตั้งค่าสำหรับการสำรองข้อมูล')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Back Button -->
    <div class="mb-6">
        <a href="{{ route('backend.settings-backup.index') }}" class="btn-custom-secondary">
            <i class="fas fa-arrow-left mr-2"></i>
            กลับไปหน้ารายการ
        </a>
    </div>

    <!-- Setting Details -->
    <div class="card">
        <div class="card-header">
            <div class="flex justify-between items-center">
                <h3 class="card-title">
                    <i class="fas fa-info-circle mr-2"></i>
                    รายละเอียดการตั้งค่า
                </h3>
                <div class="flex space-x-2">
                    <a href="{{ route('backend.settings-backup.edit', $settingsBackup) }}" class="btn-custom-primary">
                        <i class="fas fa-edit mr-2"></i>
                        แก้ไข
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Basic Information -->
                <div class="space-y-4">
                    <h4 class="font-semibold text-gray-900 border-b pb-2">ข้อมูลพื้นฐาน</h4>
                    
                    <div class="space-y-3">
                        <div>
                            <label class="text-sm font-medium text-gray-600">รหัสการตั้งค่า</label>
                            <p class="text-lg font-mono bg-gray-100 p-2 rounded">{{ $settingsBackup->key }}</p>
                        </div>

                        <div>
                            <label class="text-sm font-medium text-gray-600">ค่าการตั้งค่า</label>
                            <div class="bg-gray-50 p-3 rounded border">
                                <code class="text-sm">{{ $settingsBackup->value }}</code>
                            </div>
                        </div>

                        <div>
                            <label class="text-sm font-medium text-gray-600">ประเภทข้อมูล</label>
                            <div class="flex items-center space-x-2">
                                <span class="badge badge-primary">{{ $settingsBackup->type }}</span>
                                <span class="text-sm text-gray-600">
                                    @switch($settingsBackup->type)
                                        @case('string')
                                            ข้อความ
                                            @break
                                        @case('boolean')
                                            ใช่/ไม่ใช่
                                            @break
                                        @case('integer')
                                            ตัวเลข
                                            @break
                                        @case('json')
                                            ข้อมูลโครงสร้าง
                                            @break
                                        @default
                                            ไม่ระบุ
                                    @endswitch
                                </span>
                            </div>
                        </div>

                        <div>
                            <label class="text-sm font-medium text-gray-600">กลุ่มการตั้งค่า</label>
                            <div class="flex items-center space-x-2">
                                <span class="badge badge-secondary">{{ $settingsBackup->group }}</span>
                                <span class="text-sm text-gray-600">
                                    @switch($settingsBackup->group)
                                        @case('general')
                                            ทั่วไป
                                            @break
                                        @case('auto_backup')
                                            สำรองอัตโนมัติ
                                            @break
                                        @case('notification')
                                            การแจ้งเตือน
                                            @break
                                        @case('storage')
                                            การจัดเก็บ
                                            @break
                                        @case('security')
                                            ความปลอดภัย
                                            @break
                                        @default
                                            ไม่ระบุ
                                    @endswitch
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Additional Information -->
                <div class="space-y-4">
                    <h4 class="font-semibold text-gray-900 border-b pb-2">ข้อมูลเพิ่มเติม</h4>
                    
                    <div class="space-y-3">
                        <div>
                            <label class="text-sm font-medium text-gray-600">คำอธิบาย</label>
                            <div class="bg-gray-50 p-3 rounded border min-h-[60px]">
                                @if($settingsBackup->description)
                                    <p class="text-sm text-gray-700">{{ $settingsBackup->description }}</p>
                                @else
                                    <p class="text-sm text-gray-500 italic">ไม่มีคำอธิบาย</p>
                                @endif
                            </div>
                        </div>

                        <div>
                            <label class="text-sm font-medium text-gray-600">สถานะการใช้งาน</label>
                            <div class="flex items-center space-x-2">
                                <span class="badge {{ $settingsBackup->is_active ? 'badge-success' : 'badge-danger' }}">
                                    {{ $settingsBackup->is_active ? 'เปิดใช้งาน' : 'ปิดใช้งาน' }}
                                </span>
                                @if($settingsBackup->is_active)
                                    <i class="fas fa-check-circle text-green-500"></i>
                                @else
                                    <i class="fas fa-times-circle text-red-500"></i>
                                @endif
                            </div>
                        </div>

                        <div>
                            <label class="text-sm font-medium text-gray-600">ลำดับการแสดงผล</label>
                            <p class="text-lg font-semibold text-gray-900">{{ $settingsBackup->sort_order }}</p>
                        </div>

                        <div>
                            <label class="text-sm font-medium text-gray-600">สร้างเมื่อ</label>
                            <p class="text-sm text-gray-600">{{ $settingsBackup->created_at->format('d/m/Y H:i:s') }}</p>
                        </div>

                        <div>
                            <label class="text-sm font-medium text-gray-600">อัพเดตล่าสุด</label>
                            <p class="text-sm text-gray-600">{{ $settingsBackup->updated_at->format('d/m/Y H:i:s') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Value Preview -->
    <div class="card mt-6">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-eye mr-2"></i>
                ตัวอย่างการใช้งาน
            </h3>
        </div>
        <div class="card-body">
            <div class="space-y-4">
                <!-- PHP Usage -->
                <div>
                    <h4 class="font-semibold text-gray-900 mb-2">การใช้งานใน PHP</h4>
                    <div class="bg-gray-900 text-green-400 p-4 rounded-lg overflow-x-auto">
                        <pre><code>// ดึงค่าการตั้งค่า
$value = SettingsBackup::getValue('{{ $settingsBackup->key }}');

// ตั้งค่าการตั้งค่า
SettingsBackup::setValue('{{ $settingsBackup->key }}', '{{ $settingsBackup->value }}', '{{ $settingsBackup->type }}');

// ดึงค่าพร้อมค่าเริ่มต้น
$value = SettingsBackup::getValue('{{ $settingsBackup->key }}', '{{ $settingsBackup->type === 'boolean' ? 'false' : ($settingsBackup->type === 'integer' ? '0' : '') }}');</code></pre>
                    </div>
                </div>

                <!-- Blade Usage -->
                <div>
                    <h4 class="font-semibold text-gray-900 mb-2">การใช้งานใน Blade Template</h4>
                    <div class="bg-gray-900 text-green-400 p-4 rounded-lg overflow-x-auto">
                        <pre><code>@php
    $backupEnabled = SettingsBackup::getValue('{{ $settingsBackup->key }}', {{ $settingsBackup->type === 'boolean' ? 'false' : ($settingsBackup->type === 'integer' ? '0' : '""') }});
@endphp

@if($backupEnabled)
    <p>การสำรองข้อมูลเปิดใช้งาน</p>
@else
    <p>การสำรองข้อมูลปิดใช้งาน</p>
@endif</code></pre>
                    </div>
                </div>

                <!-- Current Value -->
                <div>
                    <h4 class="font-semibold text-gray-900 mb-2">ค่าปัจจุบัน (แปลงแล้ว)</h4>
                    <div class="bg-blue-50 p-4 rounded-lg">
                        @php
                            $convertedValue = $settingsBackup->castValue($settingsBackup->value, $settingsBackup->type);
                        @endphp
                        <div class="font-mono text-sm">
                            @if($settingsBackup->type === 'json')
                                <pre>{{ json_encode($convertedValue, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                            @else
                                <code>{{ var_export($convertedValue, true) }}</code>
                            @endif
                        </div>
                        <p class="text-xs text-gray-600 mt-2">
                            ประเภท: {{ gettype($convertedValue) }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Related Settings -->
    <div class="card mt-6">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-link mr-2"></i>
                การตั้งค่าที่เกี่ยวข้อง
            </h3>
        </div>
        <div class="card-body">
            @php
                $relatedSettings = \App\Models\SettingsBackup::where('group', $settingsBackup->group)
                    ->where('id', '!=', $settingsBackup->id)
                    ->active()
                    ->orderBy('sort_order')
                    ->get();
            @endphp

            @if($relatedSettings->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($relatedSettings as $setting)
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <div class="flex justify-between items-start mb-2">
                                <h4 class="font-semibold text-gray-900">{{ $setting->key }}</h4>
                                <span class="badge badge-primary text-xs">{{ $setting->type }}</span>
                            </div>
                            <p class="text-sm text-gray-600 mb-2">{{ $setting->value }}</p>
                            @if($setting->description)
                                <p class="text-xs text-gray-500">{{ Str::limit($setting->description, 50) }}</p>
                            @endif
                            <div class="mt-3">
                                <a href="{{ route('backend.settings-backup.show', $setting) }}" class="text-blue-600 hover:text-blue-800 text-sm">
                                    <i class="fas fa-eye mr-1"></i>
                                    ดูรายละเอียด
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8">
                    <i class="fas fa-info-circle text-gray-400 text-4xl mb-4"></i>
                    <p class="text-gray-600">ไม่มีการตั้งค่าที่เกี่ยวข้องในกลุ่มนี้</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="flex justify-end space-x-4 mt-6">
        <a href="{{ route('backend.settings-backup.index') }}" class="btn-custom-secondary">
            <i class="fas fa-list mr-2"></i>
            ดูรายการทั้งหมด
        </a>
        <a href="{{ route('backend.settings-backup.edit', $settingsBackup) }}" class="btn-custom-primary">
            <i class="fas fa-edit mr-2"></i>
            แก้ไขการตั้งค่า
        </a>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Copy code to clipboard
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function() {
        Swal.fire({
            title: 'สำเร็จ',
            text: 'คัดลอกโค้ดเรียบร้อยแล้ว',
            icon: 'success',
            timer: 2000,
            showConfirmButton: false
        });
    });
}

// Add copy buttons to code blocks
document.addEventListener('DOMContentLoaded', function() {
    const codeBlocks = document.querySelectorAll('pre code');
    codeBlocks.forEach(function(block) {
        const copyButton = document.createElement('button');
        copyButton.className = 'absolute top-2 right-2 bg-gray-700 text-white px-2 py-1 rounded text-xs hover:bg-gray-600';
        copyButton.innerHTML = '<i class="fas fa-copy"></i>';
        copyButton.onclick = function() {
            copyToClipboard(block.textContent);
        };
        
        const container = block.parentElement;
        container.style.position = 'relative';
        container.appendChild(copyButton);
    });
});
</script>
@endpush
