// User Management Navigation JavaScript
let currentTab = 'users';

function switchTab(tabName, iconClass, tabText) {
    console.log('=== switchTab called ===');
    console.log('Tab name:', tabName);
    console.log('Icon class:', iconClass);
    console.log('Tab text:', tabText);
    
    // Hide all tab content
    document.querySelectorAll('.tab-pane').forEach(pane => {
        pane.classList.remove('show', 'active');
        console.log('Removed show/active from:', pane.id);
    });
    
    // Remove active class from all nav links
    document.querySelectorAll('.nav-link').forEach(link => {
        link.classList.remove('active');
    });
    
    // Show selected tab content
    const targetPane = document.getElementById(tabName);
    console.log('Target pane:', targetPane);
    if (targetPane) {
        targetPane.classList.add('show', 'active');
        console.log('Added show/active to:', tabName);
    } else {
        console.error('Target pane not found:', tabName);
    }
    
    // Add active class to selected nav link
    const targetLink = document.getElementById(tabName + '-tab');
    if (targetLink) {
        targetLink.classList.add('active');
    }
    
    // Update mobile display
    updateMobileDisplay(iconClass, tabText);
    
    // Update current tab
    currentTab = tabName;
    
    // Save to localStorage
    localStorage.setItem('userManagementActiveTab', tabName);
    
    // Close mobile dropdown if open
    const dropdown = document.getElementById('userManagementDropdown');
    if (dropdown) {
        dropdown.style.display = 'none';
    }
}

function updateMobileDisplay(iconClass, tabText) {
    const currentTabIcon = document.getElementById('currentTabIcon');
    const currentTabText = document.getElementById('currentTabText');
    
    if (currentTabIcon) {
        currentTabIcon.className = iconClass;
    }
    
    if (currentTabText) {
        currentTabText.textContent = tabText;
    }
}

function toggleUserManagementDropdown() {
    const dropdown = document.getElementById('userManagementDropdown');
    if (dropdown) {
        if (dropdown.style.display === 'none' || dropdown.style.display === '') {
            dropdown.style.display = 'block';
        } else {
            dropdown.style.display = 'none';
        }
    }
}

function loadLastActiveTab() {
    const lastTab = localStorage.getItem('userManagementActiveTab');
    if (lastTab) {
        // Map tab names to their display info
        const tabInfo = {
            'users': { icon: 'fas fa-users', text: 'ผู้ใช้' },
            'roles': { icon: 'fas fa-user-shield', text: 'บทบาท' },
            'permissions': { icon: 'fas fa-key', text: 'สิทธิ์' }
        };
        
        if (tabInfo[lastTab]) {
            switchTab(lastTab, tabInfo[lastTab].icon, tabInfo[lastTab].text);
        }
    }
}

// Close dropdown when clicking outside
document.addEventListener('click', function(e) {
    const dropdown = document.getElementById('userManagementDropdown');
    const dropdownToggle = document.querySelector('.current-tab-display');
    
    if (dropdown && dropdownToggle && 
        !dropdown.contains(e.target) && 
        !dropdownToggle.contains(e.target)) {
        dropdown.style.display = 'none';
    }
});

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    loadLastActiveTab();
});
