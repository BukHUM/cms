@extends('backend.layouts.app')

@section('title', 'การตั้งค่าประสิทธิภาพ')
@section('page-title', 'การตั้งค่าประสิทธิภาพ')
@section('page-description', 'จัดการการตั้งค่าประสิทธิภาพและปรับปรุงระบบ')

@section('content')
<div class="main-content-area">
    <!-- Cache Status Info -->
    @php
        $cacheEnabledSetting = $settings_generals->where('key', 'cache_enabled')->first();
        $cacheStatus = $cacheEnabledSetting ? ($cacheEnabledSetting->is_active ? 'เปิดใช้งาน' : 'ปิดใช้งาน') : 'ไม่ทราบสถานะ';
        $cacheStatusColor = $cacheEnabledSetting ? ($cacheEnabledSetting->is_active ? 'text-green-600' : 'text-red-600') : 'text-gray-600';
    @endphp
    <div class="bg-blue-50 dark:bg-blue-900 border border-blue-200 dark:border-blue-700 rounded-lg p-4 mb-6">
        <div class="flex items-center">
            <i class="fas fa-info-circle text-blue-600 dark:text-blue-400 mr-2"></i>
            <div>
                <h3 class="text-sm font-medium text-blue-900 dark:text-blue-100">สถานะ Cache ระบบ</h3>
                <p class="text-sm text-blue-700 dark:text-blue-300">
                    Cache ปัจจุบัน: <span class="font-semibold {{ $cacheStatusColor }}">{{ $cacheStatus }}</span>
                    @if($cacheEnabledSetting)
                        (ค่า: {{ $cacheEnabledSetting->value }})
                    @endif
                </p>
                @if($cacheEnabledSetting && !$cacheEnabledSetting->is_active)
                    <p class="text-xs text-orange-600 dark:text-orange-400 mt-1">
                        <i class="fas fa-exclamation-triangle mr-1"></i>
                        เมื่อปิด Cache แล้ว ระบบจะอ่านข้อมูลจากฐานข้อมูลโดยตรง ไม่ต้องล้าง Cache
                    </p>
                @endif
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="flex flex-col sm:flex-row sm:justify-between space-y-2 sm:space-y-0 sm:space-x-2 mb-6">
        <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-2">
            <!-- Clear Cache Button -->
            <button type="button" 
                    onclick="clearAllCache()"
                    class="bg-orange-600 text-white px-4 py-2 rounded-md hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 w-full sm:w-auto">
                <i class="fas fa-trash-alt mr-2"></i>
                ล้าง Cache ทั้งหมด
            </button>
            
                <!-- Clear Performance Cache Button -->
                <button type="button" 
                        onclick="clearPerformanceCache()"
                        class="bg-purple-600 text-white px-4 py-2 rounded-md hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 w-full sm:w-auto">
                    <i class="fas fa-memory mr-2"></i>
                    ล้าง Performance Cache
                </button>
                
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow mb-6">
        <div class="p-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Search -->
                <div class="relative">
                    <input type="text" 
                           id="search" 
                           placeholder="ค้นหาการตั้งค่า..." 
                           class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-blue-500 focus:border-blue-500 h-10 bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                           value="{{ request('search') }}"
                           autocomplete="off">
                    
                    <!-- Search Suggestions Dropdown -->
                    <div id="search-suggestions" class="absolute z-50 w-full mt-1 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md shadow-lg hidden max-h-60 overflow-y-auto">
                        <!-- Suggestions will be populated here -->
                    </div>
                    
                    <!-- Loading Indicator -->
                    <div id="search-loading" class="absolute inset-y-0 right-0 pr-3 flex items-center hidden">
                        <i class="fas fa-spinner fa-spin text-gray-400"></i>
                    </div>
                    
                    <!-- Search Hint -->
                    <div class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                        <i class="fas fa-info-circle mr-1"></i>
                        ค้นหาได้จาก: คีย์ (cache_enabled), คำอธิบาย (เปิดใช้งาน Cache), ค่า (true)
                    </div>
                </div>

                <!-- Status Filter -->
                <select id="status-filter" 
                        class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 h-10 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                    <option value="">ทุกสถานะ</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>เปิดใช้งาน</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>ปิดใช้งาน</option>
                </select>

                <!-- Group Filter -->
                <select id="group-filter" 
                        class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 h-10 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                    <option value="">ทุกกลุ่ม</option>
                    <option value="cache" {{ request('group') == 'cache' ? 'selected' : '' }}>การตั้งค่า Cache</option>
                    <option value="database" {{ request('group') == 'database' ? 'selected' : '' }}>การตั้งค่าฐานข้อมูล</option>
                    <option value="memory" {{ request('group') == 'memory' ? 'selected' : '' }}>การตั้งค่าหน่วยความจำ</option>
                    <option value="session" {{ request('group') == 'session' ? 'selected' : '' }}>การตั้งค่า Session</option>
                    <option value="queue" {{ request('group') == 'queue' ? 'selected' : '' }}>การตั้งค่า Queue</option>
                    <option value="logging" {{ request('group') == 'logging' ? 'selected' : '' }}>การตั้งค่า Logging</option>
                    <option value="optimization" {{ request('group') == 'optimization' ? 'selected' : '' }}>การตั้งค่าการปรับปรุงประสิทธิภาพ</option>
                </select>

                <!-- Clear Filters Button -->
                <button type="button" 
                        id="clear-filters"
                        class="bg-gray-600 text-white px-4 py-2 rounded-md hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 flex items-center justify-center h-10">
                    <i class="fas fa-times mr-2"></i>
                    ล้างตัวกรอง
                </button>
            </div>
        </div>
    </div>

    <!-- Settings Table -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white">
                    รายการการตั้งค่าประสิทธิภาพ ({{ $settings_generals->total() }} รายการ)
            </h3>
        </div>
        </div>

        @if($settings_generals->count() > 0)
        <!-- Desktop Table View -->
        <div class="hidden md:block overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            คีย์
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            ค่า
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            ประเภท
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            กลุ่ม
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            สถานะ
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            การดำเนินการ
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach($settings_generals as $settings_performance)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <i class="{{ $settings_performance->type_icon }} text-gray-400 mr-2"></i>
                                <div>
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $settings_performance->key }}</div>
                                    @if($settings_performance->description)
                                    <div class="text-sm text-gray-500 dark:text-gray-400">{{ Str::limit($settings_performance->description, 50) }}</div>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900 dark:text-white max-w-xs truncate">
                                {{ $settings_performance->formatted_value }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200">
                                {{ ucfirst($settings_performance->type) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 dark:bg-gray-600 text-gray-800 dark:text-gray-200">
                                {{ $settings_performance->group_name }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $settings_performance->is_active ? 'bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200' : 'bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200' }}">
                                {{ $settings_performance->is_active ? 'เปิดใช้งาน' : 'ปิดใช้งาน' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex items-center space-x-3">
                                <!-- View Button -->
                                <a href="{{ route('backend.settings-performance.show', $settings_performance->id) }}" 
                                   class="inline-flex items-center justify-center w-8 h-8 text-blue-600 dark:text-blue-400 hover:text-blue-900 dark:hover:text-blue-300 hover:bg-blue-50 dark:hover:bg-blue-900 rounded-md transition-colors duration-200"
                                   title="ดูรายละเอียด">
                                    <i class="fas fa-eye text-sm"></i>
                                </a>
                                
                                <!-- Edit Button -->
                                <button type="button" 
                                        onclick="openEditModal({{ $settings_performance->id }})"
                                        class="inline-flex items-center justify-center w-8 h-8 text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300 hover:bg-indigo-50 dark:hover:bg-indigo-900 rounded-md transition-colors duration-200"
                                        title="แก้ไข">
                                    <i class="fas fa-edit text-sm"></i>
                                </button>
                                
                                <!-- Toggle Status Button -->
                                @if($settings_performance->is_active)
                                <button type="button" 
                                        onclick="toggleStatus({{ $settings_performance->id }})"
                                        class="inline-flex items-center justify-center w-8 h-8 bg-green-100 dark:bg-green-900 text-green-600 dark:text-green-400 hover:bg-green-200 dark:hover:bg-green-800 hover:text-green-700 dark:hover:text-green-300 rounded-md transition-colors duration-200"
                                        title="ปิดการใช้งาน">
                                    <i class="fas fa-toggle-off text-sm"></i>
                                </button>
                                @else
                                <button type="button" 
                                        onclick="toggleStatus({{ $settings_performance->id }})"
                                        class="inline-flex items-center justify-center w-8 h-8 bg-red-100 dark:bg-red-900 text-red-600 dark:text-red-400 hover:bg-red-200 dark:hover:bg-red-800 hover:text-red-700 dark:hover:text-red-300 rounded-md transition-colors duration-200"
                                        title="เปิดการใช้งาน">
                                    <i class="fas fa-toggle-on text-sm"></i>
                                </button>
                                @endif
                                
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Mobile Card View -->
        <div class="md:hidden space-y-4 p-4">
            @foreach($settings_generals as $settings_performance)
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4">
                <!-- Header with Key and Status -->
                <div class="flex items-start justify-between mb-4">
                    <div class="flex items-center flex-1">
                        <i class="{{ $settings_performance->type_icon }} text-gray-400 mr-3 text-lg"></i>
                        <div class="flex-1 min-w-0">
                            <h3 class="text-base font-medium text-gray-900 dark:text-white truncate">{{ $settings_performance->key }}</h3>
                            @if($settings_performance->description)
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ Str::limit($settings_performance->description, 80) }}</p>
                            @endif
                        </div>
                    </div>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $settings_performance->is_active ? 'bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200' : 'bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200' }}">
                        {{ $settings_performance->is_active ? 'เปิด' : 'ปิด' }}
                    </span>
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center justify-center space-x-3 pt-3 border-t border-gray-200 dark:border-gray-700">
                    <!-- View Button -->
                    <a href="{{ route('backend.settings-performance.show', $settings_performance->id) }}" 
                       class="inline-flex items-center justify-center w-10 h-10 text-blue-600 dark:text-blue-400 hover:text-blue-900 dark:hover:text-blue-300 hover:bg-blue-50 dark:hover:bg-blue-900 rounded-md transition-colors duration-200"
                       title="ดูรายละเอียด">
                        <i class="fas fa-eye"></i>
                    </a>
                    
                    <!-- Edit Button -->
                    <button type="button" 
                            onclick="openEditModal({{ $settings_performance->id }})"
                            class="inline-flex items-center justify-center w-10 h-10 text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300 hover:bg-indigo-50 dark:hover:bg-indigo-900 rounded-md transition-colors duration-200"
                            title="แก้ไข">
                        <i class="fas fa-edit"></i>
                    </button>
                    
                    <!-- Toggle Status Button -->
                    @if($settings_performance->is_active)
                    <button type="button" 
                            onclick="toggleStatus({{ $settings_performance->id }})"
                            class="inline-flex items-center justify-center w-10 h-10 bg-green-100 dark:bg-green-900 text-green-600 dark:text-green-400 hover:bg-green-200 dark:hover:bg-green-800 hover:text-green-700 dark:hover:text-green-300 rounded-md transition-colors duration-200"
                            title="ปิดการใช้งาน">
                        <i class="fas fa-toggle-off"></i>
                    </button>
                    @else
                    <button type="button" 
                            onclick="toggleStatus({{ $settings_performance->id }})"
                            class="inline-flex items-center justify-center w-10 h-10 bg-red-100 dark:bg-red-900 text-red-600 dark:text-red-400 hover:bg-red-200 dark:hover:bg-red-800 hover:text-red-700 dark:hover:text-red-300 rounded-md transition-colors duration-200"
                            title="เปิดการใช้งาน">
                        <i class="fas fa-toggle-on"></i>
                    </button>
                    @endif
                    
                </div>
            </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-3 sm:space-y-0">
                <div class="text-sm text-gray-700 dark:text-gray-300 text-center sm:text-left">
                    แสดง {{ $settings_generals->firstItem() ?? 0 }} ถึง {{ $settings_generals->lastItem() ?? 0 }} จาก {{ $settings_generals->total() }} รายการ
                </div>
                <div class="flex items-center justify-center">
                    {{ $settings_generals->links() }}
                </div>
            </div>
        </div>
        @else
        <div class="px-6 py-12 text-center">
                <i class="fas fa-chart-line text-gray-400 text-4xl mb-4"></i>
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">ไม่พบการตั้งค่าประสิทธิภาพ</h3>
            <p class="text-gray-500 dark:text-gray-400 mb-4">ยังไม่มีการตั้งค่าประสิทธิภาพในระบบ</p>
        </div>
        @endif
    </div>
