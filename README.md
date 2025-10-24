# CMS Backend System

<p align="center">
<img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="300" alt="Laravel Logo">
</p>

<p align="center">
<strong>Content Management System Backend</strong><br>
Built with Laravel Framework
</p>

## About This Project

This is a comprehensive Content Management System (CMS) backend built with Laravel framework. The system provides a robust foundation for managing users, roles, permissions, content, and system administration with a clean, organized database structure using `core_` prefixed tables. It includes advanced features like system update management, performance monitoring, comprehensive audit logging, and proper database integrity with foreign key constraints.

## Features

### Core System Features
- **User Management**: Complete user registration, authentication, and profile management with CMS fields
- **Role-Based Access Control**: Flexible role and permission system with proper relationships
- **Audit Logging**: Comprehensive system activity tracking with foreign key constraints
- **Login Security**: Advanced login attempt tracking and security monitoring
- **Session Management**: Secure session handling with proper database relationships

### Settings Management
- **Unified Settings System**: Single table (`core_settings`) with category-based organization (general, performance, backup, email, security, system)
- **Base Controller Architecture**: Shared functionality through `BaseSettingsController` with category-specific controllers
- **Dynamic Configuration**: Flexible settings system with type casting, validation, and bulk operations
- **Modern UI Components**: Modal-based editing, SweetAlert2 notifications, and responsive design
- **Database-First Configuration**: Settings stored in database with .env override capability
- **Caching System**: Advanced caching with automatic cache invalidation
- **Helper Functions**: Easy-to-use helper functions (`setting()`, `settings()`, `set_setting()`)
- **Blade Directives**: Custom Blade directives (`@setting`, `@ifsetting`, `@ifnotsetting`)
- **Artisan Commands**: Management commands for testing, syncing, and cache management
- **System Update Management**: Laravel core, packages, and configuration updates

### System Administration
- **System Information**: Comprehensive system monitoring and diagnostics
- **Database Organization**: All tables use `core_` prefix with proper foreign key constraints
- **Migration Management**: Organized migration system with proper dependencies
- **Cache Management**: Advanced caching system with database storage

### Technical Features
- **RESTful API**: Clean API endpoints for frontend integration
- **Modern UI**: Tailwind CSS with modal-based interactions and responsive design
- **CSS Components**: Reusable component system with `form-input`, `btn-primary`, `btn-secondary`, `btn-success`, `btn-warning` classes
- **Code Optimization**: Reduced code duplication through CSS component classes and Tailwind utilities
- **UI Standards**: Consistent design system with predefined components and focus styles

## Database Structure

All database tables use the `core_` prefix for consistency and proper organization:

### Core System Tables
- `core_users` - User accounts and profiles with CMS fields
- `core_roles` - User roles and permissions
- `core_permissions` - System permissions
- `core_role_permissions` - Role-permission relationships
- `core_user_roles` - User-role assignments
- `core_audit_logs` - System activity tracking with foreign key constraints
- `core_login_attempts` - Security monitoring and login tracking
- `core_sessions` - User sessions with proper foreign key relationships
- `core_cache` - Application cache storage
- `core_jobs` - Background job processing
- `core_migrations` - Migration tracking (configured in database.php)

### Settings Management Tables
- `core_settings` - **Unified settings table** for all configuration types (general, performance, backup, email, security, system)
- `core_settings_updates` - System update tracking

### Database Seeders
- `CmsSeeder` - Creates admin and editor users with roles
- `PermissionSeeder` - Creates permissions and assigns them to roles
- `GeneralSettingsSeeder` - Creates basic system settings

### Database Features
- **Foreign Key Constraints**: Proper referential integrity with cascade operations
- **Soft Deletes**: Data preservation with soft deletion capabilities
- **Audit Trail**: Complete change tracking and activity monitoring
- **Indexing**: Optimized database performance with proper indexing

## Production Deployment

### Automated Deployment (Recommended)

**Step 1: Install PHP Extensions**
```bash
# Make the script executable
chmod +x install-php-extensions.sh

# Run the PHP extensions installation script
sudo ./install-php-extensions.sh
```

**Step 2: Deploy Application**
```bash
# Make the deployment script executable
chmod +x deploy-production.sh

# Run the deployment script
./deploy-production.sh
```

