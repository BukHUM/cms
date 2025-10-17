// Simple filterByStatus function that works with select elements
function filterByStatus(status) {
    console.log('=== filterByStatus called ===');
    console.log('Status parameter:', status);
    
    try {
        const rows = document.querySelectorAll('.user-row');
        console.log('Found rows:', rows.length);
        
        let visibleCount = 0;
        
        rows.forEach((row, index) => {
            const statusBadge = row.querySelector('.status-badge');
            console.log(`Row ${index}:`, row, 'Status badge:', statusBadge);
            
            if (statusBadge) {
                const rowStatusText = statusBadge.textContent.trim();
                // Map Thai text to English status
                let rowStatus = '';
                if (rowStatusText === 'ใช้งาน') {
                    rowStatus = 'active';
                } else if (rowStatusText === 'รอการยืนยัน') {
                    rowStatus = 'pending';
                } else if (rowStatusText === 'ระงับการใช้งาน') {
                    rowStatus = 'suspended';
                } else if (rowStatusText === 'ไม่ใช้งาน') {
                    rowStatus = 'inactive';
                }
                
                console.log(`Row ${index} status text:`, rowStatusText, 'Row status:', rowStatus, 'Filter status:', status);
                
                if (status === '' || rowStatus === status) {
                    row.style.display = '';
                    visibleCount++;
                    console.log(`Row ${index} shown`);
                } else {
                    row.style.display = 'none';
                    console.log(`Row ${index} hidden`);
                }
            } else {
                // If no status badge found, show the row
                row.style.display = '';
                visibleCount++;
                console.log(`Row ${index} shown (no status badge)`);
            }
        });
        
        console.log('Final visible count:', visibleCount);
        
        // Update count
        const userCountElement = document.getElementById('userCount');
        if (userCountElement) {
            userCountElement.textContent = visibleCount;
            console.log('Updated user count to:', visibleCount);
        } else {
            console.error('User count element not found');
        }
        
        // Show/hide pagination based on visible count
        const paginationContainer = document.querySelector('.pagination-container');
        if (paginationContainer) {
            if (visibleCount === 0) {
                paginationContainer.style.display = 'none';
                console.log('Pagination hidden');
            } else {
                paginationContainer.style.display = 'block';
                console.log('Pagination shown');
            }
        } else {
            console.error('Pagination container not found');
        }
        
    } catch (error) {
        console.error('Error in filterByStatus:', error);
    }
}

// Simple filterByRole function that works with select elements
function filterByRole(roleId) {
    console.log('=== filterByRole called ===');
    console.log('Role ID parameter:', roleId);
    
    try {
        const rows = document.querySelectorAll('.user-row');
        console.log('Found rows:', rows.length);
        
        let visibleCount = 0;
        
        rows.forEach((row, index) => {
            if (roleId === '') {
                // Show all rows
                row.style.display = '';
                visibleCount++;
                console.log(`Row ${index} shown (all roles)`);
            } else {
                // Check if row has the selected role
                const roleBadges = row.querySelectorAll('.role-badge');
                let hasRole = false;
                
                roleBadges.forEach(badge => {
                    if (badge.dataset.roleId === roleId) {
                        hasRole = true;
                    }
                });
                
                if (hasRole) {
                    row.style.display = '';
                    visibleCount++;
                    console.log(`Row ${index} shown (has role ${roleId})`);
                } else {
                    row.style.display = 'none';
                    console.log(`Row ${index} hidden (no role ${roleId})`);
                }
            }
        });
        
        console.log('Final visible count:', visibleCount);
        
        // Update count
        const userCountElement = document.getElementById('userCount');
        if (userCountElement) {
            userCountElement.textContent = visibleCount;
            console.log('Updated user count to:', visibleCount);
        } else {
            console.error('User count element not found');
        }
        
        // Show/hide pagination based on visible count
        const paginationContainer = document.querySelector('.pagination-container');
        if (paginationContainer) {
            if (visibleCount === 0) {
                paginationContainer.style.display = 'none';
                console.log('Pagination hidden');
            } else {
                paginationContainer.style.display = 'block';
                console.log('Pagination shown');
            }
        } else {
            console.error('Pagination container not found');
        }
        
    } catch (error) {
        console.error('Error in filterByRole:', error);
    }
}

// Search function for user search input
function searchUsers(searchTerm) {
    console.log('=== searchUsers called ===');
    console.log('Search term:', searchTerm);
    
    try {
        const rows = document.querySelectorAll('.user-row');
        console.log('Found rows:', rows.length);
        
        let visibleCount = 0;
        
        rows.forEach((row, index) => {
            if (searchTerm === '') {
                // Show all rows if search is empty
                row.style.display = '';
                visibleCount++;
                console.log(`Row ${index} shown (empty search)`);
            } else {
                // Search in user name, email, and phone
                const nameElement = row.querySelector('.fw-medium');
                const emailElement = row.querySelector('.me-2');
                const phoneElement = row.querySelector('.text-muted');
                
                let found = false;
                
                if (nameElement && nameElement.textContent.toLowerCase().includes(searchTerm.toLowerCase())) {
                    found = true;
                }
                
                if (emailElement && emailElement.textContent.toLowerCase().includes(searchTerm.toLowerCase())) {
                    found = true;
                }
                
                if (phoneElement && phoneElement.textContent.toLowerCase().includes(searchTerm.toLowerCase())) {
                    found = true;
                }
                
                if (found) {
                    row.style.display = '';
                    visibleCount++;
                    console.log(`Row ${index} shown (found in search)`);
                } else {
                    row.style.display = 'none';
                    console.log(`Row ${index} hidden (not found in search)`);
                }
            }
        });
        
        console.log('Final visible count:', visibleCount);
        
        // Update count
        const userCountElement = document.getElementById('userCount');
        if (userCountElement) {
            userCountElement.textContent = visibleCount;
            console.log('Updated user count to:', visibleCount);
        } else {
            console.error('User count element not found');
        }
        
        // Show/hide pagination based on visible count
        const paginationContainer = document.querySelector('.pagination-container');
        if (paginationContainer) {
            if (visibleCount === 0) {
                paginationContainer.style.display = 'none';
                console.log('Pagination hidden');
            } else {
                paginationContainer.style.display = 'block';
                console.log('Pagination shown');
            }
        } else {
            console.error('Pagination container not found');
        }
        
    } catch (error) {
        console.error('Error in searchUsers:', error);
    }
}
