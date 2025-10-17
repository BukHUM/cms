# UI Standard Documentation

## 📋 Overview
เอกสารนี้สรุปมาตรฐานการใช้งาน UI Components และ Styles สำหรับ CMS Backend

## ⚠️ สำคัญ: ใช้ Tailwind CSS เท่านั้น
**ห้ามสร้าง Custom CSS เพิ่มเติม** - โปรเจคนี้ใช้ Tailwind CSS v3.4.18 เป็นหลัก และมี Custom Components ที่กำหนดไว้แล้วเท่านั้น

## 🎯 สรุปการใช้งานสำหรับ AI
- **ใช้ Tailwind utility classes**: `bg-blue-600`, `text-white`, `px-4 py-2`, `rounded-md`
- **ใช้ Custom Components ที่มีอยู่**: `.btn-primary`, `.card`, `.form-input`, `.table`, `.alert`
- **ห้ามสร้าง CSS ใหม่**: ไม่ต้องเขียน `<style>` หรือ CSS classes เพิ่มเติม
- **ใช้ Font Awesome icons**: `<i class="fas fa-icon-name"></i>`
- **Responsive**: ใช้ `sm:`, `md:`, `lg:`, `xl:` prefixes
- **ใช้แบบ Local**: ทุก library ใช้แบบ local ไม่ต้องเรียกใช้จาก CDN

## 🎨 CSS Framework & Libraries

### Tailwind CSS v3.4.18 (หลัก)
- **Framework**: Tailwind CSS สำหรับ utility-first CSS
- **Installation**: `npm install tailwindcss`
- **Configuration**: `tailwind.config.cjs`
- **PostCSS**: `postcss.config.cjs`
- **Main CSS**: `resources/css/app.css`
- **การใช้งาน**: ใช้ Tailwind utility classes เป็นหลัก
- **Note**: ใช้แบบ local ไม่ต้องเรียกใช้จาก CDN

### Font Awesome
- **Version**: Font Awesome Free
- **Usage**: Icons สำหรับ UI components
- **Import**: `@import '@fortawesome/fontawesome-free/css/all.css';`
- **Installation**: `npm install @fortawesome/fontawesome-free`
- **Note**: ใช้แบบ local ไม่ต้องเรียกใช้จาก CDN

### Font Prompt
- **Font Family**: Prompt (Thai font)
- **Installation**: `npm install @fontsource/prompt`
- **Import**: `@import '@fontsource/prompt/400.css';` และ `@import '@fontsource/prompt/700.css';`
- **Usage**: ข้อความภาษาไทย
- **Note**: ใช้แบบ local ไม่ต้องเรียกใช้จาก Google Fonts CDN

## 🏗️ Project Structure

```
resources/
├── css/
│   └── app.css                 # Main CSS file
├── js/
│   └── app.js                  # Main JS file
└── views/
    ├── frontend/               # Frontend views
    │   ├── layouts/
    │   │   └── app.blade.php   # Frontend layout
    │   └── welcome.blade.php   # Frontend homepage
    └── backend/                # Backend views
        ├── layouts/
        │   └── app.blade.php   # Backend layout
        └── dashboard.blade.php # Backend dashboard
```

## 🎯 CSS Classes Usage

### Layout Classes
```css
/* Container */
.container { @apply mx-auto px-4; }

/* Grid System */
.grid { @apply grid; }
.grid-cols-1 { @apply grid-cols-1; }
.grid-cols-2 { @apply grid-cols-2; }
.grid-cols-3 { @apply grid-cols-3; }
.grid-cols-4 { @apply grid-cols-4; }

/* Flexbox */
.flex { @apply flex; }
.flex-col { @apply flex-col; }
.flex-row { @apply flex-row; }
.justify-center { @apply justify-center; }
.items-center { @apply items-center; }
```