</div>

<!-- Edit Modal -->
<div id="editModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 hidden">
    <div class="relative top-4 sm:top-20 mx-auto p-4 sm:p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white dark:bg-gray-800 max-h-[90vh] overflow-y-auto">
        <!-- Modal Header -->
        <div class="flex justify-between items-center pb-3 border-b dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                <i class="fas fa-edit mr-2"></i>
                แก้ไขการตั้งค่าประสิทธิภาพ
            </h3>
            <button onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>

        <!-- Modal Body -->
        <div class="mt-4">
            <form id="editForm" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" id="edit_id" name="id" value="">

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                    <!-- Left Column -->
                    <div class="space-y-4">
                        <!-- Key (Read-only) -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                <i class="fas fa-key mr-1"></i>
                                คีย์ <span class="text-red-500">*</span>
                            </label>
                            <div id="edit_key_display" class="block w-full px-3 py-2 bg-gray-100 dark:bg-gray-600 border border-gray-300 dark:border-gray-600 rounded-md text-gray-700 dark:text-gray-300 font-mono">
                                <!-- Key will be populated here -->
                            </div>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">ไม่สามารถแก้ไขได้</p>
                        </div>

                        <!-- Value -->
                        <div>
                            <label for="edit_value" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                <i class="fas fa-edit mr-1"></i>
                                ค่า <span class="text-red-500">*</span>
                            </label>
                            
                            <!-- Text Input (default) -->
                            <input type="text"
                                   id="edit_value"
                                   name="value"
                                   class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('value') border-red-500 @enderror"
                                   placeholder="ค่าของการตั้งค่า">
                            
                            <!-- Dropdown (for options) -->
                            <select id="edit_value_select"
                                    name="value"
                                    class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('value') border-red-500 @enderror hidden">
                                <!-- Options will be populated here -->
                            </select>
                            
                            <!-- Hint Text -->
                            <div id="value_hint" class="text-xs text-blue-600 dark:text-blue-400 mt-1 hidden">
                                <i class="fas fa-info-circle mr-1"></i>
                                <span id="hint_text"></span>
                            </div>
                            
                            <div id="value_error" class="text-red-600 text-sm mt-1 hidden"></div>
                        </div>

                        <!-- Type (Read-only) -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                <i class="fas fa-tag mr-1"></i>
                                ประเภท <span class="text-red-500">*</span>
                            </label>
                            <div id="edit_type_display" class="block w-full px-3 py-2 bg-gray-100 dark:bg-gray-600 border border-gray-300 dark:border-gray-600 rounded-md text-gray-700 dark:text-gray-300">
                                <!-- Type will be populated here -->
                            </div>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">ไม่สามารถแก้ไขได้</p>
                        </div>

                        <!-- Group Name (Read-only) -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                <i class="fas fa-folder mr-1"></i>
                                กลุ่ม <span class="text-red-500">*</span>
                            </label>
                            <div id="edit_group_display" class="block w-full px-3 py-2 bg-gray-100 dark:bg-gray-600 border border-gray-300 dark:border-gray-600 rounded-md text-gray-700 dark:text-gray-300">
                                <!-- Group will be populated here -->
                            </div>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">ไม่สามารถแก้ไขได้</p>
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div class="space-y-4">
                        <!-- Description -->
                        <div>
                            <label for="edit_description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                <i class="fas fa-info-circle mr-1"></i>
                                คำอธิบาย
                            </label>
                            <textarea id="edit_description"
                                      name="description"
                                      rows="3"
                                      class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('description') border-red-500 @enderror"
                                      placeholder="คำอธิบายการตั้งค่านี้"></textarea>
                            <div id="description_error" class="text-red-600 text-sm mt-1 hidden"></div>
                        </div>

                        <!-- Status -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <i class="fas fa-toggle-on mr-1"></i>
                                สถานะ
                            </label>
                            <div class="flex items-center">
                                <input type="checkbox"
                                       id="edit_is_active"
                                       name="is_active"
                                       value="1"
                                       class="rounded border-gray-300 dark:border-gray-600 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 dark:bg-gray-700">
                                <label for="edit_is_active" class="ml-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    เปิดใช้งาน
                                </label>
                            </div>
                        </div>

                        <!-- Current Value Display -->
                        <div class="bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-md p-3">
                            <h4 class="text-sm font-semibold text-gray-900 dark:text-white mb-2">
                                <i class="fas fa-info-circle mr-1"></i>
                                ค่าปัจจุบัน
                            </h4>
                            <div class="text-xs text-gray-700 dark:text-gray-300 space-y-1">
                                <div>
                                    <span class="font-medium">ประเภท:</span>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 ml-1" id="current_type">
                                        -
                                    </span>
                                </div>
                                <div>
                                    <span class="font-medium">กลุ่ม:</span>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 dark:bg-gray-600 text-gray-800 dark:text-gray-200 ml-1" id="current_group">
                                        -
                                    </span>
                                </div>
                                <div>
                                    <span class="font-medium">ค่า:</span>
                                    <span class="ml-1 font-mono text-xs bg-white dark:bg-gray-800 px-2 py-1 rounded border dark:border-gray-600" id="current_value">
                                        -
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="flex justify-end space-x-3 mt-6 pt-4 border-t dark:border-gray-700">
                    <button type="button"
                            onclick="closeEditModal()"
                            class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                        <i class="fas fa-times mr-2"></i>
                        ยกเลิก
                    </button>
                    <button type="submit"
                   class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                        <i class="fas fa-save mr-2"></i>
                        บันทึก
                    </button>
            </div>
            </form>
        </div>
    </div>
