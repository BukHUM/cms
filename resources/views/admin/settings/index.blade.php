@extends('layouts.admin')

@section('title', 'ตั้งค่าระบบ')
@section('page-title', 'ตั้งค่าระบบ')
@section('page-subtitle', 'จัดการการตั้งค่าระบบและค่าพารามิเตอร์ต่างๆ')

@push('styles')
@vite(['resources/css/settings.css'])
<link rel="stylesheet" href="{{ asset('js/libs/sweetalert2.min.css') }}">
<style>
/* Hide all tab content initially to prevent flashing */
.tab-content .tab-pane {
    display: none !important;
}
.tab-content .tab-pane.show {
    display: block !important;
}
</style>
@endpush

@section('content')
<!-- Include Navigation -->
@include('admin.settings.partials.navigation')

<div class="tab-content" id="settingsTabContent">
    <!-- Include all tab partials -->
    @include('admin.settings.partials.general')
    @include('admin.settings.partials.email')
    @include('admin.settings.partials.security')
    @include('admin.settings.partials.backup')
    @include('admin.settings.partials.audit')
    @include('admin.settings.partials.performance')
    @include('admin.settings.partials.update')
    @include('admin.settings.partials.system-info')
</div>
@endsection

@push('scripts')
<!-- SweetAlert2 -->
<script src="{{ asset('js/libs/sweetalert2.min.js') }}"></script>

<!-- Settings JavaScript Files -->
<script src="{{ asset('js/settings/settings-navigation.js') }}"></script>
<script src="{{ asset('js/settings/settings-general.js') }}"></script>
<script src="{{ asset('js/settings/settings-email.js') }}"></script>
<script src="{{ asset('js/settings/settings-security.js') }}"></script>
<script src="{{ asset('js/settings/settings-backup.js') }}"></script>
<script src="{{ asset('js/settings/settings-audit.js') }}"></script>
<script src="{{ asset('js/settings/settings-performance.js') }}"></script>
<script src="{{ asset('js/settings/settings-update.js') }}"></script>
<script src="{{ asset('js/settings/settings-system-info.js') }}"></script>
<script src="{{ asset('js/settings/settings-utils.js') }}"></script>

<!-- Force Settings Menu Active -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add data attribute to body
    document.body.setAttribute('data-page', 'settings');
    
    // Force add active class to settings menu
    const settingsNavLink = document.querySelector('a[href*="settings"]');
    if (settingsNavLink) {
        settingsNavLink.classList.add('active');
    }
    
    // Load last active tab
    loadLastActiveTab();
});
</script>
@endpush