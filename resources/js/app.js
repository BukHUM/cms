import './bootstrap';
import Swal from 'sweetalert2';

// Make SweetAlert2 globally available
window.Swal = Swal;

// Responsive utilities
window.ResponsiveUtils = {
    // Check if device is mobile
    isMobile() {
        return window.innerWidth < 768;
    },
    
    // Check if device is tablet
    isTablet() {
        return window.innerWidth >= 768 && window.innerWidth < 1024;
    },
    
    // Check if device is desktop
    isDesktop() {
        return window.innerWidth >= 1024;
    },
    
    // Get current breakpoint
    getBreakpoint() {
        if (this.isMobile()) return 'mobile';
        if (this.isTablet()) return 'tablet';
        return 'desktop';
    },
    
    // Add responsive class to element
    addResponsiveClass(element, classes) {
        const breakpoint = this.getBreakpoint();
        const responsiveClasses = classes[breakpoint] || classes.default || '';
        element.classList.add(...responsiveClasses.split(' '));
    },
    
    // Handle responsive table
    makeTableResponsive(table) {
        if (this.isMobile()) {
            table.classList.add('table-responsive');
        } else {
            table.classList.remove('table-responsive');
        }
    },
    
    // Handle responsive images
    makeImageResponsive(img) {
        img.classList.add('max-w-full', 'h-auto');
    },
    
    // Handle responsive forms
    makeFormResponsive(form) {
        form.classList.add('form-responsive');
    }
};

// Mobile-specific utilities
window.MobileUtils = {
    // Prevent zoom on form inputs (iOS)
    preventZoom() {
        const inputs = document.querySelectorAll('input, select, textarea');
        inputs.forEach(input => {
            if (window.innerWidth < 768) {
                input.style.fontSize = '16px';
            }
        });
    },
    
    // Add touch-friendly classes
    makeTouchFriendly(elements) {
        elements.forEach(el => {
            el.classList.add('touch-target');
        });
    },
    
    // Handle mobile navigation
    initMobileNav() {
        const mobileMenuButton = document.getElementById('mobile-menu-button');
        const mobileSidebar = document.getElementById('mobile-sidebar');
        const overlay = document.getElementById('mobile-overlay');
        
        if (mobileMenuButton && mobileSidebar) {
            mobileMenuButton.addEventListener('click', () => {
                mobileSidebar.classList.remove('-translate-x-full');
                if (overlay) overlay.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            });
        }
        
        const closeButton = document.getElementById('close-mobile-sidebar');
        if (closeButton && mobileSidebar) {
            closeButton.addEventListener('click', () => {
                mobileSidebar.classList.add('-translate-x-full');
                if (overlay) overlay.classList.add('hidden');
                document.body.style.overflow = '';
            });
        }
        
        if (overlay && mobileSidebar) {
            overlay.addEventListener('click', () => {
                mobileSidebar.classList.add('-translate-x-full');
                overlay.classList.add('hidden');
                document.body.style.overflow = '';
            });
        }
    },
    
    // Handle swipe gestures
    initSwipeGestures() {
        let startX = 0;
        let startY = 0;
        
        document.addEventListener('touchstart', (e) => {
            startX = e.touches[0].clientX;
            startY = e.touches[0].clientY;
        });
        
        document.addEventListener('touchend', (e) => {
            if (!startX || !startY) return;
            
            const endX = e.changedTouches[0].clientX;
            const endY = e.changedTouches[0].clientY;
            
            const diffX = startX - endX;
            const diffY = startY - endY;
            
            // Horizontal swipe
            if (Math.abs(diffX) > Math.abs(diffY)) {
                if (diffX > 50) {
                    // Swipe left - close sidebar
                    const mobileSidebar = document.getElementById('mobile-sidebar');
                    if (mobileSidebar && !mobileSidebar.classList.contains('-translate-x-full')) {
                        mobileSidebar.classList.add('-translate-x-full');
                        const overlay = document.getElementById('mobile-overlay');
                        if (overlay) overlay.classList.add('hidden');
                        document.body.style.overflow = '';
                    }
                }
            }
            
            startX = 0;
            startY = 0;
        });
    }
};

// Initialize responsive features
document.addEventListener('DOMContentLoaded', function() {
    // Initialize mobile navigation
    if (window.MobileUtils && window.MobileUtils.initMobileNav) {
        window.MobileUtils.initMobileNav();
    }
    
    // Initialize swipe gestures
    if (window.MobileUtils && window.MobileUtils.initSwipeGestures) {
        window.MobileUtils.initSwipeGestures();
    }
    
    // Prevent zoom on form inputs
    if (window.MobileUtils && window.MobileUtils.preventZoom) {
        window.MobileUtils.preventZoom();
    }
    
    // Make all tables responsive
    const tables = document.querySelectorAll('table');
    if (tables.length > 0 && window.ResponsiveUtils && window.ResponsiveUtils.makeTableResponsive) {
        tables.forEach(table => {
            window.ResponsiveUtils.makeTableResponsive(table);
        });
    }
    
    // Make all images responsive
    const images = document.querySelectorAll('img');
    if (images.length > 0 && window.ResponsiveUtils && window.ResponsiveUtils.makeImageResponsive) {
        images.forEach(img => {
            window.ResponsiveUtils.makeImageResponsive(img);
        });
    }
    
    // Make all forms responsive
    const forms = document.querySelectorAll('form');
    if (forms.length > 0 && window.ResponsiveUtils && window.ResponsiveUtils.makeFormResponsive) {
        forms.forEach(form => {
            window.ResponsiveUtils.makeFormResponsive(form);
        });
    }
    
    // Handle window resize
    window.addEventListener('resize', () => {
        // Re-apply responsive classes
        if (tables.length > 0 && window.ResponsiveUtils && window.ResponsiveUtils.makeTableResponsive) {
            tables.forEach(table => {
                window.ResponsiveUtils.makeTableResponsive(table);
            });
        }
        
        // Re-apply zoom prevention
        if (window.MobileUtils && window.MobileUtils.preventZoom) {
            window.MobileUtils.preventZoom();
        }
    });
});

// Export for use in other modules
// Note: Using window objects instead of ES6 modules for better compatibility
