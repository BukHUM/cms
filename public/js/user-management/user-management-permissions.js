// Permissions Tab JavaScript
document.addEventListener('DOMContentLoaded', function() {
    // Search functionality
    const permissionsSearchInput = document.getElementById('permissionsSearchInput');
    if (permissionsSearchInput) {
        permissionsSearchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const tableRows = document.querySelectorAll('#permissionsTable tbody tr');
            
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
    const permissionNameInput = document.getElementById('permissionName');
    if (permissionNameInput) {
        permissionNameInput.addEventListener('input', function() {
            const slug = this.value
                .toLowerCase()
                .replace(/[^a-z0-9\s-]/g, '')
                .replace(/\s+/g, '-')
                .replace(/-+/g, '-')
                .trim('-');
            document.getElementById('permissionSlug').value = slug;
        });
    }

    // Add permission form
    const addPermissionForm = document.getElementById('addPermissionForm');
    if (addPermissionForm) {
        addPermissionForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Get form data
            const formData = {
                name: document.getElementById('permissionName').value,
                slug: document.getElementById('permissionSlug').value,
                description: document.getElementById('permissionDescription').value,
                group: document.getElementById('permissionGroup').value,
                action: document.getElementById('permissionAction').value,
                resource: document.getElementById('permissionResource').value,
                is_active: document.getElementById('permissionIsActive').checked,
                sort_order: parseInt(document.getElementById('permissionSortOrder').value) || 0
            };
            
            // Send data to backend
            fetch('{{ route("user-management.permissions.store") }}', {
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
                    SwalHelper.success('เพิ่มสิทธิ์เรียบร้อยแล้ว');
                    // Close modal
                    const modal = bootstrap.Modal.getInstance(document.getElementById('addPermissionModal'));
                    modal.hide();
                    // Reset form
                    this.reset();
                    // Reload page
                    setTimeout(() => {
                        location.reload();
                    }, 1500);
                } else {
                    SwalHelper.error(data.message || 'ไม่สามารถเพิ่มสิทธิ์ได้');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                SwalHelper.error('เกิดข้อผิดพลาดในการเพิ่มสิทธิ์');
            });
        });
    }
});

// Permission actions
function editPermission(permissionId) {
    console.log('Edit permission:', permissionId);
    // Redirect to edit page
    window.location.href = `{{ route('user-management.permissions.show') }}/${permissionId}/edit`;
}

function viewPermission(permissionId) {
    console.log('View permission:', permissionId);
    // Redirect to view page
    window.location.href = `{{ route('user-management.permissions.show') }}/${permissionId}`;
}

function deletePermission(permissionId) {
    SwalHelper.confirmDelete('คุณแน่ใจหรือไม่ที่จะลบสิทธิ์นี้?', function() {
        fetch(`{{ route('user-management.permissions.delete') }}/${permissionId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                SwalHelper.success('ลบสิทธิ์เรียบร้อยแล้ว');
                setTimeout(() => {
                    location.reload();
                }, 1500);
            } else {
                SwalHelper.error(data.message || 'ไม่สามารถลบสิทธิ์ได้');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            SwalHelper.error('เกิดข้อผิดพลาดในการลบสิทธิ์');
        });
    });
}