### Color Classes
```css
/* Primary Colors */
.bg-primary { @apply bg-blue-600; }
.text-primary { @apply text-blue-600; }
.border-primary { @apply border-blue-600; }

/* Secondary Colors */
.bg-secondary { @apply bg-gray-600; }
.text-secondary { @apply text-gray-600; }
.border-secondary { @apply border-gray-600; }

/* Success Colors */
.bg-success { @apply bg-green-600; }
.text-success { @apply text-green-600; }
.border-success { @apply border-green-600; }

/* Warning Colors */
.bg-warning { @apply bg-yellow-600; }
.text-warning { @apply text-yellow-600; }
.border-warning { @apply border-yellow-600; }

/* Danger Colors */
.bg-danger { @apply bg-red-600; }
.text-danger { @apply text-red-600; }
.border-danger { @apply border-red-600; }
```

### Typography Classes
```css
/* Headings */
.h1 { @apply text-4xl font-bold; }
.h2 { @apply text-3xl font-bold; }
.h3 { @apply text-2xl font-bold; }
.h4 { @apply text-xl font-bold; }
.h5 { @apply text-lg font-bold; }
.h6 { @apply text-base font-bold; }

/* Body Text */
.text-body { @apply text-base; }
.text-small { @apply text-sm; }
.text-large { @apply text-lg; }

/* Font Weights */
.font-light { @apply font-light; }
.font-normal { @apply font-normal; }
.font-medium { @apply font-medium; }
.font-semibold { @apply font-semibold; }
.font-bold { @apply font-bold; }
```

### Button Classes
```css
/* Primary Button */
.btn-primary {
  @apply bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2;
}

/* Secondary Button */
.btn-secondary {
  @apply bg-gray-600 text-white px-4 py-2 rounded-md hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2;
}

/* Success Button */
.btn-success {
  @apply bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2;
}

/* Warning Button */
.btn-warning {
  @apply bg-yellow-600 text-white px-4 py-2 rounded-md hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2;
}

/* Danger Button */
.btn-danger {
  @apply bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2;
}

/* Button Sizes */
.btn-sm { @apply px-2 py-1 text-sm; }
.btn-lg { @apply px-6 py-3 text-lg; }
```

### Form Classes
```css
/* Form Input */
.form-input {
  @apply block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500;
}

/* Form Label */
.form-label {
  @apply block text-sm font-medium text-gray-700 mb-1;
}

/* Form Group */
.form-group {
  @apply mb-4;
}

/* Form Error */
.form-error {
  @apply text-red-600 text-sm mt-1;
}
```

### Card Classes
```css
/* Card */
.card {
  @apply bg-white rounded-lg shadow-md p-6;
}

/* Card Header */
.card-header {
  @apply border-b border-gray-200 pb-4 mb-4;
}

/* Card Title */
.card-title {
  @apply text-lg font-semibold text-gray-900;
}

/* Card Body */
.card-body {
  @apply text-gray-700;
}

/* Card Footer */
.card-footer {
  @apply border-t border-gray-200 pt-4 mt-4;
}
```

### Table Classes
```css
/* Table */
.table {
  @apply w-full border-collapse;
}

/* Table Header */
.table-header {
  @apply bg-gray-50 border-b border-gray-200;
}

/* Table Cell */
.table-cell {
  @apply px-4 py-2 border-b border-gray-200;
}

/* Table Row */
.table-row {
  @apply hover:bg-gray-50;
}
```

### Alert Classes
```css
/* Alert */
.alert {
  @apply p-4 rounded-md mb-4;
}

/* Alert Success */
.alert-success {
  @apply bg-green-100 border border-green-400 text-green-700;
}

/* Alert Warning */
.alert-warning {
  @apply bg-yellow-100 border border-yellow-400 text-yellow-700;
}

/* Alert Error */
.alert-error {
  @apply bg-red-100 border border-red-400 text-red-700;
}

/* Alert Info */
.alert-info {
  @apply bg-blue-100 border border-blue-400 text-blue-700;
}
```

### Layout Components

