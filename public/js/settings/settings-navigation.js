// ========================================
// SETTINGS NAVIGATION FUNCTIONS
// ========================================

// Mobile Settings Navigation
function toggleSettingsDropdown() {
    const dropdown = document.getElementById('settingsDropdown');
    const arrow = document.querySelector('.dropdown-arrow');
    
    if (dropdown.style.display === 'none') {
        dropdown.style.display = 'block';
        arrow.style.transform = 'rotate(180deg)';
    } else {
        dropdown.style.display = 'none';
        arrow.style.transform = 'rotate(0deg)';
    }
}

// Switch between tabs (works for both desktop and mobile)
function switchTab(tabId, iconClass, tabText) {
    // Hide all tab panes
    document.querySelectorAll('.tab-pane').forEach(pane => {
        pane.classList.remove('show', 'active');
    });
    
    // Show selected tab pane
    document.getElementById(tabId).classList.add('show', 'active');
    
    // Update mobile navigation display
    document.getElementById('currentTabIcon').className = iconClass;
    document.getElementById('currentTabText').textContent = tabText;
    
    // Close dropdown
    document.getElementById('settingsDropdown').style.display = 'none';
    document.querySelector('.dropdown-arrow').style.transform = 'rotate(0deg)';
    
    // Update desktop tabs if visible
    if (window.innerWidth >= 768) {
        document.querySelectorAll('.nav-link').forEach(link => {
            link.classList.remove('active');
        });
        document.getElementById(tabId + '-tab').classList.add('active');
    }
    
    // Save current tab to localStorage
    localStorage.setItem('lastActiveSettingsTab', tabId);
    localStorage.setItem('lastActiveSettingsTabIcon', iconClass);
    localStorage.setItem('lastActiveSettingsTabText', tabText);
    
    // Note: Performance data will be loaded manually when user clicks refresh buttons
}

// ========================================
// TAB PERSISTENCE FUNCTIONS
// ========================================

// Load last active tab from localStorage
function loadLastActiveTab() {
    const lastTab = localStorage.getItem('lastActiveSettingsTab');
    const lastTabIcon = localStorage.getItem('lastActiveSettingsTabIcon');
    const lastTabText = localStorage.getItem('lastActiveSettingsTabText');
    
    if (lastTab && lastTabIcon && lastTabText) {
        // Hide all tab panes first
        document.querySelectorAll('.tab-pane').forEach(pane => {
            pane.classList.remove('show', 'active');
        });
        
        // Show selected tab pane immediately
        document.getElementById(lastTab).classList.add('show', 'active');
        
        // Update mobile navigation display
        document.getElementById('currentTabIcon').className = lastTabIcon;
        document.getElementById('currentTabText').textContent = lastTabText;
        
        // Update desktop tabs if visible
        if (window.innerWidth >= 768) {
            document.querySelectorAll('.nav-link').forEach(link => {
                link.classList.remove('active');
            });
            const desktopTab = document.getElementById(lastTab + '-tab');
            if (desktopTab) {
                desktopTab.classList.add('active');
            }
        }
        
        console.log('Loaded last active tab:', lastTab);
    } else {
        // Default to general tab if no saved tab
        console.log('No saved tab found, using default general tab');
        
        // Show general tab immediately
        document.getElementById('general').classList.add('show', 'active');
        document.getElementById('general-tab').classList.add('active');
        document.getElementById('currentTabIcon').className = 'fas fa-cog';
        document.getElementById('currentTabText').textContent = 'ทั่วไป';
    }
}
