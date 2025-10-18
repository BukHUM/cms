@extends('backend.layouts.app')

@section('title', 'Performance Settings - การตั้งค่าประสิทธิภาพ')

@section('content')
<div class="main-content">
    <main class="main-content-area">
        <!-- Header Section -->
        <div class="mb-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">
                        <i class="fas fa-chart-line mr-2 text-blue-600"></i>
                        Performance Settings
                    </h1>
                    <p class="text-sm text-gray-600 mt-1">การตั้งค่าประสิทธิภาพของระบบ</p>
                </div>
                <div class="mt-4 sm:mt-0 flex space-x-2">
                    <a href="{{ route('backend.settings-performance.create') }}" class="btn-primary">
                        <i class="fas fa-plus mr-2"></i>
                        เพิ่มการตั้งค่า
                    </a>
                    <button onclick="exportPerformanceSettings()" class="btn-secondary">
                        <i class="fas fa-download mr-2"></i>
                        Export CSV
                    </button>
                </div>
            </div>
        </div>

        <!-- Filter Section -->
        <div class="card mb-6">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-filter mr-2"></i>
                    ตัวกรองข้อมูล
                </h3>
            </div>
            <div class="card-body">
                <form id="filter-form" method="GET" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <!-- Search -->
                    <div class="form-group">
                        <label class="form-label">ค้นหา</label>
                        <input type="text" name="search" value="{{ request('search') }}" 
                               class="form-input" placeholder="ค้นหาชื่อ, คีย์, คำอธิบาย...">
                    </div>

                    <!-- Category -->
                    <div class="form-group">
                        <label class="form-label">หมวดหมู่</label>
                        <select name="category" class="form-input">
                            <option value="">ทั้งหมด</option>
                            @foreach($categories as $key => $label)
                                <option value="{{ $key }}" {{ request('category') == $key ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Type -->
                    <div class="form-group">
                        <label class="form-label">ประเภทข้อมูล</label>
                        <select name="type" class="form-input">
                            <option value="">ทั้งหมด</option>
                            @foreach($types as $key => $label)
                                <option value="{{ $key }}" {{ request('type') == $key ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Status -->
                    <div class="form-group">
                        <label class="form-label">สถานะ</label>
                        <select name="status" class="form-input">
                            <option value="">ทั้งหมด</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>ใช้งาน</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>ไม่ใช้งาน</option>
                        </select>
                    </div>

                    <!-- Filter Buttons -->
                    <div class="form-group md:col-span-2 lg:col-span-4">
                        <div class="flex space-x-2">
                            <button type="submit" class="btn-primary">
                                <i class="fas fa-search mr-2"></i>
                                ค้นหา
                            </button>
                            <a href="{{ route('backend.settings-performance.index') }}" class="btn-secondary">
                                <i class="fas fa-times mr-2"></i>
                                ล้างตัวกรอง
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Performance Settings Table -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-list mr-2"></i>
                    รายการการตั้งค่าประสิทธิภาพ
                    <span class="text-sm font-normal text-gray-500 ml-2">
                        ({{ $performanceSettings->count() }} รายการ)
                    </span>
                </h3>
            </div>
            <div class="card-body p-0">
                <div class="overflow-x-auto">
                    <table class="table">
                        <thead class="table-header">
                            <tr>
                                <th class="table-cell font-medium">ชื่อ</th>
                                <th class="table-cell font-medium">คีย์</th>
                                <th class="table-cell font-medium">ค่า</th>
                                <th class="table-cell font-medium">ประเภท</th>
                                <th class="table-cell font-medium">หมวดหมู่</th>
                                <th class="table-cell font-medium">สถานะ</th>
                                <th class="table-cell font-medium">เรียงลำดับ</th>
                                <th class="table-cell font-medium">จัดการ</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($performanceSettings as $setting)
                                <tr class="table-row">
                                    <td class="table-cell">
                                        <div>
                                            <div class="font-medium text-sm">{{ $setting->name }}</div>
                                            @if($setting->description)
                                                <div class="text-xs text-gray-500 mt-1">{{ \Illuminate\Support\Str::limit($setting->description, 50) }}</div>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="table-cell">
                                        <span class="text-sm font-mono bg-gray-100 px-2 py-1 rounded">{{ $setting->key }}</span>
                                    </td>
                                    <td class="table-cell">
                                        <div class="text-sm">
                                            @if($setting->type === 'boolean')
                                                <span class="badge {{ $setting->typed_value ? 'badge-success' : 'badge-secondary' }}">
                                                    {{ $setting->typed_value ? 'เปิด' : 'ปิด' }}
                                                </span>
                                            @elseif($setting->type === 'array' || $setting->type === 'json')
                                                <span class="text-gray-600">{{ count($setting->typed_value) }} รายการ</span>
                                            @else
                                                <span class="font-mono">{{ \Illuminate\Support\Str::limit($setting->value, 30) }}</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="table-cell">
                                        <span class="badge badge-primary">{{ $setting->type }}</span>
                                    </td>
                                    <td class="table-cell">
                                        <span class="text-sm">{{ $categories[$setting->category] ?? $setting->category }}</span>
                                    </td>
                                    <td class="table-cell">
                                        <span class="badge {{ $setting->is_active ? 'badge-success' : 'badge-secondary' }}">
                                            {{ $setting->is_active ? 'ใช้งาน' : 'ไม่ใช้งาน' }}
                                        </span>
                                    </td>
                                    <td class="table-cell">
                                        <span class="text-sm">{{ $setting->sort_order }}</span>
                                    </td>
                                    <td class="table-cell">
                                        <div class="flex space-x-1">
                                            <a href="{{ route('backend.settings-performance.show', $setting) }}" 
                                               class="btn-primary btn-sm">
                                                <i class="fas fa-eye mr-1"></i>
                                                ดู
                                            </a>
                                            <a href="{{ route('backend.settings-performance.edit', $setting) }}" 
                                               class="btn-warning btn-sm">
                                                <i class="fas fa-edit mr-1"></i>
                                                แก้ไข
                                            </a>
                                            <button onclick="resetSetting({{ $setting->id }})" 
                                                    class="btn-secondary btn-sm"
                                                    title="รีเซ็ตเป็นค่าเริ่มต้น">
                                                <i class="fas fa-undo mr-1"></i>
                                                รีเซ็ต
                                            </button>
                                            <button onclick="deleteSetting({{ $setting->id }})" 
                                                    class="btn-danger btn-sm">
                                                <i class="fas fa-trash mr-1"></i>
                                                ลบ
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="table-cell text-center py-8">
                                        <div class="text-gray-500">
                                            <i class="fas fa-chart-line text-4xl mb-4"></i>
                                            <p class="text-lg font-medium">ไม่พบการตั้งค่าประสิทธิภาพ</p>
                                            <p class="text-sm">ยังไม่มีการตั้งค่าประสิทธิภาพในระบบ</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            
            @if(method_exists($performanceSettings, 'hasPages') && $performanceSettings->hasPages())
                <div class="card-footer">
                    {{ $performanceSettings->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </main>
</div>

<script>
function exportPerformanceSettings() {
    const form = document.getElementById('filter-form');
    const formData = new FormData(form);
    
    // Create URL with current filters
    const params = new URLSearchParams();
    for (let [key, value] of formData.entries()) {
        if (value) params.append(key, value);
    }
    
    const url = '{{ route("backend.settings-performance.export") }}?' + params.toString();
    
    // Show loading
    Swal.fire({
        title: 'กำลังส่งออกข้อมูล...',
        text: 'กรุณารอสักครู่',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });
    
    // Create download link
    const link = document.createElement('a');
    link.href = url;
    link.download = 'performance_settings.csv';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
    
    // Hide loading
    setTimeout(() => {
        Swal.close();
    }, 1000);
}

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

function deleteSetting(id) {
    Swal.fire({
        title: 'ลบการตั้งค่า?',
        text: 'คุณต้องการลบการตั้งค่านี้หรือไม่? การดำเนินการนี้ไม่สามารถย้อนกลับได้!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'ใช่, ลบ!',
        cancelButtonText: 'ยกเลิก'
    }).then((result) => {
        if (result.isConfirmed) {
            // Create form and submit
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route("backend.settings-performance.destroy", ":id") }}'.replace(':id', id);
            
            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = '{{ csrf_token() }}';
            
            const methodField = document.createElement('input');
            methodField.type = 'hidden';
            methodField.name = '_method';
            methodField.value = 'DELETE';
            
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