#### Responsive Sidebar
```css
/* Mobile Overlay */
.mobile-overlay {
  @apply fixed inset-0 bg-black bg-opacity-50 z-40 lg:hidden hidden;
}

/* Sidebar Container */
.sidebar {
  @apply fixed inset-y-0 left-0 z-50 w-64 bg-white shadow-lg transform -translate-x-full transition-transform duration-300 ease-in-out lg:translate-x-0 lg:static lg:inset-0;
}

/* Sidebar Header */
.sidebar-header {
  @apply p-6;
}

.sidebar-title {
  @apply text-xl font-bold text-gray-900;
}

.sidebar-title-mobile {
  @apply hidden sm:inline;
}

.sidebar-title-compact {
  @apply sm:hidden;
}

/* Sidebar Close Button */
.sidebar-close-btn {
  @apply lg:hidden text-gray-500 hover:text-gray-700;
}

/* Sidebar Navigation */
.sidebar-nav {
  @apply mt-6 overflow-y-auto h-full pb-20;
}

.sidebar-section-title {
  @apply text-xs font-semibold text-gray-500 uppercase tracking-wider px-6 py-2;
}

.sidebar-nav-item {
  @apply flex items-center px-6 py-3 text-gray-700 hover:bg-gray-50 transition-colors duration-200;
}

.sidebar-nav-item-active {
  @apply bg-blue-50 border-r-4 border-blue-500;
}

.sidebar-nav-icon {
  @apply mr-3 w-5 text-center;
}

.sidebar-nav-text {
  @apply truncate;
}
```

#### Responsive Header
```css
/* Header Container */
.header {
  @apply bg-white shadow-sm border-b;
}

.header-content {
  @apply px-4 sm:px-6 py-4;
}

.header-main {
  @apply flex justify-between items-center;
}

.header-left {
  @apply flex items-center;
}

.header-right {
  @apply flex items-center space-x-2 sm:space-x-4;
}

/* Mobile Menu Button */
.mobile-menu-btn {
  @apply lg:hidden text-gray-500 hover:text-gray-700 mr-4;
}

/* Page Title */
.page-title {
  @apply text-xl sm:text-2xl font-bold text-gray-900;
}

.page-description {
  @apply text-sm text-gray-600 mt-1 hidden sm:block;
}

/* Notification Button */
.notification-btn {
  @apply flex items-center text-sm text-gray-600 hover:text-gray-900 p-2;
}

.notification-icon {
  @apply text-lg;
}

.notification-badge {
  @apply bg-red-500 text-white text-xs rounded-full px-2 py-1 ml-1 hidden sm:inline;
}

/* User Profile */
.user-profile {
  @apply flex items-center space-x-2 sm:space-x-3;
}

.user-info {
  @apply text-right hidden sm:block;
}

.user-name {
  @apply text-sm font-medium text-gray-900;
}

.user-role {
  @apply text-xs text-gray-500;
}

.user-avatar {
  @apply w-8 h-8 sm:w-10 sm:h-10 bg-blue-500 rounded-full flex items-center justify-center;
}

.user-avatar-icon {
  @apply text-white text-sm sm:text-base;
}
```

#### Main Content Area
```css
/* Main Content Container */
.main-content {
  @apply flex-1 flex flex-col lg:ml-0;
}

.main-content-area {
  @apply flex-1 p-4 sm:p-6;
}
```

### Dashboard Components

