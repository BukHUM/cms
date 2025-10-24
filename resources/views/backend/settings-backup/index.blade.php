@extends('backend.layouts.app')

@section('title', 'การตั้งค่าสำรองข้อมูล')
@section('page-title', 'การตั้งค่าสำรองข้อมูล')
@section('page-description', 'จัดการการตั้งค่าสำรองข้อมูลและกู้คืนระบบ')

@section('content')
<div class="main-content-area">

    <!-- Settings Section -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow mb-6">
        <div class="p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                        <i class="fas fa-cog mr-3 text-orange-600"></i>
                        การตั้งค่าการสำรองข้อมูล
                    </h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">กำหนดค่าการสำรองข้อมูลอัตโนมัติและการจัดเก็บ</p>
                </div>
                <div class="flex items-center space-x-2">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                        <i class="fas fa-check-circle mr-1"></i>
                        เปิดใช้งาน
                    </span>
                </div>
            </div>
            
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Database Settings -->
                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6">
                    <div class="flex items-center mb-4">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center">
                                <i class="fas fa-database text-blue-600 text-lg"></i>
                            </div>
                        </div>
                        <div class="ml-3">
                            <h4 class="text-lg font-semibold text-gray-900 dark:text-white">การตั้งค่าฐานข้อมูล</h4>
                            <p class="text-sm text-gray-600 dark:text-gray-400">กำหนดการสำรองฐานข้อมูล</p>
                        </div>
                    </div>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
                                <i class="fas fa-check-square mr-1"></i>
                                เลือกประเภทข้อมูลที่จะสำรอง
                            </label>
                            <div class="space-y-3">
                                <div class="bg-white dark:bg-gray-800 rounded-lg p-4 border border-gray-200 dark:border-gray-600">
                                    <div class="flex items-center justify-between mb-2">
                                        <label class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                            <i class="fas fa-database mr-2 text-blue-500"></i>
                                            สำรองฐานข้อมูล
                                        </label>
                                        <label class="relative inline-flex items-center cursor-pointer">
                                            <input type="checkbox" class="sr-only peer" id="backup_database_enabled" 
                                                   {{ ($settings['database']->where('key', 'backup_database_enabled')->first()->value ?? '0') == '1' ? 'checked' : '' }}>
                                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>
                                        </label>
                                    </div>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">รวมฐานข้อมูลทั้งหมด (MySQL, PostgreSQL, SQLite)</p>
                                </div>

                                <div class="bg-white dark:bg-gray-800 rounded-lg p-4 border border-gray-200 dark:border-gray-600">
                                    <div class="flex items-center justify-between mb-2">
                                        <label class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                            <i class="fas fa-folder mr-2 text-green-500"></i>
                                            สำรองไฟล์ระบบ
                                        </label>
                                        <label class="relative inline-flex items-center cursor-pointer">
                                            <input type="checkbox" class="sr-only peer" id="backup_include_files" 
                                                   {{ ($settings['files']->where('key', 'backup_include_files')->first()->value ?? '0') == '1' ? 'checked' : '' }}>
                                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-green-300 dark:peer-focus:ring-green-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-green-600"></div>
                                        </label>
                                    </div>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">รวมไฟล์ config, storage, migrations</p>
                                </div>

                            </div>
                        </div>
                        
                    </div>
                </div>

                <!-- Schedule Settings -->
                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6">
                    <div class="flex items-center mb-4">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 bg-green-100 dark:bg-green-900 rounded-lg flex items-center justify-center">
                                <i class="fas fa-clock text-green-600 text-lg"></i>
                            </div>
                        </div>
                        <div class="ml-3">
                            <h4 class="text-lg font-semibold text-gray-900 dark:text-white">การสำรองอัตโนมัติ</h4>
                            <p class="text-sm text-gray-600 dark:text-gray-400">กำหนดเวลาสำรองอัตโนมัติ</p>
                        </div>
                    </div>
                    
                    <div class="space-y-4">
                        <div class="bg-white dark:bg-gray-800 rounded-lg p-4 border border-gray-200 dark:border-gray-600">
                            <div class="flex items-center justify-between mb-2">
                                <label class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                    เปิดใช้งานการสำรองอัตโนมัติ
                                </label>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" class="sr-only peer" id="backup_auto_enabled"
                                           {{ ($settings['schedule']->where('key', 'backup_auto_enabled')->first()->value ?? '0') == '1' ? 'checked' : '' }}>
                                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-green-300 dark:peer-focus:ring-green-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-green-600"></div>
                                </label>
                            </div>
                            <p class="text-xs text-gray-500 dark:text-gray-400">ระบบจะสำรองข้อมูลตามเวลาที่กำหนด</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <i class="fas fa-calendar-alt mr-1"></i>
                                ช่วงเวลาสำรองอัตโนมัติ
                            </label>
                            <select class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 bg-white dark:bg-gray-800 text-gray-900 dark:text-white" id="backup_schedule_frequency">
                                @php
                                    $scheduleSetting = $settings['schedule']->where('key', 'backup_schedule_frequency')->first();
                                    $scheduleValue = $scheduleSetting->value ?? 'disabled';
                                @endphp
                                <option value="daily" {{ $scheduleValue == 'daily' ? 'selected' : '' }}>ทุกวัน เวลา 02:00</option>
                                <option value="weekly" {{ $scheduleValue == 'weekly' ? 'selected' : '' }}>ทุกสัปดาห์ วันอาทิตย์</option>
                                <option value="monthly" {{ $scheduleValue == 'monthly' ? 'selected' : '' }}>ทุกเดือน วันที่ 1</option>
                                <option value="disabled" {{ $scheduleValue == 'disabled' ? 'selected' : '' }}>ปิดใช้งาน</option>
                            </select>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">เลือกช่วงเวลาสำรองข้อมูลอัตโนมัติ</p>
                        </div>
                    </div>
                </div>

                <!-- Storage Settings -->
                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6">
                    <div class="flex items-center mb-4">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 bg-purple-100 dark:bg-purple-900 rounded-lg flex items-center justify-center">
                                <i class="fas fa-hdd text-purple-600 text-lg"></i>
                            </div>
                        </div>
                        <div class="ml-3">
                            <h4 class="text-lg font-semibold text-gray-900 dark:text-white">การจัดเก็บ</h4>
                            <p class="text-sm text-gray-600 dark:text-gray-400">กำหนดการจัดเก็บไฟล์สำรอง</p>
                        </div>
                    </div>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <i class="fas fa-hashtag mr-1"></i>
                                จำนวนไฟล์สำรองสูงสุด
                            </label>
                            <div class="relative">
                                @php
                                    $maxFilesSetting = $settings['storage']->where('key', 'backup_max_files')->first();
                                    $maxFilesValue = $maxFilesSetting->value ?? '10';
                                @endphp
                                <input type="number" class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 bg-white dark:bg-gray-800 text-gray-900 dark:text-white" 
                                       id="backup_max_files" value="{{ $maxFilesValue }}" min="1" max="100">
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 dark:text-gray-400 text-sm">ไฟล์</span>
                                </div>
                            </div>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">จำนวนไฟล์สำรองที่เก็บไว้ (ไฟล์เก่าจะถูกลบอัตโนมัติ)</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <i class="fas fa-weight-hanging mr-1"></i>
                                ขนาดไฟล์สำรองสูงสุด
                            </label>
                            <div class="relative">
                                @php
                                    $maxSizeSetting = $settings['storage']->where('key', 'backup_max_size_mb')->first();
                                    $maxSizeValue = $maxSizeSetting->value ?? '100';
