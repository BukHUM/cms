@extends('layouts.admin')

@section('title', 'จัดการผู้ใช้')
@section('page-title', 'จัดการผู้ใช้')
@section('page-subtitle', 'จัดการข้อมูลผู้ใช้ บทบาท และสิทธิ์การเข้าถึงระบบ')

@push('styles')
@vite(['resources/css/user-management.css'])
@endpush

@section('content')
<!-- Include Navigation -->
@include('admin.user-management.partials.navigation')

<div class="tab-content" id="userManagementTabContent">
    <!-- Include all tab partials -->
    @include('admin.user-management.partials.users')
    @include('admin.user-management.partials.roles')
    @include('admin.user-management.partials.permissions')
</div>
@endsection

@push('scripts')
<!-- User Management JavaScript Files -->
<script src="{{ asset('js/user-management/user-management-navigation.js') }}"></script>
<script src="{{ asset('js/user-management/user-management-users.js') }}"></script>
<script src="{{ asset('js/user-management/user-management-roles.js') }}"></script>
<script src="{{ asset('js/user-management/user-management-permissions.js') }}"></script>
<script src="{{ asset('js/user-management/user-management-utils.js') }}"></script>

<!-- Force User Management Menu Active -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add data attribute to body
    document.body.setAttribute('data-page', 'user-management');
    
    // Force add active class to user management menu
    const userManagementNavLink = document.querySelector('a[href*="user-management"]');
    if (userManagementNavLink) {
        userManagementNavLink.classList.add('active');
    }
    
    // Remove active class from other menu items
    const allNavLinks = document.querySelectorAll('.sidebar-nav a');
    allNavLinks.forEach(link => {
        if (!link.href.includes('user-management')) {
            link.classList.remove('active');
        }
    });
    
    // Load last active tab
    if (typeof loadLastActiveTab === 'function') {
        loadLastActiveTab();
    }
});
</script>
@endpush
