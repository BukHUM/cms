@extends('backend.layouts.app')

@section('title', 'Laravel File Manager')
@section('page-title', 'Laravel File Manager')
@section('page-description', 'จัดการไฟล์ด้วย Laravel File Manager')

@section('styles')
<style>
    .lfm-container {
        height: calc(100vh - 200px);
        min-height: 600px;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        overflow: hidden;
        background: white;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }
    
    @media (min-width: 1024px) {
        .lfm-container {
            height: calc(100vh - 180px);
            min-height: 700px;
        }
    }
    
    @media (min-width: 1440px) {
        .lfm-container {
            height: calc(100vh - 160px);
            min-height: 800px;
        }
    }
    
    .lfm-iframe {
        width: 100%;
        height: 100%;
        border: none;
        background: white;
    }
    
    .lfm-toolbar {
        background: #f8fafc;
        border-bottom: 1px solid #e5e7eb;
        padding: 16px 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 16px;
    }
    
    @media (min-width: 1024px) {
        .lfm-toolbar {
            padding: 20px 24px;
            flex-wrap: nowrap;
        }
    }
    
    .lfm-type-selector {
        display: flex;
        gap: 8px;
    }
    
    .lfm-type-btn {
        padding: 8px 16px;
        border: 1px solid #d1d5db;
        background: white;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s;
        white-space: nowrap;
    }
    
    @media (min-width: 1024px) {
        .lfm-type-btn {
            padding: 10px 20px;
            font-size: 15px;
        }
    }
    
    .lfm-type-btn.active {
        background: #3b82f6;
        color: white;
        border-color: #3b82f6;
    }
    
    .lfm-type-btn:hover {
        background: #f3f4f6;
    }
    
    .lfm-type-btn.active:hover {
        background: #2563eb;
    }
    
    .lfm-actions {
        display: flex;
        gap: 8px;
    }
    
    .lfm-action-btn {
        padding: 8px 16px;
        background: #6b7280;
        color: white;
        border: none;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        transition: background 0.2s;
        white-space: nowrap;
    }
    
    @media (min-width: 1024px) {
        .lfm-action-btn {
            padding: 10px 20px;
            font-size: 15px;
        }
    }
    
    .lfm-action-btn:hover {
        background: #4b5563;
    }
    
    .lfm-action-btn.primary {
        background: #3b82f6;
    }
    
    .lfm-action-btn.primary:hover {
        background: #2563eb;
    }
    
    @media (max-width: 768px) {
        .lfm-container {
            height: calc(100vh - 220px);
            min-height: 500px;
        }
        
        .lfm-toolbar {
            padding: 12px 16px;
            flex-direction: column;
            align-items: stretch;
        }
        
        .lfm-type-selector {
            justify-content: center;
            margin-bottom: 12px;
        }
        
        .lfm-actions {
            justify-content: center;
            flex-wrap: wrap;
        }
        
        .lfm-type-btn,
        .lfm-action-btn {
            padding: 8px 12px;
            font-size: 13px;
        }
    }
    
    .lfm-main-content {
        padding: 20px;
        max-width: 100%;
    }
    
    @media (min-width: 1024px) {
        .lfm-main-content {
            padding: 24px;
        }
    }
    
    @media (min-width: 1440px) {
        .lfm-main-content {
            padding: 32px;
            max-width: 1400px;
            margin: 0 auto;
        }
    }
</style>
@endsection

@section('content')
<div class="main-content-area lfm-main-content">
    <!-- Toolbar -->
    <div class="lfm-toolbar">
        <div class="lfm-type-selector">
            <button type="button" 
                    class="lfm-type-btn {{ $type === 'Files' ? 'active' : '' }}"
                    onclick="switchType('Files')">
                <i class="fas fa-file mr-1"></i>
                ไฟล์ทั้งหมด
            </button>
            <button type="button" 
                    class="lfm-type-btn {{ $type === 'Images' ? 'active' : '' }}"
                    onclick="switchType('Images')">
                <i class="fas fa-image mr-1"></i>
                รูปภาพ
            </button>
        </div>
        
        <div class="lfm-actions">
            <button type="button" 
                    class="lfm-action-btn"
                    onclick="refreshLFM()">
                <i class="fas fa-sync-alt mr-1"></i>
                รีเฟรช
            </button>
            <button type="button" 
                    class="lfm-action-btn primary"
                    onclick="openInNewTab()">
                <i class="fas fa-external-link-alt mr-1"></i>
                เปิดในแท็บใหม่
            </button>
        </div>
    </div>
    
    <!-- File Manager Container -->
    <div class="lfm-container">
        <iframe id="lfmIframe" 
                class="lfm-iframe" 
                src="{{ $lfmUrl }}"
                title="Laravel File Manager">
        </iframe>
    </div>
</div>

<script>
let currentType = '{{ $type }}';
let lfmUrl = '{{ $lfmUrl }}';

function switchType(type) {
    currentType = type;
    
    // Update active button
    document.querySelectorAll('.lfm-type-btn').forEach(btn => {
        btn.classList.remove('active');
    });
    event.target.classList.add('active');
    
    // Update iframe source
    const newUrl = lfmUrl.replace(/type=[^&]*/, 'type=' + type);
    document.getElementById('lfmIframe').src = newUrl;
}

function refreshLFM() {
    const iframe = document.getElementById('lfmIframe');
    iframe.src = iframe.src;
}

function openInNewTab() {
    window.open(lfmUrl, '_blank');
}

// Handle iframe messages
window.addEventListener('message', function(event) {
    if (event.data && event.data.type === 'lfm-file-selected') {
        // Handle file selection from LFM
        console.log('File selected:', event.data.file);
        
        // You can add custom logic here to handle file selection
        // For example, close the file manager and return to parent page
        if (window.opener) {
            window.opener.postMessage({
                type: 'file-selected',
                file: event.data.file
            }, '*');
            window.close();
        }
    }
});

// Auto-resize iframe on load
document.getElementById('lfmIframe').addEventListener('load', function() {
    console.log('LFM loaded successfully');
});
</script>
@endsection
