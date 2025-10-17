// Users Tab JavaScript
document.addEventListener('DOMContentLoaded', function() {
    // Modal event listeners
    const viewModal = document.getElementById('viewUserModal');
    const editModal = document.getElementById('editUserModal');
    
    if (viewModal) {
        viewModal.addEventListener('hidden.bs.modal', function() {
            // Clear modal content when closed
            document.getElementById('viewUserModalBody').innerHTML = `
                <div class="text-center">
                    <div class="spinner-border" role="status">
                        <span class="visually-hidden">กำลังโหลด...</span>
                    </div>
                    <p class="mt-2">กำลังโหลดข้อมูลผู้ใช้...</p>
                </div>
            `;
        });
    }
    
    if (editModal) {
        editModal.addEventListener('hidden.bs.modal', function() {
            // Clear modal content when closed
            document.getElementById('editUserModalBody').innerHTML = `
                <div class="text-center">
                    <div class="spinner-border" role="status">
                        <span class="visually-hidden">กำลังโหลด...</span>
                    </div>
                    <p class="mt-2">กำลังโหลดข้อมูลผู้ใช้...</p>
                </div>
            `;
        });
    }
    
    // Search functionality
    const usersSearchInput = document.getElementById('usersSearchInput');
    if (usersSearchInput) {
        usersSearchInput.addEventListener('input', UserManagementUtils.debounce(function() {
            filterUsers();
        }, 300));
    }

    // Status filter
    const statusFilter = document.getElementById('statusFilter');
    if (statusFilter) {
        statusFilter.addEventListener('change', function() {
            filterUsers();
        });
    }

    // Role filter
    const roleFilter = document.getElementById('roleFilter');
    if (roleFilter) {
        roleFilter.addEventListener('change', function() {
            filterUsers();
        });
    }

    // Select all functionality
    const selectAllCheckbox = document.getElementById('selectAll');
    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function() {
            toggleSelectAll();
        });
    }

    // Edit user form
    const editUserForm = document.getElementById('editUserForm');
    if (editUserForm) {
        editUserForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const userId = window.currentEditingUserId;
            if (!userId) {
                Swal.fire({
                    title: 'ข้อผิดพลาด',
                    text: 'ไม่พบข้อมูลผู้ใช้ที่ต้องการแก้ไข',
                    icon: 'error',
                    customClass: {
                container: 'swal2-container-high-zindex'
            },
            confirmButtonText: 'ตกลง'
                });
                return;
            }
            
            // Call the proper save function
            saveUserChanges(userId);
        });
    }

    // Initialize bulk actions state
    updateBulkActions();

    // Add user form
    const addUserForm = document.getElementById('addUserForm');
    if (addUserForm) {
        addUserForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Get form data
            const formData = {
                name: document.getElementById('firstName').value + ' ' + document.getElementById('lastName').value,
                email: document.getElementById('email').value,
                phone: document.getElementById('phone').value,
                status: document.getElementById('status').value,
                password: document.getElementById('password').value,
                password_confirmation: document.getElementById('password_confirmation').value,
                roles: Array.from(document.getElementById('roles').selectedOptions).map(option => option.value)
            };
            
            // Validate password confirmation
            if (formData.password !== formData.password_confirmation) {
                SwalHelper.error('รหัสผ่านไม่ตรงกัน');
                return;
            }
            
            // Send data to backend
            fetch('{{ route("user-management.users.store") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify(formData)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    SwalHelper.success('เพิ่มผู้ใช้เรียบร้อยแล้ว');
                    // Close modal
                    const modal = bootstrap.Modal.getInstance(document.getElementById('addUserModal'));
                    modal.hide();
                    // Reset form
                    this.reset();
                    // Reload page
                    setTimeout(() => {
                        location.reload();
                    }, 1500);
                } else {
                    SwalHelper.error(data.message || 'ไม่สามารถเพิ่มผู้ใช้ได้');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                SwalHelper.error('เกิดข้อผิดพลาดในการเพิ่มผู้ใช้');
            });
        });
    }
});

// User actions
function editUser(userId) {
    console.log('Edit user:', userId);
    
    // Debug modal state
    console.log('Modal element:', document.getElementById('editUserModal'));
    console.log('Modal classes:', document.getElementById('editUserModal').className);
    
    // Force remove any existing modal instances
    const existingModal = bootstrap.Modal.getInstance(document.getElementById('editUserModal'));
    if (existingModal) {
        console.log('Disposing existing modal');
        existingModal.dispose();
    }
    
    // Show edit modal with proper options
    const editModal = new bootstrap.Modal(document.getElementById('editUserModal'), {
        backdrop: false,
        keyboard: true,
        focus: true
    });
    
    // Store current user ID for form submission
    window.currentEditingUserId = userId;
    
    // Show modal
    editModal.show();
    
    // Force modal z-index and check navigation
    setTimeout(() => {
        const modalElement = document.getElementById('editUserModal');
        
        // Check navigation bar z-index
        const navbars = document.querySelectorAll('.navbar, .nav, .navigation, .header, .top-bar');
        navbars.forEach(nav => {
            console.log('Navigation element:', nav, 'z-index:', window.getComputedStyle(nav).zIndex);
            nav.style.zIndex = '1000';
        });
        
        // Force modal z-index
        modalElement.style.zIndex = '999999';
        modalElement.style.position = 'fixed';
        modalElement.style.top = '0';
        modalElement.style.left = '0';
        modalElement.style.width = '100%';
        modalElement.style.height = '100%';
        
        const modalDialog = modalElement.querySelector('.modal-dialog');
        if (modalDialog) {
            modalDialog.style.zIndex = '1000000';
            modalDialog.style.position = 'relative';
        }
        const modalContent = modalElement.querySelector('.modal-content');
        if (modalContent) {
            modalContent.style.zIndex = '1000001';
            modalContent.style.position = 'relative';
        }
        
        console.log('Forced edit modal z-index and checked navigation');
    }, 50);
    
    // Remove any existing backdrop
    setTimeout(() => {
        const backdrops = document.querySelectorAll('.modal-backdrop');
        backdrops.forEach(backdrop => {
            backdrop.remove();
        });
        console.log('Removed existing backdrops');
    }, 100);
    
    // Debug after show
    setTimeout(() => {
        console.log('Modal shown, classes:', document.getElementById('editUserModal').className);
        console.log('Modal display:', window.getComputedStyle(document.getElementById('editUserModal')).display);
        console.log('Modal z-index:', window.getComputedStyle(document.getElementById('editUserModal')).zIndex);
    }, 200);
    
    // Load user data after modal is shown
    setTimeout(() => {
        loadUserForEdit(userId);
    }, 100);
}

