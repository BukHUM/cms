@extends('backend.layouts.app')

@section('title', 'Media Browser')
@section('page-title', 'Media Browser')
@section('page-description', 'จัดการไฟล์สื่อและรูปภาพด้วย Spatie Media Library')

@section('content')
@if(session('error'))
<div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6 flex items-center justify-between">
    <div class="flex items-center">
        <i class="fas fa-exclamation-triangle mr-2"></i>
        {{ session('error') }}
    </div>
    <button type="button" class="text-red-500 hover:text-red-700" onclick="this.parentElement.parentElement.remove()">
        <i class="fas fa-times"></i>
    </button>
</div>
@endif

<div class="space-y-6">
    <!-- Drag and Drop Zone -->
    <div id="dropZone" class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center transition-colors duration-200 hover:border-blue-400 hover:bg-blue-50">
        <div class="space-y-4">
            <div class="mx-auto w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center">
                <i class="fas fa-cloud-upload-alt text-gray-400 text-2xl"></i>
            </div>
            <div>
                <p class="text-lg font-medium text-gray-900">ลากไฟล์มาวางที่นี่เพื่ออัปโหลด</p>
                <p class="text-sm text-gray-500 mt-1">หรือ <button type="button" class="text-blue-600 hover:text-blue-800 font-medium" onclick="openUploadModal()">คลิกเพื่อเลือกไฟล์</button></p>
            </div>
            <div class="mt-4">
                <button type="button" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center transition-colors duration-200 mx-auto" onclick="openUploadModal()">
                    <i class="fas fa-plus mr-2"></i>
                    เพิ่มไฟล์ใหม่
                </button>
            </div>
            <div class="text-xs text-gray-400">
                รองรับไฟล์รูปภาพ, PDF, และเอกสาร Word
            </div>
        </div>
        
        <!-- Upload Progress for Drag & Drop -->
        <div id="dropZoneProgress" class="hidden mt-4">
            <div class="w-full bg-gray-200 rounded-full h-2.5">
                <div id="dropZoneProgressBar" class="bg-blue-600 h-2.5 rounded-full transition-all duration-300" style="width: 0%"></div>
            </div>
            <p class="text-sm text-gray-600 mt-2">กำลังอัปโหลด...</p>
        </div>
    </div>

    <!-- Main Content -->
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        <!-- Sidebar -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-sm">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <i class="fas fa-folder-tree mr-2 text-blue-600"></i>
                        โฟลเดอร์
                    </h3>
                </div>
                <div class="p-6">
                    <div class="space-y-2">
                        @forelse($directories as $directory)
                        <div class="flex items-center p-3 rounded-lg hover:bg-gray-50 cursor-pointer transition-colors duration-200" data-path="{{ $directory['path'] }}">
                            <i class="fas fa-folder text-yellow-500 mr-3"></i>
                            <div class="flex-1">
                                <p class="font-medium text-gray-900">{{ \Carbon\Carbon::parse($directory['name'])->format('d/m/Y') }}</p>
                                <p class="text-sm text-gray-500">{{ number_format($directory['size'] / 1024, 2) }} KB</p>
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-8">
                            <i class="fas fa-folder-open text-gray-300 text-4xl mb-3"></i>
                            <p class="text-gray-500">ไม่มีโฟลเดอร์</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content Area -->
        <div class="lg:col-span-3">
            <div class="bg-white rounded-lg shadow-sm">
                <div class="px-6 py-4 border-b border-gray-200">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                            <i class="fas fa-images mr-2 text-blue-600"></i>
                            ไฟล์สื่อ
                        </h3>
                        <div class="flex gap-2">
                            <div class="flex bg-gray-100 rounded-lg p-1">
                                <button type="button" class="px-3 py-1 rounded-md text-gray-600 hover:text-blue-600" id="gridViewBtn">
                                    <i class="fas fa-th"></i>
                                </button>
                                <button type="button" class="px-3 py-1 rounded-md bg-white shadow-sm text-blue-600 font-medium" id="listViewBtn">
                                    <i class="fas fa-list"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="p-6">
                    @if($mediaFiles->count() > 0)
                        <!-- Grid View -->
                        <div id="gridView" class="hidden grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                            @foreach($mediaFiles as $media)
                            <div class="bg-white border border-gray-200 rounded-lg overflow-hidden hover:shadow-lg transition-all duration-300 group relative">
                                <div class="aspect-square bg-gray-50 flex items-center justify-center overflow-hidden">
                                    @if(str_starts_with($media->mime_type, 'image/'))
                                        <img src="{{ $media->getUrl() }}" alt="{{ $media->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                    @else
                                        <div class="text-center">
                                            <i class="fas fa-file text-gray-400 text-4xl mb-2"></i>
                                            <p class="text-sm text-gray-500">{{ pathinfo($media->name, PATHINFO_EXTENSION) }}</p>
                                        </div>
                                    @endif
                                </div>
                                <div class="p-4">
                                    <h4 class="font-medium text-gray-900 truncate mb-1" title="{{ $media->name }}">
                                        {{ Str::limit($media->name, 20) }}
                                    </h4>
                                    <p class="text-sm text-gray-500">{{ number_format($media->size / 1024, 2) }} KB</p>
                                </div>
                                <div class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                                    <div class="flex gap-1">
                                        <button class="bg-blue-500 hover:bg-blue-600 text-white p-2 rounded-full text-sm" onclick="viewMedia({{ $media->id }})" title="ดู">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="bg-green-500 hover:bg-green-600 text-white p-2 rounded-full text-sm" onclick="downloadMedia({{ $media->id }})" title="ดาวน์โหลด">
                                            <i class="fas fa-download"></i>
                                        </button>
                                        <button class="bg-red-500 hover:bg-red-600 text-white p-2 rounded-full text-sm" onclick="deleteMedia({{ $media->id }})" title="ลบ">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>

                        <!-- List View -->
                        <div id="listView">
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ชื่อไฟล์</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ประเภท</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ขนาด</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">วันที่สร้าง</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">การดำเนินการ</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($mediaFiles as $media)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    @if(str_starts_with($media->mime_type, 'image/'))
                                                        <img src="{{ $media->getUrl() }}" alt="{{ $media->name }}" class="h-10 w-10 rounded-lg object-cover mr-3">
                                                    @else
                                                        <div class="h-10 w-10 bg-gray-100 rounded-lg flex items-center justify-center mr-3">
                                                            <i class="fas fa-file text-gray-400"></i>
                                                        </div>
                                                    @endif
                                                    <span class="text-sm font-medium text-gray-900">{{ $media->name }}</span>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                                    {{ $media->mime_type }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ number_format($media->size / 1024, 2) }} KB
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $media->created_at->format('d/m/Y H:i') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <div class="flex gap-2">
                                                    <button class="text-blue-600 hover:text-blue-900" onclick="viewMedia({{ $media->id }})" title="ดู">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                    <button class="text-green-600 hover:text-green-900" onclick="downloadMedia({{ $media->id }})" title="ดาวน์โหลด">
                                                        <i class="fas fa-download"></i>
                                                    </button>
                                                    <button class="text-red-600 hover:text-red-900" onclick="deleteMedia({{ $media->id }})" title="ลบ">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    @else
                        <div class="text-center py-12">
                            <i class="fas fa-images text-gray-300 text-6xl mb-4"></i>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">ไม่มีไฟล์สื่อ</h3>
                            <p class="text-gray-500 mb-6">เริ่มต้นด้วยการอัปโหลดไฟล์แรกของคุณ</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Upload Modal -->
