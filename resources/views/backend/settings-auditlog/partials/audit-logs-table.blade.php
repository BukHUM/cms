@forelse($auditLogs as $log)
    <tr class="table-row">
        <td class="table-cell">
            <div class="text-sm">
                <div class="font-medium">{{ $log->created_at->format('d/m/Y') }}</div>
                <div class="text-gray-500 dark:text-gray-400">{{ $log->created_at->format('H:i:s') }}</div>
            </div>
        </td>
        <td class="table-cell">
            @if($log->user)
                <div class="flex items-center">
                    <div class="w-8 h-8 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center mr-3">
                        <i class="fas fa-user text-blue-600 dark:text-blue-400 text-sm"></i>
                    </div>
                    <div>
                        <div class="font-medium text-sm">{{ $log->user->name }}</div>
                        <div class="text-xs text-gray-500 dark:text-gray-400">{{ $log->user->email }}</div>
                    </div>
                </div>
            @else
                <span class="text-gray-500 dark:text-gray-400 text-sm">System</span>
            @endif
        </td>
        <td class="table-cell">
            @php
                $eventColors = [
                    'created' => 'bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200',
                    'updated' => 'bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200',
                    'deleted' => 'bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200',
                    'restored' => 'bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200',
                ];
                $color = $eventColors[$log->event] ?? 'bg-gray-100 dark:bg-gray-600 text-gray-800 dark:text-gray-200';
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
            <div class="text-gray-500 dark:text-gray-400">
                <i class="fas fa-inbox text-4xl mb-4"></i>
                <p class="text-lg font-medium">ไม่พบข้อมูล Audit Log</p>
                <p class="text-sm">ยังไม่มีการเปลี่ยนแปลงในระบบ</p>
            </div>
        </td>
    </tr>
@endforelse
