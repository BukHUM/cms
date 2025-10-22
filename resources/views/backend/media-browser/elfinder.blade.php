@extends('backend.layouts.app')

@section('title', 'Elfinder File Manager')
@section('page-title', 'Elfinder File Manager')
@section('page-description', 'จัดการไฟล์ด้วย Elfinder - UI/UX ที่ดีกว่า')

@section('styles')
<link rel="stylesheet" href="{{ asset('vendor/elfinder/css/elfinder.min.css') }}">
<link rel="stylesheet" href="{{ asset('vendor/elfinder/css/theme.css') }}">
<link rel="stylesheet" href="{{ asset('vendor/elfinder/css/elfinder.full.css') }}">
<style>
    .elfinder-container {
        height: calc(100vh - 200px);
        min-height: 600px;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        overflow: hidden;
        background: white;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }
    
    @media (min-width: 1024px) {
        .elfinder-container {
            height: calc(100vh - 180px);
            min-height: 700px;
        }
    }
    
    @media (min-width: 1440px) {
        .elfinder-container {
            height: calc(100vh - 160px);
            min-height: 800px;
        }
    }
    
    .elfinder-toolbar {
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
        .elfinder-toolbar {
            padding: 20px 24px;
            flex-wrap: nowrap;
        }
    }
    
    .elfinder-actions {
        display: flex;
        gap: 8px;
    }
    
    .elfinder-action-btn {
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
        .elfinder-action-btn {
            padding: 10px 20px;
            font-size: 15px;
        }
    }
    
    .elfinder-action-btn:hover {
        background: #4b5563;
    }
    
    .elfinder-action-btn.primary {
        background: #3b82f6;
    }
    
    .elfinder-action-btn.primary:hover {
        background: #2563eb;
    }
    
    @media (max-width: 768px) {
        .elfinder-container {
            height: calc(100vh - 220px);
            min-height: 500px;
        }
        
        .elfinder-toolbar {
            padding: 12px 16px;
            flex-direction: column;
            align-items: stretch;
        }
        
        .elfinder-actions {
            justify-content: center;
            flex-wrap: wrap;
        }
        
        .elfinder-action-btn {
            padding: 8px 12px;
            font-size: 13px;
        }
    }
    
    .elfinder-main-content {
        padding: 20px;
        max-width: 100%;
    }
    
    @media (min-width: 1024px) {
        .elfinder-main-content {
            padding: 24px;
        }
    }
    
    @media (min-width: 1440px) {
        .elfinder-main-content {
            padding: 32px;
            max-width: 1400px;
            margin: 0 auto;
        }
    }
</style>
@endsection

@section('content')
<div class="main-content-area elfinder-main-content">
    <!-- Toolbar -->
    <div class="elfinder-toolbar">
        <div>
            <h3 class="text-lg font-semibold text-gray-800">Elfinder File Manager</h3>
            <p class="text-sm text-gray-600">จัดการไฟล์ด้วย UI/UX ที่ดีกว่า</p>
        </div>
        
        <div class="elfinder-actions">
            <button type="button" 
                    class="elfinder-action-btn"
                    onclick="refreshElfinder()">
                <i class="fas fa-sync-alt mr-1"></i>
                รีเฟรช
            </button>
            <button type="button" 
                    class="elfinder-action-btn primary"
                    onclick="openInNewTab()">
                <i class="fas fa-external-link-alt mr-1"></i>
                เปิดในแท็บใหม่
            </button>
        </div>
    </div>
    
    <!-- File Manager Container -->
    <div class="elfinder-container">
        <div id="elfinder"></div>
    </div>
</div>

@section('scripts')
<!-- jQuery (ensure it's loaded first) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>

<!-- Elfinder JS -->
<script src="{{ asset('vendor/elfinder/js/elfinder.min.js') }}"></script>

<script>
$(document).ready(function() {
    console.log('jQuery loaded:', typeof $ !== 'undefined');
    console.log('Elfinder loaded:', typeof $.fn.elfinder !== 'undefined');
    
    try {
        $('#elfinder').elfinder({
            url: '/elfinder/connector',
            height: '100%',
            width: '100%',
            lang: 'en', // Use English since Thai is not available
            uiOptions: {
                toolbar: [
                    ['back', 'forward'],
                    ['reload'],
                    ['home', 'up'],
                    ['mkdir', 'mkfile', 'upload'],
                    ['open', 'download'],
                    ['info'],
                    ['quicklook'],
                    ['copy', 'cut', 'paste'],
                    ['rm'],
                    ['duplicate', 'rename', 'edit'],
                    ['extract', 'archive'],
                    ['search'],
                    ['view', 'sort']
                ]
            },
            handlers: {
                dblclick: function(event, elfinderInstance) {
                    console.log('Double click detected');
                },
                load: function(event, elfinderInstance) {
                    console.log('Elfinder loaded successfully');
                },
                error: function(event, elfinderInstance) {
                    console.error('Elfinder error:', event);
                }
            }
        });
    } catch (error) {
        console.error('Error initializing Elfinder:', error);
        $('#elfinder').html('<div style="padding: 20px; text-align: center; color: #666;">Error loading file manager. Please check console for details.</div>');
    }
});

function refreshElfinder() {
    try {
        if ($('#elfinder').elfinder('instance')) {
            $('#elfinder').elfinder('instance').reload();
        } else {
            console.error('Elfinder instance not found');
        }
    } catch (error) {
        console.error('Error refreshing Elfinder:', error);
    }
}

function openInNewTab() {
    const elfinderUrl = '{{ route("backend.media-browser.elfinder") }}';
    window.open(elfinderUrl, 'ElfinderFileManager', 'width=1200,height=800,scrollbars=yes,resizable=yes');
}
</script>
@endsection
