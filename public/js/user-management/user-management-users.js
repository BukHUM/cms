// Users Tab JavaScript
document.addEventListener('DOMContentLoaded', function() {
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
    // Redirect to edit page
    window.location.href = `{{ route('user-management.users.show') }}/${userId}/edit`;
}

function viewUser(userId) {
    console.log('View user:', userId);
    // Redirect to view page
    window.location.href = `{{ route('user-management.users.show') }}/${userId}`;
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
    SwalHelper.confirmDelete('คุณแน่ใจหรือไม่ที่จะลบผู้ใช้นี้?', function() {
        fetch(`{{ route('user-management.users.delete') }}/${userId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                SwalHelper.success('ลบผู้ใช้เรียบร้อยแล้ว');
                setTimeout(() => {
                    location.reload();
                }, 1500);
            } else {
                SwalHelper.error(data.message || 'ไม่สามารถลบผู้ใช้ได้');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            SwalHelper.error('เกิดข้อผิดพลาดในการลบผู้ใช้');
        });
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
    const bulkActions = document.querySelectorAll('[onclick*="bulkStatusUpdate"]');
    
    bulkActions.forEach(action => {
        action.style.opacity = selectedCheckboxes.length > 0 ? '1' : '0.5';
        action.style.pointerEvents = selectedCheckboxes.length > 0 ? 'auto' : 'none';
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
