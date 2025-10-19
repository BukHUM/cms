@extends('backend.layouts.app')

@section('title', 'การตั้งค่าประสิทธิภาพ')
@section('page-title', 'การตั้งค่าประสิทธิภาพ')
@section('page-description', 'จัดการการตั้งค่าประสิทธิภาพและปรับปรุงระบบ')

@section('content')
<div class="main-content-area">
    <!-- Action Buttons -->
    <div class="flex flex-col sm:flex-row sm:justify-end space-y-2 sm:space-y-0 sm:space-x-2 mb-6">
        <a href="{{ route('backend.settings-performance.create') }}" 
           class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 w-full sm:w-auto">
            <i class="fas fa-plus mr-2"></i>
            เพิ่มการตั้งค่าใหม่
        </a>
    </div>

    <!-- Performance Settings List -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white">
                รายการการตั้งค่าประสิทธิภาพ
            </h3>
        </div>
        <div class="p-6">
            <div class="text-center py-12">
                <i class="fas fa-chart-line text-gray-400 text-4xl mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">ยังไม่มีการตั้งค่าประสิทธิภาพ</h3>
                <p class="text-gray-500 dark:text-gray-400 mb-4">เริ่มต้นด้วยการเพิ่มการตั้งค่าประสิทธิภาพใหม่</p>
                <a href="{{ route('backend.settings-performance.create') }}" 
                   class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                    <i class="fas fa-plus mr-2"></i>
                    เพิ่มการตั้งค่าใหม่
                </a>
            </div>
        </div>
    </div>
</div>
@endsection