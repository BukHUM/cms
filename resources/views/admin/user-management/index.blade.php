@extends('layouts.admin')

@section('title', 'จัดการผู้ใช้')
@section('page-title', 'จัดการผู้ใช้')
@section('page-subtitle', 'จัดการข้อมูลผู้ใช้ บทบาท และสิทธิ์การเข้าถึงระบบ')

@push('styles')
@vite(['resources/css/settings.css'])
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

@push('styles')
@vite(['resources/css/settings.css'])
<style>
/* Hide all tab content initially to prevent flashing */
.tab-content .tab-pane {
    display: none !important;
}
.tab-content .tab-pane.show {
    display: block !important;
}

/* User Management specific styles */




/* Enhanced search and filter styles */
.input-group .form-control {
    border-left: none;
    box-shadow: none;
}

.input-group .form-control:focus {
    border-color: #3b82f6;
    box-shadow: 0 0 0 0.2rem rgba(59, 130, 246, 0.25);
}

.input-group .form-control:focus + .input-group-text i.fa-search {
    color: #1d4ed8 !important;
    transform: scale(1.15);
}

.input-group-text {
    border-right: none;
    background-color: #f8f9fa;
}

.input-group-text i.fa-search {
    color: #3b82f6 !important;
    font-size: 1rem;
    transition: all 0.2s ease;
}

.input-group:hover .input-group-text i.fa-search {
    color: #2563eb !important;
    transform: scale(1.1);
}

.dropdown-toggle::after {
    margin-left: 0.5rem;
}

.dropdown-menu {
    border: none;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    border-radius: 8px;
    padding: 0.5rem 0;
    background-color: #fff !important;
    z-index: 1050 !important;
    position: absolute !important;
}

.dropdown-menu.show {
    display: block !important;
    z-index: 1050 !important;
}

.dropdown {
    position: relative;
}

/* Fix z-index issues */
.card {
    position: relative;
    z-index: 1;
}

.card-header {
    position: relative;
    z-index: 2;
}

.table-responsive {
    position: relative;
    z-index: 1;
}

/* Lower z-index for table and cards to ensure dropdowns are on top */
#usersTable {
    z-index: 1 !important;
}

.table {
    z-index: 1 !important;
}

.table thead {
    z-index: 1 !important;
}

.table tbody {
    z-index: 1 !important;
}

/* Force dropdown to be absolutely on top - more specific */
.dropdown-menu {
    z-index: 999999 !important;
    position: absolute !important;
    top: 100% !important;
    left: 0 !important;
    right: auto !important;
    transform: none !important;
    will-change: auto !important;
    background-color: #fff !important;
    border: 1px solid #dee2e6 !important;
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
}

.dropdown-menu.show {
    z-index: 999999 !important;
    position: absolute !important;
    display: block !important;
}

.dropdown-menu[data-bs-popper] {
    z-index: 999999 !important;
    position: absolute !important;
    transform: none !important;
}

/* Force dropdown container to be on top */
.dropdown {
    position: relative !important;
    z-index: 99999 !important;
}

.dropdown.show {
    z-index: 99999 !important;
}

/* Override Bootstrap's positioning */
.dropdown-menu[style*="position"] {
    position: absolute !important;
    z-index: 999999 !important;
}

/* Fix table-responsive overflow that hides dropdowns */
.table-responsive {
    overflow-x: auto !important;
    overflow-y: visible !important;
}

/* Remove horizontal scroll on desktop - only show on mobile */
@media (min-width: 768px) {
    .table-responsive {
        overflow-x: visible !important;
        overflow-y: visible !important;
    }
}

/* Only show horizontal scroll on mobile */
@media (max-width: 767px) {
    .table-responsive {
        overflow-x: auto !important;
        overflow-y: visible !important;
    }
}

/* Ensure search and filter section is visible and on top */
.d-flex.gap-2.align-items-center {
    overflow: visible !important;
    position: relative !important;
    z-index: 999999 !important;
}

/* Force the entire filter row to be on top */
.row:has(.d-flex.gap-2.align-items-center) {
    position: relative !important;
    z-index: 99999 !important;
    overflow: visible !important;
}

/* Force the card containing filters to be on top */
.card:has(.d-flex.gap-2.align-items-center) {
    position: relative !important;
    z-index: 99999 !important;
    overflow: visible !important;
}

