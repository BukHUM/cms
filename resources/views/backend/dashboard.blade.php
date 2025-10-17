@extends('backend.layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')
@section('page-description', 'ภาพรวมระบบ CMS')

@section('content')
<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                <i class="fas fa-users text-xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">ผู้ใช้งานทั้งหมด</p>
                <p class="text-2xl font-semibold text-gray-900" id="total-users">-</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-green-100 text-green-600">
                <i class="fas fa-user-check text-xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">ผู้ใช้งานที่ใช้งานอยู่</p>
                <p class="text-2xl font-semibold text-gray-900" id="active-users">-</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                <i class="fas fa-user-tag text-xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">บทบาททั้งหมด</p>
                <p class="text-2xl font-semibold text-gray-900" id="total-roles">-</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                <i class="fas fa-key text-xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">สิทธิ์ทั้งหมด</p>
                <p class="text-2xl font-semibold text-gray-900" id="total-permissions">-</p>
            </div>
        </div>
    </div>
</div>

<!-- Recent Activities -->
<div class="bg-white rounded-lg shadow">
    <div class="px-6 py-4 border-b border-gray-200">
        <h3 class="text-lg font-medium text-gray-900">
            <i class="fas fa-history mr-2"></i>
            กิจกรรมล่าสุด
        </h3>
    </div>
    <div class="p-6">
        <div id="recent-activities" class="space-y-4">
            <!-- Activities will be loaded here -->
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Load dashboard data
    async function loadDashboardData() {
        try {
            const response = await fetch('/api/dashboard');
            const data = await response.json();
            
            // Update stats
            document.getElementById('total-users').textContent = data.stats.total_users;
            document.getElementById('active-users').textContent = data.stats.active_users;
            document.getElementById('total-roles').textContent = data.stats.total_roles;
            document.getElementById('total-permissions').textContent = data.stats.total_permissions;
            
            // Update recent activities
            const activitiesContainer = document.getElementById('recent-activities');
            activitiesContainer.innerHTML = '';
            
            data.recent_activities.forEach(activity => {
                const activityElement = document.createElement('div');
                activityElement.className = 'flex items-center space-x-3 p-3 bg-gray-50 rounded-lg';
                activityElement.innerHTML = `
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-${getActivityIcon(activity.event)} text-blue-600 text-sm"></i>
                        </div>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900">
                            ${activity.event} ${activity.auditable_type}
                        </p>
                        <p class="text-sm text-gray-500">
                            ${new Date(activity.created_at).toLocaleString('th-TH')}
                        </p>
                    </div>
                `;
                activitiesContainer.appendChild(activityElement);
            });
            
        } catch (error) {
            console.error('Error loading dashboard data:', error);
            Swal.fire({
                icon: 'error',
                title: 'เกิดข้อผิดพลาด',
                text: 'ไม่สามารถโหลดข้อมูล Dashboard ได้'
            });
        }
    }

    function getActivityIcon(event) {
        const icons = {
            'created': 'plus',
            'updated': 'edit',
            'deleted': 'trash',
            'login': 'sign-in-alt',
            'logout': 'sign-out-alt'
        };
        return icons[event] || 'info';
    }

    // Load data when page loads
    document.addEventListener('DOMContentLoaded', loadDashboardData);
</script>
@endpush
