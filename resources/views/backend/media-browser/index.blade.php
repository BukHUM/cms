@extends('backend.layouts.app')

@section('title', 'Media Browser')
@section('page-title', 'Media Browser')
@section('page-description', 'จัดการไฟล์และโฟลเดอร์ในระบบ')

@section('styles')
<style>
    /* Drag and Drop Styles */
    .drag-over {
        background-color: rgba(59, 130, 246, 0.1) !important;
    }
    
    #dropZoneOverlay {
        backdrop-filter: blur(2px);
        animation: pulse 2s infinite;
    }
    
    @keyframes pulse {
        0%, 100% {
            opacity: 0.8;
        }
        50% {
            opacity: 1;
        }
    }
    
    /* File upload progress */
    .upload-progress {
        position: fixed;
        top: 20px;
        right: 20px;
        background: white;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        padding: 16px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        z-index: 1000;
        min-width: 300px;
    }
    
    .upload-progress-item {
        display: flex;
        align-items: center;
        margin-bottom: 8px;
    }
    
    .upload-progress-item:last-child {
        margin-bottom: 0;
    }
    
    .upload-progress-bar {
        width: 100%;
        height: 4px;
        background-color: #e5e7eb;
        border-radius: 2px;
        overflow: hidden;
        margin-top: 4px;
    }
    
    .upload-progress-fill {
        height: 100%;
        background-color: #3b82f6;
        transition: width 0.3s ease;
    }
    
    
    /* Modern Grid View Styles */
    .grid-view-container {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
        gap: 12px;
        padding: 16px;
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        border-radius: 12px;
        min-height: 400px;
    }
    
    .dark .grid-view-container {
        background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
    }
    
    .grid-item {
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(226, 232, 240, 0.8);
        border-radius: 12px;
        padding: 16px 12px;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        text-align: center;
        min-height: 100px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        position: relative;
        overflow: hidden;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }
    
    .grid-item::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(135deg, rgba(59, 130, 246, 0.1) 0%, rgba(147, 51, 234, 0.1) 100%);
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    
    .grid-item:hover {
        background: rgba(255, 255, 255, 1);
        border-color: #3b82f6;
        transform: translateY(-4px) scale(1.02);
        box-shadow: 0 10px 25px rgba(59, 130, 246, 0.15);
    }
    
    .grid-item:hover::before {
        opacity: 1;
    }
    
    .grid-item.selected {
        background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.2), 0 8px 20px rgba(59, 130, 246, 0.1);
        transform: translateY(-2px);
    }
    
    .dark .grid-item {
        background: rgba(30, 41, 59, 0.9);
        border-color: rgba(71, 85, 105, 0.8);
    }
    
    .dark .grid-item:hover {
        background: rgba(30, 41, 59, 1);
        border-color: #3b82f6;
    }
    
    .dark .grid-item.selected {
        background: linear-gradient(135deg, #1e3a8a 0%, #1e40af 100%);
        border-color: #3b82f6;
    }
    
    .grid-icon {
        font-size: 32px;
        margin-bottom: 8px;
        position: relative;
        z-index: 1;
        transition: transform 0.3s ease;
    }
    
    .grid-item:hover .grid-icon {
        transform: scale(1.1);
    }
    
    .grid-name {
        font-size: 12px;
        font-weight: 600;
        color: #475569;
        line-height: 1.3;
        word-break: break-word;
        text-align: center;
        max-width: 100%;
        overflow: hidden;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        position: relative;
        z-index: 1;
    }
    
    .dark .grid-name {
        color: #cbd5e1;
    }
    
    /* Grid Item Type Indicators */
    .grid-item[data-type="folder"] {
        border-left: 4px solid #3b82f6;
    }
    
    .grid-item[data-type="images"] {
        border-left: 4px solid #10b981;
    }
    
    .grid-item[data-type="documents"] {
        border-left: 4px solid #f59e0b;
    }
    
    .grid-item[data-type="videos"] {
        border-left: 4px solid #ef4444;
    }
    
    .grid-item[data-type="audio"] {
        border-left: 4px solid #8b5cf6;
    }
    
    /* Modern List View Styles */
    .list-view-container {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(226, 232, 240, 0.8);
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        min-height: 400px;
    }
    
    .dark .list-view-container {
        background: rgba(30, 41, 59, 0.95);
        border-color: rgba(71, 85, 105, 0.8);
    }
    
    .list-header {
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        border-bottom: 1px solid rgba(226, 232, 240, 0.8);
        padding: 12px 20px;
        font-size: 13px;
        font-weight: 700;
        color: #475569;
        display: grid;
        grid-template-columns: 1fr 100px 140px 120px;
        gap: 20px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        position: sticky;
        top: 0;
        z-index: 10;
    }
    
    .dark .list-header {
        background: linear-gradient(135deg, #374151 0%, #1f2937 100%);
        border-bottom-color: rgba(71, 85, 105, 0.8);
        color: #cbd5e1;
    }
    
    .list-view-item {
        display: grid;
        grid-template-columns: 1fr 100px 140px 120px;
        gap: 20px;
        padding: 12px 20px;
        border-bottom: 1px solid rgba(241, 245, 249, 0.8);
        cursor: pointer;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        align-items: center;
        min-height: 56px;
        position: relative;
    }
    
    .list-view-item::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 0;
        background: linear-gradient(135deg, #3b82f6 0%, #8b5cf6 100%);
        transition: width 0.3s ease;
    }
    
    .list-view-item:hover {
        background: linear-gradient(135deg, rgba(248, 250, 252, 0.8) 0%, rgba(241, 245, 249, 0.6) 100%);
        transform: translateX(4px);
    }
    
    .list-view-item:hover::before {
        width: 4px;
    }
    
    .list-view-item.selected {
        background: linear-gradient(135deg, rgba(239, 246, 255, 0.9) 0%, rgba(219, 234, 254, 0.7) 100%);
        border-left: 4px solid #3b82f6;
        transform: translateX(0);
    }
    
    .list-view-item.selected::before {
        width: 4px;
    }
    
    .dark .list-view-item {
        border-bottom-color: rgba(51, 65, 85, 0.8);
    }
    
    .dark .list-view-item:hover {
        background: linear-gradient(135deg, rgba(51, 65, 85, 0.8) 0%, rgba(30, 41, 59, 0.6) 100%);
    }
    
    .dark .list-view-item.selected {
        background: linear-gradient(135deg, rgba(30, 58, 138, 0.9) 0%, rgba(30, 64, 175, 0.7) 100%);
    }
    
    .list-view-item:last-child {
        border-bottom: none;
    }
    
    .list-item-name {
        display: flex;
        align-items: center;
        gap: 12px;
        font-weight: 600;
        color: #1e293b;
        min-width: 0;
        font-size: 14px;
    }
    
    .dark .list-item-name {
        color: #f1f5f9;
    }
    
    .list-item-icon {
        width: 24px;
        height: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        background: rgba(248, 250, 252, 0.8);
        border-radius: 6px;
        transition: all 0.2s ease;
    }
    
    .list-view-item:hover .list-item-icon {
        background: rgba(59, 130, 246, 0.1);
        transform: scale(1.1);
    }
    
    .dark .list-item-icon {
        background: rgba(51, 65, 85, 0.8);
    }
    
    .dark .list-view-item:hover .list-item-icon {
        background: rgba(59, 130, 246, 0.2);
    }
    
    .list-item-name-text {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    
    .list-item-size {
        font-size: 13px;
        font-weight: 500;
        color: #64748b;
        text-align: right;
    }
    
    .dark .list-item-size {
        color: #94a3b8;
    }
    
    .list-item-date {
        font-size: 13px;
        font-weight: 500;
        color: #64748b;
        text-align: right;
    }
    
    .dark .list-item-date {
        color: #94a3b8;
    }
    
    .list-item-type {
        font-size: 12px;
        font-weight: 600;
        color: #64748b;
        text-align: right;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .dark .list-item-type {
        color: #94a3b8;
    }
    
    /* Type-specific styling */
    .list-view-item[data-type="folder"] .list-item-icon {
        background: rgba(59, 130, 246, 0.1);
        color: #3b82f6;
    }
    
    .list-view-item[data-type="images"] .list-item-icon {
        background: rgba(16, 185, 129, 0.1);
        color: #10b981;
    }
    
    .list-view-item[data-type="documents"] .list-item-icon {
        background: rgba(245, 158, 11, 0.1);
        color: #f59e0b;
    }
    
    .list-view-item[data-type="videos"] .list-item-icon {
        background: rgba(239, 68, 68, 0.1);
        color: #ef4444;
    }
    
    .list-view-item[data-type="audio"] .list-item-icon {
        background: rgba(139, 92, 246, 0.1);
        color: #8b5cf6;
    }
    
    /* Modern Animations and Effects */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    @keyframes slideInRight {
        from {
            opacity: 0;
            transform: translateX(20px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }
    
    .grid-item {
        animation: fadeInUp 0.3s ease-out;
    }
    
    .list-view-item {
        animation: slideInRight 0.2s ease-out;
    }
    
    /* Staggered animations */
    .grid-item:nth-child(1) { animation-delay: 0.05s; }
    .grid-item:nth-child(2) { animation-delay: 0.1s; }
    .grid-item:nth-child(3) { animation-delay: 0.15s; }
    .grid-item:nth-child(4) { animation-delay: 0.2s; }
    .grid-item:nth-child(5) { animation-delay: 0.25s; }
    .grid-item:nth-child(6) { animation-delay: 0.3s; }
    .grid-item:nth-child(7) { animation-delay: 0.35s; }
    .grid-item:nth-child(8) { animation-delay: 0.4s; }
    
    /* Loading shimmer effect */
    .loading-shimmer {
        background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
        background-size: 200% 100%;
        animation: shimmer 1.5s infinite;
    }
    
    @keyframes shimmer {
        0% {
            background-position: -200% 0;
        }
        100% {
            background-position: 200% 0;
        }
    }
    
    /* Enhanced empty state */
    .empty-state {
        text-align: center;
        padding: 60px 20px;
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        border-radius: 16px;
        border: 2px dashed #cbd5e1;
    }
    
    .dark .empty-state {
        background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
        border-color: #475569;
    }
    
    .empty-state-icon {
        font-size: 64px;
        color: #94a3b8;
        margin-bottom: 16px;
        animation: float 3s ease-in-out infinite;
    }
    
    @keyframes float {
        0%, 100% {
            transform: translateY(0px);
        }
        50% {
            transform: translateY(-10px);
        }
    }
    
    /* Responsive adjustments */
    @media (min-width: 640px) {
        .grid-view-container {
            grid-template-columns: repeat(auto-fill, minmax(110px, 1fr));
        }
    }
    
    @media (min-width: 768px) {
        .grid-view-container {
            grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
        }
    }
    
    @media (min-width: 1024px) {
        .grid-view-container {
            grid-template-columns: repeat(auto-fill, minmax(130px, 1fr));
        }
    }
    
    @media (min-width: 1280px) {
        .grid-view-container {
            grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
        }
    }
    
    /* Mobile responsive for list view */
    @media (max-width: 768px) {
        .list-header {
            grid-template-columns: 1fr 80px;
            font-size: 11px;
            padding: 8px 12px;
        }
        
        .list-view-item {
            grid-template-columns: 1fr 80px;
            padding: 8px 12px;
        }
        
        .list-item-date,
        .list-item-type {
            display: none;
        }
        
        .grid-view-container {
            grid-template-columns: repeat(auto-fill, minmax(90px, 1fr));
            gap: 8px;
            padding: 12px;
        }
        
        .grid-item {
            min-height: 80px;
            padding: 12px 8px;
        }
        
        .grid-icon {
            font-size: 24px;
        }
        
        .grid-name {
            font-size: 10px;
        }
    }
</style>
@endsection

@section('content')
<div class="main-content-area" 
     ondrop="handleDrop(event)" 
     ondragover="handleDragOver(event)" 
     ondragenter="handleDragEnter(event)" 
     ondragleave="handleDragLeave(event)">
    <!-- Toolbar -->
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-6 space-y-4 sm:space-y-0">
        <!-- Left Side - Actions -->
        <div class="flex flex-wrap items-center space-x-3">
            <!-- Upload Button -->
            <button type="button" 
                    onclick="openUploadDialog()"
                    class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 flex items-center">
                <i class="fas fa-upload mr-2"></i>
                อัปโหลดไฟล์
            </button>
            
            <!-- Create Folder Button -->
            <button type="button" 
                    onclick="openCreateFolderDialog()"
                    class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 flex items-center">
                <i class="fas fa-folder-plus mr-2"></i>
                สร้างโฟลเดอร์
            </button>
            
            <!-- Refresh Button -->
            <button type="button" 
                    onclick="refreshMediaBrowser()"
                    class="bg-gray-600 text-white px-4 py-2 rounded-md hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 flex items-center">
                <i class="fas fa-sync-alt mr-2"></i>
                รีเฟรช
            </button>
            
            <!-- Laravel File Manager Button -->
            <button type="button" 
                    onclick="openLaravelFileManager()"
                    class="bg-purple-600 text-white px-4 py-2 rounded-md hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 flex items-center">
                <i class="fas fa-folder-open mr-2"></i>
                File Manager
            </button>
        </div>
        
        <!-- Right Side - Filters and View Toggle -->
        <div class="flex flex-wrap items-center space-x-3">
            <!-- View Toggle -->
            <div class="flex bg-gray-100 dark:bg-gray-700 rounded-md p-1">
                <button type="button" 
                        id="gridViewBtn"
                        onclick="switchView('grid')"
                        class="px-3 py-1 rounded text-sm font-medium transition-colors duration-200 bg-blue-600 text-white">
                    <i class="fas fa-th-large mr-1"></i>
                    Grid
                </button>
                <button type="button" 
                        id="listViewBtn"
                        onclick="switchView('list')"
                        class="px-3 py-1 rounded text-sm font-medium transition-colors duration-200 text-gray-600 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600">
                    <i class="fas fa-list mr-1"></i>
                    List
                </button>
            </div>
            
            <!-- Search -->
            <div class="relative">
                <input type="text" 
                       id="mediaSearch"
                       placeholder="ค้นหาไฟล์..." 
                       class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                       style="width: 250px;">
            </div>
            
            <!-- Type Filter -->
            <select id="mediaTypeFilter" 
                    class="block px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                <option value="all">ทุกประเภท</option>
                <option value="images">รูปภาพ</option>
                <option value="icons">ไอคอน</option>
                <option value="documents">เอกสาร</option>
                <option value="videos">วิดีโอ</option>
                <option value="audio">เสียง</option>
            </select>
        </div>
    </div>

    <!-- Breadcrumbs -->
    <div id="mediaBreadcrumbs" class="flex items-center space-x-2 mb-6 text-sm">
        <!-- Breadcrumbs will be populated here -->
    </div>

    <!-- File Upload Input (Hidden) -->
    <input type="file" 
           id="mediaFileInput" 
           multiple 
           class="hidden"
           accept="image/*,.ico,.pdf,.doc,.docx,.txt,.mp4,.avi,.mov,.wmv,.mp3,.wav,.ogg">

    <!-- Loading Indicator -->
    <div id="mediaLoading" class="text-center py-12 hidden">
        <i class="fas fa-spinner fa-spin text-4xl text-gray-400"></i>
        <p class="text-gray-500 mt-4">กำลังโหลด...</p>
    </div>

    <!-- Media Container -->
    <div id="mediaContainer" class="relative" 
         ondrop="handleDrop(event)" 
         ondragover="handleDragOver(event)" 
         ondragenter="handleDragEnter(event)" 
         ondragleave="handleDragLeave(event)">
        
        <!-- Grid View -->
        <div id="mediaGrid" class="grid-view-container">
            <!-- Media items will be populated here -->
        </div>
        
        <!-- List View -->
        <div id="mediaList" class="list-view-container hidden">
            <!-- List Header -->
            <div class="list-header">
                <div>ชื่อ</div>
                <div>ขนาด</div>
                <div>วันที่แก้ไข</div>
                <div>ประเภท</div>
            </div>
            <!-- Media items will be populated here -->
        </div>
        
        <!-- Drop Zone Overlay -->
        <div id="dropZoneOverlay" class="absolute inset-0 bg-blue-500 bg-opacity-20 border-4 border-dashed border-blue-500 rounded-lg flex items-center justify-center hidden z-10">
            <div class="text-center text-blue-700 dark:text-blue-300">
                <i class="fas fa-cloud-upload-alt text-6xl mb-4"></i>
                <h3 class="text-2xl font-bold mb-2">วางไฟล์ที่นี่</h3>
                <p class="text-lg">ลากไฟล์มาวางเพื่ออัปโหลด</p>
            </div>
        </div>
    </div>

    <!-- Empty State -->
    <div id="mediaEmpty" class="empty-state hidden">
        <div class="empty-state-icon">
            <i class="fas fa-folder-open"></i>
        </div>
        <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">ไม่มีไฟล์</h3>
        <p class="text-gray-500 dark:text-gray-400 mb-8 max-w-md mx-auto">เริ่มต้นด้วยการอัปโหลดไฟล์หรือสร้างโฟลเดอร์ใหม่</p>
        
        <!-- Drag & Drop Hint -->
        <div class="bg-blue-50 dark:bg-blue-900/30 border border-blue-200 dark:border-blue-700 rounded-xl p-6 mb-8 max-w-md mx-auto">
            <div class="flex items-center justify-center mb-3">
                <i class="fas fa-hand-paper text-blue-500 mr-2 text-lg"></i>
                <span class="text-blue-700 dark:text-blue-300 font-semibold">เคล็ดลับ</span>
            </div>
            <p class="text-blue-600 dark:text-blue-400 text-sm leading-relaxed">คุณสามารถลากไฟล์จากคอมพิวเตอร์มาวางในพื้นที่นี้เพื่ออัปโหลดได้เลย!</p>
        </div>
        
        <div class="flex flex-col sm:flex-row justify-center gap-4">
            <button type="button" 
                    onclick="openUploadDialog()"
                    class="bg-gradient-to-r from-blue-600 to-blue-700 text-white px-8 py-3 rounded-xl hover:from-blue-700 hover:to-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transform hover:scale-105 transition-all duration-200 shadow-lg">
                <i class="fas fa-upload mr-2"></i>
                อัปโหลดไฟล์
            </button>
            <button type="button" 
                    onclick="openCreateFolderDialog()"
                    class="bg-gradient-to-r from-green-600 to-green-700 text-white px-8 py-3 rounded-xl hover:from-green-700 hover:to-green-800 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transform hover:scale-105 transition-all duration-200 shadow-lg">
                <i class="fas fa-folder-plus mr-2"></i>
                สร้างโฟลเดอร์
            </button>
        </div>
    </div>
    
    <!-- Global Drop Zone Overlay -->
    <div id="globalDropZoneOverlay" class="fixed inset-0 bg-blue-500 bg-opacity-20 border-4 border-dashed border-blue-500 flex items-center justify-center hidden z-50">
        <div class="text-center text-blue-700 dark:text-blue-300 bg-white dark:bg-gray-800 rounded-lg p-8 shadow-lg">
            <i class="fas fa-cloud-upload-alt text-8xl mb-6"></i>
            <h3 class="text-3xl font-bold mb-4">วางไฟล์ที่นี่</h3>
            <p class="text-xl mb-2">ลากไฟล์มาวางเพื่ออัปโหลด</p>
            <p class="text-sm text-gray-500">รองรับไฟล์ขนาดไม่เกิน 10MB</p>
        </div>
    </div>
</div>

<!-- Upload Progress Indicator -->
<div id="uploadProgress" class="upload-progress hidden">
    <div class="flex justify-between items-center mb-2">
        <h4 class="font-semibold text-gray-900">กำลังอัปโหลด...</h4>
        <button onclick="closeUploadProgress()" class="text-gray-400 hover:text-gray-600">
            <i class="fas fa-times"></i>
        </button>
    </div>
    <div id="uploadProgressItems"></div>
</div>

<!-- Create Folder Modal -->
<div id="createFolderModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 hidden">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white dark:bg-gray-800">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">
                <i class="fas fa-folder-plus mr-2"></i>
                สร้างโฟลเดอร์ใหม่
            </h3>
            <div class="mb-4">
                <label for="folderName" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    ชื่อโฟลเดอร์
                </label>
                <input type="text" 
                       id="folderName"
                       class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                       placeholder="ชื่อโฟลเดอร์">
            </div>
            <div class="flex justify-end space-x-3">
                <button type="button"
                        onclick="closeCreateFolderModal()"
                        class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                    ยกเลิก
                </button>
                <button type="button"
                        onclick="createFolder()"
                        class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                    สร้าง
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// Media Browser JavaScript
let currentMediaPath = '{{ $currentPath ?? "" }}';
let selectedMediaFile = null;
let currentView = 'grid'; // 'grid' or 'list'

// Switch View Function
function switchView(view) {
    currentView = view;
    
    const gridBtn = document.getElementById('gridViewBtn');
    const listBtn = document.getElementById('listViewBtn');
    const gridContainer = document.getElementById('mediaGrid');
    const listContainer = document.getElementById('mediaList');
    
    if (view === 'grid') {
        gridBtn.className = 'px-3 py-1 rounded text-sm font-medium transition-colors duration-200 bg-blue-600 text-white';
        listBtn.className = 'px-3 py-1 rounded text-sm font-medium transition-colors duration-200 text-gray-600 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600';
        gridContainer.classList.remove('hidden');
        listContainer.classList.add('hidden');
    } else {
        listBtn.className = 'px-3 py-1 rounded text-sm font-medium transition-colors duration-200 bg-blue-600 text-white';
        gridBtn.className = 'px-3 py-1 rounded text-sm font-medium transition-colors duration-200 text-gray-600 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600';
        listContainer.classList.remove('hidden');
        gridContainer.classList.add('hidden');
    }
    
    // Update view without reloading data
    const currentData = window.currentMediaData;
    if (currentData) {
        updateMediaGrid(currentData);
    }
}

// Load Media Browser Data
function loadMediaBrowser() {
    const loading = document.getElementById('mediaLoading');
    const grid = document.getElementById('mediaGrid');
    const list = document.getElementById('mediaList');
    const empty = document.getElementById('mediaEmpty');
    
    loading.classList.remove('hidden');
    grid.classList.add('hidden');
    list.classList.add('hidden');
    empty.classList.add('hidden');
    
    const search = document.getElementById('mediaSearch').value;
    const type = document.getElementById('mediaTypeFilter').value;
    
    let url = `{{ route('backend.media-browser.index') }}?ajax=1`;
    const params = [];
    
    if (currentMediaPath) params.push(`path=${encodeURIComponent(currentMediaPath)}`);
    if (search) params.push(`search=${encodeURIComponent(search)}`);
    if (type !== 'all') params.push(`type=${encodeURIComponent(type)}`);
    
    if (params.length > 0) {
        url += '&' + params.join('&');
    }
    
    fetch(url, {
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        loading.classList.add('hidden');
        
        if (data.success) {
            updateMediaGrid(data.data);
            updateBreadcrumbs(data.data.breadcrumbs);
        } else {
            showMediaError('เกิดข้อผิดพลาดในการโหลดข้อมูล');
        }
    })
    .catch(error => {
        loading.classList.add('hidden');
        console.error('Media browser error:', error);
        showMediaError('เกิดข้อผิดพลาดในการโหลดข้อมูล');
    });
}

// Update Media Grid/List
function updateMediaGrid(data) {
    // Store current data globally for view switching
    window.currentMediaData = data;
    
    const grid = document.getElementById('mediaGrid');
    const list = document.getElementById('mediaList');
    const empty = document.getElementById('mediaEmpty');
    
    grid.innerHTML = '';
    list.innerHTML = '';
    
    // Add folders
    data.folders.forEach(folder => {
        const folderElement = currentView === 'grid' ? 
            createFolderElement(folder) : 
            createListFolderElement(folder);
        
        if (currentView === 'grid') {
            grid.appendChild(folderElement);
        } else {
            list.appendChild(folderElement);
        }
    });
    
    // Add files
    data.files.forEach(file => {
        const fileElement = currentView === 'grid' ? 
            createFileElement(file) : 
            createListFileElement(file);
        
        if (currentView === 'grid') {
            grid.appendChild(fileElement);
        } else {
            list.appendChild(fileElement);
        }
    });
    
    if (data.folders.length === 0 && data.files.length === 0) {
        empty.classList.remove('hidden');
        grid.classList.add('hidden');
        list.classList.add('hidden');
    } else {
        empty.classList.add('hidden');
        if (currentView === 'grid') {
            grid.classList.remove('hidden');
            list.classList.add('hidden');
        } else {
            list.classList.remove('hidden');
            grid.classList.add('hidden');
        }
    }
}

// Create Folder Element
function createFolderElement(folder) {
    const div = document.createElement('div');
    div.className = 'grid-item';
    div.setAttribute('data-type', 'folder');
    div.onclick = () => navigateToFolder(folder.path);
    
    div.innerHTML = `
        <div class="grid-icon">
            <i class="fas fa-folder text-blue-500"></i>
        </div>
        <div class="grid-name" title="${folder.name}">${folder.name}</div>
    `;
    
    return div;
}

// Create File Element
function createFileElement(file) {
    const div = document.createElement('div');
    div.className = 'grid-item';
    div.setAttribute('data-type', file.type);
    div.onclick = () => selectFile(file);
    
    const icon = getFileIcon(file.type, file.extension);
    const size = formatFileSize(file.size);
    
    div.innerHTML = `
        <div class="grid-icon">
            ${file.type === 'images' ? 
                `<img src="${file.url}" alt="${file.name}" class="w-8 h-8 object-cover rounded-lg shadow-sm">` : 
                `<i class="${icon} text-gray-500"></i>`
            }
        </div>
        <div class="grid-name" title="${file.name}">${file.name}</div>
    `;
    
    return div;
}

// Create List Folder Element
function createListFolderElement(folder) {
    const div = document.createElement('div');
    div.className = 'list-view-item';
    div.setAttribute('data-type', 'folder');
    div.onclick = () => navigateToFolder(folder.path);
    
    const modifiedDate = folder.modified_at ? new Date(folder.modified_at).toLocaleDateString('th-TH') : '-';
    
    div.innerHTML = `
        <div class="list-item-name">
            <div class="list-item-icon">
                <i class="fas fa-folder"></i>
            </div>
            <div class="list-item-name-text">${folder.name}</div>
        </div>
        <div class="list-item-size">-</div>
        <div class="list-item-date">${modifiedDate}</div>
        <div class="list-item-type">โฟลเดอร์</div>
    `;
    
    return div;
}

// Create List File Element
function createListFileElement(file) {
    const div = document.createElement('div');
    div.className = 'list-view-item';
    div.setAttribute('data-type', file.type);
    div.onclick = () => selectFile(file);
    
    const icon = getFileIcon(file.type, file.extension);
    const size = formatFileSize(file.size);
    const modifiedDate = file.modified_at ? new Date(file.modified_at).toLocaleDateString('th-TH') : '-';
    
    div.innerHTML = `
        <div class="list-item-name">
            <div class="list-item-icon">
                ${file.type === 'images' ? 
                    `<img src="${file.url}" alt="${file.name}" class="w-5 h-5 object-cover rounded">` : 
                    `<i class="${icon}"></i>`
                }
            </div>
            <div class="list-item-name-text">${file.name}</div>
        </div>
        <div class="list-item-size">${size}</div>
        <div class="list-item-date">${modifiedDate}</div>
        <div class="list-item-type">${file.type}</div>
    `;
    
    return div;
}

// Get File Icon
function getFileIcon(type, extension) {
    const icons = {
        'images': 'fas fa-image',
        'icons': 'fas fa-star',
        'documents': 'fas fa-file-alt',
        'videos': 'fas fa-video',
        'audio': 'fas fa-music',
        'other': 'fas fa-file'
    };
    
    return icons[type] || icons['other'];
}

// Format File Size
function formatFileSize(bytes) {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
}

// Navigate to Folder
function navigateToFolder(path) {
    currentMediaPath = path;
    loadMediaBrowser();
}

// Select File
function selectFile(file) {
    selectedMediaFile = file;
    
    // Update UI to show selection
    if (currentView === 'grid') {
        document.querySelectorAll('#mediaGrid .grid-item').forEach(el => {
            el.classList.remove('selected');
        });
        event.currentTarget.classList.add('selected');
    } else {
        document.querySelectorAll('#mediaList .list-view-item').forEach(el => {
            el.classList.remove('selected');
        });
        event.currentTarget.classList.add('selected');
    }
}

// Update Breadcrumbs
function updateBreadcrumbs(breadcrumbs) {
    const container = document.getElementById('mediaBreadcrumbs');
    container.innerHTML = '';
    
    breadcrumbs.forEach((crumb, index) => {
        const span = document.createElement('span');
        
        if (index === breadcrumbs.length - 1) {
            span.className = 'text-gray-900 dark:text-white font-medium';
            span.textContent = crumb.name;
        } else {
            span.className = 'text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 cursor-pointer';
            span.textContent = crumb.name;
            span.onclick = () => navigateToFolder(crumb.path);
        }
        
        container.appendChild(span);
        
        if (index < breadcrumbs.length - 1) {
            const separator = document.createElement('span');
            separator.className = 'text-gray-400 mx-2';
            separator.innerHTML = '<i class="fas fa-chevron-right"></i>';
            container.appendChild(separator);
        }
    });
}

// Open Upload Dialog
function openUploadDialog() {
    document.getElementById('mediaFileInput').click();
}

// Handle File Upload
document.getElementById('mediaFileInput').addEventListener('change', function(e) {
    const files = e.target.files;
    if (files.length > 0) {
        uploadFiles(files);
    }
});

// Upload Files
function uploadFiles(files) {
    const formData = new FormData();
    
    Array.from(files).forEach(file => {
        formData.append('files[]', file);
    });
    
    if (currentMediaPath) {
        formData.append('path', currentMediaPath);
    }
    
    // Show upload progress
    showUploadProgress(files);
    
    fetch('{{ route("backend.media-browser.upload") }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        hideUploadProgress();
        
        if (data.success) {
            const fileCount = data.files ? data.files.length : 1;
            Swal.fire({
                title: 'สำเร็จ!',
                text: `อัปโหลดไฟล์ ${fileCount} ไฟล์เรียบร้อยแล้ว`,
                icon: 'success',
                confirmButtonText: 'ตกลง'
            });
            loadMediaBrowser();
        } else {
            Swal.fire({
                title: 'เกิดข้อผิดพลาด!',
                text: data.message || 'เกิดข้อผิดพลาดในการอัปโหลด',
                icon: 'error',
                confirmButtonText: 'ตกลง'
            });
        }
    })
    .catch(error => {
        hideUploadProgress();
        console.error('Upload error:', error);
        Swal.fire({
            title: 'เกิดข้อผิดพลาด!',
            text: 'เกิดข้อผิดพลาดในการอัปโหลด',
            icon: 'error',
            confirmButtonText: 'ตกลง'
        });
    });
}

// Open Create Folder Dialog
function openCreateFolderDialog() {
    document.getElementById('createFolderModal').classList.remove('hidden');
    document.getElementById('folderName').value = '';
}

// Close Create Folder Modal
function closeCreateFolderModal() {
    document.getElementById('createFolderModal').classList.add('hidden');
}

// Create Folder
function createFolder() {
    const folderName = document.getElementById('folderName').value.trim();
    
    if (!folderName) {
        Swal.fire({
            title: 'ข้อมูลไม่ครบถ้วน!',
            text: 'กรุณากรอกชื่อโฟลเดอร์',
            icon: 'warning',
            confirmButtonText: 'ตกลง'
        });
        return;
    }
    
    const formData = new FormData();
    formData.append('name', folderName);
    if (currentMediaPath) {
        formData.append('path', currentMediaPath);
    }
    
    fetch('{{ route("backend.media-browser.create-folder") }}', {
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
                title: 'สำเร็จ!',
                text: 'สร้างโฟลเดอร์เรียบร้อยแล้ว',
                icon: 'success',
                confirmButtonText: 'ตกลง'
            });
            closeCreateFolderModal();
            loadMediaBrowser();
        } else {
            Swal.fire({
                title: 'เกิดข้อผิดพลาด!',
                text: data.message || 'เกิดข้อผิดพลาดในการสร้างโฟลเดอร์',
                icon: 'error',
                confirmButtonText: 'ตกลง'
            });
        }
    })
    .catch(error => {
        console.error('Create folder error:', error);
        Swal.fire({
            title: 'เกิดข้อผิดพลาด!',
            text: 'เกิดข้อผิดพลาดในการสร้างโฟลเดอร์',
            icon: 'error',
            confirmButtonText: 'ตกลง'
        });
    });
}

// Refresh Media Browser
function refreshMediaBrowser() {
    loadMediaBrowser();
}

// Open Laravel File Manager
function openLaravelFileManager() {
    const lfmUrl = '{{ route("backend.media-browser.lfm") }}';
    
    // Open in new window/tab
    const lfmWindow = window.open(lfmUrl, 'LaravelFileManager', 'width=1200,height=800,scrollbars=yes,resizable=yes');
    
    // Focus the new window
    if (lfmWindow) {
        lfmWindow.focus();
    }
}

// Show Media Error
function showMediaError(message) {
    const empty = document.getElementById('mediaEmpty');
    empty.innerHTML = `
        <i class="fas fa-exclamation-triangle text-6xl text-red-400 mb-6"></i>
        <h3 class="text-xl font-medium text-gray-900 dark:text-white mb-4">เกิดข้อผิดพลาด</h3>
        <p class="text-gray-500 dark:text-gray-400">${message}</p>
    `;
    empty.classList.remove('hidden');
}

// Drag & Drop Functions
function handleDragOver(e) {
    e.preventDefault();
    e.dataTransfer.dropEffect = 'copy';
}

function handleDragEnter(e) {
    e.preventDefault();
    
    // Show global drop zone overlay
    const globalDropZone = document.getElementById('globalDropZoneOverlay');
    const localDropZone = document.getElementById('dropZoneOverlay');
    
    if (globalDropZone) {
        globalDropZone.classList.remove('hidden');
    }
    if (localDropZone) {
        localDropZone.classList.remove('hidden');
    }
    
    // Add visual feedback
    document.body.style.cursor = 'copy';
}

function handleDragLeave(e) {
    e.preventDefault();
    
    // Only hide if we're leaving the entire drop zone
    if (!e.currentTarget.contains(e.relatedTarget)) {
        const globalDropZone = document.getElementById('globalDropZoneOverlay');
        const localDropZone = document.getElementById('dropZoneOverlay');
        
        if (globalDropZone) {
            globalDropZone.classList.add('hidden');
        }
        if (localDropZone) {
            localDropZone.classList.add('hidden');
        }
        
        document.body.style.cursor = 'default';
    }
}

function handleDrop(e) {
    e.preventDefault();
    
    // Hide all drop zones
    const globalDropZone = document.getElementById('globalDropZoneOverlay');
    const localDropZone = document.getElementById('dropZoneOverlay');
    
    if (globalDropZone) {
        globalDropZone.classList.add('hidden');
    }
    if (localDropZone) {
        localDropZone.classList.add('hidden');
    }
    
    document.body.style.cursor = 'default';
    
    const files = e.dataTransfer.files;
    if (files.length > 0) {
        console.log('Files dropped:', files.length); // Debug log
        
        // Validate files
        const validFiles = Array.from(files).filter(file => {
            const maxSize = 10 * 1024 * 1024; // 10MB
            if (file.size > maxSize) {
                Swal.fire({
                    title: 'ไฟล์ใหญ่เกินไป!',
                    text: `ไฟล์ ${file.name} มีขนาด ${formatFileSize(file.size)} ซึ่งเกินขีดจำกัด 10MB`,
                    icon: 'warning',
                    confirmButtonText: 'ตกลง'
                });
                return false;
            }
            return true;
        });
        
        if (validFiles.length > 0) {
            console.log('Valid files:', validFiles.length); // Debug log
            uploadFiles(validFiles);
        }
    }
}

// Add drag and drop support to the entire page
document.addEventListener('DOMContentLoaded', function() {
    console.log('Media Browser initialized'); // Debug log
    
    // Prevent default drag behaviors on the entire page
    document.addEventListener('dragover', function(e) {
        e.preventDefault();
        console.log('Drag over detected'); // Debug log
    });
    
    document.addEventListener('drop', function(e) {
        e.preventDefault();
        console.log('Drop detected'); // Debug log
    });
    
    // Add visual feedback when dragging files over the page
    document.addEventListener('dragenter', function(e) {
        console.log('Drag enter detected', e.dataTransfer.types); // Debug log
        if (e.dataTransfer.types.includes('Files')) {
            document.body.classList.add('drag-over');
            console.log('Files detected, adding drag-over class'); // Debug log
        }
    });
    
    document.addEventListener('dragleave', function(e) {
        console.log('Drag leave detected'); // Debug log
        if (!e.relatedTarget) {
            document.body.classList.remove('drag-over');
        }
    });
    
    document.addEventListener('drop', function(e) {
        console.log('Drop event on document'); // Debug log
        document.body.classList.remove('drag-over');
    });
    
    // Initialize Media Browser
    loadMediaBrowser();
    
    // Search
    document.getElementById('mediaSearch').addEventListener('input', function() {
        clearTimeout(this.searchTimeout);
        this.searchTimeout = setTimeout(() => {
            loadMediaBrowser();
        }, 300);
    });
    
    // Type Filter
    document.getElementById('mediaTypeFilter').addEventListener('change', function() {
        loadMediaBrowser();
    });
    
    // Keyboard shortcuts
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            if (!document.getElementById('createFolderModal').classList.contains('hidden')) {
                closeCreateFolderModal();
            }
        }
    });
});

