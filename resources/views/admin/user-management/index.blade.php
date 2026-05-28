@extends('layouts.admin')

@section('title', 'จัดการผู้ใช้')
@section('subtitle', 'จัดการข้อมูลผู้ใช้ บทบาท และสิทธิ์การเข้าถึงระบบ')

@push('head')
<meta name="user-id" content="{{ auth()->id() }}">
@endpush

@section('content')
<!-- User Management Navigation -->
<div class="bg-white rounded-2xl shadow-lg border border-slate-200 mb-8">
    <div class="border-b border-slate-200">
        <nav class="flex space-x-8 px-6" aria-label="Tabs">
            <button class="tab-button active py-4 px-1 border-b-2 border-primary font-medium text-sm text-primary" data-tab="users">
                <i class="fas fa-users mr-2"></i>
                ผู้ใช้
            </button>
            <button class="tab-button py-4 px-1 border-b-2 border-transparent font-medium text-sm text-slate-500 hover:text-slate-700 hover:border-slate-300" data-tab="roles">
                <i class="fas fa-user-tag mr-2"></i>
                บทบาท
            </button>
            <button class="tab-button py-4 px-1 border-b-2 border-transparent font-medium text-sm text-slate-500 hover:text-slate-700 hover:border-slate-300" data-tab="permissions">
                <i class="fas fa-key mr-2"></i>
                สิทธิ์
            </button>
        </nav>
    </div>
</div>