### Manual Production Setup
```bash
# 1. Install PHP Extensions (Ubuntu/Debian)
sudo apt-get update
sudo apt-get install php8.2-exif php8.2-mbstring php8.2-xml php8.2-bcmath php8.2-fileinfo php8.2-ctype php8.2-json php8.2-tokenizer php8.2-openssl php8.2-pdo php8.2-curl php8.2-gd php8.2-zip php8.2-intl php8.2-imagick

# 2. Install Composer dependencies
composer install --no-dev --optimize-autoloader --ignore-platform-reqs

# 3. Clean npm configuration
rm -f /root/.npmrc
rm -f .npmrc

# 4. Clean node_modules
rm -rf node_modules package-lock.json

# 5. Install dependencies
npm install

# 6. Build assets
npm run build

# 7. Run migrations
php artisan migrate --force

# 8. Ensure storage directories and symlink exist
mkdir -p storage/app/public/settings
mkdir -p storage/app/public/backups
mkdir -p storage/app/public/uploads
php artisan storage:link

# 9. Seed production data
php artisan db:seed --force

# 10. Optimize application
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 11. Set proper permissions
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

## System Requirements

- **PHP**: 8.2 or higher
- **Composer**: Latest version
- **Node.js**: 18.0.0 or higher
- **npm**: 8.0.0 or higher
- **Database**: MySQL 5.7+ or SQLite 3.8+
- **Web Server**: Apache/Nginx (for production)

### Required PHP Extensions
- exif (for image processing)
- mbstring (for string handling)
- xml (for XML processing)
- bcmath (for mathematical operations)
- fileinfo (for file type detection)
- ctype (for character type checking)
- json (for JSON processing)
- tokenizer (for PHP tokenization)
- openssl (for encryption)
- pdo (for database access)
- curl (for HTTP requests)
- gd (for image manipulation)
- zip (for archive handling)
- intl (for internationalization)
- imagick (for advanced image processing)

## Troubleshooting

### File Upload Issues

**Problem**: Uploaded files (site_logo, site_favicon) return 404 error
```
GET https://backend.tonkla.co/storage/settings/site_logo_1761142641.png 404 (Not Found)
```

**Solutions**:

1. **Check Storage Link**
```bash
# Verify storage link exists
ls -la public/storage

# If missing, create it
php artisan storage:link
```

2. **Check File Permissions**
```bash
# Set proper permissions
sudo chown -R www-data:www-data storage/app/public
sudo chmod -R 775 storage/app/public
sudo chown -R www-data:www-data public/storage
sudo chmod -R 775 public/storage
```

3. **Verify File Upload**
```bash
# Check if file exists in storage
ls -la storage/app/public/settings/

# Check if file exists in public storage
ls -la public/storage/settings/
```

4. **Check Web Server Configuration**
```bash
# For Apache - ensure .htaccess allows file access
# For Nginx - ensure location block includes storage files
```

5. **Clear Laravel Cache**
```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

### PHP Extension Issues

**Problem**: Missing PHP extensions causing composer install failures

**Solution**:
```bash
# Install missing extensions
sudo apt-get install php8.2-exif php8.2-mbstring php8.2-xml php8.2-bcmath

# Restart PHP-FPM
sudo systemctl restart php8.2-fpm

# Try composer install again
composer install --ignore-platform-reqs
```

### Database Seeder Issues

**Problem**: `GeneralSettingsSeeder` class not found

**Solution**: The seeder has been renamed to `AllSettingsSeeder`. This is automatically handled in the updated code.

## Installation

### 1. Git Setup & Repository Clone

**Option A: Clone from existing repository**
```bash
git clone https://github.com/BukHUM/corecms.git .
cd backend
```

**Option B: Initialize new repository**
```bash
# Initialize git repository
git init

# Add remote origin (replace with your repository URL)
git remote add origin https://github.com/yourusername/your-repo.git

# Create initial commit
git add .
git commit -m "Initial commit: CMS Backend System"
git branch -M main
git push -u origin main
```

### 2. Install Dependencies

**Install PHP dependencies**
```bash
composer install
```

**Install Node.js dependencies**
```bash
npm install
```

**Install additional frontend packages**
```bash
# Install Tailwind CSS
npm install -D tailwindcss postcss autoprefixer

# Install FontAwesome
npm install @fortawesome/fontawesome-free

# Install SweetAlert2
npm install sweetalert2
```

### 3. Environment Setup

```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Update .env file with your database credentials
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=your_database_name
# DB_USERNAME=your_username
# DB_PASSWORD=your_password
```

### 4. Database Setup

```bash
# Run migrations
php artisan migrate

# Seed essential data (simplified - only necessary seeders)
php artisan db:seed
```

**Seeder Information:**
- `CmsSeeder`: Creates admin and editor users with roles
- `PermissionSeeder`: Creates permissions and assigns them to roles  
- `GeneralSettingsSeeder`: Creates basic system settings