</div>

<script>
// Search functionality
let searchTimeout;
let currentSearchTerm = '';
let currentSettings = [];

// Global cleanup management
const cleanupManager = {
    timeouts: new Set(),
    eventListeners: new Map(),
    
    // Add timeout for cleanup tracking
    addTimeout(timeoutId) {
        this.timeouts.add(timeoutId);
        return timeoutId;
    },
    
    // Clear all timeouts
    clearAllTimeouts() {
        this.timeouts.forEach(timeoutId => {
            clearTimeout(timeoutId);
        });
        this.timeouts.clear();
    },
    
    // Add event listener for cleanup tracking
    addEventListener(element, event, handler) {
        element.addEventListener(event, handler);
        
        if (!this.eventListeners.has(element)) {
            this.eventListeners.set(element, []);
        }
        this.eventListeners.get(element).push({ event, handler });
    },
    
    // Remove all event listeners from element
    removeAllEventListeners(element) {
        const listeners = this.eventListeners.get(element);
        if (listeners) {
            listeners.forEach(({ event, handler }) => {
                element.removeEventListener(event, handler);
            });
            this.eventListeners.delete(element);
        }
    },
    
    // Cleanup everything
    cleanup() {
        this.clearAllTimeouts();
        this.eventListeners.forEach((listeners, element) => {
            listeners.forEach(({ event, handler }) => {
                element.removeEventListener(event, handler);
            });
        });
        this.eventListeners.clear();
    }
};

// Enhanced timeout management
function createTimeout(callback, delay) {
    const timeoutId = setTimeout(() => {
        cleanupManager.timeouts.delete(timeoutId);
        callback();
    }, delay);
    return cleanupManager.addTimeout(timeoutId);
}

// Enhanced event listener management
function addManagedEventListener(element, event, handler) {
    cleanupManager.addEventListener(element, event, handler);
}

// Clear All Cache Function (with rate limiting and security)
function clearAllCache() {
    // Check if button is disabled (rate limiting)
    const button = document.querySelector('[onclick="clearAllCache()"]');
    if (button && button.disabled) {
        Swal.fire({
            title: 'กรุณารอสักครู่',
            text: 'คุณพยายามล้าง Cache บ่อยเกินไป กรุณารอสักครู่ก่อนลองใหม่',
            icon: 'warning',
            confirmButtonText: 'ตกลง'
        });
        return;
    }

    Swal.fire({
        title: 'ล้าง Cache ทั้งหมด?',
        text: 'คุณต้องการล้าง cache ทั้งหมดของระบบหรือไม่? การดำเนินการนี้อาจใช้เวลาสักครู่',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#f97316',
        cancelButtonColor: '#d33',
        confirmButtonText: 'ใช่, ล้าง Cache!',
        cancelButtonText: 'ยกเลิก'
    }).then((result) => {
        if (result.isConfirmed) {
            // Disable button to prevent multiple clicks
            if (button) {
                button.disabled = true;
                button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>กำลังล้าง Cache...';
            }

            // Show loading
            Swal.fire({
                title: 'กำลังล้าง Cache...',
                text: 'กรุณารอสักครู่',
                allowOutsideClick: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            fetch('{{ route("backend.settings-performance.clear-cache") }}', {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        title: 'สำเร็จ!',
                        text: data.message || 'ล้าง Cache ทั้งหมดเรียบร้อยแล้ว',
                        icon: 'success',
                        confirmButtonText: 'ตกลง'
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        title: 'เกิดข้อผิดพลาด!',
                        text: data.message || 'เกิดข้อผิดพลาดในการล้าง Cache',
                        icon: 'error',
                        confirmButtonText: 'ตกลง'
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    title: 'เกิดข้อผิดพลาด!',
                    text: 'เกิดข้อผิดพลาดในการล้าง Cache',
                    icon: 'error',
                    confirmButtonText: 'ตกลง'
                });
            })
            .finally(() => {
                // Re-enable button after 5 minutes
                if (button) {
                    setTimeout(() => {
                        button.disabled = false;
                        button.innerHTML = '<i class="fas fa-trash-alt mr-2"></i>ล้าง Cache ทั้งหมด';
                    }, 300000); // 5 minutes
                }
            });
        }
    });
}