#### Stats Cards
```css
/* Stats Card Container */
.stats-grid {
  @apply grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8;
}

/* Individual Stats Card */
.stats-card {
  @apply bg-white rounded-lg shadow p-6;
}

/* Stats Card Content */
.stats-card-content {
  @apply flex items-center;
}

/* Stats Card Icon Container */
.stats-icon-container {
  @apply p-3 rounded-full;
}

/* Stats Icon Backgrounds */
.stats-icon-blue {
  @apply bg-blue-100 text-blue-600;
}

.stats-icon-green {
  @apply bg-green-100 text-green-600;
}

.stats-icon-purple {
  @apply bg-purple-100 text-purple-600;
}

.stats-icon-yellow {
  @apply bg-yellow-100 text-yellow-600;
}

.stats-icon-red {
  @apply bg-red-100 text-red-600;
}

/* Stats Card Text Container */
.stats-text-container {
  @apply ml-4;
}

/* Stats Card Label */
.stats-label {
  @apply text-sm font-medium text-gray-600;
}

/* Stats Card Value */
.stats-value {
  @apply text-2xl font-semibold text-gray-900;
}
```

#### Activity Feed
```css
/* Activity Feed Container */
.activity-feed {
  @apply bg-white rounded-lg shadow;
}

/* Activity Feed Header */
.activity-feed-header {
  @apply px-6 py-4 border-b border-gray-200;
}

/* Activity Feed Title */
.activity-feed-title {
  @apply text-lg font-medium text-gray-900;
}

/* Activity Feed Body */
.activity-feed-body {
  @apply p-6;
}

/* Activity Items Container */
.activity-items {
  @apply space-y-4;
}

/* Individual Activity Item */
.activity-item {
  @apply flex items-center space-x-3 p-3 bg-gray-50 rounded-lg;
}

/* Activity Item Icon Container */
.activity-item-icon {
  @apply flex-shrink-0;
}

/* Activity Item Icon Background */
.activity-item-icon-bg {
  @apply w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center;
}

/* Activity Item Icon */
.activity-item-icon-icon {
  @apply text-blue-600 text-sm;
}

/* Activity Item Content */
.activity-item-content {
  @apply flex-1 min-w-0;
}

/* Activity Item Title */
.activity-item-title {
  @apply text-sm font-medium text-gray-900;
}

/* Activity Item Time */
.activity-item-time {
  @apply text-sm text-gray-500;
}
```

## 🔧 JavaScript Libraries

### SweetAlert2
- **Version**: Latest
- **Usage**: Alert dialogs และ notifications
- **Installation**: `npm install sweetalert2`
- **Import**: `import Swal from 'sweetalert2';`
- **Global**: `window.Swal = Swal;`
- **Note**: ใช้แบบ local ไม่ต้องเรียกใช้จาก CDN

### Usage Examples
```javascript
// Success Alert
Swal.fire({
  title: 'Success!',
  text: 'Operation completed successfully.',
  icon: 'success',
  confirmButtonText: 'OK'
});

// Error Alert
Swal.fire({
  title: 'Error!',
  text: 'Something went wrong.',
  icon: 'error',
  confirmButtonText: 'OK'
});

// Confirmation Dialog
Swal.fire({
  title: 'Are you sure?',
  text: "You won't be able to revert this!",
  icon: 'warning',
  showCancelButton: true,
  confirmButtonColor: '#3085d6',
  cancelButtonColor: '#d33',
  confirmButtonText: 'Yes, delete it!'
}).then((result) => {
  if (result.isConfirmed) {
    // Handle confirmation
  }
});
```

### Mobile Menu JavaScript
```javascript
// Mobile Menu Toggle Functionality
document.addEventListener('DOMContentLoaded', function() {
    const mobileMenuButton = document.getElementById('mobile-menu-button');
    const closeSidebarButton = document.getElementById('close-sidebar');
    const sidebar = document.getElementById('sidebar');
    const mobileOverlay = document.getElementById('mobile-overlay');

    function openSidebar() {
        sidebar.classList.remove('-translate-x-full');
        mobileOverlay.classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
    }

    function closeSidebar() {
        sidebar.classList.add('-translate-x-full');
        mobileOverlay.classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    }

    // Open sidebar
    mobileMenuButton.addEventListener('click', openSidebar);

    // Close sidebar
    closeSidebarButton.addEventListener('click', closeSidebar);
    mobileOverlay.addEventListener('click', closeSidebar);

    // Close sidebar on escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && !sidebar.classList.contains('-translate-x-full')) {
            closeSidebar();
        }
    });

    // Handle window resize
    window.addEventListener('resize', function() {
        if (window.innerWidth >= 1024) {
            closeSidebar();
        }
    });
});
```