@endphp
                                <input type="number" class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 bg-white dark:bg-gray-800 text-gray-900 dark:text-white" 
                                       id="backup_max_size_mb" value="{{ $maxSizeValue }}" min="1" max="10000">
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 dark:text-gray-400 text-sm">MB</span>
                                </div>
                            </div>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">ขนาดไฟล์สำรองสูงสุดต่อไฟล์</p>
                        </div>
                        
                    </div>
                </div>
            </div>
            
            <!-- Action Buttons -->
            <div class="mt-8 pt-6 border-t border-gray-200 dark:border-gray-600">
                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center space-y-3 sm:space-y-0">
                    <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                        <i class="fas fa-info-circle mr-2 text-blue-500"></i>
                        การตั้งค่าจะมีผลทันทีหลังจากบันทึก
                    </div>
                    <div class="flex space-x-3">
                        <button id="create-backup-btn" class="bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-6 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-colors duration-200">
                            <i class="fas fa-database mr-2"></i>
                            สำรองข้อมูล
                        </button>
                        <button type="button" onclick="saveSettings()" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-6 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors duration-200">
                            <i class="fas fa-save mr-2"></i>
                            บันทึกการตั้งค่า
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Backup List Section -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
        <div class="p-6">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                    <i class="fas fa-list mr-2 text-purple-600"></i>
                    รายการไฟล์สำรองข้อมูล
                </h3>
                <button id="refresh-backups" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors duration-200">
                    <i class="fas fa-sync-alt"></i>
                </button>
            </div>
            <div id="backup-list" class="space-y-4">
                <!-- Backup items will be loaded here -->
                <div class="text-center py-8">
                    <div class="mx-auto w-12 h-12 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center mb-4">
                        <i class="fas fa-spinner fa-spin text-blue-600 text-xl"></i>
                    </div>
                    <p class="text-gray-600 dark:text-gray-400">กำลังโหลดข้อมูล...</p>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    // Initialize page
    document.addEventListener('DOMContentLoaded', function() {
        loadBackupList();
    });

    // Create backup button
    document.getElementById('create-backup-btn').addEventListener('click', function() {
        createBackup();
    });

    // Create backup
    function createBackup() {
        // Get settings from the settings section
        const databaseEnabled = document.getElementById('backup_database_enabled').checked;
        const includeFiles = document.getElementById('backup_include_files').checked;
        
        const formData = new FormData();
        formData.append('backup_name', 'backup_' + new Date().toISOString().slice(0,19).replace(/:/g, '-'));
        formData.append('include_files', includeFiles);
        formData.append('include_database', databaseEnabled);
        formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
        
        // Show loading state
        Swal.fire({
            title: 'กำลังสร้างสำรองข้อมูล...',
            text: 'กรุณารอสักครู่',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
        
        fetch('{{ route("backend.settings-backup.create-backup") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'สำเร็จ!',
                        text: 'สร้างสำรองข้อมูลเรียบร้อยแล้ว',
                        timer: 2000,
                        timerProgressBar: true,
                        showConfirmButton: false
                    });
                    loadBackupList();
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'เกิดข้อผิดพลาด!',
                        text: data.message || 'เกิดข้อผิดพลาดในการสร้างสำรองข้อมูล',
                        confirmButtonText: 'ตกลง'
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'เกิดข้อผิดพลาด!',
                    text: 'เกิดข้อผิดพลาดในการสร้างสำรองข้อมูล',
                    confirmButtonText: 'ตกลง'
                });
            });
    }

    // Load backup list
    function loadBackupList() {
        const refreshBtn = document.getElementById('refresh-backups');
        const originalIcon = refreshBtn.innerHTML;
        
        // Show loading state
        refreshBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
        refreshBtn.disabled = true;
        
        // Show loading in container
        const container = document.getElementById('backup-list');
        container.innerHTML = `
            <div class="text-center py-8">
                <div class="mx-auto w-12 h-12 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center mb-4">
                    <i class="fas fa-spinner fa-spin text-blue-600 text-xl"></i>
                </div>
                <p class="text-gray-600 dark:text-gray-400">กำลังโหลดข้อมูล...</p>
            </div>
        `;
        fetch('{{ route("backend.settings-backup.get-backups") }}', {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    displayBackupList(data.backups);
                } else {
                    showError('ไม่สามารถโหลดรายการสำรองข้อมูลได้');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showError('เกิดข้อผิดพลาดในการโหลดข้อมูล');
            })
            .finally(() => {
                // Restore button state
                refreshBtn.innerHTML = originalIcon;
                refreshBtn.disabled = false;
            });
    }

    // Display backup list
    function displayBackupList(backups) {
        const container = document.getElementById('backup-list');
        
        if (backups.length === 0) {
            container.innerHTML = `
                <div class="text-center py-12">
                    <div class="mx-auto w-16 h-16 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mb-4">
                        <i class="fas fa-database text-gray-400 text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">ยังไม่มีไฟล์สำรองข้อมูล</h3>
                    <p class="text-gray-600 dark:text-gray-400">ใช้ปุ่ม "สำรองข้อมูล" ข้างบนเพื่อสร้างไฟล์สำรองข้อมูลแรก</p>
                </div>
            `;
            return;
        }

        container.innerHTML = backups.map(backup => {
            // Determine backup type
            let backupType = '';
            let typeIcon = '';
            let typeColor = '';
            
            if (backup.include_database && backup.include_files) {
                backupType = 'ไฟล์ + ฐานข้อมูล';
                typeIcon = 'fas fa-database';
                typeColor = 'text-purple-600';
            } else if (backup.include_database) {
                backupType = 'ฐานข้อมูล';
                typeIcon = 'fas fa-database';
                typeColor = 'text-blue-600';
            } else if (backup.include_files) {
                backupType = 'ไฟล์ระบบ';
                typeIcon = 'fas fa-folder';
                typeColor = 'text-green-600';
            } else {
                backupType = 'ไม่ระบุ';
                typeIcon = 'fas fa-question';
                typeColor = 'text-gray-600';
            }
            
            return `
            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 border border-gray-200 dark:border-gray-600 hover:shadow-md transition-shadow duration-200">
                <div class="flex justify-between items-start">
                    <div class="flex-1">
                        <h4 class="font-semibold text-gray-900 dark:text-white mb-2">${backup.name}</h4>
                        <div class="flex items-center space-x-4 text-sm text-gray-600 dark:text-gray-400">
                            <span class="flex items-center"><i class="fas fa-calendar mr-1 text-blue-500"></i>${formatDate(backup.created_at)}</span>
                            <span class="flex items-center"><i class="fas fa-hdd mr-1 text-green-500"></i>${backup.size}</span>
                            <span class="flex items-center"><i class="${typeIcon} mr-1 ${typeColor}"></i>${backupType}</span>
                        </div>
                    </div>
                    <div class="flex space-x-2">
                        <button onclick="downloadBackup('${backup.name}')" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded-md text-sm transition-colors duration-200">
                            <i class="fas fa-download mr-1"></i>ดาวน์โหลด
                        </button>
                        <button onclick="deleteBackup('${backup.name}')" class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded-md text-sm transition-colors duration-200">
                            <i class="fas fa-trash mr-1"></i>ลบ
                        </button>
                    </div>
                </div>
            </div>
        `;
        }).join('');
    }

    // Download backup
    function downloadBackup(backupName) {
        window.open('{{ route("backend.settings-backup.download", ":name") }}'.replace(':name', backupName), '_blank');
    }

    // Delete backup
    function deleteBackup(backupName) {
        Swal.fire({
            title: 'คุณแน่ใจหรือไม่?',
            text: `คุณต้องการลบไฟล์สำรองข้อมูล "${backupName}" หรือไม่?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'ใช่, ลบเลย!',
            cancelButtonText: 'ยกเลิก'
        }).then((result) => {
            if (result.isConfirmed) {
                // Show loading
                Swal.fire({
                    title: 'กำลังลบ...',
                    text: 'กรุณารอสักครู่',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                fetch('{{ route("backend.settings-backup.delete-backup", ":name") }}'.replace(':name', backupName), {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'ลบเรียบร้อย!',
                                text: 'ไฟล์สำรองข้อมูลถูกลบเรียบร้อยแล้ว',
                                timer: 2000,
                                timerProgressBar: true,
                                showConfirmButton: false
                            });
                            loadBackupList();
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'เกิดข้อผิดพลาด!',
                                text: data.message || 'เกิดข้อผิดพลาดในการลบไฟล์สำรองข้อมูล',
                                confirmButtonText: 'ตกลง'
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'เกิดข้อผิดพลาด!',
                            text: 'เกิดข้อผิดพลาดในการลบไฟล์สำรองข้อมูล',
                            confirmButtonText: 'ตกลง'
                        });
                    });
            }
        });
    }

    // Format bytes
    function formatBytes(bytes, decimals = 2) {
        if (bytes === 0) return '0 Bytes';
        
        const k = 1024;
        const dm = decimals < 0 ? 0 : decimals;
        const sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];
        
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        
        return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + ' ' + sizes[i];
    }

    // Format date
    function formatDate(dateString) {
        const date = new Date(dateString);
        return date.toLocaleDateString('th-TH', {
            year: 'numeric',
            month: 'short',
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        });
    }

    // Show success message
    function showSuccess(message) {
        // You can implement a toast notification here
        alert(message);
    }

    // Show error message
    function showError(message) {
        // You can implement a toast notification here
        alert(message);
    }

    // Refresh buttons
    document.getElementById('refresh-backups').addEventListener('click', loadBackupList);

    // Save settings functionality
    function saveSettings() {
        const settings = [];
        
        // Database settings
        const databaseEnabled = document.getElementById('backup_database_enabled').checked ? '1' : '0';
        
        settings.push(
            { key: 'backup_database_enabled', value: databaseEnabled }
        );
        
        // Files settings
        const includeFiles = document.getElementById('backup_include_files').checked ? '1' : '0';
        
        settings.push(
            { key: 'backup_include_files', value: includeFiles }
        );
        
        // Schedule settings
        const autoEnabled = document.getElementById('backup_auto_enabled').checked ? '1' : '0';
        const scheduleFrequency = document.getElementById('backup_schedule_frequency').value;
        
        settings.push(
            { key: 'backup_auto_enabled', value: autoEnabled },
            { key: 'backup_schedule_frequency', value: scheduleFrequency }
        );
        
        // Storage settings
        const maxFiles = document.getElementById('backup_max_files').value;
        const maxSize = document.getElementById('backup_max_size_mb').value;
        
        settings.push(
            { key: 'backup_max_files', value: maxFiles },
            { key: 'backup_max_size_mb', value: maxSize }
        );

        // Show loading state
        Swal.fire({
            title: 'กำลังบันทึก...',
            text: 'กรุณารอสักครู่',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        fetch('{{ route("backend.settings-backup.update-settings") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ settings: settings })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'บันทึกสำเร็จ!',
                    text: data.message,
                    timer: 2000,
                    timerProgressBar: true,
                    showConfirmButton: false
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'เกิดข้อผิดพลาด!',
                    text: data.message || 'เกิดข้อผิดพลาดในการบันทึกการตั้งค่า',
                    confirmButtonText: 'ตกลง'
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'เกิดข้อผิดพลาด!',
                text: 'เกิดข้อผิดพลาดในการบันทึกการตั้งค่า',
                confirmButtonText: 'ตกลง'
            });
        });
    }

    // Enhanced success/error messages
    function showSuccess(message) {
        Swal.fire({
            icon: 'success',
            title: 'สำเร็จ!',
            text: message,
            timer: 3000,
            timerProgressBar: true,
            showConfirmButton: false,
            toast: true,
            position: 'top-end'
        });
    }

    function showError(message) {
        Swal.fire({
            icon: 'error',
            title: 'เกิดข้อผิดพลาด!',
            text: message,
            confirmButtonText: 'ตกลง',
            confirmButtonColor: '#ef4444'
        });
    }
</script>
@endpush