// Clear Performance Cache Function (with rate limiting and security)
function clearPerformanceCache() {
    // Check if button is disabled (rate limiting)
    const button = document.querySelector('[onclick="clearPerformanceCache()"]');
    if (button && button.disabled) {
        Swal.fire({
            title: 'กรุณารอสักครู่',
            text: 'คุณพยายามล้าง Performance Cache บ่อยเกินไป กรุณารอสักครู่ก่อนลองใหม่',
            icon: 'warning',
            confirmButtonText: 'ตกลง'
        });
        return;
    }

    Swal.fire({
        title: 'ล้าง Performance Cache?',
        text: 'คุณต้องการล้าง cache ที่เกี่ยวข้องกับ performance เท่านั้นหรือไม่?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#7c3aed',
        cancelButtonColor: '#d33',
        confirmButtonText: 'ใช่, ล้าง Performance Cache!',
        cancelButtonText: 'ยกเลิก'
    }).then((result) => {
        if (result.isConfirmed) {
            // Disable button to prevent multiple clicks
            if (button) {
                button.disabled = true;
                button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>กำลังล้าง Cache...';
            }

            // Show loading
            Swal.fire({
                title: 'กำลังล้าง Performance Cache...',
                text: 'กรุณารอสักครู่',
                allowOutsideClick: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            fetch('{{ route("backend.settings-performance.clear-performance-cache") }}', {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        title: 'สำเร็จ!',
                        text: data.message || 'ล้าง Performance Cache เรียบร้อยแล้ว',
                        icon: 'success',
                        confirmButtonText: 'ตกลง'
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        title: 'เกิดข้อผิดพลาด!',
                        text: data.message || 'เกิดข้อผิดพลาดในการล้าง Performance Cache',
                        icon: 'error',
                        confirmButtonText: 'ตกลง'
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    title: 'เกิดข้อผิดพลาด!',
                    text: 'เกิดข้อผิดพลาดในการล้าง Performance Cache',
                    icon: 'error',
                    confirmButtonText: 'ตกลง'
                });
            })
            .finally(() => {
                // Re-enable button after 3 minutes
                if (button) {
                    setTimeout(() => {
                        button.disabled = false;
                        button.innerHTML = '<i class="fas fa-memory mr-2"></i>ล้าง Performance Cache';
                    }, 180000); // 3 minutes
                }
            });
        }
    });
}

// Perform status filter only
function performStatusFilter(status) {
    // If no status filter, show all settings
    if (!status || status === '') {
        resetTableToAll();
        return;
    }
    
    let url = `{{ route('backend.settings-performance.index') }}?ajax=1&status=${encodeURIComponent(status)}`;
    
    fetch(url, {
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        hideSearchLoading();
        if (data.success) {
            updateTableWithResults(data.settings);
        } else {
            console.error('Status filter failed:', data);
        }
    })
    .catch(error => {
        console.error('Status filter error:', error);
        hideSearchLoading();
    });
}

// Perform live search
function performLiveSearch(searchTerm, showSuggestions = true) {
    // If no search term, check if we have status filter
    if (searchTerm === '') {
        hideSuggestions();
        const status = document.getElementById('status-filter').value;
        if (status && status !== '') {
            performStatusFilter(status);
        } else {
            resetTableToAll();
        }
        return;
    }
    
    const status = document.getElementById('status-filter').value;
    let url = `{{ route('backend.settings-performance.index') }}?search=${encodeURIComponent(searchTerm)}&ajax=1`;
    if (status) {
        url += `&status=${encodeURIComponent(status)}`;
    }
    
    fetch(url, {
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        hideSearchLoading();
        if (data.success) {
            updateTableWithResults(data.settings);
            if (showSuggestions) {
                showSuggestions(data.suggestions, searchTerm);
            }
        } else {
            console.error('Search failed:', data);
        }
    })
    .catch(error => {
        console.error('Search error:', error);
        hideSearchLoading();
    });
}

// Update table with search results
function updateTableWithResults(settings) {
    console.log('updateTableWithResults called with:', settings);
    const tbody = document.querySelector('tbody');
    const mobileCards = document.querySelector('.md\\:hidden .space-y-4');
    const tableHeader = document.querySelector('.text-lg.font-medium.text-gray-900');
    
    // Store current settings
    currentSettings = settings;
    console.log('Updated currentSettings:', currentSettings);
    
    // Only require tbody, mobileCards is optional
    if (!tbody) {
        console.error('tbody not found, cannot update table');
        return;
    }
    
    // Update table header with count
    if (tableHeader) {
        tableHeader.textContent = `รายการการตั้งค่าประสิทธิภาพ (${settings.length} รายการ)`;
    }
    
    // Update desktop table
    tbody.innerHTML = '';
    settings.forEach(setting => {
        const row = createTableRow(setting);
        tbody.appendChild(row);
    });
    
    // Update mobile cards if found
    if (mobileCards) {
        mobileCards.innerHTML = '';
        settings.forEach(setting => {
            const card = createMobileCard(setting);
            mobileCards.appendChild(card);
        });
    }
}

// Create table row with highlighting - using safe DOM methods
function createTableRow(setting) {
    const row = document.createElement('tr');
    row.className = 'hover:bg-gray-50 dark:hover:bg-gray-700';
    
    const highlightedKey = highlightText(setting.key, currentSearchTerm);
    const highlightedDescription = setting.description ? highlightText(setting.description, currentSearchTerm) : '';
    const highlightedValue = highlightText(setting.formatted_value, currentSearchTerm);
    
    // Create cells using DOM methods for better security
    const cells = [
        createKeyCell(setting, highlightedKey, highlightedDescription),
        createValueCell(highlightedValue),
        createTypeCell(setting.type),
        createGroupCell(setting.group_name),
        createStatusCell(setting.is_active),
        createActionsCell(setting)
    ];
    
    cells.forEach(cell => row.appendChild(cell));
    
    return row;
}

// Helper functions to create table cells safely
function createKeyCell(setting, highlightedKey, highlightedDescription) {
    const cell = document.createElement('td');
    cell.className = 'px-6 py-4 whitespace-nowrap';
    
    const container = document.createElement('div');
    container.className = 'flex items-center';
    
    const icon = document.createElement('i');
    icon.className = `${setting.type_icon} text-gray-400 mr-2`;
    
    const content = document.createElement('div');
    
    const keyDiv = document.createElement('div');
    keyDiv.className = 'text-sm font-medium text-gray-900 dark:text-white';
    keyDiv.innerHTML = highlightedKey; // Safe because it's already processed by highlightText
    
    content.appendChild(keyDiv);
    
    if (highlightedDescription) {
        const descDiv = document.createElement('div');
        descDiv.className = 'text-sm text-gray-500 dark:text-gray-400';
        descDiv.innerHTML = highlightedDescription; // Safe because it's already processed
        content.appendChild(descDiv);
    }
    
    container.appendChild(icon);
    container.appendChild(content);
    cell.appendChild(container);
    
    return cell;
}

