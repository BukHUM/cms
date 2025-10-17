@extends('backend.layouts.app')

@section('title', 'Audit Log Details - รายละเอียดการเปลี่ยนแปลง')

@section('content')
<div class="main-content">
    <main class="main-content-area">
        <!-- Header Section -->
        <div class="mb-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">
                        <i class="fas fa-history mr-2 text-blue-600"></i>
                        รายละเอียด Audit Log
                    </h1>
                    <p class="text-sm text-gray-600 mt-1">ID: {{ $auditLog->id }}</p>
                </div>
                <div class="mt-4 sm:mt-0">
                    <a href="{{ route('backend.settings.auditlog.index') }}" class="btn-secondary">
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
                    <!-- Event Information -->
                    <div>
                        <label class="form-label">เหตุการณ์</label>
                        @php
                            $eventColors = [
                                'created' => 'bg-green-100 text-green-800',
                                'updated' => 'bg-blue-100 text-blue-800',
                                'deleted' => 'bg-red-100 text-red-800',
                                'restored' => 'bg-yellow-100 text-yellow-800',
                            ];
                            $color = $eventColors[$auditLog->event] ?? 'bg-gray-100 text-gray-800';
                        @endphp
                        <div class="mt-1">
                            <span class="badge {{ $color }} text-lg px-3 py-1">
                                {{ ucfirst($auditLog->event) }}
                            </span>
                        </div>
                    </div>

                    <!-- Date & Time -->
                    <div>
                        <label class="form-label">วันที่/เวลา</label>
                        <div class="mt-1 text-sm">
                            <div class="font-medium">{{ $auditLog->created_at->format('d/m/Y H:i:s') }}</div>
                            <div class="text-gray-500">{{ $auditLog->created_at->diffForHumans() }}</div>
                        </div>
                    </div>

                    <!-- User Information -->
                    <div>
                        <label class="form-label">ผู้ใช้</label>
                        <div class="mt-1">
                            @if($auditLog->user)
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                        <i class="fas fa-user text-blue-600"></i>
                                    </div>
                                    <div>
                                        <div class="font-medium">{{ $auditLog->user->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $auditLog->user->email }}</div>
                                    </div>
                                </div>
                            @else
                                <span class="text-gray-500">System</span>
                            @endif
                        </div>
                    </div>

                    <!-- Auditable Information -->
                    <div>
                        <label class="form-label">ประเภทข้อมูล</label>
                        <div class="mt-1">
                            <span class="text-sm font-medium">{{ class_basename($auditLog->auditable_type) }}</span>
                            <div class="text-xs text-gray-500">{{ $auditLog->auditable_type }}</div>
                        </div>
                    </div>

                    <!-- Auditable ID -->
                    <div>
                        <label class="form-label">รหัสข้อมูล</label>
                        <div class="mt-1">
                            <span class="text-sm font-mono bg-gray-100 px-2 py-1 rounded">{{ $auditLog->auditable_id }}</span>
                        </div>
                    </div>

                    <!-- IP Address -->
                    <div>
                        <label class="form-label">IP Address</label>
                        <div class="mt-1">
                            <span class="text-sm font-mono">{{ $auditLog->ip_address ?? '-' }}</span>
                        </div>
                    </div>
                </div>

                <!-- URL -->
                @if($auditLog->url)
                    <div class="mt-6">
                        <label class="form-label">URL</label>
                        <div class="mt-1">
                            <span class="text-sm text-blue-600 break-all">{{ $auditLog->url }}</span>
                        </div>
                    </div>
                @endif

                <!-- User Agent -->
                @if($auditLog->user_agent)
                    <div class="mt-6">
                        <label class="form-label">User Agent</label>
                        <div class="mt-1">
                            <span class="text-sm text-gray-600 break-all">{{ $auditLog->user_agent }}</span>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Changes Details -->
        @if($auditLog->old_values || $auditLog->new_values)
            <div class="card mb-6">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-exchange-alt mr-2"></i>
                        รายละเอียดการเปลี่ยนแปลง
                    </h3>
                </div>
                <div class="card-body">
                    @if($auditLog->event === 'created')
                        <!-- Created Event -->
                        <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                            <div class="flex items-center mb-3">
                                <i class="fas fa-plus text-green-600 mr-2"></i>
                                <h4 class="font-medium text-green-800">ข้อมูลที่สร้างใหม่</h4>
                            </div>
                            @if($auditLog->new_values)
                                <div class="bg-white rounded border p-4">
                                    <pre class="text-sm text-gray-700 whitespace-pre-wrap">{{ json_encode($auditLog->new_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                </div>
                            @endif
                        </div>
                    @elseif($auditLog->event === 'updated')
                        <!-- Updated Event -->
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            @if($auditLog->old_values)
                                <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                                    <div class="flex items-center mb-3">
                                        <i class="fas fa-minus text-red-600 mr-2"></i>
                                        <h4 class="font-medium text-red-800">ค่าเดิม</h4>
                                    </div>
                                    <div class="bg-white rounded border p-4">
                                        <pre class="text-sm text-gray-700 whitespace-pre-wrap">{{ json_encode($auditLog->old_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                    </div>
                                </div>
                            @endif

                            @if($auditLog->new_values)
                                <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                                    <div class="flex items-center mb-3">
                                        <i class="fas fa-plus text-green-600 mr-2"></i>
                                        <h4 class="font-medium text-green-800">ค่าใหม่</h4>
                                    </div>
                                    <div class="bg-white rounded border p-4">
                                        <pre class="text-sm text-gray-700 whitespace-pre-wrap">{{ json_encode($auditLog->new_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- Field-by-field comparison -->
                        @if($auditLog->old_values && $auditLog->new_values)
                            <div class="mt-6">
                                <h4 class="font-medium text-gray-900 mb-4">
                                    <i class="fas fa-list mr-2"></i>
                                    การเปรียบเทียบฟิลด์
                                </h4>
                                <div class="space-y-3">
                                    @php
                                        $allKeys = array_unique(array_merge(
                                            array_keys($auditLog->old_values ?? []),
                                            array_keys($auditLog->new_values ?? [])
                                        ));
                                    @endphp
                                    @foreach($allKeys as $key)
                                        @php
                                            $oldValue = $auditLog->old_values[$key] ?? null;
                                            $newValue = $auditLog->new_values[$key] ?? null;
                                            $hasChanged = $oldValue !== $newValue;
                                        @endphp
                                        <div class="border rounded-lg p-4 {{ $hasChanged ? 'border-yellow-200 bg-yellow-50' : 'border-gray-200 bg-gray-50' }}">
                                            <div class="flex items-center justify-between mb-2">
                                                <span class="font-medium text-sm">{{ $key }}</span>
                                                @if($hasChanged)
                                                    <span class="badge badge-warning text-xs">เปลี่ยนแปลง</span>
                                                @else
                                                    <span class="badge badge-secondary text-xs">ไม่เปลี่ยนแปลง</span>
                                                @endif
                                            </div>
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                                                <div>
                                                    <span class="text-gray-600">เดิม:</span>
                                                    <div class="mt-1 p-2 bg-white rounded border">
                                                        {{ $oldValue ? json_encode($oldValue, JSON_UNESCAPED_UNICODE) : 'null' }}
                                                    </div>
                                                </div>
                                                <div>
                                                    <span class="text-gray-600">ใหม่:</span>
                                                    <div class="mt-1 p-2 bg-white rounded border">
                                                        {{ $newValue ? json_encode($newValue, JSON_UNESCAPED_UNICODE) : 'null' }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    @elseif($auditLog->event === 'deleted')
                        <!-- Deleted Event -->
                        <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                            <div class="flex items-center mb-3">
                                <i class="fas fa-trash text-red-600 mr-2"></i>
                                <h4 class="font-medium text-red-800">ข้อมูลที่ถูกลบ</h4>
                            </div>
                            @if($auditLog->old_values)
                                <div class="bg-white rounded border p-4">
                                    <pre class="text-sm text-gray-700 whitespace-pre-wrap">{{ json_encode($auditLog->old_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                </div>
                            @endif
                        </div>
                    @else
                        <!-- Other Events -->
                        <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                            <div class="flex items-center mb-3">
                                <i class="fas fa-info-circle text-gray-600 mr-2"></i>
                                <h4 class="font-medium text-gray-800">ข้อมูลเหตุการณ์</h4>
                            </div>
                            @if($auditLog->new_values)
                                <div class="bg-white rounded border p-4">
                                    <pre class="text-sm text-gray-700 whitespace-pre-wrap">{{ json_encode($auditLog->new_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        @endif

        <!-- Related Auditable Object -->
        @if($auditLog->auditable)
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-link mr-2"></i>
                        ข้อมูลที่เกี่ยวข้อง
                    </h3>
                </div>
                <div class="card-body">
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <div class="flex items-center mb-3">
                            <i class="fas fa-database text-blue-600 mr-2"></i>
                            <h4 class="font-medium text-blue-800">{{ class_basename($auditLog->auditable_type) }}</h4>
                        </div>
                        <div class="text-sm text-gray-700">
                            <p><strong>ID:</strong> {{ $auditLog->auditable_id }}</p>
                            @if(method_exists($auditLog->auditable, 'toArray'))
                                <div class="mt-2">
                                    <strong>ข้อมูลปัจจุบัน:</strong>
                                    <div class="mt-1 bg-white rounded border p-3">
                                        <pre class="text-xs text-gray-600 whitespace-pre-wrap">{{ json_encode($auditLog->auditable->toArray(), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </main>
</div>
@endsection