function viewUser(userId) {
    console.log('View user:', userId);
    
    // Check if Bootstrap is loaded
    if (typeof bootstrap === 'undefined') {
        console.error('Bootstrap is not loaded!');
        Swal.fire({
            title: 'ข้อผิดพลาด',
            text: 'Bootstrap is not loaded. Please refresh the page.',
            icon: 'error',
            customClass: {
                container: 'swal2-container-high-zindex'
            },
            confirmButtonText: 'ตกลง'
        });
        return;
    }
    
    // Check if modal element exists
    const modalElement = document.getElementById('viewUserModal');
    if (!modalElement) {
        console.error('Modal element not found!');
        Swal.fire({
            title: 'ข้อผิดพลาด',
            text: 'Modal element not found. Please refresh the page.',
            icon: 'error',
            customClass: {
                container: 'swal2-container-high-zindex'
            },
            confirmButtonText: 'ตกลง'
        });
        return;
    }
    
    console.log('View Modal element:', modalElement);
    console.log('View Modal classes:', modalElement.className);
    
    // Force remove any existing modal instances
    const existingModal = bootstrap.Modal.getInstance(modalElement);
    if (existingModal) {
        console.log('Disposing existing view modal');
        existingModal.dispose();
    }
    
    // Try simple modal creation first
    try {
        const viewModal = new bootstrap.Modal(modalElement, {
            backdrop: false,
            keyboard: true,
            focus: true
        });
        
        console.log('Modal instance created:', viewModal);
        
        // Store current user ID for edit button
        window.currentViewingUserId = userId;
        
        // Show modal
        viewModal.show();
        
        console.log('Modal.show() called');
        
        // Force modal z-index and check navigation
        setTimeout(() => {
            // Check navigation bar z-index
            const navbars = document.querySelectorAll('.navbar, .nav, .navigation, .header, .top-bar');
            navbars.forEach(nav => {
                console.log('Navigation element:', nav, 'z-index:', window.getComputedStyle(nav).zIndex);
                nav.style.zIndex = '1000';
            });
            
            // Force modal z-index
            modalElement.style.zIndex = '999999';
            modalElement.style.position = 'fixed';
            modalElement.style.top = '0';
            modalElement.style.left = '0';
            modalElement.style.width = '100%';
            modalElement.style.height = '100%';
            
            const modalDialog = modalElement.querySelector('.modal-dialog');
            if (modalDialog) {
                modalDialog.style.zIndex = '1000000';
                modalDialog.style.position = 'relative';
            }
            const modalContent = modalElement.querySelector('.modal-content');
            if (modalContent) {
                modalContent.style.zIndex = '1000001';
                modalContent.style.position = 'relative';
            }
            
            console.log('Forced modal z-index and checked navigation');
        }, 50);
        
        // Remove any existing backdrop
        setTimeout(() => {
            const backdrops = document.querySelectorAll('.modal-backdrop');
            backdrops.forEach(backdrop => {
                backdrop.remove();
            });
            console.log('Removed existing backdrops');
        }, 100);
        
        // Debug after show
        setTimeout(() => {
            console.log('View Modal shown, classes:', modalElement.className);
            console.log('View Modal display:', window.getComputedStyle(modalElement).display);
            console.log('View Modal z-index:', window.getComputedStyle(modalElement).zIndex);
            console.log('View Modal opacity:', window.getComputedStyle(modalElement).opacity);
            console.log('View Modal visibility:', window.getComputedStyle(modalElement).visibility);
            console.log('Modal backdrop:', document.querySelector('.modal-backdrop'));
            
            // Check if modal is actually visible
            const modalRect = modalElement.getBoundingClientRect();
            console.log('Modal position:', modalRect);
            console.log('Modal width:', modalRect.width);
            console.log('Modal height:', modalRect.height);
            
            // Force show modal if not visible
            if (modalRect.width === 0 || modalRect.height === 0) {
                console.log('Modal not visible, forcing display');
                modalElement.style.display = 'block';
                modalElement.style.opacity = '1';
                modalElement.style.visibility = 'visible';
                modalElement.style.zIndex = '9999';
            }
            
            // Force enable pointer events for all modal elements
            console.log('Enabling pointer events for modal elements');
            modalElement.style.pointerEvents = 'auto';
            const modalContent = modalElement.querySelector('.modal-content');
            if (modalContent) {
                modalContent.style.pointerEvents = 'auto';
            }
            
            // Enable pointer events for all child elements
            const allElements = modalElement.querySelectorAll('*');
            allElements.forEach(element => {
                element.style.pointerEvents = 'auto';
            });
            
            // Test click events
            console.log('Testing click events...');
            const closeBtn = modalElement.querySelector('.btn-close');
            const cancelBtn = modalElement.querySelector('.btn-secondary');
            const editBtn = modalElement.querySelector('#editUserFromViewBtn');
            
            if (closeBtn) {
                console.log('Close button found:', closeBtn);
                closeBtn.addEventListener('click', function(e) {
                    console.log('Close button clicked!');
                    e.preventDefault();
                    e.stopPropagation();
                    bootstrap.Modal.getInstance(modalElement).hide();
                });
            }
            
            if (cancelBtn) {
                console.log('Cancel button found:', cancelBtn);
                cancelBtn.addEventListener('click', function(e) {
                    console.log('Cancel button clicked!');
                    e.preventDefault();
                    e.stopPropagation();
                    bootstrap.Modal.getInstance(modalElement).hide();
                });
            }
            
            if (editBtn) {
                console.log('Edit button found:', editBtn);
                editBtn.addEventListener('click', function(e) {
                    console.log('Edit button clicked!');
                    e.preventDefault();
                    e.stopPropagation();
                    bootstrap.Modal.getInstance(modalElement).hide();
                    editUser(userId);
                });
            }
        }, 200);
        
        // Load user data after modal is shown
        setTimeout(() => {
            loadUserForView(userId);
        }, 100);
        
    } catch (error) {
        console.error('Error creating modal:', error);
        Swal.fire({
            title: 'ข้อผิดพลาด',
            text: 'Error creating modal: ' + error.message,
            icon: 'error',
            customClass: {
                container: 'swal2-container-high-zindex'
            },
            confirmButtonText: 'ตกลง'
        });
    }
}