function createValueCell(highlightedValue) {
    const cell = document.createElement('td');
    cell.className = 'px-6 py-4 whitespace-nowrap';
    
    const div = document.createElement('div');
    div.className = 'text-sm text-gray-900 dark:text-white max-w-xs truncate';
    div.innerHTML = highlightedValue; // Safe because it's already processed
    
    cell.appendChild(div);
    return cell;
}

function createTypeCell(type) {
    const cell = document.createElement('td');
    cell.className = 'px-6 py-4 whitespace-nowrap';
    
    const span = document.createElement('span');
    span.className = 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200';
    span.textContent = type ? type.charAt(0).toUpperCase() + type.slice(1) : 'Unknown';
    
    cell.appendChild(span);
    return cell;
}

function createGroupCell(groupName) {
    const cell = document.createElement('td');
    cell.className = 'px-6 py-4 whitespace-nowrap';
    
    const span = document.createElement('span');
    span.className = 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 dark:bg-gray-600 text-gray-800 dark:text-gray-200';
    span.textContent = groupName;
    
    cell.appendChild(span);
    return cell;
}

function createStatusCell(isActive) {
    const cell = document.createElement('td');
    cell.className = 'px-6 py-4 whitespace-nowrap';
    
    const span = document.createElement('span');
    span.className = `inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${isActive ? 'bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200' : 'bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200'}`;
    span.textContent = isActive ? 'เปิดใช้งาน' : 'ปิดใช้งาน';
    
    cell.appendChild(span);
    return cell;
}

function createActionsCell(setting) {
    const cell = document.createElement('td');
    cell.className = 'px-6 py-4 whitespace-nowrap text-sm font-medium';
    
    const container = document.createElement('div');
    container.className = 'flex items-center space-x-3';
    
    // View button
    const viewBtn = document.createElement('a');
    viewBtn.href = `/backend/settings-performance/${setting.id}`;
    viewBtn.className = 'inline-flex items-center justify-center w-8 h-8 text-blue-600 dark:text-blue-400 hover:text-blue-900 dark:hover:text-blue-300 hover:bg-blue-50 dark:hover:bg-blue-900 rounded-md transition-colors duration-200';
    viewBtn.title = 'ดูรายละเอียด';
    viewBtn.innerHTML = '<i class="fas fa-eye text-sm"></i>';
    
    // Edit button
    const editBtn = document.createElement('button');
    editBtn.type = 'button';
    editBtn.className = 'inline-flex items-center justify-center w-8 h-8 text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300 hover:bg-indigo-50 dark:hover:bg-indigo-900 rounded-md transition-colors duration-200';
    editBtn.title = 'แก้ไข';
    editBtn.innerHTML = '<i class="fas fa-edit text-sm"></i>';
    editBtn.onclick = () => openEditModal(setting.id);
    
    container.appendChild(viewBtn);
    container.appendChild(editBtn);
    
    // Toggle status button
    const toggleBtn = document.createElement('button');
    toggleBtn.type = 'button';
    toggleBtn.className = `inline-flex items-center justify-center w-8 h-8 ${setting.is_active ? 'bg-green-100 dark:bg-green-900 text-green-600 dark:text-green-400 hover:bg-green-200 dark:hover:bg-green-800 hover:text-green-700 dark:hover:text-green-300' : 'bg-red-100 dark:bg-red-900 text-red-600 dark:text-red-400 hover:bg-red-200 dark:hover:bg-red-800 hover:text-red-700 dark:hover:text-red-300'} rounded-md transition-colors duration-200`;
    toggleBtn.title = setting.is_active ? 'ปิดการใช้งาน' : 'เปิดการใช้งาน';
    toggleBtn.innerHTML = `<i class="fas fa-toggle-${setting.is_active ? 'off' : 'on'} text-sm"></i>`;
    console.log('Creating toggle button for setting:', setting);
    toggleBtn.onclick = () => {
        console.log('Toggle button clicked for setting:', setting);
        toggleStatus(setting.id);
    };
    
    container.appendChild(toggleBtn);
    
    
    cell.appendChild(container);
    return cell;
}

// Create mobile card with highlighting - using safe DOM methods
function createMobileCard(setting) {
    const card = document.createElement('div');
    card.className = 'bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4';
    
    const highlightedKey = highlightText(setting.key, currentSearchTerm);
    const highlightedDescription = setting.description ? highlightText(setting.description, currentSearchTerm) : '';
    
    // Header section
    const header = document.createElement('div');
    header.className = 'flex items-start justify-between mb-4';
    
    const leftSection = document.createElement('div');
    leftSection.className = 'flex items-center flex-1';
    
    const icon = document.createElement('i');
    icon.className = `${setting.type_icon} text-gray-400 mr-3 text-lg`;
    
    const content = document.createElement('div');
    content.className = 'flex-1 min-w-0';
    
    const title = document.createElement('h3');
    title.className = 'text-base font-medium text-gray-900 dark:text-white truncate';
    title.innerHTML = highlightedKey; // Safe because it's already processed
    
    content.appendChild(title);
    
    if (highlightedDescription) {
        const desc = document.createElement('p');
        desc.className = 'text-sm text-gray-500 dark:text-gray-400 mt-1';
        desc.innerHTML = highlightedDescription; // Safe because it's already processed
        content.appendChild(desc);
    }
    
    leftSection.appendChild(icon);
    leftSection.appendChild(content);
    
    const statusSpan = document.createElement('span');
    statusSpan.className = `inline-flex items-center px-3 py-1 rounded-full text-sm font-medium ${setting.is_active ? 'bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200' : 'bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200'}`;
    statusSpan.textContent = setting.is_active ? 'เปิด' : 'ปิด';
    
    header.appendChild(leftSection);
    header.appendChild(statusSpan);
    
    // Actions section
    const actions = document.createElement('div');
    actions.className = 'flex items-center justify-center space-x-3 pt-3 border-t border-gray-200 dark:border-gray-700';
    
    // View button
    const viewBtn = document.createElement('a');
    viewBtn.href = `/backend/settings-performance/${setting.id}`;
    viewBtn.className = 'inline-flex items-center justify-center w-10 h-10 text-blue-600 dark:text-blue-400 hover:text-blue-900 dark:hover:text-blue-300 hover:bg-blue-50 dark:hover:bg-blue-900 rounded-md transition-colors duration-200';
    viewBtn.title = 'ดูรายละเอียด';
    viewBtn.innerHTML = '<i class="fas fa-eye"></i>';
    
    // Edit button
    const editBtn = document.createElement('button');
    editBtn.type = 'button';
    editBtn.className = 'inline-flex items-center justify-center w-10 h-10 text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300 hover:bg-indigo-50 dark:hover:bg-indigo-900 rounded-md transition-colors duration-200';
    editBtn.title = 'แก้ไข';
    editBtn.innerHTML = '<i class="fas fa-edit"></i>';
    editBtn.onclick = () => openEditModal(setting.id);
    
    actions.appendChild(viewBtn);
    actions.appendChild(editBtn);
    
    // Toggle status button
    const toggleBtn = document.createElement('button');
    toggleBtn.type = 'button';
    toggleBtn.className = `inline-flex items-center justify-center w-10 h-10 ${setting.is_active ? 'bg-green-100 dark:bg-green-900 text-green-600 dark:text-green-400 hover:bg-green-200 dark:hover:bg-green-800 hover:text-green-700 dark:hover:text-green-300' : 'bg-red-100 dark:bg-red-900 text-red-600 dark:text-red-400 hover:bg-red-200 dark:hover:bg-red-800 hover:text-red-700 dark:hover:text-red-300'} rounded-md transition-colors duration-200`;
    toggleBtn.title = setting.is_active ? 'ปิดการใช้งาน' : 'เปิดการใช้งาน';
    toggleBtn.innerHTML = `<i class="fas fa-toggle-${setting.is_active ? 'off' : 'on'}"></i>`;
    console.log('Creating mobile toggle button for setting:', setting);
    toggleBtn.onclick = () => {
        console.log('Mobile toggle button clicked for setting:', setting);
        toggleStatus(setting.id);
    };
    
    actions.appendChild(toggleBtn);
    
    
    card.appendChild(header);
    card.appendChild(actions);
    
    return card;
}