**Available Users After Seeding:**
- Admin: `admin@example.com` / `admin123` (Full access)
- Editor: `editor@example.com` / `editor123` (Limited access)

### 5. Build Assets & Start Server

```bash
# Build frontend assets
npm run build
# หรือสำหรับ development
npm run dev

# Start development server
php artisan serve
```

## API Endpoints

### Authentication & User Management
- `POST /api/login` - User login
- `POST /api/logout` - User logout
- `POST /api/register` - User registration
- `GET /api/users` - List all users
- `POST /api/users` - Create new user
- `GET /api/users/{id}` - Get user details
- `PUT /api/users/{id}` - Update user
- `DELETE /api/users/{id}` - Delete user

### Role & Permission Management
- `GET /api/roles` - List all roles
- `POST /api/roles` - Create new role
- `PUT /api/roles/{id}` - Update role
- `DELETE /api/roles/{id}` - Delete role
- `GET /api/permissions` - List all permissions
- `POST /api/permissions` - Create new permission
- `PUT /api/permissions/{id}` - Update permission
- `DELETE /api/permissions/{id}` - Delete permission

### Settings Management
- `GET /api/settings-general` - List general settings
- `GET /api/settings-general/{id}` - Get general setting details
- `PUT /api/settings-general/{id}` - Update general setting
- `PATCH /api/settings-general/{id}/toggle-status` - Toggle setting status
- `POST /api/settings-general/{id}/reset` - Reset setting to default

- `GET /api/settings-performance` - List performance settings
- `GET /api/settings-performance/{id}` - Get performance setting details
- `PUT /api/settings-performance/{id}` - Update performance setting
- `PATCH /api/settings-performance/{id}/toggle-status` - Toggle setting status
- `POST /api/settings-performance/{id}/reset` - Reset setting to default

- `GET /api/settings-backup` - List backup settings
- `GET /api/settings-backup/{id}` - Get backup setting details
- `PUT /api/settings-backup/{id}` - Update backup setting
- `PATCH /api/settings-backup/{id}/toggle-status` - Toggle setting status
- `POST /api/settings-backup/{id}/reset` - Reset setting to default

- `GET /api/settings-email` - List email settings
- `POST /api/settings-email` - Update email settings

- `GET /api/settings-security` - List security settings
- `POST /api/settings-security` - Update security settings

### Settings System Usage
```php
// Helper Functions
$siteName = setting('site_name', 'Default Site');
$maintenanceMode = setting('maintenance_mode', false);
$generalSettings = settings('general');

// Set settings
set_setting('site_name', 'My Site', 'string', 'general');
toggle_setting('site_name');
clear_settings_cache();
```

```blade
{{-- Blade Directives --}}
<title>@setting('site_name')</title>

@ifsetting('maintenance_mode')
    <div class="alert">@setting('maintenance_message')</div>
@endsetting

@ifnotsetting('enable_registration')
    <div class="info">การลงทะเบียนถูกปิดใช้งาน</div>
@endsetting
```

```bash
# Artisan Commands
php artisan settings:show                    # View all settings
php artisan settings:show general            # View general settings
php artisan settings:test                    # Test settings functionality
php artisan settings:sync-env               # Sync from .env file
php artisan settings:clear-cache            # Clear settings cache
```

### CSS Components System
The system uses a reusable CSS component system to reduce code duplication and maintain consistency:

```css
/* Form Components */
.form-input {
    @apply block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white;
}

/* Button Components */
.btn-primary {
    @apply bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2;
}

.btn-secondary {
    @apply bg-gray-600 text-white px-4 py-2 rounded-md hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2;
}

.btn-success {
    @apply bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2;
}

.btn-warning {
    @apply bg-yellow-600 text-white px-4 py-2 rounded-md hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2;
}
```

```blade
{{-- Usage Examples --}}
{{-- Form Inputs --}}
<input type="text" class="form-input" placeholder="Enter text">
<input type="email" class="form-input" placeholder="Enter email">
<select class="form-input">
    <option>Select option</option>
</select>
<textarea class="form-input" rows="3"></textarea>

{{-- Buttons --}}
<button class="btn-primary">Primary Action</button>
<button class="btn-secondary">Secondary Action</button>
<button class="btn-success">Success Action</button>
<button class="btn-warning">Warning Action</button>

{{-- Loading States --}}
<button class="btn-primary disabled:opacity-60 disabled:cursor-not-allowed" disabled>
    <i class="fas fa-spinner animate-spin mr-2"></i>Loading...
</button>
```