/* Force all parent containers to allow dropdown visibility */
.card-body {
    overflow: visible !important;
}

.row {
    overflow: visible !important;
}

.col-md-6 {
    overflow: visible !important;
}

/* Additional overflow fixes */
.container-fluid {
    overflow: visible !important;
}

.tab-content {
    overflow: visible !important;
}

.tab-pane {
    overflow: visible !important;
}

/* Bootstrap dropdown positioning fixes */
.dropdown-menu {
    position: absolute !important;
    top: 100% !important;
    left: 0 !important;
    right: auto !important;
    transform: none !important;
    will-change: auto !important;
}

/* Ensure dropdown is positioned correctly */
.dropdown-menu[data-bs-popper] {
    position: absolute !important;
    top: 100% !important;
    left: 0 !important;
    right: auto !important;
    transform: none !important;
}

/* Override any Bootstrap positioning */
.dropdown-menu[style*="position: fixed"] {
    position: absolute !important;
    z-index: 999999 !important;
}

.dropdown-menu[style*="position: static"] {
    position: absolute !important;
    z-index: 999999 !important;
}

.dropdown-item {
    padding: 0.5rem 1rem;
    transition: all 0.2s;
}

.dropdown-item:hover {
    background-color: #f8f9fa;
    color: #495057;
}

.dropdown-item i {
    width: 16px;
    text-align: center;
}

/* Icon colors in dropdowns */
.dropdown-item i.text-success {
    color: #28a745 !important;
}

.dropdown-item i.text-danger {
    color: #dc3545 !important;
}

.dropdown-item i.text-warning {
    color: #ffc107 !important;
}

.dropdown-item i.text-secondary {
    color: #6c757d !important;
}

/* Filter button icon colors */
.btn-outline-secondary i.text-primary {
    color: #3b82f6 !important;
}

/* Filter button styles - no hover effects (moved to admin.blade.php) */

.dropdown-divider {
    margin: 0.5rem 0;
    border-color: #e9ecef;
}

/* Filter button styles - no hover effects */
.btn-outline-secondary {
    border-color: #dee2e6 !important;
    color: #6c757d !important;
    transition: none !important;
}

.btn-outline-secondary:hover {
    background-color: transparent !important;
    border-color: #dee2e6 !important;
    color: #6c757d !important;
    transform: none !important;
    box-shadow: none !important;
}

.btn-outline-secondary:focus {
    box-shadow: none !important;
    border-color: #dee2e6 !important;
    background-color: transparent !important;
}

.btn-outline-secondary:active {
    background-color: transparent !important;
    border-color: #dee2e6 !important;
    color: #6c757d !important;
}

/* Active filter button styles */
.btn-outline-secondary.active {
    background-color: #3b82f6;
    border-color: #3b82f6;
    color: white;
    transform: translateY(0);
}

.btn-outline-secondary.active:hover {
    background-color: #2563eb;
    border-color: #2563eb;
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(59, 130, 246, 0.3);
}


/* Responsive adjustments */
@media (max-width: 768px) {
    
    /* Mobile table improvements */
    .table-responsive {
        border: none;
        box-shadow: none;
    }
    
    .table-responsive::-webkit-scrollbar {
        height: 8px;
    }
    
    .table-responsive::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 4px;
    }
    
    .table-responsive::-webkit-scrollbar-thumb {
        background: #c1c1c1;
        border-radius: 4px;
    }
    
    .table-responsive::-webkit-scrollbar-thumb:hover {
        background: #a8a8a8;
    }
    
    /* Ensure table maintains minimum width */
    #usersTable {
        min-width: 800px !important;
    }
    
    /* Compact table cells on mobile */
    #usersTable th,
    #usersTable td {
        padding: 0.5rem 0.75rem;
        white-space: nowrap;
    }
    
    /* Smaller user avatar on mobile */
    #usersTable img {
        width: 35px !important;
        height: 35px !important;
    }
    
    /* Compact action buttons */
    #usersTable .btn-sm {
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
    }
}

.role-badge {
    display: inline-block;
    padding: 0.25rem 0.5rem;
    border-radius: 6px;
    font-size: 0.75rem;
    font-weight: 500;
    color: white;
    transition: transform 0.2s;
}

.role-badge:hover {
    transform: scale(1.05);
}