// Highlight search terms in text
function highlightText(text, searchTerm) {
    if (!searchTerm || !text) return text;
    
    // Convert to string if not already
    const textStr = String(text);
    
    const regex = new RegExp(`(${escapeRegExp(searchTerm)})`, 'gi');
    return textStr.replace(regex, '<span class="bg-yellow-200 px-1 rounded">$1</span>');
}

// Escape special regex characters
function escapeRegExp(string) {
    return string.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
}

// Show search suggestions - using safe DOM methods
function showSuggestions(suggestions, searchTerm) {
    const suggestionsContainer = document.getElementById('search-suggestions');
    
    if (!suggestions || suggestions.length === 0) {
        hideSuggestions();
        return;
    }
    
    // Clear existing suggestions
    suggestionsContainer.innerHTML = '';
    
    suggestions.forEach(suggestion => {
        const item = document.createElement('div');
        item.className = 'px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700 cursor-pointer border-b border-gray-100 dark:border-gray-600 last:border-b-0';
        
        const container = document.createElement('div');
        container.className = 'flex items-center';
        
        const icon = document.createElement('i');
        icon.className = `${suggestion.type_icon} text-gray-400 mr-2`;
        
        const content = document.createElement('div');
        content.className = 'flex-1';
        
        const keyDiv = document.createElement('div');
        keyDiv.className = 'text-sm font-medium text-gray-900 dark:text-white';
        keyDiv.innerHTML = highlightText(suggestion.key, searchTerm); // Safe because it's already processed
        
        const descDiv = document.createElement('div');
        descDiv.className = 'text-xs text-gray-500 dark:text-gray-400';
        descDiv.innerHTML = highlightText(suggestion.description || '', searchTerm); // Safe because it's already processed
        
        content.appendChild(keyDiv);
        content.appendChild(descDiv);
        
        const typeSpan = document.createElement('span');
        typeSpan.className = 'text-xs text-gray-400 dark:text-gray-500';
        typeSpan.textContent = suggestion.type;
        
        container.appendChild(icon);
        container.appendChild(content);
        container.appendChild(typeSpan);
        
        item.appendChild(container);
        
        item.addEventListener('click', function() {
            document.getElementById('search').value = suggestion.key;
            currentSearchTerm = suggestion.key;
            hideSuggestions();
            // Perform search again to get the correct data, but don't show suggestions
            performLiveSearch(suggestion.key, false);
        });
        
        suggestionsContainer.appendChild(item);
    });
    
    suggestionsContainer.classList.remove('hidden');
}

// Hide search suggestions
function hideSuggestions() {
    document.getElementById('search-suggestions').classList.add('hidden');
}

// Show search loading indicator
function showSearchLoading() {
    document.getElementById('search-loading').classList.remove('hidden');
}

// Hide search loading indicator
function hideSearchLoading() {
    document.getElementById('search-loading').classList.add('hidden');
}

// Reset table to show all settings
function resetTableToAll() {
    const tbody = document.querySelector('tbody');
    const mobileCards = document.querySelector('.md\\:hidden .space-y-4');
    const tableHeader = document.querySelector('.text-lg.font-medium.text-gray-900');
    
    if (!tbody) return;
    
    // Show loading indicator
    showSearchLoading();
    
    // Fetch all settings without any filters
    fetch(`{{ route('backend.settings-performance.index') }}?ajax=1`, {
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        hideSearchLoading();
        if (data.success) {
            updateTableWithResults(data.settings);
        } else {
            console.error('Reset failed:', data);
        }
    })
    .catch(error => {
        console.error('Reset error:', error);
        hideSearchLoading();
    });
}

function toggleStatus(id) {
    console.log('Toggle status for ID:', id);
    console.log('currentSettings:', currentSettings);
    
    // Get setting info from currentSettings array instead of DOM
    const setting = currentSettings.find(s => s.id === id);
    console.log('Found setting:', setting);
    
    if (!setting) {
        console.error('Setting not found:', id);
        Swal.fire({
            title: 'เกิดข้อผิดพลาด!',
            text: 'ไม่พบการตั้งค่าที่ต้องการ',
            icon: 'error',
            confirmButtonText: 'ตกลง'
        });
        return;
    }
    
    const settingKey = setting.key;
    const currentStatus = setting.is_active ? 'เปิดใช้งาน' : 'ปิดใช้งาน';
    
    console.log('Setting key:', settingKey);
    console.log('Current status:', currentStatus);
    
    let confirmText = 'คุณต้องการเปลี่ยนสถานะการตั้งค่านี้หรือไม่?';
    let confirmButtonText = 'ใช่, เปลี่ยนสถานะ!';
    
    // Special handling for cache settings
    if (settingKey === 'cache_enabled') {
        if (currentStatus === 'ปิดใช้งาน') {
            confirmText = 'คุณต้องการเปิดการใช้งาน Cache หรือไม่? ระบบจะทำงานเร็วขึ้น';
            confirmButtonText = 'ใช่, เปิด Cache!';
        } else {
            confirmText = 'คุณต้องการปิดการใช้งาน Cache หรือไม่? ระบบอาจทำงานช้าลง';
            confirmButtonText = 'ใช่, ปิด Cache!';
        }
    }
    
    // Special handling for opcache
    if (settingKey === 'opcache_enabled') {
        if (currentStatus === 'ปิดใช้งาน') {
            confirmText = 'คุณต้องการเปิด OPcache หรือไม่? จะช่วยเพิ่มประสิทธิภาพ PHP';
            confirmButtonText = 'ใช่, เปิด OPcache!';
        } else {
            confirmText = 'คุณต้องการปิด OPcache หรือไม่? อาจทำให้ประสิทธิภาพลดลง';
            confirmButtonText = 'ใช่, ปิด OPcache!';
        }
    }
    
    Swal.fire({
        title: 'คุณแน่ใจหรือไม่?',
        text: confirmText,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: confirmButtonText,
        cancelButtonText: 'ยกเลิก'
    }).then((result) => {
        if (result.isConfirmed) {
            const url = '{{ route('backend.settings-performance.toggle-status', 1) }}'.replace('1', id);
            console.log('Toggle URL:', url);
            
            fetch(url, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'X-HTTP-Method-Override': 'PATCH'
                }
            })
            .then(response => {
                console.log('Toggle response status:', response.status);
                return response.json();
            })
            .then(data => {
                console.log('Toggle response data:', data);
                if (data.message) {
                    let successText = 'เปลี่ยนสถานะเรียบร้อยแล้ว';
                    
                    // Special message for cache settings
                    if (settingKey === 'cache_enabled') {
                        if (currentStatus === 'ปิดใช้งาน') {
                            successText = 'เปิดการใช้งาน Cache เรียบร้อยแล้ว ระบบจะทำงานเร็วขึ้น';
                        } else {
                            successText = 'ปิดการใช้งาน Cache เรียบร้อยแล้ว ระบบอาจทำงานช้าลง';
                        }
                    }
                    
                    // Special message for opcache
                    if (settingKey === 'opcache_enabled') {
                        if (currentStatus === 'ปิดใช้งาน') {
                            successText = 'เปิด OPcache เรียบร้อยแล้ว จะช่วยเพิ่มประสิทธิภาพ PHP';
                        } else {
                            successText = 'ปิด OPcache เรียบร้อยแล้ว อาจทำให้ประสิทธิภาพลดลง';
                        }
                    }
                    
                    Swal.fire({
                        title: 'สำเร็จ!',
                        text: successText,
                        icon: 'success',
                        confirmButtonText: 'ตกลง'
                    }).then(() => {
                        location.reload();
                    });
                } else if (data.success === false) {
                    Swal.fire({
                        title: 'เกิดข้อผิดพลาด!',
                        text: data.message || 'เกิดข้อผิดพลาดในการเปลี่ยนสถานะ',
                        icon: 'error',
                        confirmButtonText: 'ตกลง'
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    title: 'เกิดข้อผิดพลาด!',
                    text: 'เกิดข้อผิดพลาดในการเปลี่ยนสถานะ',
                    icon: 'error',
                    confirmButtonText: 'ตกลง'
                });
            });
        }
    });
}