function manageUserRoles(userId) {
    console.log('Manage roles for user:', userId);
    
    // Load user data
    fetch(`{{ route('user-management.users.show') }}/${userId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const user = data.data.user;
                const userRoles = data.data.roles;
                
                // Update modal content
                document.getElementById('userInfo').innerHTML = `
                    <div class="alert alert-info">
                        <h6 class="mb-1">${user.name}</h6>
                        <small>${user.email}</small>
                    </div>
                `;
                
                // Generate roles HTML
                let rolesHtml = '';
                const allRoles = data.data.all_roles;
                
                allRoles.forEach(role => {
                    const isChecked = userRoles.includes(role.id.toString());
                    rolesHtml += `
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" name="roles[]" value="${role.id}" id="user_role_${role.id}" ${isChecked ? 'checked' : ''}>
                            <label class="form-check-label" for="user_role_${role.id}">
                                <span class="role-badge" style="background-color: ${role.color};">
                                    ${role.name}
                                </span>
                                ${role.description ? `<small class="text-muted d-block">${role.description}</small>` : ''}
                            </label>
                        </div>
                    `;
                });
                
                document.getElementById('rolesContainer').innerHTML = rolesHtml;
                
                // Show modal
                const modal = new bootstrap.Modal(document.getElementById('manageUserRolesModal'));
                modal.show();
                
                // Set up form submission
                document.getElementById('manageUserRolesForm').onsubmit = function(e) {
                    e.preventDefault();
                    
                    const selectedRoles = Array.from(document.querySelectorAll('#manageUserRolesForm input[name="roles[]"]:checked')).map(cb => cb.value);
                    
                    fetch(`{{ route('user-management.users.roles') }}/${userId}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({ roles: selectedRoles })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            SwalHelper.success('อัปเดตบทบาทเรียบร้อยแล้ว');
                            modal.hide();
                            setTimeout(() => {
                                location.reload();
                            }, 1500);
                        } else {
                            SwalHelper.error(data.message || 'ไม่สามารถอัปเดตบทบาทได้');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        SwalHelper.error('เกิดข้อผิดพลาดในการอัปเดตบทบาท');
                    });
                };
            } else {
                SwalHelper.error(data.message || 'ไม่สามารถโหลดข้อมูลได้');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            SwalHelper.error('เกิดข้อผิดพลาดในการโหลดข้อมูล');
        });
}