<!-- Tab Content -->
<div class="tab-content">
    <!-- Users Tab -->
    <div id="users-tab" class="tab-pane active">
        <!-- Users Header -->
        <div class="bg-white rounded-2xl shadow-lg border border-slate-200 mb-6">
            <div class="px-6 py-4 border-b border-slate-200">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h3 class="text-lg font-semibold text-slate-800">รายการผู้ใช้</h3>
                        <p class="text-sm text-slate-600">จัดการข้อมูลผู้ใช้ในระบบ</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <!-- Search -->
                        <div class="relative">
                            <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                            <input type="text" id="userSearch" placeholder="ค้นหาผู้ใช้..." class="pl-10 pr-4 py-2 border border-slate-200 rounded-lg bg-slate-50 text-slate-700 text-sm focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 transition-smooth w-64">
                        </div>
                        <!-- Add User Button -->
                        <button id="addUserBtn" class="bg-gradient-primary text-white px-4 py-2 rounded-lg font-medium hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300">
                            <i class="fas fa-plus mr-2"></i>
                            เพิ่มผู้ใช้
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Users Table -->
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">ผู้ใช้</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">บทบาท</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">สถานะ</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">อัพเดทล่าสุด</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-slate-500 uppercase tracking-wider">การดำเนินการ</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-slate-200">
                        <!-- Sample User Row -->
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-gradient-primary rounded-lg flex items-center justify-center text-white font-semibold mr-4">
                                        JD
                                    </div>
                                    <div>
                                        <div class="text-sm font-medium text-slate-900">John Doe</div>
                                        <div class="text-sm text-slate-500">john@example.com</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    Administrator
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <div class="w-1.5 h-1.5 bg-green-400 rounded-full mr-1.5"></div>
                                    Active
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">
                                2 ชั่วโมงที่แล้ว
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end gap-2">
                                    <button class="text-primary hover:text-primary-dark transition-colors" title="แก้ไข">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="text-slate-600 hover:text-slate-800 transition-colors" title="ดูรายละเอียด">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="text-red-600 hover:text-red-800 transition-colors" title="ลบ">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        
                        <!-- More sample rows... -->
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-gradient-success rounded-lg flex items-center justify-center text-white font-semibold mr-4">
                                        JS
                                    </div>
                                    <div>
                                        <div class="text-sm font-medium text-slate-900">Jane Smith</div>
                                        <div class="text-sm text-slate-500">jane@example.com</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    Moderator
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <div class="w-1.5 h-1.5 bg-green-400 rounded-full mr-1.5"></div>
                                    Active
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">
                                1 วันที่แล้ว
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end gap-2">
                                    <button class="text-primary hover:text-primary-dark transition-colors" title="แก้ไข">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="text-slate-600 hover:text-slate-800 transition-colors" title="ดูรายละเอียด">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="text-red-600 hover:text-red-800 transition-colors" title="ลบ">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-slate-200">
                <div class="flex items-center justify-between">
                    <div class="text-sm text-slate-700">
                        แสดง <span class="font-medium">1</span> ถึง <span class="font-medium">10</span> จาก <span class="font-medium">97</span> รายการ
                    </div>
                    <div class="flex items-center gap-2">
                        <button class="px-3 py-2 text-sm font-medium text-slate-500 bg-white border border-slate-300 rounded-lg hover:bg-slate-50 disabled:opacity-50 disabled:cursor-not-allowed" disabled>
                            ก่อนหน้า
                        </button>
                        <button class="px-3 py-2 text-sm font-medium text-white bg-primary border border-primary rounded-lg hover:bg-primary-dark">
                            1
                        </button>
                        <button class="px-3 py-2 text-sm font-medium text-slate-500 bg-white border border-slate-300 rounded-lg hover:bg-slate-50">
                            2
                        </button>
                        <button class="px-3 py-2 text-sm font-medium text-slate-500 bg-white border border-slate-300 rounded-lg hover:bg-slate-50">
                            3
                        </button>
                        <button class="px-3 py-2 text-sm font-medium text-slate-500 bg-white border border-slate-300 rounded-lg hover:bg-slate-50">
                            ถัดไป
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Roles Tab -->
    <div id="roles-tab" class="tab-pane hidden">
        <!-- Roles Header -->
        <div class="bg-white rounded-2xl shadow-lg border border-slate-200 mb-6">
            <div class="px-6 py-4 border-b border-slate-200">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h3 class="text-lg font-semibold text-slate-800">จัดการบทบาท</h3>
                        <p class="text-sm text-slate-600">กำหนดบทบาทและสิทธิ์การเข้าถึง</p>
                    </div>
                    <button class="bg-gradient-primary text-white px-4 py-2 rounded-lg font-medium hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300">
                        <i class="fas fa-plus mr-2"></i>
                        เพิ่มบทบาท
                    </button>
                </div>
            </div>
            
            <!-- Roles Grid -->
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <!-- Admin Role -->
                    <div class="bg-gradient-to-br from-red-50 to-red-100 border border-red-200 rounded-xl p-6 hover:shadow-lg transition-all duration-300">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 bg-gradient-danger rounded-xl flex items-center justify-center text-white">
                                <i class="fas fa-crown text-xl"></i>
                            </div>
                            <span class="text-xs font-semibold text-red-600 bg-red-100 px-2 py-1 rounded-full">4 ผู้ใช้</span>
                        </div>
                        <h4 class="text-lg font-semibold text-red-800 mb-2">Administrator</h4>
                        <p class="text-sm text-red-600 mb-4">สิทธิ์สูงสุดในการจัดการระบบ</p>
                        <div class="flex items-center gap-2">
                            <button class="text-red-600 hover:text-red-800 transition-colors" title="แก้ไข">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="text-red-600 hover:text-red-800 transition-colors" title="ดูรายละเอียด">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>
                    
                    <!-- Moderator Role -->
                    <div class="bg-gradient-to-br from-green-50 to-green-100 border border-green-200 rounded-xl p-6 hover:shadow-lg transition-all duration-300">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 bg-gradient-success rounded-xl flex items-center justify-center text-white">
                                <i class="fas fa-user-shield text-xl"></i>
                            </div>
                            <span class="text-xs font-semibold text-green-600 bg-green-100 px-2 py-1 rounded-full">12 ผู้ใช้</span>
                        </div>
                        <h4 class="text-lg font-semibold text-green-800 mb-2">Moderator</h4>
                        <p class="text-sm text-green-600 mb-4">จัดการเนื้อหาและผู้ใช้ทั่วไป</p>
                        <div class="flex items-center gap-2">
                            <button class="text-green-600 hover:text-green-800 transition-colors" title="แก้ไข">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="text-green-600 hover:text-green-800 transition-colors" title="ดูรายละเอียด">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>
                    
                    <!-- User Role -->
                    <div class="bg-gradient-to-br from-blue-50 to-blue-100 border border-blue-200 rounded-xl p-6 hover:shadow-lg transition-all duration-300">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 bg-gradient-info rounded-xl flex items-center justify-center text-white">
                                <i class="fas fa-user text-xl"></i>
                            </div>
                            <span class="text-xs font-semibold text-blue-600 bg-blue-100 px-2 py-1 rounded-full">81 ผู้ใช้</span>
                        </div>
                        <h4 class="text-lg font-semibold text-blue-800 mb-2">User</h4>
                        <p class="text-sm text-blue-600 mb-4">ผู้ใช้ทั่วไปในระบบ</p>
                        <div class="flex items-center gap-2">
                            <button class="text-blue-600 hover:text-blue-800 transition-colors" title="แก้ไข">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="text-blue-600 hover:text-blue-800 transition-colors" title="ดูรายละเอียด">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Permissions Tab -->
    <div id="permissions-tab" class="tab-pane hidden">
        <!-- Permissions Header -->
        <div class="bg-white rounded-2xl shadow-lg border border-slate-200 mb-6">
            <div class="px-6 py-4 border-b border-slate-200">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h3 class="text-lg font-semibold text-slate-800">จัดการสิทธิ์</h3>
                        <p class="text-sm text-slate-600">กำหนดสิทธิ์การเข้าถึงฟีเจอร์ต่างๆ</p>
                    </div>
                    <button class="bg-gradient-primary text-white px-4 py-2 rounded-lg font-medium hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300">
                        <i class="fas fa-plus mr-2"></i>
                        เพิ่มสิทธิ์
                    </button>
                </div>
            </div>
            
            <!-- Permissions List -->
            <div class="p-6">
                <div class="space-y-4">
                    <!-- User Management Permission -->
                    <div class="flex items-center justify-between p-4 bg-slate-50 rounded-xl border border-slate-200">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 bg-gradient-primary rounded-lg flex items-center justify-center text-white">
                                <i class="fas fa-users"></i>
                            </div>
                            <div>
                                <h4 class="font-semibold text-slate-800">จัดการผู้ใช้</h4>
                                <p class="text-sm text-slate-600">user.manage</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-xs font-semibold text-green-600 bg-green-100 px-2 py-1 rounded-full">3 บทบาท</span>
                            <button class="text-slate-600 hover:text-slate-800 transition-colors" title="แก้ไข">
                                <i class="fas fa-edit"></i>
                            </button>
                        </div>
                    </div>
                    
                    <!-- Settings Permission -->
                    <div class="flex items-center justify-between p-4 bg-slate-50 rounded-xl border border-slate-200">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 bg-gradient-info rounded-lg flex items-center justify-center text-white">
                                <i class="fas fa-cog"></i>
                            </div>
                            <div>
                                <h4 class="font-semibold text-slate-800">ตั้งค่าระบบ</h4>
                                <p class="text-sm text-slate-600">system.settings</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-xs font-semibold text-green-600 bg-green-100 px-2 py-1 rounded-full">1 บทบาท</span>
                            <button class="text-slate-600 hover:text-slate-800 transition-colors" title="แก้ไข">
                                <i class="fas fa-edit"></i>
                            </button>
                        </div>
                    </div>
                    
                    <!-- Reports Permission -->
                    <div class="flex items-center justify-between p-4 bg-slate-50 rounded-xl border border-slate-200">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 bg-gradient-success rounded-lg flex items-center justify-center text-white">
                                <i class="fas fa-chart-bar"></i>
                            </div>
                            <div>
                                <h4 class="font-semibold text-slate-800">ดูรายงาน</h4>
                                <p class="text-sm text-slate-600">reports.view</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-xs font-semibold text-green-600 bg-green-100 px-2 py-1 rounded-full">2 บทบาท</span>
                            <button class="text-slate-600 hover:text-slate-800 transition-colors" title="แก้ไข">
                                <i class="fas fa-edit"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Tab switching functionality
    const tabButtons = document.querySelectorAll('.tab-button');
    const tabPanes = document.querySelectorAll('.tab-pane');
    
    tabButtons.forEach(button => {
        button.addEventListener('click', function() {
            const targetTab = this.getAttribute('data-tab');
            
            // Remove active class from all buttons and panes
            tabButtons.forEach(btn => {
                btn.classList.remove('active', 'border-primary', 'text-primary');
                btn.classList.add('border-transparent', 'text-slate-500');
            });
            
            tabPanes.forEach(pane => {
                pane.classList.add('hidden');
                pane.classList.remove('active');
            });
            
            // Add active class to clicked button and corresponding pane
            this.classList.add('active', 'border-primary', 'text-primary');
            this.classList.remove('border-transparent', 'text-slate-500');
            
            const targetPane = document.getElementById(targetTab + '-tab');
            if (targetPane) {
                targetPane.classList.remove('hidden');
                targetPane.classList.add('active');
            }
            
            // Save active tab to localStorage
            localStorage.setItem('activeUserManagementTab', targetTab);
        });
    });
    
    // Load last active tab
    const lastActiveTab = localStorage.getItem('activeUserManagementTab');
    if (lastActiveTab) {
        const button = document.querySelector(`[data-tab="${lastActiveTab}"]`);
        if (button) {
            button.click();
        }
    }
    
    // Search functionality
    const searchInput = document.getElementById('userSearch');
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            // Implement search logic here
            console.log('Searching for:', searchTerm);
        });
    }
    
    // Add user button
    const addUserBtn = document.getElementById('addUserBtn');
    if (addUserBtn) {
        addUserBtn.addEventListener('click', function() {
            // Implement add user modal
            console.log('Add user clicked');
        });
    }
});
</script>
@endpush