### System Management
- `GET /api/settings-update` - System update status
- `POST /api/settings-update/quick-update` - Execute system updates
- `POST /api/settings-update/clear-cache` - Clear system cache
- `POST /api/settings-update/optimize` - Optimize system
- `GET /api/settings-systeminfo` - System information
- `GET /api/settings-systeminfo/export` - Export system info

### Dashboard
- `GET /api/dashboard` - Dashboard statistics

## Default Credentials

After running the seeder, you can login with:

### Admin Account
- **Email**: admin@example.com
- **Password**: admin123
- **Role**: Administrator (Full access)

### Editor Account
- **Email**: editor@example.com
- **Password**: editor123
- **Role**: Editor (Limited access)

## Technology Stack

### Backend
- **Framework**: Laravel 11
- **Database**: MySQL/SQLite with proper foreign key constraints
- **Authentication**: Laravel Sanctum
- **API**: RESTful API design with organized controllers
- **System Management**: Update tracking, performance monitoring, settings backup
- **Security**: Audit logging, login attempt tracking, session management
- **Database Integrity**: Foreign key constraints, cascade operations, soft deletes

### Frontend
- **CSS Framework**: Tailwind CSS with utility-first approach
- **Icons**: FontAwesome with proper loading states and animations
- **Notifications**: SweetAlert2 with loading states and progress indicators
- **Build Tool**: Vite for fast development and optimized production builds
- **JavaScript**: Vanilla JS with modern ES6+ features
- **UI Components**: Modern responsive design with consistent styling and accessibility

## Development

### Backend Commands
```bash
# Run tests
php artisan test

# Code style formatting
./vendor/bin/pint

# Database migrations
php artisan migrate
php artisan migrate:rollback
php artisan migrate:status
php artisan migrate:fresh --seed

# Clear caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan optimize:clear

# Database operations
php artisan db:wipe
php artisan db:seed
```

### Frontend Commands
```bash
# Development build with hot reload
npm run dev

# Production build
npm run build

# Watch for changes
npm run watch

# Install new packages
npm install package-name

# Install additional packages (if needed)
npm install sweetalert2
npm install @fortawesome/fontawesome-free
npm install -D tailwindcss postcss autoprefixer
```

## Contributing

### Git Workflow
1. **Fork the repository** or clone your own repository
2. **Create feature branch** (`git checkout -b feature/amazing-feature`)
3. **Make changes** and test thoroughly
4. **Commit changes** (`git commit -m 'Add some amazing feature'`)
5. **Push to branch** (`git push origin feature/amazing-feature`)
6. **Open Pull Request** for review

### Git Best Practices
```bash
# Always pull latest changes before starting work
git pull origin main

# Create descriptive commit messages
git commit -m "feat: add user authentication system"
git commit -m "fix: resolve database migration issue"
git commit -m "docs: update installation instructions"

# Use conventional commit format
# feat: new feature
# fix: bug fix
# docs: documentation changes
# style: formatting changes
# refactor: code refactoring
# test: adding tests
# chore: maintenance tasks
```

## Development Guidelines

- Follow PSR-12 coding standards
- Write comprehensive tests for new features
- Update documentation for any API changes
- Use meaningful commit messages
- Ensure all tests pass before submitting PR
- Maintain proper database relationships with foreign key constraints
- Use consistent naming conventions for tables and controllers
- Organize controllers in appropriate namespaces (Backend/Frontend)
- Use `updateOrCreate()` in seeders to prevent duplicate data
- Keep seeder files minimal and focused on essential data only
- **CSS Components**: Use predefined CSS components (`form-input`, `btn-primary`, `btn-secondary`, `btn-success`, `btn-warning`) instead of inline Tailwind classes
- **Code Duplication**: Avoid repeating CSS classes by using component system
- **Tailwind Utilities**: Prefer Tailwind utilities over custom CSS for consistency and performance

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## Support

For support and questions, please contact the development team or create an issue in the repository.

## Changelog

### Version 1.7.0 (Latest)
- ✅ **Production Deployment Scripts**: Added automated deployment scripts for Ubuntu/Debian servers
- ✅ **PHP 8.2 Compatibility**: Updated deployment script to support PHP 8.2 instead of requiring PHP 8.3
- ✅ **PHP Extensions Installer**: Created `install-php-extensions.sh` for easy PHP extension installation
- ✅ **Error Handling**: Improved error handling and fallback options for composer install
- ✅ **Platform Requirements**: Added `--ignore-platform-reqs` support for package compatibility
- ✅ **Deployment Guide**: Added comprehensive troubleshooting guide for file upload and extension issues
- ✅ **Database Seeder Fix**: Fixed `GeneralSettingsSeeder` class name mismatch in `DatabaseSeeder.php`
- ✅ **Documentation Update**: Updated README with automated deployment instructions and troubleshooting