// Setup value input based on setting type and options
function setupValueInput(data) {
    const textInput = document.getElementById('edit_value');
    const selectInput = document.getElementById('edit_value_select');
    const hintDiv = document.getElementById('value_hint');
    const hintText = document.getElementById('hint_text');
    
    // Hide both inputs first
    textInput.classList.add('hidden');
    selectInput.classList.add('hidden');
    hintDiv.classList.add('hidden');
    
    // Clear select options
    selectInput.innerHTML = '';
    
    // Parse options if available
    let options = null;
    if (data.options) {
        try {
            options = JSON.parse(data.options);
        } catch (e) {
            console.warn('Failed to parse options:', data.options);
        }
    }
    
    // Setup input based on type and options
    if (options && Object.keys(options).length > 0 && data.type !== 'boolean') {
        // Use dropdown for settings with options (except boolean)
        selectInput.classList.remove('hidden');
        
        // Add options to select
        Object.entries(options).forEach(([value, label]) => {
            const option = document.createElement('option');
            option.value = value;
            option.textContent = label;
            if (value === data.value) {
                option.selected = true;
            }
            selectInput.appendChild(option);
        });
        
        // Show hint
        hintDiv.classList.remove('hidden');
        hintText.textContent = `เลือกจากตัวเลือกที่มี: ${Object.values(options).join(', ')}`;
    } else {
        // Use text input for settings without options
        if (data.type === 'boolean') {
            // For boolean settings, show read-only display instead of input
            textInput.classList.add('hidden');
            textInput.value = data.value || '';
            
            // Show special message for boolean settings
            hintDiv.classList.remove('hidden');
            hintText.textContent = 'ใช้ปุ่มเปิด/ปิดด้านขวาแทนการแก้ไขค่า';
            hintDiv.className = 'text-xs text-orange-600 dark:text-orange-400 mt-1';
        } else {
            textInput.classList.remove('hidden');
            textInput.value = data.value || '';
            
            // Show hint based on type
            const hints = {
                'integer': 'ใส่ตัวเลขจำนวนเต็ม เช่น 120, 3600',
                'string': 'ใส่ข้อความ',
                'email': 'ใส่ที่อยู่อีเมลที่ถูกต้อง',
                'url': 'ใส่ URL ที่ถูกต้อง',
                'json': 'ใส่ข้อมูล JSON ที่ถูกต้อง'
            };
            
            if (hints[data.type]) {
                hintDiv.classList.remove('hidden');
                hintText.textContent = hints[data.type];
            }
        }
    }
}