// Upload Progress Functions
function showUploadProgress(files) {
    const progressContainer = document.getElementById('uploadProgress');
    const progressItems = document.getElementById('uploadProgressItems');
    
    progressItems.innerHTML = '';
    
    Array.from(files).forEach((file, index) => {
        const item = document.createElement('div');
        item.className = 'upload-progress-item';
        item.id = `upload-item-${index}`;
        item.innerHTML = `
            <div class="flex-1">
                <div class="flex items-center">
                    <i class="fas fa-file mr-2 text-gray-500"></i>
                    <span class="text-sm text-gray-700 truncate">${file.name}</span>
                </div>
                <div class="upload-progress-bar">
                    <div class="upload-progress-fill" id="progress-${index}" style="width: 0%"></div>
                </div>
            </div>
        `;
        progressItems.appendChild(item);
    });
    
    progressContainer.classList.remove('hidden');
}

function hideUploadProgress() {
    const progressContainer = document.getElementById('uploadProgress');
    progressContainer.classList.add('hidden');
}

function closeUploadProgress() {
    hideUploadProgress();
}

function updateUploadProgress(fileIndex, percentage) {
    const progressBar = document.getElementById(`progress-${fileIndex}`);
    if (progressBar) {
        progressBar.style.width = `${percentage}%`;
    }
}
</script>
@endsection