### Layout Components Usage Examples
```html
<!-- Responsive Sidebar -->
<div class="mobile-overlay" id="mobile-overlay"></div>
<div class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <div class="flex items-center justify-between">
            <h2 class="sidebar-title">
                <i class="fas fa-cogs mr-2"></i>
                <span class="sidebar-title-mobile">CMS Backend</span>
                <span class="sidebar-title-compact">CMS</span>
            </h2>
            <button class="sidebar-close-btn" id="close-sidebar">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
    </div>
    
    <nav class="sidebar-nav">
        <div class="sidebar-section-title">เมนูหลัก</div>
        <a href="#" class="sidebar-nav-item sidebar-nav-item-active">
            <i class="fas fa-tachometer-alt sidebar-nav-icon"></i>
            <span class="sidebar-nav-text">Dashboard</span>
        </a>
    </nav>
</div>

<!-- Responsive Header -->
<header class="header">
    <div class="header-content">
        <div class="header-main">
            <div class="header-left">
                <button class="mobile-menu-btn" id="mobile-menu-button">
                    <i class="fas fa-bars text-xl"></i>
                </button>
                <div>
                    <h1 class="page-title">Dashboard</h1>
                    <p class="page-description">ภาพรวมระบบ</p>
                </div>
            </div>
            <div class="header-right">
                <button class="notification-btn">
                    <i class="fas fa-bell notification-icon"></i>
                    <span class="notification-badge">3</span>
                </button>
                <div class="user-profile">
                    <div class="user-info">
                        <p class="user-name">Admin User</p>
                        <p class="user-role">Administrator</p>
                    </div>
                    <div class="user-avatar">
                        <i class="fas fa-user user-avatar-icon"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>

<!-- Main Content -->
<div class="main-content">
    <main class="main-content-area">
        <!-- Page content goes here -->
    </main>
</div>
```

### Dashboard Components Usage Examples
```html
<!-- Stats Cards Grid -->
<div class="stats-grid">
    <div class="stats-card">
        <div class="stats-card-content">
            <div class="stats-icon-container stats-icon-blue">
                <i class="fas fa-users text-xl"></i>
            </div>
            <div class="stats-text-container">
                <p class="stats-label">ผู้ใช้งานทั้งหมด</p>
                <p class="stats-value">1,234</p>
            </div>
        </div>
    </div>
    
    <div class="stats-card">
        <div class="stats-card-content">
            <div class="stats-icon-container stats-icon-green">
                <i class="fas fa-user-check text-xl"></i>
            </div>
            <div class="stats-text-container">
                <p class="stats-label">ผู้ใช้งานที่ใช้งานอยู่</p>
                <p class="stats-value">567</p>
            </div>
        </div>
    </div>
</div>

<!-- Activity Feed -->
<div class="activity-feed">
    <div class="activity-feed-header">
        <h3 class="activity-feed-title">
            <i class="fas fa-history mr-2"></i>
            กิจกรรมล่าสุด
        </h3>
    </div>
    <div class="activity-feed-body">
        <div class="activity-items">
            <div class="activity-item">
                <div class="activity-item-icon">
                    <div class="activity-item-icon-bg">
                        <i class="fas fa-plus activity-item-icon-icon"></i>
                    </div>
                </div>
                <div class="activity-item-content">
                    <p class="activity-item-title">created User</p>
                    <p class="activity-item-time">17/10/2024 14:30</p>
                </div>
            </div>
        </div>
    </div>
</div>
```

## 📱 Responsive Design