### Version 1.6.0
- ✅ **CSS Components System**: Implemented reusable CSS component system with `form-input`, `btn-primary`, `btn-secondary`, `btn-success`, `btn-warning` classes
- ✅ **Code Optimization**: Reduced code duplication by replacing inline Tailwind classes with CSS components
- ✅ **Custom CSS Removal**: Eliminated custom CSS in favor of Tailwind utilities (`animate-spin`, `disabled:opacity-60`, `disabled:cursor-not-allowed`)
- ✅ **UI Consistency**: Standardized form inputs and buttons across all settings pages
- ✅ **Performance Improvement**: Reduced bundle size by removing redundant CSS and using utility-first approach
- ✅ **Maintainability**: Improved code maintainability through centralized component definitions

### Version 1.5.0
- ✅ **Simplified Seeder System**: Streamlined database seeding with only essential seeders
- ✅ **Updated User Credentials**: Changed default admin password to `admin123` for better security
- ✅ **Role-Based Access**: Added editor user with limited permissions for testing
- ✅ **Git Setup Guide**: Added comprehensive git repository setup instructions
- ✅ **Documentation Cleanup**: Removed redundant seeder references and simplified installation process
- ✅ **Production Optimization**: Simplified production deployment with single seeder command

### Version 1.4.0
- ✅ **Database-First Settings System**: Complete settings management with database storage and .env override
- ✅ **Advanced Caching**: Implemented intelligent caching system with automatic invalidation
- ✅ **Helper Functions**: Easy-to-use helper functions (`setting()`, `settings()`, `set_setting()`)
- ✅ **Blade Directives**: Custom Blade directives (`@setting`, `@ifsetting`, `@ifnotsetting`)
- ✅ **Artisan Commands**: Comprehensive management commands for testing, syncing, and cache management
- ✅ **Type Casting**: Automatic type casting for boolean, integer, float, JSON, and array values
- ✅ **Configuration Override**: Automatic override of Laravel config values from database settings
- ✅ **Service Providers**: Integrated settings loading into application bootstrap process
- ✅ **Documentation**: Complete documentation with examples and best practices

### Version 1.3.0
- ✅ **UI Modernization**: Implemented modal-based editing system for settings management
- ✅ **SweetAlert2 Integration**: Replaced native alerts with modern SweetAlert2 notifications
- ✅ **UI Standards Compliance**: Updated all components to follow UI_STANDARD.md guidelines
- ✅ **Settings Refinement**: Removed redundant settings and organized by specific categories
- ✅ **Functionality Cleanup**: Removed create/delete functionality, focused on edit/view operations
- ✅ **Navigation Enhancement**: Dynamic page titles and descriptions in navigation bar
- ✅ **Seeder Organization**: Created specific seeders for each settings category
- ✅ **Code Optimization**: Removed unused files and consolidated shared components

### Version 1.2.0
- ✅ **Settings Normalization**: Unified all settings into single `core_settings` table with category-based organization
- ✅ **Controller Refactoring**: Created `BaseSettingsController` for shared functionality and category-specific controllers
- ✅ **Naming Consistency**: Renamed controllers to follow `Settings{Category}Controller` convention
- ✅ **Migration Data Transfer**: Successfully migrated data from old tables to unified structure
- ✅ **File Cleanup**: Removed redundant controllers, models, and migration files
- ✅ **Route Organization**: Updated all routes to reflect new controller structure
- ✅ **Database Optimization**: Eliminated duplicate tables and improved data organization

### Version 1.1.0
- ✅ **Database Structure Refactoring**: Renamed tables to follow consistent `core_settings_` naming convention
- ✅ **Controller Organization**: Moved settings controllers to `Backend` namespace for better separation
- ✅ **Foreign Key Constraints**: Added proper foreign key constraints for data integrity
- ✅ **Migration Optimization**: Consolidated migrations and removed redundant rename operations
- ✅ **Settings Management**: Separated general and performance settings with proper controllers
- ✅ **Database Integrity**: Implemented cascade operations and proper referential integrity

### Version 1.0.0
- ✅ **System Update Management**: Laravel core, packages, and configuration updates
- ✅ **System Information**: Comprehensive system monitoring and diagnostics
- ✅ **Performance Settings**: System performance optimization and monitoring
- ✅ **SweetAlert2 Integration**: Modern notification system
- ✅ **Tailwind CSS**: Modern responsive UI design
- ✅ **Audit Logging**: Comprehensive system activity tracking
- ✅ **User Management**: Complete user registration, authentication, and profile management
- ✅ **Role-Based Access Control**: Flexible role and permission system