// Edit Modal functionality
function openEditModal(id) {
    // Fetch setting data
    fetch('{{ route('backend.settings-performance.show', 1) }}'.replace('1', id), {
        headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        
        // Set the setting ID
        document.getElementById('edit_id').value = id;
        
        // Setup value input based on type and options
        setupValueInput(data);
        
        // Populate form fields
        document.getElementById('edit_description').value = data.description || '';
        document.getElementById('edit_is_active').checked = data.is_active;

        // Update read-only displays
        document.getElementById('edit_key_display').textContent = data.key;
        document.getElementById('edit_type_display').textContent = data.type ? data.type.charAt(0).toUpperCase() + data.type.slice(1) : 'Unknown';
        document.getElementById('edit_group_display').textContent = data.group_name ? data.group_name.charAt(0).toUpperCase() + data.group_name.slice(1) : 'Unknown';

        // Update current value display
        document.getElementById('current_type').textContent = data.type ? data.type.charAt(0).toUpperCase() + data.type.slice(1) : 'Unknown';
        document.getElementById('current_group').textContent = data.group_name ? data.group_name.charAt(0).toUpperCase() + data.group_name.slice(1) : 'Unknown';
        
        // For boolean settings, show formatted value
        if (data.type === 'boolean') {
            const booleanValue = data.value === 'true' || data.value === '1' ? 'เปิด' : 'ปิด';
            document.getElementById('current_value').textContent = booleanValue;
        } else {
            document.getElementById('current_value').textContent = data.value;
        }

        // Update form action - use update route for AJAX update
        document.getElementById('editForm').action = '{{ route('backend.settings-performance.update', 1) }}'.replace('1', id);

        // Show modal
        document.getElementById('editModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        
        // For boolean settings, hide save button and show message
        const saveButton = document.querySelector('#editModal button[type="submit"]');
        const cancelButton = document.querySelector('#editModal button[onclick="closeEditModal()"]');
        
        if (data.type === 'boolean') {
            saveButton.style.display = 'none';
            cancelButton.textContent = 'ปิด';
            
            // Add message after cancel button
            let messageDiv = document.getElementById('boolean-message');
            if (!messageDiv) {
                messageDiv = document.createElement('div');
                messageDiv.id = 'boolean-message';
                messageDiv.className = 'text-center text-sm text-orange-600 dark:text-orange-400 mt-2';
                messageDiv.innerHTML = '<i class="fas fa-info-circle mr-1"></i>ใช้ปุ่มเปิด/ปิดด้านขวาแทนการแก้ไขค่า';
                cancelButton.parentNode.appendChild(messageDiv);
            }
        } else {
            saveButton.style.display = 'inline-flex';
            cancelButton.textContent = 'ยกเลิก';
            
            // Remove message if exists
            const messageDiv = document.getElementById('boolean-message');
            if (messageDiv) {
                messageDiv.remove();
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire({
            title: 'เกิดข้อผิดพลาด!',
            text: 'เกิดข้อผิดพลาดในการโหลดข้อมูล',
            icon: 'error',
            confirmButtonText: 'ตกลง'
        });
    });
}

function closeEditModal() {
    document.getElementById('editModal').classList.add('hidden');
    document.body.style.overflow = '';

    // Clear form
    document.getElementById('editForm').reset();

    // Clear errors
    const errorElements = document.querySelectorAll('[id$="_error"]');
    errorElements.forEach(element => {
        element.classList.add('hidden');
        element.textContent = '';
    });
}


// Cleanup function to prevent memory leaks
function cleanup() {
    cleanupManager.cleanup();
}

// Cleanup on page unload
window.addEventListener('beforeunload', cleanup);

// Cleanup on page visibility change (when user switches tabs)
document.addEventListener('visibilitychange', function() {
    if (document.hidden) {
        // Page is hidden, cleanup timeouts to save resources
        cleanupManager.clearAllTimeouts();
    }
});

// Initialize cleanup management
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM Content Loaded - Performance Settings');
    console.log('Swal available:', typeof Swal);
    
    // Initialize currentSettings with initial data from the page
    const initialSettings = @json($settings_generals->items());
    currentSettings = initialSettings;
    console.log('Initialized currentSettings:', currentSettings);
    
    // Debug: Check cache_enabled setting
    const cacheEnabledSetting = currentSettings.find(s => s.key === 'cache_enabled');
    if (cacheEnabledSetting) {
        console.log('Cache Enabled Setting:', cacheEnabledSetting);
        console.log('Cache is currently:', cacheEnabledSetting.is_active ? 'ENABLED' : 'DISABLED');
        console.log('Cache value:', cacheEnabledSetting.value);
    } else {
        console.warn('cache_enabled setting not found!');
    }
    
    // Add managed event listeners for better cleanup
    const searchInput = document.getElementById('search');
    const statusFilter = document.getElementById('status-filter');
    const groupFilter = document.getElementById('group-filter');
    const clearFiltersBtn = document.getElementById('clear-filters');
    const editForm = document.getElementById('editForm');
    const editModal = document.getElementById('editModal');
    
    if (searchInput) {
        addManagedEventListener(searchInput, 'input', function(e) {
            const searchTerm = e.target.value.trim();
            currentSearchTerm = searchTerm;
            
            // Clear previous timeout
            clearTimeout(searchTimeout);
            
            // Hide suggestions if search is empty
            if (searchTerm === '') {
                hideSuggestions();
                resetTableToAll();
                return;
            }
            
            // Show loading indicator
            showSearchLoading();
            
            // Debounce search - wait 300ms after user stops typing
            searchTimeout = createTimeout(() => {
                performLiveSearch(searchTerm);
            }, 300);
        });
        
        addManagedEventListener(searchInput, 'focus', function() {
            if (this.value.trim() !== '') {
                performLiveSearch(this.value.trim());
            }
        });
    }
    
    if (statusFilter) {
        addManagedEventListener(statusFilter, 'change', function() {
            const searchTerm = document.getElementById('search').value.trim();
            const status = this.value;
            
            // Clear previous timeout
            clearTimeout(searchTimeout);
            
            // If no search term and no status filter, reset to all
            if (searchTerm === '' && status === '') {
                resetTableToAll();
                return;
            }
            
            // Show loading indicator
            showSearchLoading();
            
            // Debounce filter - wait 300ms after user stops changing
            searchTimeout = createTimeout(() => {
                if (searchTerm !== '') {
                    performLiveSearch(searchTerm, false);
                } else {
                    // Only status filter, no search term
                    performStatusFilter(status);
                }
            }, 300);
        });
    }
    
    if (groupFilter) {
        addManagedEventListener(groupFilter, 'change', function() {
            const searchTerm = document.getElementById('search').value.trim();
            const status = document.getElementById('status-filter').value;
            const group = this.value;
            
            // Clear previous timeout
            clearTimeout(searchTimeout);
            
            // If no filters, reset to all
            if (searchTerm === '' && status === '' && group === '') {
                resetTableToAll();
                return;
            }
            
            // Show loading indicator
            showSearchLoading();
            
            // Debounce filter - wait 300ms after user stops changing
            searchTimeout = createTimeout(() => {
                let url = `{{ route('backend.settings-performance.index') }}?ajax=1`;
                const params = [];
                
                if (searchTerm) params.push(`search=${encodeURIComponent(searchTerm)}`);
                if (status) params.push(`status=${encodeURIComponent(status)}`);
                if (group) params.push(`group=${encodeURIComponent(group)}`);
                
                if (params.length > 0) {
                    url += '&' + params.join('&');
                }
                
                fetch(url, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    hideSearchLoading();
                    if (data.success) {
                        updateTableWithResults(data.settings);
                    } else {
                        console.error('Group filter failed:', data);
                    }
                })
                .catch(error => {
                    console.error('Group filter error:', error);
                    hideSearchLoading();
                });
            }, 300);
        });
    }
    
    if (clearFiltersBtn) {
        addManagedEventListener(clearFiltersBtn, 'click', function() {
            document.getElementById('search').value = '';
            document.getElementById('status-filter').value = '';
            document.getElementById('group-filter').value = '';
            currentSearchTerm = '';
            hideSuggestions();
            resetTableToAll();
        });
    }
    
    if (editForm) {
        addManagedEventListener(editForm, 'submit', function(e) {
            e.preventDefault();

            // Validate required field - check both text input and select
            const textInput = document.getElementById('edit_value');
            const selectInput = document.getElementById('edit_value_select');
            const activeInput = textInput.classList.contains('hidden') ? selectInput : textInput;
            
            if (!activeInput.value.trim()) {
                Swal.fire({
                    title: 'ข้อมูลไม่ครบถ้วน!',
                    text: 'กรุณาเลือกหรือกรอกค่าการตั้งค่า',
                    icon: 'error',
                    confirmButtonText: 'ตกลง'
                });
                return;
            }

            const formData = new FormData(this);
            const settingId = document.getElementById('edit_id').value;
            const url = '{{ route('backend.settings-performance.update', 1) }}'.replace('1', settingId);

            fetch(url, {
                method: 'POST',
                body: formData,
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'X-HTTP-Method-Override': 'PUT'
                }
            })
            .then(response => {
                if (!response.ok) {
                    return response.text().then(text => {
                        throw new Error(`HTTP ${response.status}: ${text}`);
                    });
                }
                
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    closeEditModal();
                    location.reload();
                } else {
                    // Display validation errors
                    if (data.errors) {
                        Object.keys(data.errors).forEach(field => {
                            const errorElement = document.getElementById(field + '_error');
                            if (errorElement) {
                                errorElement.textContent = data.errors[field][0];
                                errorElement.classList.remove('hidden');
                            }
                        });
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    title: 'เกิดข้อผิดพลาด!',
                    text: 'เกิดข้อผิดพลาดในการบันทึกข้อมูล',
                    icon: 'error',
                    confirmButtonText: 'ตกลง'
                });
            });
        });
    }
    
    if (editModal) {
        addManagedEventListener(editModal, 'click', function(e) {
            if (e.target === this) {
                closeEditModal();
            }
        });
    }
    
    // Global keyboard event listener
    addManagedEventListener(document, 'keydown', function(e) {
        if (e.key === 'Escape' && !document.getElementById('editModal').classList.contains('hidden')) {
            closeEditModal();
        }
    });
});
</script>
@endsection