<div id="uploadModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                    <i class="fas fa-upload mr-2 text-blue-600"></i>
                    อัปโหลดไฟล์
                </h3>
                <button type="button" class="text-gray-400 hover:text-gray-600" onclick="closeUploadModal()">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
        </div>
        <div class="px-6 py-4">
            <form id="uploadForm" enctype="multipart/form-data">
                @csrf
                <div class="mb-6">
                    <label for="mediaFiles" class="block text-sm font-medium text-gray-700 mb-2">เลือกไฟล์</label>
                    <input type="file" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 border border-gray-300 rounded-lg cursor-pointer bg-gray-50" id="mediaFiles" name="media[]" multiple accept="image/*,application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document">
                    <p class="mt-1 text-sm text-gray-500">รองรับไฟล์รูปภาพ, PDF, และเอกสาร Word</p>
                </div>
                <div class="mb-6">
                    <label for="collection" class="block text-sm font-medium text-gray-700 mb-2">คอลเลกชัน</label>
                    <select class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" id="collection" name="collection">
                        <option value="default">Default</option>
                        <option value="images">Images</option>
                        <option value="documents">Documents</option>
                    </select>
                </div>
            </form>
            <div id="uploadProgress" class="hidden">
                <div class="w-full bg-gray-200 rounded-full h-2.5">
                    <div class="bg-blue-600 h-2.5 rounded-full transition-all duration-300" style="width: 0%"></div>
                </div>
                <p class="text-sm text-gray-600 mt-2">กำลังอัปโหลด...</p>
            </div>
        </div>
        <div class="px-6 py-4 border-t border-gray-200 flex justify-end gap-3">
            <button type="button" class="px-4 py-2 text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors duration-200" onclick="closeUploadModal()">ยกเลิก</button>
            <button type="button" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors duration-200" onclick="uploadFiles()">อัปโหลด</button>
        </div>
    </div>
</div>

<!-- Media Preview Modal -->
<div id="mediaPreviewModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-xl max-w-6xl w-full max-h-[90vh] overflow-y-auto">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900" id="mediaPreviewModalLabel">ตัวอย่างไฟล์</h3>
                <button type="button" class="text-gray-400 hover:text-gray-600" onclick="closeMediaPreviewModal()">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
        </div>
        <div class="px-6 py-4 text-center">
            <div id="mediaPreviewContent">
                <!-- Content will be loaded here -->
            </div>
        </div>
        <div class="px-6 py-4 border-t border-gray-200 flex justify-end gap-3">
            <button type="button" class="px-4 py-2 text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors duration-200" onclick="closeMediaPreviewModal()">ปิด</button>
            <button type="button" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors duration-200" onclick="downloadCurrentMedia()">ดาวน์โหลด</button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let currentMediaId = null;

// Drag and Drop functionality
document.addEventListener('DOMContentLoaded', function() {
    const dropZone = document.getElementById('dropZone');
    
    // Prevent default drag behaviors
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, preventDefaults, false);
        document.body.addEventListener(eventName, preventDefaults, false);
    });
    
    // Highlight drop zone when item is dragged over it
    ['dragenter', 'dragover'].forEach(eventName => {
        dropZone.addEventListener(eventName, highlight, false);
    });
    
    ['dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, unhighlight, false);
    });
    
    // Handle dropped files
    dropZone.addEventListener('drop', handleDrop, false);
    
    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }
    
    function highlight(e) {
        dropZone.classList.add('border-blue-500', 'bg-blue-100');
        dropZone.classList.remove('border-gray-300');
    }
    
    function unhighlight(e) {
        dropZone.classList.remove('border-blue-500', 'bg-blue-100');
        dropZone.classList.add('border-gray-300');
    }
    
    function handleDrop(e) {
        const dt = e.dataTransfer;
        const files = dt.files;
        
        if (files.length > 0) {
            uploadFilesFromDrop(files);
        }
    }
});

function uploadFilesFromDrop(files) {
    const formData = new FormData();
    
    // Add files to FormData
    for (let i = 0; i < files.length; i++) {
        formData.append('media[]', files[i]);
    }
    
    // Add collection (default)
    formData.append('collection', 'default');
    
    // Add CSRF token
    formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
    
    // Show progress in drop zone
    const dropZoneProgress = document.getElementById('dropZoneProgress');
    const dropZoneProgressBar = document.getElementById('dropZoneProgressBar');
    
    dropZoneProgress.classList.remove('hidden');
    
    // Simulate progress (since we don't have real progress from server)
    let progress = 0;
    const progressInterval = setInterval(() => {
        progress += Math.random() * 15;
        if (progress > 90) progress = 90;
        dropZoneProgressBar.style.width = progress + '%';
    }, 200);
    
    // Upload files
    fetch('/backend/media-browser/upload', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        clearInterval(progressInterval);
        dropZoneProgressBar.style.width = '100%';
        
        setTimeout(() => {
            if (data.success) {
                location.reload();
            } else {
                alert('เกิดข้อผิดพลาดในการอัปโหลด: ' + data.message);
                dropZoneProgress.classList.add('hidden');
            }
        }, 500);
    })
    .catch(error => {
        clearInterval(progressInterval);
        console.error('Error:', error);
        alert('เกิดข้อผิดพลาดในการอัปโหลด');
        dropZoneProgress.classList.add('hidden');
    });
}

// Modal functions
function openUploadModal() {
    document.getElementById('uploadModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeUploadModal() {
    document.getElementById('uploadModal').classList.add('hidden');
    document.body.style.overflow = '';
}

function closeMediaPreviewModal() {
    document.getElementById('mediaPreviewModal').classList.add('hidden');
    document.body.style.overflow = '';
}

// View switching with localStorage
function switchToGridView() {
    const gridView = document.getElementById('gridView');
    const listView = document.getElementById('listView');
    const gridViewBtn = document.getElementById('gridViewBtn');
    const listViewBtn = document.getElementById('listViewBtn');
    
    if (gridView && listView && gridViewBtn && listViewBtn) {
        gridView.classList.remove('hidden');
        listView.classList.add('hidden');
        gridViewBtn.classList.add('bg-white', 'shadow-sm', 'text-blue-600', 'font-medium');
        gridViewBtn.classList.remove('text-gray-600');
        listViewBtn.classList.remove('bg-white', 'shadow-sm', 'text-blue-600', 'font-medium');
        listViewBtn.classList.add('text-gray-600');
        localStorage.setItem('mediaBrowserView', 'grid');
    }
}

function switchToListView() {
    const gridView = document.getElementById('gridView');
    const listView = document.getElementById('listView');
    const gridViewBtn = document.getElementById('gridViewBtn');
    const listViewBtn = document.getElementById('listViewBtn');
    
    if (gridView && listView && gridViewBtn && listViewBtn) {
        gridView.classList.add('hidden');
        listView.classList.remove('hidden');
        listViewBtn.classList.add('bg-white', 'shadow-sm', 'text-blue-600', 'font-medium');
        listViewBtn.classList.remove('text-gray-600');
        gridViewBtn.classList.remove('bg-white', 'shadow-sm', 'text-blue-600', 'font-medium');
        gridViewBtn.classList.add('text-gray-600');
        localStorage.setItem('mediaBrowserView', 'list');
    }
}

// Initialize view based on localStorage
function initializeView() {
    const savedView = localStorage.getItem('mediaBrowserView');
    if (savedView === 'grid') {
        switchToGridView();
    } else {
        switchToListView(); // Default to list view
    }
}

// Event listeners
const gridViewBtn = document.getElementById('gridViewBtn');
const listViewBtn = document.getElementById('listViewBtn');

if (gridViewBtn) {
    gridViewBtn.addEventListener('click', switchToGridView);
}

if (listViewBtn) {
    listViewBtn.addEventListener('click', switchToListView);
}

// Initialize view after all elements are ready
initializeView();

// Media actions
function viewMedia(mediaId) {
    currentMediaId = mediaId;
    // Load media preview
    fetch(`/backend/media-browser/media/${mediaId}`)
        .then(response => response.json())
        .then(data => {
            const content = document.getElementById('mediaPreviewContent');
            if (data.mime_type.startsWith('image/')) {
                content.innerHTML = `<img src="${data.url}" class="max-w-full max-h-96 mx-auto rounded-lg shadow-lg" alt="${data.name}">`;
            } else {
                content.innerHTML = `<div class="text-center"><i class="fas fa-file text-gray-400 text-8xl mb-4"></i><p class="text-lg text-gray-600">${data.name}</p></div>`;
            }
            document.getElementById('mediaPreviewModalLabel').textContent = data.name;
            document.getElementById('mediaPreviewModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        })
        .catch(error => {
            console.error('Error:', error);
            alert('เกิดข้อผิดพลาดในการโหลดไฟล์');
        });
}

function downloadMedia(mediaId) {
    window.open(`/backend/media-browser/download/${mediaId}`, '_blank');
}

function deleteMedia(mediaId) {
    if (confirm('คุณแน่ใจหรือไม่ที่จะลบไฟล์นี้?')) {
        fetch(`/backend/media-browser/delete/${mediaId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('เกิดข้อผิดพลาดในการลบไฟล์');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('เกิดข้อผิดพลาดในการลบไฟล์');
        });
    }
}

function downloadCurrentMedia() {
    if (currentMediaId) {
        downloadMedia(currentMediaId);
    }
}

function createFolder() {
    const folderName = prompt('กรุณาใส่ชื่อโฟลเดอร์:');
    if (folderName) {
        // Implement folder creation logic
        alert('ฟีเจอร์สร้างโฟลเดอร์จะเพิ่มในอนาคต');
    }
}

function uploadFiles() {
    const formData = new FormData(document.getElementById('uploadForm'));
    const progressBar = document.querySelector('#uploadProgress .bg-blue-600');
    const progressContainer = document.getElementById('uploadProgress');
    
    progressContainer.classList.remove('hidden');
    
    fetch('/backend/media-browser/upload', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('เกิดข้อผิดพลาดในการอัปโหลด: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('เกิดข้อผิดพลาดในการอัปโหลด');
    })
    .finally(() => {
        progressContainer.classList.add('hidden');
    });
}

// Close modals when clicking outside
document.addEventListener('click', function(e) {
    if (e.target.id === 'uploadModal') {
        closeUploadModal();
    }
    if (e.target.id === 'mediaPreviewModal') {
        closeMediaPreviewModal();
    }
});

// Close modals with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeUploadModal();
        closeMediaPreviewModal();
    }
});
</script>
@endpush

