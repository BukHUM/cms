@extends('backend.layouts.app')

@section('title', 'รายละเอียดการสำรองข้อมูล')
@section('page-title', 'รายละเอียดการสำรองข้อมูล')
@section('page-description', 'ดูรายละเอียดการสำรองข้อมูล')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Back Button -->
    <div class="mb-6">
        <a href="{{ route('backend.settings-backup.index') }}" class="btn-custom-secondary">
            <i class="fas fa-arrow-left mr-2"></i>
            กลับไปหน้ารายการ
        </a>
    </div>

    <!-- Backup Details -->
    <div class="card">
        <div class="card-header">
            <div class="flex justify-between items-center">
                <h3 class="card-title">
                    <i class="fas fa-database mr-2"></i>
                    รายละเอียดการสำรองข้อมูล
                </h3>
                <div class="flex space-x-2">
                    <a href="{{ route('backend.settings-backup.download', $settingsBackup->name) }}" class="btn-custom-primary">
                        <i class="fas fa-download mr-2"></i>
                        ดาวน์โหลด
                    </a>
                    <button onclick="deleteBackup('{{ $settingsBackup->name }}')" class="btn-custom-danger">
                        <i class="fas fa-trash mr-2"></i>
                        ลบ
                    </button>
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
                            <label class="text-sm font-medium text-gray-600">ชื่อไฟล์สำรองข้อมูล</label>
                            <p class="text-lg font-mono bg-gray-100 p-2 rounded">{{ $settingsBackup->name }}</p>
                        </div>

                        <div>
                            <label class="text-sm font-medium text-gray-600">ขนาดไฟล์</label>
                            <div class="bg-gray-50 p-3 rounded border">
                                <code class="text-sm">{{ $settingsBackup->size }}</code>
                            </div>
                        </div>

                        <div>
                            <label class="text-sm font-medium text-gray-600">วันที่สร้าง</label>
                            <div class="flex items-center space-x-2">
                                <span class="badge badge-primary">{{ $settingsBackup->created_at }}</span>
                            </div>
                        </div>

                        <div>
                            <label class="text-sm font-medium text-gray-600">รวมไฟล์ระบบ</label>
                            <div class="flex items-center space-x-2">
                                <span class="badge {{ $settingsBackup->include_files ? 'badge-success' : 'badge-secondary' }}">
                                    {{ $settingsBackup->include_files ? 'ใช่' : 'ไม่' }}
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
                            <label class="text-sm font-medium text-gray-600">เส้นทางไฟล์</label>
                            <div class="bg-gray-50 p-3 rounded border min-h-[60px]">
                                <p class="text-sm text-gray-700">{{ $settingsBackup->path }}</p>
                            </div>
                        </div>

                        <div>
                            <label class="text-sm font-medium text-gray-600">สถานะ</label>
                            <div class="flex items-center space-x-2">
                                <span class="badge badge-success">
                                    พร้อมใช้งาน
                                </span>
                                <i class="fas fa-check-circle text-green-500"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="flex justify-end space-x-4 mt-6">
        <a href="{{ route('backend.settings-backup.index') }}" class="btn-custom-secondary">
            <i class="fas fa-list mr-2"></i>
            ดูรายการทั้งหมด
        </a>
        <a href="{{ route('backend.settings-backup.download', $settingsBackup->name) }}" class="btn-custom-primary">
            <i class="fas fa-download mr-2"></i>
            ดาวน์โหลดไฟล์สำรอง
        </a>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Delete backup function
function deleteBackup(backupName) {
    if (confirm('คุณแน่ใจหรือไม่ที่จะลบไฟล์สำรองข้อมูลนี้?')) {
        fetch('{{ route("backend.settings-backup.delete-backup", ":name") }}'.replace(':name', backupName), {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('ลบไฟล์สำรองข้อมูลเรียบร้อยแล้ว');
                window.location.href = '{{ route("backend.settings-backup.index") }}';
            } else {
                alert(data.message || 'เกิดข้อผิดพลาดในการลบไฟล์สำรองข้อมูล');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('เกิดข้อผิดพลาดในการลบไฟล์สำรองข้อมูล');
        });
    }
}
</script>
@endpush