.status-badge {
    display: inline-block;
    padding: 0.25rem 0.5rem;
    border-radius: 6px;
    font-size: 0.75rem;
    font-weight: 500;
    color: white;
    border: none;
    transition: all 0.2s;
}

.status-badge:hover {
    transform: scale(1.05);
}

.permission-badge {
    display: inline-block;
    padding: 0.25rem 0.5rem;
    border-radius: 6px;
    font-size: 0.75rem;
    font-weight: 500;
    color: white;
}

.bg-success { background-color: #28a745; }
.bg-warning { background-color: #ffc107; color: #212529; }
.bg-danger { background-color: #dc3545; }
.bg-secondary { background-color: #6c757d; }
.bg-info { background-color: #17a2b8; }
.bg-primary { background-color: #007bff; }

.group-badge {
    background-color: #e9ecef;
    color: #495057;
    padding: 0.25rem 0.5rem;
    border-radius: 6px;
    font-size: 0.75rem;
    font-weight: 500;
}

.action-badge {
    background-color: #d1ecf1;
    color: #0c5460;
    padding: 0.25rem 0.5rem;
    border-radius: 6px;
    font-size: 0.75rem;
    font-weight: 500;
}

.resource-badge {
    background-color: #f8d7da;
    color: #721c24;
    padding: 0.25rem 0.5rem;
    border-radius: 6px;
    font-size: 0.75rem;
    font-weight: 500;
}

.permission-group {
    background: #f8f9fa;
    border-radius: 8px;
    padding: 1rem;
    margin-bottom: 1rem;
}

.permission-group h6 {
    color: #495057;
    margin-bottom: 0.5rem;
    font-weight: 600;
}

.permission-item {
    display: flex;
    align-items: center;
    padding: 0.5rem;
    border-radius: 4px;
    margin-bottom: 0.25rem;
    transition: background-color 0.2s;
}

.permission-item:hover {
    background-color: #e9ecef;
}

.permission-item input[type="checkbox"] {
    margin-right: 0.5rem;
}

.action-buttons {
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
}

.search-container {
    min-width: 300px;
}

/* Table improvements */
.table-hover tbody tr:hover {
    background-color: rgba(0,123,255,0.05);
}

.user-row {
    transition: all 0.2s;
}

.user-row:hover {
    transform: translateX(2px);
}

/* Empty state */
.empty-state {
    padding: 3rem 1rem;
}

.empty-state i {
    opacity: 0.5;
}

/* Modal improvements */
.modal-header.bg-primary {
    border-radius: 0.375rem 0.375rem 0 0;
}

.modal-xl {
    max-width: 90%;
}

/* Form improvements */
.form-control:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 0.2rem rgba(0,123,255,0.25);
}

.form-select:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 0.2rem rgba(0,123,255,0.25);
}

/* Button improvements */
.btn {
    border-radius: 6px;
    transition: all 0.2s;
}

.btn:hover {
    transform: translateY(-1px);
}

.btn-sm {
    padding: 0.375rem 0.75rem;
}

/* Card improvements */
.card {
    border: none;
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.card-header {
    background-color: #f8f9fa;
    border-bottom: 1px solid #dee2e6;
    border-radius: 12px 12px 0 0 !important;
}

/* Badge improvements */
.badge {
    border-radius: 6px;
}

/* Dropdown improvements */
.dropdown-menu {
    border-radius: 8px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.15);
    border: none;
}

.dropdown-item {
    padding: 0.5rem 1rem;
    transition: background-color 0.2s;
}

.dropdown-item:hover {
    background-color: #f8f9fa;
}

/* Responsive improvements */
@media (max-width: 768px) {
    .search-container {
        min-width: auto;
        width: 100%;
    }
    
    .action-buttons {
        width: 100%;
        justify-content: center;
    }
    
    .action-buttons .btn {
        flex: 1;
        min-width: 0;
    }
    
    
    .modal-xl {
        max-width: 95%;
        margin: 0.5rem;
    }
    
    .table-responsive {
        font-size: 0.875rem;
        overflow-x: auto !important;
        overflow-y: visible !important;
    }
    
    .btn-group .btn {
        padding: 0.25rem 0.5rem;
    }
}
</style>
@endpush

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
    
    // Load last active tab
    loadLastActiveTab();
});
</script>
@endpush