### Breakpoints
```css
/* Mobile First */
.sm: { @apply sm:; }    /* 640px */
.md: { @apply md:; }    /* 768px */
.lg: { @apply lg:; }    /* 1024px */
.xl: { @apply xl:; }    /* 1280px */
.2xl: { @apply 2xl:; }  /* 1536px */
```

### Responsive Examples
```css
/* Responsive Grid */
.responsive-grid {
  @apply grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4;
}

/* Responsive Text */
.responsive-text {
  @apply text-sm md:text-base lg:text-lg;
}

/* Responsive Padding */
.responsive-padding {
  @apply p-4 md:p-6 lg:p-8;
}
```

## 🎨 Custom Components ที่มีอยู่แล้ว
**หมายเหตุ**: Components เหล่านี้ถูกกำหนดไว้แล้วใน `resources/css/app.css` - ห้ามสร้างใหม่

### Loading Spinner
```css
.spinner { @apply animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600; }
```

### Badge Components
```css
.badge { @apply inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium; }
.badge-primary { @apply bg-blue-100 text-blue-800; }
.badge-secondary { @apply bg-gray-100 text-gray-800; }
.badge-success { @apply bg-green-100 text-green-800; }
.badge-warning { @apply bg-yellow-100 text-yellow-800; }
.badge-danger { @apply bg-red-100 text-red-800; }
```

## 🚀 Development Workflow

### 1. Development Mode
```bash
# Start Vite dev server
npm run dev

# Start Laravel server
php artisan serve
```

### 2. Production Build
```bash
# Build assets
npm run build

# Remove hot file to use built assets
rm public/hot
```

### 3. Asset Loading
- **Development**: Uses Vite dev server (`public/hot`)
- **Production**: Uses built assets (`public/build/`)

## 📝 Best Practices

### ⚠️ กฎสำคัญ
1. **ห้ามสร้าง Custom CSS เพิ่มเติม** - ใช้ Tailwind utility classes และ Custom Components ที่มีอยู่แล้วเท่านั้น
2. **ใช้ Tailwind utility classes เป็นหลัก** - เช่น `bg-blue-600`, `text-white`, `px-4 py-2`
3. **ใช้ Custom Components ที่กำหนดไว้แล้ว** - เช่น `.btn-primary`, `.card`, `.form-input`

### 1. CSS Organization
- ใช้ Tailwind utility classes เป็นหลัก
- ใช้ Custom Components ที่มีอยู่แล้วเท่านั้น
- ห้ามสร้าง CSS classes ใหม่

### 2. Responsive Design
- Mobile-first approach
- ใช้ responsive prefixes (sm:, md:, lg:, xl:)
- ทดสอบบน devices ต่างๆ

### 3. Performance
- ใช้ built assets ใน production
- Optimize images และ fonts
- Minimize CSS และ JS files

### 4. Accessibility
- ใช้ semantic HTML
- เพิ่ม ARIA labels
- ใช้ proper color contrast
- Support keyboard navigation

### 5. Component Usage
- **Buttons**: ใช้ `.btn-primary`, `.btn-secondary`, `.btn-success`, `.btn-warning`, `.btn-danger`
- **Forms**: ใช้ `.form-input`, `.form-label`, `.form-group`, `.form-error`
- **Cards**: ใช้ `.card`, `.card-header`, `.card-title`, `.card-body`, `.card-footer`
- **Tables**: ใช้ `.table`, `.table-header`, `.table-cell`, `.table-row`
- **Alerts**: ใช้ `.alert`, `.alert-success`, `.alert-warning`, `.alert-error`, `.alert-info`
- **Layout**: ใช้ `.sidebar`, `.header`, `.main-content`, `.mobile-overlay`
- **Dashboard**: ใช้ `.stats-grid`, `.stats-card`, `.activity-feed`, `.activity-item`

## 🔍 Troubleshooting

