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
- **System Update Management**: Laravel core, packages, and configuration updates

### System Administration
- **System Information**: Comprehensive system monitoring and diagnostics
- **Database Organization**: All tables use `core_` prefix with proper foreign key constraints
- **Migration Management**: Organized migration system with proper dependencies
- **Cache Management**: Advanced caching system with database storage

### Technical Features
- **RESTful API**: Clean API endpoints for frontend integration
- **Modern UI**: Tailwind CSS with SweetAlert2 notifications and modal-based interactions
- **Database Integrity**: Foreign key constraints and cascade operations
- **Soft Deletes**: Data preservation with soft deletion capabilities
- **Indexing**: Optimized database performance with proper indexing
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

### Database Features
- **Foreign Key Constraints**: Proper referential integrity
- **Soft Deletes**: Data preservation with soft deletion
- **Audit Trail**: Complete change tracking
- **Indexing**: Optimized database performance
- **Cascade Operations**: Proper data cleanup on deletion

## Production Deployment

### Quick Deployment Script
```bash
# Make the script executable
chmod +x deploy-production.sh

# Run the deployment script
./deploy-production.sh
```

### Manual Production Setup
```bash
# 1. Clean npm configuration
rm -f /root/.npmrc
rm -f .npmrc

# 2. Clean node_modules
rm -rf node_modules package-lock.json

# 3. Install dependencies
npm install

# 4. Build assets
npm run build

# 5. Run migrations
php artisan migrate --force

# 6. Seed production data
php artisan db:seed --class=CmsSeeder --force
php artisan db:seed --class=PermissionSeeder --force
php artisan db:seed --class=GeneralSettingsSeeder --force
php artisan db:seed --class=EmailSettingsSeeder --force
php artisan db:seed --class=SecuritySettingsSeeder --force
php artisan db:seed --class=PerformanceSettingsSeeder --force
php artisan db:seed --class=PerformancePermissionsSeeder --force
php artisan db:seed --class=AssignPerformancePermissionsSeeder --force

# 7. Optimize application
php artisan optimize
```

## System Requirements

- **PHP**: 8.2 or higher
- **Composer**: Latest version
- **Node.js**: 18.0.0 or higher
- **npm**: 8.0.0 or higher
- **Database**: MySQL 5.7+ or SQLite 3.8+
- **Web Server**: Apache/Nginx (for production)

## Installation

1. **Clone the repository**
   ```bash
   git clone https://github.com/BukHUM/corecms.git .
   cd backend
   ```

2. **Install PHP dependencies**
   ```bash
   composer install
   ```

3. **Install Node.js dependencies**
   ```bash
   npm install
   ```

4. **Install additional frontend packages**
   ```bash
   # Install Tailwind CSS
   npm install -D tailwindcss postcss autoprefixer
   
   # Install FontAwesome
   npm install @fortawesome/fontawesome-free
   
   # Install SweetAlert2
   npm install sweetalert2
   ```

5. **Build frontend assets**
   ```bash
   npm run build
   # หรือสำหรับ development
   npm run dev
   ```

6. **Environment setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

7. **Database configuration**
   - Update `.env` file with your database credentials
   - Run migrations:
   ```bash
   php artisan migrate
   ```

8. **Seed initial data**
   ```bash
   php artisan db:seed --class=CmsSeeder
   php artisan db:seed --class=PermissionSeeder
   php artisan db:seed --class=GeneralSettingsSeeder
   php artisan db:seed --class=EmailSettingsSeeder
   php artisan db:seed --class=SecuritySettingsSeeder
   php artisan db:seed --class=PerformanceSettingsSeeder
   php artisan db:seed --class=PerformancePermissionsSeeder
   php artisan db:seed --class=AssignPerformancePermissionsSeeder
   ```

9. **Start development server**
   ```bash
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
- **Email**: admin@example.com
- **Password**: password

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
- **CSS Framework**: Tailwind CSS
- **Icons**: FontAwesome
- **Notifications**: SweetAlert2
- **Build Tool**: Vite
- **JavaScript**: Vanilla JS / Vue.js (if applicable)
- **UI Components**: Modern responsive design with backend/frontend separation

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
php artisan db:seed --class=CmsSeeder
php artisan db:seed --class=PermissionSeeder
php artisan db:seed --class=GeneralSettingsSeeder
php artisan db:seed --class=EmailSettingsSeeder
php artisan db:seed --class=SecuritySettingsSeeder
php artisan db:seed --class=PerformanceSettingsSeeder
php artisan db:seed --class=PerformancePermissionsSeeder
php artisan db:seed --class=AssignPerformancePermissionsSeeder
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

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add some amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## Development Guidelines

- Follow PSR-12 coding standards
- Write comprehensive tests for new features
- Update documentation for any API changes
- Use meaningful commit messages
- Ensure all tests pass before submitting PR
- Maintain proper database relationships with foreign key constraints
- Use consistent naming conventions for tables and controllers
- Organize controllers in appropriate namespaces (Backend/Frontend)

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## Support

For support and questions, please contact the development team or create an issue in the repository.

## Changelog

### Version 1.3.0 (Latest)
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