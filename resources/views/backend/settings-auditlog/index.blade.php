@extends('backend.layouts.app')

@section('title', 'Audit Log - การติดตามการเปลี่ยนแปลง')

@section('content')
<div class="main-content">
    <main class="main-content-area">
        <!-- Header Section -->
        <div class="mb-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">
                        <i class="fas fa-history mr-2 text-blue-600"></i>
                        Audit Log
                    </h1>
                    <p class="text-sm text-gray-600 mt-1">การติดตามการเปลี่ยนแปลงในระบบ</p>
                </div>
                <div class="mt-4 sm:mt-0">
                    <button onclick="exportAuditLogs()" class="btn-secondary">
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
                               class="form-input" placeholder="ค้นหาผู้ใช้, ประเภทข้อมูล, เหตุการณ์...">
                    </div>

                    <!-- Event Type -->
                    <div class="form-group">
                        <label class="form-label">ประเภทเหตุการณ์</label>
                        <select name="event" class="form-input">
                            <option value="">ทั้งหมด</option>
                            @foreach($eventTypes as $event)
                                <option value="{{ $event }}" {{ request('event') == $event ? 'selected' : '' }}>
                                    {{ ucfirst($event) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Auditable Type -->
                    <div class="form-group">
                        <label class="form-label">ประเภทข้อมูล</label>
                        <select name="auditable_type" class="form-input">
                            <option value="">ทั้งหมด</option>
                            @foreach($auditableTypes as $type)
                                <option value="{{ $type }}" {{ request('auditable_type') == $type ? 'selected' : '' }}>
                                    {{ class_basename($type) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Date Range -->
                    <div class="form-group">
                        <label class="form-label">ช่วงวันที่</label>
                        <div class="flex space-x-2">
                            <input type="date" name="date_from" value="{{ request('date_from') }}" 
                                   class="form-input flex-1" placeholder="จากวันที่">
                            <input type="date" name="date_to" value="{{ request('date_to') }}" 
                                   class="form-input flex-1" placeholder="ถึงวันที่">
                        </div>
                    </div>

                    <!-- Filter Buttons -->
                    <div class="form-group md:col-span-2 lg:col-span-4">
                        <div class="flex space-x-2">
                            <button type="submit" class="btn-primary">
                                <i class="fas fa-search mr-2"></i>
                                ค้นหา
                            </button>
                            <a href="{{ route('backend.settings.auditlog.index') }}" class="btn-secondary">
                                <i class="fas fa-times mr-2"></i>
                                ล้างตัวกรอง
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Audit Logs Table -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-list mr-2"></i>
                    รายการ Audit Log
                    <span class="text-sm font-normal text-gray-500 ml-2">
                        ({{ $auditLogs->total() }} รายการ)
                    </span>
                </h3>
            </div>
            <div class="card-body p-0">
                <div class="overflow-x-auto">
                    <table class="table">
                        <thead class="table-header">
                            <tr>
                                <th class="table-cell font-medium">วันที่/เวลา</th>
                                <th class="table-cell font-medium">ผู้ใช้</th>
                                <th class="table-cell font-medium">เหตุการณ์</th>
                                <th class="table-cell font-medium">ประเภทข้อมูล</th>
                                <th class="table-cell font-medium">ข้อมูลที่เปลี่ยนแปลง</th>
                                <th class="table-cell font-medium">การเปลี่ยนแปลง</th>
                                <th class="table-cell font-medium">IP Address</th>
                                <th class="table-cell font-medium">จัดการ</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($auditLogs as $log)
                                <tr class="table-row">
                                    <td class="table-cell">
                                        <div class="text-sm">
                                            <div class="font-medium">{{ $log->created_at->format('d/m/Y') }}</div>
                                            <div class="text-gray-500">{{ $log->created_at->format('H:i:s') }}</div>
                                        </div>
                                    </td>
                                    <td class="table-cell">
                                        @if($log->user)
                                            <div class="flex items-center">
                                                <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                                    <i class="fas fa-user text-blue-600 text-sm"></i>
                                                </div>
                                                <div>
                                                    <div class="font-medium text-sm">{{ $log->user->name }}</div>
                                                    <div class="text-xs text-gray-500">{{ $log->user->email }}</div>
                                                </div>
                                            </div>
                                        @else
                                            <span class="text-gray-500 text-sm">System</span>
                                        @endif
                                    </td>
                                    <td class="table-cell">
                                        @php
                                            $eventColors = [
                                                'created' => 'bg-green-100 text-green-800',
                                                'updated' => 'bg-blue-100 text-blue-800',
                                                'deleted' => 'bg-red-100 text-red-800',
                                                'restored' => 'bg-yellow-100 text-yellow-800',
                                            ];
                                            $color = $eventColors[$log->event] ?? 'bg-gray-100 text-gray-800';
                                        @endphp
                                        <span class="badge {{ $color }}">
                                            {{ ucfirst($log->event) }}
                                        </span>
                                    </td>
                                    <td class="table-cell">
                                        <span class="text-sm font-medium">{{ class_basename($log->auditable_type) }}</span>
                                    </td>
                                    <td class="table-cell">
                                        <span class="text-sm">ID: {{ $log->auditable_id }}</span>
                                    </td>
                                    <td class="table-cell">
                                        @if($log->event === 'created')
                                            <span class="text-green-600 text-sm">
                                                <i class="fas fa-plus mr-1"></i>
                                                สร้างใหม่
                                            </span>
                                        @elseif($log->event === 'updated')
                                            @php
                                                $oldCount = $log->old_values ? count($log->old_values) : 0;
                                                $newCount = $log->new_values ? count($log->new_values) : 0;
                                            @endphp
                                            <span class="text-blue-600 text-sm">
                                                <i class="fas fa-edit mr-1"></i>
                                                แก้ไข {{ $newCount }} ฟิลด์
                                            </span>
                                        @elseif($log->event === 'deleted')
                                            <span class="text-red-600 text-sm">
                                                <i class="fas fa-trash mr-1"></i>
                                                ลบข้อมูล
                                            </span>
                                        @else
                                            <span class="text-gray-600 text-sm">
                                                <i class="fas fa-info-circle mr-1"></i>
                                                {{ ucfirst($log->event) }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="table-cell">
                                        <span class="text-sm font-mono">{{ $log->ip_address ?? '-' }}</span>
                                    </td>
                                    <td class="table-cell">
                                        <a href="{{ route('backend.settings.auditlog.show', $log) }}" 
                                           class="btn-primary btn-sm">
                                            <i class="fas fa-eye mr-1"></i>
                                            ดูรายละเอียด
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="table-cell text-center py-8">
                                        <div class="text-gray-500">
                                            <i class="fas fa-inbox text-4xl mb-4"></i>
                                            <p class="text-lg font-medium">ไม่พบข้อมูล Audit Log</p>
                                            <p class="text-sm">ยังไม่มีการเปลี่ยนแปลงในระบบ</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            
            <div class="card-footer">
                <div class="flex items-center justify-between">
                    <div class="text-sm text-gray-700">
                        แสดง {{ $auditLogs->firstItem() ?? 0 }} ถึง {{ $auditLogs->lastItem() ?? 0 }} จาก {{ $auditLogs->total() }} รายการ
                    </div>
                    <div class="flex items-center">
                        {{ $auditLogs->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<script>
function exportAuditLogs() {
    const form = document.getElementById('filter-form');
    const formData = new FormData(form);
    
    // Create URL with current filters
    const params = new URLSearchParams();
    for (let [key, value] of formData.entries()) {
        if (value) params.append(key, value);
    }
    
    const url = '{{ route("backend.settings.auditlog.export") }}?' + params.toString();
    
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
    link.download = 'audit_logs.csv';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
    
    // Hide loading
    setTimeout(() => {
        Swal.close();
    }, 1000);
}
</script>
@endsection