function deleteUser(userId) {
    Swal.fire({
        title: 'ยืนยันการลบ',
        text: 'คุณแน่ใจหรือไม่ที่จะลบผู้ใช้นี้?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'ลบ',
        cancelButtonText: 'ยกเลิก',
        customClass: {
            container: 'swal2-container-high-zindex'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            console.log('Delete user:', userId);
            
            // Show loading
            Swal.fire({
                title: 'กำลังลบ...',
                text: 'กรุณารอสักครู่',
                allowOutsideClick: false,
                showConfirmButton: false,
                customClass: {
                    container: 'swal2-container-high-zindex'
                },
                willOpen: () => {
                    Swal.showLoading();
                }
            });
            
            // Make API call
            fetch(`/admin/api/user-management/users/${userId}`, {
            method: 'DELETE',
            headers: {
                    'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
            .then(response => {
                console.log('Delete response:', response);
                
                if (response.success) {
                    // Show success message
                    Swal.fire({
                        title: 'สำเร็จ',
                        text: 'ลบผู้ใช้เรียบร้อยแล้ว',
                        icon: 'success',
                        customClass: {
                container: 'swal2-container-high-zindex'
            },
            confirmButtonText: 'ตกลง',
                        customClass: {
                            container: 'swal2-container-high-zindex'
                        }
                    }).then(() => {
                        // Reload page to show updated data
                        window.location.reload();
                    });
            } else {
                    throw new Error(response.message || 'เกิดข้อผิดพลาดในการลบ');
            }
        })
        .catch(error => {
                console.error('Error deleting user:', error);
                Swal.fire({
                    title: 'ข้อผิดพลาด',
                    text: 'เกิดข้อผิดพลาดในการลบ: ' + error.message,
                    icon: 'error',
                    customClass: {
                container: 'swal2-container-high-zindex'
            },
            confirmButtonText: 'ตกลง',
                    customClass: {
                        container: 'swal2-container-high-zindex'
                    }
                });
            });
        }
    });
}

// Load user data for view modal
function loadUserForView(userId) {
    const modalBody = document.getElementById('viewUserModalBody');
    
    // Show loading
    modalBody.innerHTML = `
        <div class="text-center">
            <div class="spinner-border" role="status">
                <span class="visually-hidden">กำลังโหลด...</span>
            </div>
            <p class="mt-2">กำลังโหลดข้อมูลผู้ใช้...</p>
        </div>
    `;
    
    // Make actual API call
    fetch(`/admin/api/user-management/users/${userId}`, {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(response => {
        console.log('API Response:', response);
        
        if (!response.success) {
            throw new Error(response.message || 'API Error');
        }
        
        const user = response.data.user;
        console.log('User data loaded:', user);
        
        modalBody.innerHTML = `
            <div class="row g-3">
                <div class="col-md-4 text-center">
                    <div class="mb-3">
                        ${user.avatar_url ? 
                            `<img src="${user.avatar_url}" alt="Avatar" class="rounded-circle" style="width: 80px; height: 80px; object-fit: cover;">` :
                            `<div class="rounded-circle bg-primary d-inline-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                                <i class="fas fa-user text-white fa-2x"></i>
                            </div>`
                        }
                    </div>
                    <h5 id="viewUserName">${user.name || 'ไม่ระบุ'}</h5>
                    <span class="badge ${user.status === 'active' ? 'bg-success' : 'bg-danger'}" id="viewUserStatus">
                        ${user.status === 'active' ? 'ใช้งาน' : 'ไม่ใช้งาน'}
                    </span>
                </div>
                <div class="col-md-8">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label fw-bold">ชื่อ-นามสกุล</label>
                            <p id="viewUserName">${user.name || 'ไม่ระบุ'}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">อีเมล</label>
                            <p id="viewUserEmail">${user.email || 'ไม่ระบุ'}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">เบอร์โทรศัพท์</label>
                            <p id="viewUserPhone">${user.phone || 'ไม่ระบุ'}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">สถานะ</label>
                             <p id="viewUserStatusDetail">
                                 ${user.status === 'active' ? 'ใช้งาน' : 
                                   user.status === 'inactive' ? 'ไม่ใช้งาน' : 
                                   user.status === 'pending' ? 'รอการยืนยัน' : 'ไม่ระบุ'}
                             </p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">บทบาท</label>
                            <div id="viewUserRoles">
                                ${user.roles && user.roles.length > 0 ? user.roles.map(role => `<span class="badge bg-primary me-1">${role.name}</span>`).join('') : '<span class="badge bg-secondary">ไม่มีบทบาท</span>'}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">วันที่สมัคร</label>
                            <p id="viewUserCreatedAt">${user.created_at ? new Date(user.created_at).toLocaleDateString('th-TH') : 'ไม่ระบุ'}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">อัปเดตล่าสุด</label>
                            <p id="viewUserUpdatedAt">${user.updated_at ? new Date(user.updated_at).toLocaleDateString('th-TH') : 'ไม่ระบุ'}</p>
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        // Set edit button click handler
        document.getElementById('editUserFromViewBtn').onclick = () => {
            bootstrap.Modal.getInstance(document.getElementById('viewUserModal')).hide();
            editUser(userId);
        };
    })
    .catch(error => {
        console.error('Error loading user:', error);
        Swal.fire({
            title: 'ข้อผิดพลาด',
            text: 'เกิดข้อผิดพลาดในการโหลดข้อมูลผู้ใช้',
            icon: 'error',
            customClass: {
                container: 'swal2-container-high-zindex'
            },
            confirmButtonText: 'ตกลง'
        }).then(() => {
            // Close modal
            const viewModal = bootstrap.Modal.getInstance(document.getElementById('viewUserModal'));
            viewModal.hide();
        });
    });
}

// Load user data for edit modal
function loadUserForEdit(userId) {
    const modalBody = document.getElementById('editUserModalBody');
    
    // Show loading
    modalBody.innerHTML = `
        <div class="text-center">
            <div class="spinner-border" role="status">
                <span class="visually-hidden">กำลังโหลด...</span>
            </div>
            <p class="mt-2">กำลังโหลดข้อมูลผู้ใช้...</p>
        </div>
    `;
    
    // Make actual API call
    fetch(`/admin/api/user-management/users/${userId}`, {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(response => {
        console.log('API Response for edit:', response);
        
        if (!response.success) {
            throw new Error(response.message || 'API Error');
        }
        
        const user = response.data.user;
        const allRoles = response.data.all_roles;
        console.log('User data loaded for edit:', user);
        
        modalBody.innerHTML = `
            <div class="row g-3">
                <div class="col-12">
                    <label for="editName" class="form-label">ชื่อ-นามสกุล *</label>
                    <input type="text" class="form-control" id="editName" value="${user.name || ''}" required>
                </div>
                <div class="col-md-6">
                    <label for="editEmail" class="form-label">อีเมล *</label>
                    <input type="email" class="form-control" id="editEmail" value="${user.email || ''}" required>
                </div>
                <div class="col-md-6">
                    <label for="editPhone" class="form-label">เบอร์โทรศัพท์</label>
                    <input type="tel" class="form-control" id="editPhone" value="${user.phone || ''}">
                </div>
                <div class="col-md-6">
                    <label for="editStatus" class="form-label">สถานะ</label>
                     <select class="form-select" id="editStatus">
                         <option value="active" ${user.status === 'active' ? 'selected' : ''}>ใช้งาน</option>
                         <option value="inactive" ${user.status === 'inactive' ? 'selected' : ''}>ไม่ใช้งาน</option>
                         <option value="pending" ${user.status === 'pending' ? 'selected' : ''}>รอการยืนยัน</option>
                     </select>
                </div>
                <div class="col-md-6">
                    <label for="editRoles" class="form-label">บทบาท</label>
                    <select class="form-select" id="editRoles" multiple>
                        ${allRoles ? allRoles.map(role => `<option value="${role.id}" ${user.roles && user.roles.some(userRole => userRole.id === role.id) ? 'selected' : ''}>${role.name}</option>`).join('') : '<option value="">ไม่มีบทบาท</option>'}
                    </select>
                </div>
                <div class="col-12">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="editChangePassword">
                        <label class="form-check-label" for="editChangePassword">
                            เปลี่ยนรหัสผ่าน
                        </label>
                    </div>
                </div>
                <div class="col-md-6" id="editPasswordFields" style="display: none;">
                    <label for="editPassword" class="form-label">รหัสผ่านใหม่</label>
                    <input type="password" class="form-control" id="editPassword">
                </div>
                <div class="col-md-6" id="editPasswordConfirmFields" style="display: none;">
                    <label for="editPasswordConfirm" class="form-label">ยืนยันรหัสผ่าน</label>
                    <input type="password" class="form-control" id="editPasswordConfirm">
                </div>
            </div>
        `;
        
        // Handle password change checkbox
        document.getElementById('editChangePassword').addEventListener('change', function() {
            const passwordFields = document.getElementById('editPasswordFields');
            const passwordConfirmFields = document.getElementById('editPasswordConfirmFields');
            
            if (this.checked) {
                passwordFields.style.display = 'block';
                passwordConfirmFields.style.display = 'block';
                document.getElementById('editPassword').required = true;
                document.getElementById('editPasswordConfirm').required = true;
            } else {
                passwordFields.style.display = 'none';
                passwordConfirmFields.style.display = 'none';
                document.getElementById('editPassword').required = false;
                document.getElementById('editPasswordConfirm').required = false;
            }
        });
        
        // Form submission is handled globally
        })
        .catch(error => {
        console.error('Error loading user for edit:', error);
        Swal.fire({
            title: 'ข้อผิดพลาด',
            text: 'เกิดข้อผิดพลาดในการโหลดข้อมูลผู้ใช้',
            icon: 'error',
            customClass: {
                container: 'swal2-container-high-zindex'
            },
            confirmButtonText: 'ตกลง'
        }).then(() => {
            // Close modal
            const editModal = bootstrap.Modal.getInstance(document.getElementById('editUserModal'));
            editModal.hide();
        });
    });
}

// Save user changes
function saveUserChanges(userId) {
    console.log('Saving user changes for ID:', userId);
    
    // Get form data
    const formData = {
        name: document.getElementById('editName').value,
        email: document.getElementById('editEmail').value,
        phone: document.getElementById('editPhone').value,
        status: document.getElementById('editStatus').value,
        roles: Array.from(document.getElementById('editRoles').selectedOptions).map(option => option.value)
    };
    
    // Add password if change password is checked
    const changePassword = document.getElementById('editChangePassword').checked;
    if (changePassword) {
        const password = document.getElementById('editPassword').value;
        const passwordConfirm = document.getElementById('editPasswordConfirm').value;
        
        if (password !== passwordConfirm) {
            Swal.fire({
                title: 'ข้อผิดพลาด',
                text: 'รหัสผ่านไม่ตรงกัน',
                icon: 'error',
                customClass: {
                container: 'swal2-container-high-zindex'
            },
            confirmButtonText: 'ตกลง',
                customClass: {
                    container: 'swal2-container-high-zindex'
                }
            });
            return;
        }
        
        if (password.length < 6) {
            Swal.fire({
                title: 'ข้อผิดพลาด',
                text: 'รหัสผ่านต้องมีอย่างน้อย 6 ตัวอักษร',
                icon: 'error',
                customClass: {
                container: 'swal2-container-high-zindex'
            },
            confirmButtonText: 'ตกลง',
                customClass: {
                    container: 'swal2-container-high-zindex'
                }
            });
            return;
        }
        
        formData.password = password;
    }
    
    console.log('Form data:', formData);
    
    // Show loading
    const submitBtn = document.querySelector('#editUserForm button[type="submit"]');
    const originalText = submitBtn.textContent;
    submitBtn.textContent = 'กำลังบันทึก...';
    submitBtn.disabled = true;
    
    // Make API call
    fetch(`/admin/api/user-management/users/${userId}`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json; charset=UTF-8',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify(formData)
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(response => {
        console.log('Save response:', response);
        
        if (response.success) {
            // Show success message
            Swal.fire({
                title: 'สำเร็จ',
                text: 'บันทึกข้อมูลเรียบร้อยแล้ว',
                icon: 'success',
                customClass: {
                container: 'swal2-container-high-zindex'
            },
            confirmButtonText: 'ตกลง',
                customClass: {
                    container: 'swal2-container-high-zindex'
                }
            }).then(() => {
                // Close modal
                const editModal = bootstrap.Modal.getInstance(document.getElementById('editUserModal'));
                editModal.hide();
                
                // Reload page to show updated data
                window.location.reload();
            });
        } else {
            throw new Error(response.message || 'เกิดข้อผิดพลาดในการบันทึก');
        }
    })
    .catch(error => {
        console.error('Error saving user:', error);
        Swal.fire({
            title: 'ข้อผิดพลาด',
            text: 'เกิดข้อผิดพลาดในการบันทึก: ' + error.message,
            icon: 'error',
            customClass: {
                container: 'swal2-container-high-zindex'
            },
            confirmButtonText: 'ตกลง',
            customClass: {
                container: 'swal2-container-high-zindex'
            },
            confirmButtonText: 'ตกลง'
        });
    })
    .finally(() => {
        // Restore button
        submitBtn.textContent = originalText;
        submitBtn.disabled = false;
    });
}

function exportUsers() {
    console.log('Exporting users...');
    SwalHelper.loading('กำลังส่งออกข้อมูลผู้ใช้...');
    
    // Create download link
    const link = document.createElement('a');
    link.href = '{{ route("user-management.users.export") }}';
    link.download = 'users_export.csv';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
    
    setTimeout(() => {
        SwalHelper.close();
        SwalHelper.success('ส่งออกข้อมูลผู้ใช้สำเร็จ!');
    }, 2000);
}

// New UX/UI Functions
function filterUsers() {
    const searchTerm = document.getElementById('usersSearchInput').value.toLowerCase();
    const statusFilter = document.getElementById('statusFilter').value;
    const roleFilter = document.getElementById('roleFilter').value;
    const tableRows = document.querySelectorAll('#usersTable tbody tr.user-row');
    let visibleCount = 0;
    
    tableRows.forEach(row => {
        const text = row.textContent.toLowerCase();
        const userStatus = row.getAttribute('data-status');
        const userRoles = Array.from(row.querySelectorAll('.role-badge')).map(badge => badge.textContent.trim());
        
        let showRow = true;
        
        // Search filter
        if (searchTerm && !text.includes(searchTerm)) {
            showRow = false;
        }
        
        // Status filter
        if (statusFilter && userStatus !== statusFilter) {
            showRow = false;
        }
        
        // Role filter
        if (roleFilter) {
            const roleId = roleFilter;
            const hasRole = row.querySelector(`input[value="${roleId}"]`) !== null;
            if (!hasRole) {
                showRow = false;
            }
        }
        
        if (showRow) {
            row.style.display = '';
            visibleCount++;
        } else {
            row.style.display = 'none';
        }
    });
    
    // Update count
    document.getElementById('userCount').textContent = visibleCount;
}

function toggleSelectAll() {
    const selectAllCheckbox = document.getElementById('selectAll');
    const userCheckboxes = document.querySelectorAll('.user-checkbox');
    
    userCheckboxes.forEach(checkbox => {
        checkbox.checked = selectAllCheckbox.checked;
    });
    
    updateBulkActions();
}

function selectAllUsers() {
    const userCheckboxes = document.querySelectorAll('.user-checkbox');
    userCheckboxes.forEach(checkbox => {
        checkbox.checked = true;
    });
    document.getElementById('selectAll').checked = true;
    updateBulkActions();
}

function clearSelection() {
    const userCheckboxes = document.querySelectorAll('.user-checkbox');
    userCheckboxes.forEach(checkbox => {
        checkbox.checked = false;
    });
    document.getElementById('selectAll').checked = false;
    updateBulkActions();
}

function updateBulkActions() {
    const selectedCheckboxes = document.querySelectorAll('.user-checkbox:checked');
    const bulkActions = document.querySelectorAll('[onclick*="bulkStatusUpdate"], [onclick*="bulkDeleteUsers"]');
    
    bulkActions.forEach(action => {
        if (selectedCheckboxes.length > 0) {
            // Enable actions
            action.style.opacity = '1';
            action.style.pointerEvents = 'auto';
            action.style.cursor = 'pointer';
            action.classList.remove('disabled');
        } else {
            // Disable actions
            action.style.opacity = '0.5';
            action.style.pointerEvents = 'none';
            action.style.cursor = 'not-allowed';
            action.classList.add('disabled');
        }
    });
}

function bulkStatusUpdate(status) {
    const selectedCheckboxes = document.querySelectorAll('.user-checkbox:checked');
    if (selectedCheckboxes.length === 0) {
        SwalHelper.error('กรุณาเลือกผู้ใช้ที่ต้องการอัปเดต');
        return;
    }
    
    const userIds = Array.from(selectedCheckboxes).map(cb => cb.value);
    const statusText = {
        'active': 'ใช้งาน',
        'inactive': 'ไม่ใช้งาน',
        'pending': 'รอการยืนยัน',
        'suspended': 'ระงับการใช้งาน'
    };
    
    SwalHelper.confirmDelete(`คุณแน่ใจหรือไม่ที่จะเปลี่ยนสถานะผู้ใช้ ${userIds.length} คนเป็น "${statusText[status]}"?`, function() {
        UserManagementUtils.showLoading('กำลังอัปเดตสถานะ...');
        
        Promise.all(userIds.map(userId => 
            UserManagementUtils.apiRequest(`{{ route('user-management.users.status') }}/${userId}`, {
                method: 'POST',
                body: JSON.stringify({ status: status })
            })
        ))
        .then(responses => Promise.all(responses.map(r => r.json())))
        .then(results => {
            UserManagementUtils.hideLoading();
            if (results.every(r => r.success)) {
                SwalHelper.success(`อัปเดตสถานะผู้ใช้ ${userIds.length} คนเรียบร้อยแล้ว`);
                setTimeout(() => location.reload(), 1500);
            } else {
                SwalHelper.error('เกิดข้อผิดพลาดในการอัปเดตบางรายการ');
            }
        })
        .catch(error => {
            UserManagementUtils.hideLoading();
            console.error('Error:', error);
            SwalHelper.error('เกิดข้อผิดพลาดในการอัปเดตสถานะ');
        });
    });
}

function bulkDeleteUsers() {
    const selectedCheckboxes = document.querySelectorAll('.user-checkbox:checked');
    if (selectedCheckboxes.length === 0) {
        SwalHelper.error('กรุณาเลือกผู้ใช้ที่ต้องการลบ');
        return;
    }
    
    const userIds = Array.from(selectedCheckboxes).map(cb => cb.value);
    
    // Check if trying to delete current user
    const currentUserId = document.querySelector('meta[name="user-id"]')?.content;
    if (currentUserId && userIds.includes(currentUserId)) {
        SwalHelper.error('ไม่สามารถลบบัญชีของตัวเองได้');
        return;
    }
    
    SwalHelper.confirmDelete(`คุณแน่ใจหรือไม่ที่จะลบผู้ใช้ ${userIds.length} คน?\n\nการดำเนินการนี้ไม่สามารถย้อนกลับได้`, function() {
        UserManagementUtils.showLoading('กำลังลบผู้ใช้...');
        
        Promise.all(userIds.map(userId => 
            UserManagementUtils.apiRequest(`/admin/api/user-management/users/${userId}`, {
                method: 'DELETE'
            })
        ))
        .then(responses => Promise.all(responses.map(r => r.json())))
        .then(results => {
            UserManagementUtils.hideLoading();
            if (results.every(r => r.success)) {
                SwalHelper.success(`ลบผู้ใช้ ${userIds.length} คนเรียบร้อยแล้ว`);
                setTimeout(() => location.reload(), 1500);
            } else {
                SwalHelper.error('เกิดข้อผิดพลาดในการลบบางรายการ');
            }
        })
        .catch(error => {
            UserManagementUtils.hideLoading();
            console.error('Error:', error);
            SwalHelper.error('เกิดข้อผิดพลาดในการลบผู้ใช้');
        });
    });
}

function updateUserStatus(userId, status) {
    const statusText = {
        'active': 'ใช้งาน',
        'inactive': 'ไม่ใช้งาน',
        'pending': 'รอการยืนยัน',
        'suspended': 'ระงับการใช้งาน'
    };
    
    SwalHelper.confirmDelete(`คุณแน่ใจหรือไม่ที่จะเปลี่ยนสถานะเป็น "${statusText[status]}"?`, function() {
        UserManagementUtils.showLoading('กำลังอัปเดตสถานะ...');
        
        UserManagementUtils.apiRequest(`{{ route('user-management.users.status') }}/${userId}`, {
            method: 'POST',
            body: JSON.stringify({ status: status })
        })
        .then(response => response.json())
        .then(data => {
            UserManagementUtils.hideLoading();
            if (data.success) {
                SwalHelper.success('อัปเดตสถานะเรียบร้อยแล้ว');
                setTimeout(() => location.reload(), 1500);
            } else {
                SwalHelper.error(data.message || 'ไม่สามารถอัปเดตสถานะได้');
            }
        })
        .catch(error => {
            UserManagementUtils.hideLoading();
            console.error('Error:', error);
            SwalHelper.error('เกิดข้อผิดพลาดในการอัปเดตสถานะ');
        });
    });
}

function refreshUsers() {
    UserManagementUtils.showLoading('กำลังรีเฟรชข้อมูล...');
    setTimeout(() => {
        location.reload();
    }, 1000);
}

function toggleViewMode() {
    const viewModeBtn = document.getElementById('viewModeBtn');
    const tableContainer = document.querySelector('.table-responsive');
    
    if (viewModeBtn.innerHTML.includes('fa-th-large')) {
        // Switch to card view
        viewModeBtn.innerHTML = '<i class="fas fa-table"></i>';
        // Add card view functionality here
    } else {
        // Switch to table view
        viewModeBtn.innerHTML = '<i class="fas fa-th-large"></i>';
        // Remove card view functionality here
    }
}

function togglePassword(fieldId) {
    const field = document.getElementById(fieldId);
    const button = field.nextElementSibling;
    const icon = button.querySelector('i');
    
    if (field.type === 'password') {
        field.type = 'text';
        icon.className = 'fas fa-eye-slash';
    } else {
        field.type = 'password';
        icon.className = 'fas fa-eye';
    }
}

function previewAvatar(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('avatarPreview').src = e.target.result;
        };
        reader.readAsDataURL(input.files[0]);
    }
}

// Filter functions for dropdowns
function filterByStatus(status) {
    console.log('=== filterByStatus called ===');
    console.log('Status parameter:', status);
    console.log('Function called from:', arguments.callee.caller);
    
    try {
        const rows = document.querySelectorAll('.user-row');
        console.log('Found rows:', rows.length);
        
        // Find the status dropdown button - use a simpler approach
        let statusDropdown = null;
        
        // Find dropdown by looking for the one with fa-filter icon
        const dropdowns = document.querySelectorAll('.dropdown');
        dropdowns.forEach(dropdown => {
            const button = dropdown.querySelector('.dropdown-toggle');
            if (button) {
                const icon = button.querySelector('.fa-filter');
                if (icon) {
                    statusDropdown = dropdown;
                }
            }
        });
        
        // Fallback: find by button text content
        if (!statusDropdown) {
            dropdowns.forEach(dropdown => {
                const button = dropdown.querySelector('.dropdown-toggle');
                if (button && button.textContent.includes('สถานะ')) {
                    statusDropdown = dropdown;
                }
            });
        }
        
        if (!statusDropdown) {
            console.error('Status dropdown not found');
            return;
        }
        
        const statusButton = statusDropdown.querySelector('.dropdown-toggle');
        if (!statusButton) {
            console.error('Status button not found');
            return;
        }
        
        // Update button text
        const statusText = status === '' ? 'ทุกสถานะ' : 
                          status === 'active' ? 'ใช้งาน' :
                          status === 'inactive' ? 'ไม่ใช้งาน' :
                          status === 'pending' ? 'รอการยืนยัน' :
                          status === 'suspended' ? 'ระงับการใช้งาน' : 'ทุกสถานะ';
        
        statusButton.innerHTML = `<i class="fas fa-filter text-primary me-1"></i>${statusText}`;
        console.log('Updated status button text to:', statusText);
        
        // Filter rows
        let visibleCount = 0;
        rows.forEach(row => {
            const rowStatus = row.getAttribute('data-status');
            if (status === '' || rowStatus === status) {
                row.style.display = '';
                visibleCount++;
            } else {
                row.style.display = 'none';
            }
        });
        
        console.log('Visible rows after filter:', visibleCount);
        
        // Update count
        updateUserCount();
        
        console.log('=== filterByStatus completed ===');
        
    } catch (error) {
        console.error('Error in filterByStatus:', error);
    }
}

function filterByRole(roleId) {
    console.log('=== filterByRole called ===');
    console.log('Role ID parameter:', roleId);
    console.log('Function called from:', arguments.callee.caller);
    
    try {
        const rows = document.querySelectorAll('.user-row');
        console.log('Found rows:', rows.length);
        
        // Find the role dropdown button - use a simpler approach
        let roleDropdown = null;
        
        // Find dropdown by looking for the one with fa-user-shield icon
        const dropdowns = document.querySelectorAll('.dropdown');
        dropdowns.forEach(dropdown => {
            const button = dropdown.querySelector('.dropdown-toggle');
            if (button) {
                const icon = button.querySelector('.fa-user-shield');
                if (icon) {
                    roleDropdown = dropdown;
                }
            }
        });
        
        // Fallback: find by button text content
        if (!roleDropdown) {
            dropdowns.forEach(dropdown => {
                const button = dropdown.querySelector('.dropdown-toggle');
                if (button && button.textContent.includes('บทบาท')) {
                    roleDropdown = dropdown;
                }
            });
        }
        
        if (!roleDropdown) {
            console.error('Role dropdown not found');
            return;
        }
        
        const roleButton = roleDropdown.querySelector('.dropdown-toggle');
        if (!roleButton) {
            console.error('Role button not found');
            return;
        }
        
        // Update button text
        if (roleId === '') {
            roleButton.innerHTML = '<i class="fas fa-user-shield text-primary me-1"></i>ทุกบทบาท';
        } else {
            const roleName = getRoleNameById(roleId);
            roleButton.innerHTML = `<i class="fas fa-user-shield text-primary me-1"></i>${roleName}`;
        }
        
        console.log('Updated role button text');
        
        // Filter rows
        let visibleCount = 0;
        rows.forEach(row => {
            if (roleId === '') {
                row.style.display = '';
                visibleCount++;
            } else {
                const roleBadges = row.querySelectorAll('.role-badge');
                let hasRole = false;
                roleBadges.forEach(badge => {
                    if (badge.textContent.trim() === getRoleNameById(roleId)) {
                        hasRole = true;
                    }
                });
                if (hasRole) {
                    row.style.display = '';
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                }
            }
        });
        
        console.log('Visible rows after filter:', visibleCount);
        
        // Update count
        updateUserCount();
        
        console.log('=== filterByRole completed ===');
        
    } catch (error) {
        console.error('Error in filterByRole:', error);
    }
}

function getRoleNameById(roleId) {
    // Map role IDs to names based on the actual data
    const roleNames = {
        '1': 'Super Admin',
        '2': 'Admin', 
        '3': 'Moderator',
        '4': 'User'
    };
    return roleNames[roleId] || 'Unknown Role';
}

function updateUserCount() {
    const visibleRows = document.querySelectorAll('.user-row:not([style*="display: none"])');
    const countElement = document.getElementById('userCount');
    if (countElement) {
        countElement.textContent = visibleRows.length;
    }
}

// Test function to verify JavaScript is working
function testFilter() {
    console.log('Test filter function called');
    alert('JavaScript is working!');
}

// Test function to check dropdown elements
function testDropdownElements() {
    console.log('=== Testing Dropdown Elements ===');
    
    // Check status dropdown items
    const statusItems = document.querySelectorAll('[data-filter="status"]');
    console.log('Status items found:', statusItems.length);
    statusItems.forEach((item, index) => {
        console.log(`Status item ${index}:`, {
            element: item,
            dataFilter: item.getAttribute('data-filter'),
            dataValue: item.getAttribute('data-value'),
            text: item.textContent.trim(),
            href: item.getAttribute('href')
        });
    });
    
    // Check role dropdown items
    const roleItems = document.querySelectorAll('[data-filter="role"]');
    console.log('Role items found:', roleItems.length);
    roleItems.forEach((item, index) => {
        console.log(`Role item ${index}:`, {
            element: item,
            dataFilter: item.getAttribute('data-filter'),
            dataValue: item.getAttribute('data-value'),
            text: item.textContent.trim(),
            href: item.getAttribute('href')
        });
    });
    
    // Test manual click
    console.log('Testing manual click on first status item...');
    if (statusItems.length > 0) {
        const firstItem = statusItems[0];
        console.log('Clicking:', firstItem);
        firstItem.click();
    }
    
    alert('Check console for element details');
}

// Initialize filter event listeners
document.addEventListener('DOMContentLoaded', function() {
    console.log('User Management JS loaded');
    
    // Simple approach - just make sure functions are available
    console.log('Functions made available globally');
    
    // Test if functions are available globally
    window.testFilter = testFilter;
    window.testDropdownElements = testDropdownElements;
    window.filterByStatus = filterByStatus;
    window.filterByRole = filterByRole;
    
    console.log('All functions available:', {
        testFilter: typeof window.testFilter,
        filterByStatus: typeof window.filterByStatus,
        filterByRole: typeof window.filterByRole
    });
});
