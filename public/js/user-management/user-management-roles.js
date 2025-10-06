// Roles Tab JavaScript
document.addEventListener('DOMContentLoaded', function() {
    // Search functionality
    const rolesSearchInput = document.getElementById('rolesSearchInput');
    if (rolesSearchInput) {
        rolesSearchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const tableRows = document.querySelectorAll('#rolesTable tbody tr');
            
            tableRows.forEach(row => {
                const text = row.textContent.toLowerCase();
                if (text.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    }

    // Auto-generate slug from name
    const roleNameInput = document.getElementById('roleName');
    if (roleNameInput) {
        roleNameInput.addEventListener('input', function() {
            const slug = this.value
                .toLowerCase()
                .replace(/[^a-z0-9\s-]/g, '')
                .replace(/\s+/g, '-')
                .replace(/-+/g, '-')
                .trim('-');
            document.getElementById('roleSlug').value = slug;
        });
    }

    // Add role form
    const addRoleForm = document.getElementById('addRoleForm');
    if (addRoleForm) {
        addRoleForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Get form data
            const formData = {
                name: document.getElementById('roleName').value,
                slug: document.getElementById('roleSlug').value,
                description: document.getElementById('roleDescription').value,
                color: document.getElementById('roleColor').value,
                is_active: document.getElementById('roleIsActive').checked,
                sort_order: parseInt(document.getElementById('roleSortOrder').value) || 0,
                permissions: Array.from(document.querySelectorAll('input[name="permissions[]"]:checked')).map(cb => cb.value)
            };
            
            // Send data to backend
            fetch('{{ route("user-management.roles.store") }}', {
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
                    SwalHelper.success('เพิ่มบทบาทเรียบร้อยแล้ว');
                    // Close modal
                    const modal = bootstrap.Modal.getInstance(document.getElementById('addRoleModal'));
                    modal.hide();
                    // Reset form
                    this.reset();
                    // Reload page
                    setTimeout(() => {
                        location.reload();
                    }, 1500);
                } else {
                    SwalHelper.error(data.message || 'ไม่สามารถเพิ่มบทบาทได้');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                SwalHelper.error('เกิดข้อผิดพลาดในการเพิ่มบทบาท');
            });
        });
    }
});

// Role actions
function editRole(roleId) {
    console.log('Edit role:', roleId);
    // Redirect to edit page
    window.location.href = `{{ route('user-management.roles.show') }}/${roleId}/edit`;
}

function viewRole(roleId) {
    console.log('View role:', roleId);
    // Redirect to view page
    window.location.href = `{{ route('user-management.roles.show') }}/${roleId}`;
}

function managePermissions(roleId) {
    console.log('Manage permissions for role:', roleId);
    
    // Load role data
    fetch(`{{ route('user-management.roles.permissions') }}/${roleId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const role = data.data.role;
                const permissions = data.data.permissions;
                
                // Update modal content
                document.getElementById('roleInfo').innerHTML = `
                    <div class="alert alert-info">
                        <h6 class="mb-1">${role.name}</h6>
                        <small>${role.description || 'ไม่มีคำอธิบาย'}</small>
                    </div>
                `;
                
                // Generate permissions HTML
                let permissionsHtml = '';
                const groupedPermissions = {};
                
                // Group permissions by group
                role.permissions.forEach(permission => {
                    if (!groupedPermissions[permission.group]) {
                        groupedPermissions[permission.group] = [];
                    }
                    groupedPermissions[permission.group].push(permission);
                });
                
                Object.keys(groupedPermissions).forEach(group => {
                    permissionsHtml += `
                        <div class="permission-group">
                            <h6>${group}</h6>
                    `;
                    
                    groupedPermissions[group].forEach(permission => {
                        const isChecked = permissions.includes(permission.id.toString());
                        permissionsHtml += `
                            <div class="permission-item">
                                <input type="checkbox" name="permissions[]" value="${permission.id}" id="manage_perm_${permission.id}" ${isChecked ? 'checked' : ''}>
                                <label for="manage_perm_${permission.id}" class="form-check-label">
                                    ${permission.name}
                                    ${permission.description ? `<small class="text-muted d-block">${permission.description}</small>` : ''}
                                </label>
                            </div>
                        `;
                    });
                    
                    permissionsHtml += '</div>';
                });
                
                document.getElementById('permissionsManageContainer').innerHTML = permissionsHtml;
                
                // Show modal
                const modal = new bootstrap.Modal(document.getElementById('managePermissionsModal'));
                modal.show();
                
                // Set up form submission
                document.getElementById('managePermissionsForm').onsubmit = function(e) {
                    e.preventDefault();
                    
                    const selectedPermissions = Array.from(document.querySelectorAll('#managePermissionsForm input[name="permissions[]"]:checked')).map(cb => cb.value);
                    
                    fetch(`{{ route('user-management.roles.permissions.update') }}/${roleId}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({ permissions: selectedPermissions })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            SwalHelper.success('อัปเดตสิทธิ์เรียบร้อยแล้ว');
                            modal.hide();
                            setTimeout(() => {
                                location.reload();
                            }, 1500);
                        } else {
                            SwalHelper.error(data.message || 'ไม่สามารถอัปเดตสิทธิ์ได้');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        SwalHelper.error('เกิดข้อผิดพลาดในการอัปเดตสิทธิ์');
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

function deleteRole(roleId) {
    SwalHelper.confirmDelete('คุณแน่ใจหรือไม่ที่จะลบบทบาทนี้?', function() {
        fetch(`{{ route('user-management.roles.delete') }}/${roleId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                SwalHelper.success('ลบบทบาทเรียบร้อยแล้ว');
                setTimeout(() => {
                    location.reload();
                }, 1500);
            } else {
                SwalHelper.error(data.message || 'ไม่สามารถลบบทบาทได้');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            SwalHelper.error('เกิดข้อผิดพลาดในการลบบทบาท');
        });
    });
}