### Common Issues
1. **Styles not loading**: ตรวจสอบ `public/hot` file และ Vite server
2. **Font not working**: ตรวจสอบ @fontsource/prompt import
3. **Icons not showing**: ตรวจสอบ @fortawesome/fontawesome-free import
4. **Responsive issues**: ตรวจสอบ breakpoint classes
5. **CDN dependencies**: ตรวจสอบว่าไม่ได้เรียกใช้จาก CDN

### Solutions
1. **Clear cache**: `npm cache clean --force`
2. **Rebuild assets**: `npm run build`
3. **Restart servers**: Restart Vite และ Laravel servers
4. **Check imports**: ตรวจสอบ CSS และ JS imports

## 📚 Resources

- [Tailwind CSS Documentation](https://tailwindcss.com/docs)
- [Font Awesome Documentation](https://fontawesome.com/docs)
- [SweetAlert2 Documentation](https://sweetalert2.github.io/)
- [Laravel Vite Documentation](https://laravel.com/docs/vite)

---

## 🎯 สรุปสำหรับ AI - ห้ามสร้าง Custom CSS

### ✅ ใช้ได้
- **Tailwind utility classes**: `bg-blue-600`, `text-white`, `px-4 py-2`, `rounded-md`, `hover:bg-blue-700`
- **Custom Components ที่มีอยู่**: `.btn-primary`, `.card`, `.form-input`, `.table`, `.alert`, `.sidebar`, `.header`
- **Font Awesome icons**: `<i class="fas fa-icon-name"></i>`
- **Responsive classes**: `sm:`, `md:`, `lg:`, `xl:`

### ❌ ห้ามใช้
- **Custom CSS**: ไม่ต้องเขียน `<style>` หรือ CSS classes ใหม่
- **Inline styles**: ไม่ใช้ `style="..."` 
- **CSS frameworks อื่น**: ไม่ใช้ Bootstrap, Material UI, หรือ CSS frameworks อื่น

### 📋 ตัวอย่างการใช้งาน
```html
<!-- ✅ ถูกต้อง -->
<button class="btn-primary">บันทึก</button>
<div class="card">
  <div class="card-header">
    <h3 class="card-title">หัวข้อ</h3>
  </div>
  <div class="card-body">เนื้อหา</div>
</div>

<!-- ❌ ผิด -->
<button style="background: blue;">บันทึก</button>
<div class="my-custom-card">เนื้อหา</div>
```

---

**Last Updated**: 2024-10-17  
**Version**: 1.4.0

## 📋 Changelog

### Version 1.4.0 (2024-10-17)
- **สำคัญ**: ปรับปรุงให้ทุก library ใช้แบบ local ไม่ต้องเรียกใช้จาก CDN
- เพิ่มคำแนะนำการติดตั้ง npm packages สำหรับ Font Awesome, Font Prompt, และ SweetAlert2
- อัพเดต troubleshooting section ให้สอดคล้องกับการใช้ local packages
- เพิ่มคำเตือนเกี่ยวกับ CDN dependencies

### Version 1.3.0 (2024-10-17)
- **สำคัญ**: เพิ่มคำเตือนห้ามสร้าง Custom CSS
- เพิ่มส่วนสรุปการใช้งานสำหรับ AI
- ปรับปรุง Best Practices ให้ชัดเจนขึ้น
- ลดความซ้ำซ้อนในเอกสาร
- เพิ่มตัวอย่างการใช้งานที่ถูกต้องและผิด

### Version 1.2.0 (2024-10-17)
- เพิ่ม Responsive Layout Components
- เพิ่ม Mobile Menu functionality
- เพิ่ม Responsive Sidebar classes
- เพิ่ม Responsive Header classes
- เพิ่ม Mobile Menu JavaScript
- อัพเดต Best Practices สำหรับ Responsive Layout

### Version 1.1.0 (2024-10-17)
- เพิ่ม Dashboard Components classes
- เพิ่ม Stats Cards components
- เพิ่ม Activity Feed components
- เพิ่มตัวอย่างการใช้งาน Dashboard Components
- อัพเดต Best Practices สำหรับ Dashboard Components
