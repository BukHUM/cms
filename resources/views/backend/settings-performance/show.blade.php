@extends('backend.layouts.app')

@section('title', 'Performance Setting Details - รายละเอียดการตั้งค่าประสิทธิภาพ')

@section('content')
<div class="main-content">
    <main class="main-content-area">
        <!-- Header Section -->
        <div class="mb-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">
                        <i class="fas fa-chart-line mr-2 text-blue-600"></i>
                        รายละเอียดการตั้งค่าประสิทธิภาพ
                    </h1>
                    <p class="text-sm text-gray-600 mt-1">{{ $performance->name }}</p>
                </div>
                <div class="mt-4 sm:mt-0 flex space-x-2">
                    <a href="{{ route('backend.settings-performance.edit', $performance) }}" class="btn-warning">
                        <i class="fas fa-edit mr-2"></i>
                        แก้ไข
                    </a>
                    <button onclick="resetSetting({{ $performance->id }})" class="btn-secondary">
                        <i class="fas fa-undo mr-2"></i>
                        รีเซ็ต
                    </button>
                    <a href="{{ route('backend.settings-performance.index') }}" class="btn-secondary">
                        <i class="fas fa-arrow-left mr-2"></i>
                        กลับ
                    </a>
                </div>
            </div>
        </div>

        <!-- Basic Information -->
        <div class="card mb-6">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-info-circle mr-2"></i>
                    ข้อมูลพื้นฐาน
                </h3>
            </div>
            <div class="card-body">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <!-- Name -->
                    <div>
                        <label class="form-label">ชื่อการตั้งค่า</label>
                        <div class="mt-1">
                            <span class="text-sm font-medium">{{ $performance->name }}</span>
                        </div>
                    </div>

                    <!-- Key -->
                    <div>
                        <label class="form-label">คีย์</label>
                        <div class="mt-1">
                            <span class="text-sm font-mono bg-gray-100 px-2 py-1 rounded">{{ $performance->key }}</span>
                        </div>
                    </div>

                    <!-- Type -->
                    <div>
                        <label class="form-label">ประเภทข้อมูล</label>
                        <div class="mt-1">
                            <span class="badge badge-primary">{{ $performance->type }}</span>
                        </div>
                    </div>

                    <!-- Category -->
                    <div>
                        <label class="form-label">หมวดหมู่</label>
                        <div class="mt-1">
                            <span class="text-sm">{{ $performance->category }}</span>
                        </div>
                    </div>

                    <!-- Status -->
                    <div>
                        <label class="form-label">สถานะ</label>
                        <div class="mt-1">
                            <span class="badge {{ $performance->is_active ? 'badge-success' : 'badge-secondary' }}">
                                {{ $performance->is_active ? 'ใช้งาน' : 'ไม่ใช้งาน' }}
                            </span>
                        </div>
                    </div>

                    <!-- Sort Order -->
                    <div>
                        <label class="form-label">ลำดับการเรียง</label>
                        <div class="mt-1">
                            <span class="text-sm">{{ $performance->sort_order }}</span>
                        </div>
                    </div>
                </div>

                <!-- Description -->
                @if($performance->description)
                    <div class="mt-6">
                        <label class="form-label">คำอธิบาย</label>
                        <div class="mt-1">
                            <p class="text-sm text-gray-700">{{ $performance->description }}</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Value Information -->
        <div class="card mb-6">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-cog mr-2"></i>
                    ค่าการตั้งค่า
                </h3>
            </div>
            <div class="card-body">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Current Value -->
                    <div>
                        <label class="form-label">ค่าปัจจุบัน</label>
                        <div class="mt-1">
                            @if($performance->type === 'boolean')
                                <span class="badge {{ $performance->typed_value ? 'badge-success' : 'badge-secondary' }}">
                                    {{ $performance->typed_value ? 'เปิด' : 'ปิด' }}
                                </span>
                            @elseif($performance->type === 'array' || $performance->type === 'json')
                                <div class="bg-gray-50 border rounded-lg p-4">
                                    <pre class="text-sm text-gray-700 whitespace-pre-wrap">{{ json_encode($performance->typed_value, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                </div>
                            @else
                                <span class="text-sm font-mono bg-gray-100 px-2 py-1 rounded">{{ $performance->value }}</span>
                            @endif
                        </div>
                    </div>

                    <!-- Default Value -->
                    <div>
                        <label class="form-label">ค่าเริ่มต้น</label>
                        <div class="mt-1">
                            @if($performance->default_value)
                                @if($performance->type === 'boolean')
                                    <span class="badge {{ $performance->default_value ? 'badge-success' : 'badge-secondary' }}">
                                        {{ $performance->default_value ? 'เปิด' : 'ปิด' }}
                                    </span>
                                @elseif($performance->type === 'array' || $performance->type === 'json')
                                    <div class="bg-gray-50 border rounded-lg p-4">
                                        <pre class="text-sm text-gray-700 whitespace-pre-wrap">{{ json_encode(json_decode($performance->default_value, true), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                    </div>
                                @else
                                    <span class="text-sm font-mono bg-gray-100 px-2 py-1 rounded">{{ $performance->default_value }}</span>
                                @endif
                            @else
                                <span class="text-gray-500 text-sm">ไม่ระบุ</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Validation Rules -->
        @if($performance->validation_rules && count($performance->validation_rules) > 0)
            <div class="card mb-6">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-check-circle mr-2"></i>
                        กฎการตรวจสอบ
                    </h3>
                </div>
                <div class="card-body">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @if(isset($performance->validation_rules['required']) && $performance->validation_rules['required'])
                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                <div class="flex items-center">
                                    <i class="fas fa-exclamation-circle text-blue-600 mr-2"></i>
                                    <span class="font-medium text-blue-800">จำเป็น</span>
                                </div>
                            </div>
                        @endif

                        @if(isset($performance->validation_rules['min']))
                            <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                                <div class="flex items-center">
                                    <i class="fas fa-arrow-down text-green-600 mr-2"></i>
                                    <span class="font-medium text-green-800">ค่าต่ำสุด: {{ $performance->validation_rules['min'] }}</span>
                                </div>
                            </div>
                        @endif

                        @if(isset($performance->validation_rules['max']))
                            <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                                <div class="flex items-center">
                                    <i class="fas fa-arrow-up text-red-600 mr-2"></i>
                                    <span class="font-medium text-red-800">ค่าสูงสุด: {{ $performance->validation_rules['max'] }}</span>
                                </div>
                            </div>
                        @endif

                        @if(isset($performance->validation_rules['min_length']))
                            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                                <div class="flex items-center">
                                    <i class="fas fa-text-width text-yellow-600 mr-2"></i>
                                    <span class="font-medium text-yellow-800">ความยาวต่ำสุด: {{ $performance->validation_rules['min_length'] }}</span>
                                </div>
                            </div>
                        @endif

                        @if(isset($performance->validation_rules['max_length']))
                            <div class="bg-purple-50 border border-purple-200 rounded-lg p-4">
                                <div class="flex items-center">
                                    <i class="fas fa-text-width text-purple-600 mr-2"></i>
                                    <span class="font-medium text-purple-800">ความยาวสูงสุด: {{ $performance->validation_rules['max_length'] }}</span>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endif

        <!-- Options -->
        @if($performance->options && count($performance->options) > 0)
            <div class="card mb-6">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-list mr-2"></i>
                        ตัวเลือก
                    </h3>
                </div>
                <div class="card-body">
                    <div class="bg-gray-50 border rounded-lg p-4">
                        <pre class="text-sm text-gray-700 whitespace-pre-wrap">{{ json_encode($performance->options, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                    </div>
                </div>
            </div>
        @endif

        <!-- Metadata -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-info mr-2"></i>
                    ข้อมูลเมตา
                </h3>
            </div>
            <div class="card-body">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Created Information -->
                    <div>
                        <label class="form-label">สร้างเมื่อ</label>
                        <div class="mt-1 text-sm">
                            <div class="font-medium">{{ $performance->created_at->format('d/m/Y H:i:s') }}</div>
                            <div class="text-gray-500">{{ $performance->created_at->diffForHumans() }}</div>
                        </div>
                    </div>

                    <!-- Updated Information -->
                    <div>
                        <label class="form-label">อัพเดตล่าสุด</label>
                        <div class="mt-1 text-sm">
                            <div class="font-medium">{{ $performance->updated_at->format('d/m/Y H:i:s') }}</div>
                            <div class="text-gray-500">{{ $performance->updated_at->diffForHumans() }}</div>
                        </div>
                    </div>

                    <!-- Creator -->
                    @if($performance->creator)
                        <div>
                            <label class="form-label">ผู้สร้าง</label>
                            <div class="mt-1">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                        <i class="fas fa-user text-blue-600 text-sm"></i>
                                    </div>
                                    <div>
                                        <div class="font-medium text-sm">{{ $performance->creator->name }}</div>
                                        <div class="text-xs text-gray-500">{{ $performance->creator->email }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Updater -->
                    @if($performance->updater)
                        <div>
                            <label class="form-label">ผู้แก้ไขล่าสุด</label>
                            <div class="mt-1">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center mr-3">
                                        <i class="fas fa-user text-green-600 text-sm"></i>
                                    </div>
                                    <div>
                                        <div class="font-medium text-sm">{{ $performance->updater->name }}</div>
                                        <div class="text-xs text-gray-500">{{ $performance->updater->email }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </main>
</div>

<script>
function resetSetting(id) {
    Swal.fire({
        title: 'รีเซ็ตการตั้งค่า?',
        text: 'คุณต้องการรีเซ็ตการตั้งค่านี้เป็นค่าเริ่มต้นหรือไม่?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'ใช่, รีเซ็ต!',
        cancelButtonText: 'ยกเลิก'
    }).then((result) => {
        if (result.isConfirmed) {
            // Create form and submit
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route("backend.settings-performance.reset", ":id") }}'.replace(':id', id);
            
            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = '{{ csrf_token() }}';
            
            const methodField = document.createElement('input');
            methodField.type = 'hidden';
            methodField.name = '_method';
            methodField.value = 'POST';
            
            form.appendChild(csrfToken);
            form.appendChild(methodField);
            document.body.appendChild(form);
            form.submit();
            document.body.removeChild(form);
        }
    });
}
</script>
@endsection
