# CMS Backend System

<p align="center">
<img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="300" alt="Laravel Logo">
</p>

<p align="center">
<strong>Content Management System Backend</strong><br>
Built with Laravel Framework
</p>

## About This Project

This is a comprehensive Content Management System (CMS) backend built with Laravel framework. The system provides a robust foundation for managing users, roles, permissions, content, and system administration with a clean, organized database structure using `core_` prefixed tables. It includes advanced features like system update management, performance monitoring, and comprehensive audit logging.

## Features

- **User Management**: Complete user registration, authentication, and profile management
- **Role-Based Access Control**: Flexible role and permission system
- **Audit Logging**: Track all system activities and changes
- **Settings Management**: Dynamic configuration system
- **System Update Management**: Laravel core, packages, and configuration updates
- **System Information**: Comprehensive system monitoring and diagnostics
- **Performance Settings**: System performance optimization and monitoring
- **Login Security**: Track login attempts and implement security measures
- **RESTful API**: Clean API endpoints for frontend integration
- **Database Organization**: All tables use `core_` prefix for better organization
- **Modern UI**: Tailwind CSS with SweetAlert2 notifications

## Database Structure

All database tables use the `core_` prefix for consistency:

- `core_users` - User accounts and profiles
- `core_roles` - User roles
- `core_permissions` - System permissions
- `core_role_permissions` - Role-permission relationships
- `core_user_roles` - User-role assignments
- `core_audit_logs` - System activity tracking
- `core_settings` - Application settings
- `core_login_attempts` - Security monitoring
- `core_sessions` - User sessions
- `core_cache` - Application cache
- `core_jobs` - Background job processing
- `core_migrations` - Migration tracking
- `core_settings_updates` - System update tracking
- `core_performance_settings` - Performance configuration

## Installation

1. **Clone the repository**
   ```bash
   git clone <repository-url>
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
   
   # Install additional packages
   npm install sweetalert2
   npm install @fortawesome/fontawesome-free
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
   ```

9. **Run additional seeders**
   ```bash
   php artisan db:seed --class=PermissionSeeder
   php artisan db:seed --class=PerformanceSeeder
   ```

10. **Start development server**
    ```bash
    php artisan serve
    ```

## API Endpoints

### Authentication
- `POST /api/login` - User login
- `POST /api/logout` - User logout
- `POST /api/register` - User registration

### Dashboard
- `GET /api/dashboard` - Dashboard statistics

### User Management
- `GET /api/users` - List all users
- `POST /api/users` - Create new user
- `GET /api/users/{id}` - Get user details
- `PUT /api/users/{id}` - Update user
- `DELETE /api/users/{id}` - Delete user

### Role Management
- `GET /api/roles` - List all roles
- `POST /api/roles` - Create new role
- `PUT /api/roles/{id}` - Update role
- `DELETE /api/roles/{id}` - Delete role

### Permission Management
- `GET /api/permissions` - List all permissions
- `POST /api/permissions` - Create new permission
- `PUT /api/permissions/{id}` - Update permission
- `DELETE /api/permissions/{id}` - Delete permission

### System Management
- `GET /api/settings-update` - System update status
- `POST /api/settings-update/quick-update` - Execute system updates
- `POST /api/settings-update/clear-cache` - Clear system cache
- `POST /api/settings-update/optimize` - Optimize system
- `GET /api/settings-systeminfo` - System information
- `GET /api/settings-systeminfo/export` - Export system info
- `GET /api/settings-performance` - Performance settings
- `POST /api/settings-performance/update` - Update performance settings

## Default Credentials

After running the seeder, you can login with:
- **Email**: admin@example.com
- **Password**: password

## Technology Stack

### Backend
- **Framework**: Laravel 11
- **Database**: MySQL/SQLite
- **Authentication**: Laravel Sanctum
- **API**: RESTful API design
- **System Management**: Update tracking, performance monitoring
- **Security**: Audit logging, login attempt tracking

### Frontend
- **CSS Framework**: Tailwind CSS
- **Icons**: FontAwesome
- **Notifications**: SweetAlert2
- **Build Tool**: Vite
- **JavaScript**: Vanilla JS / Vue.js (if applicable)
- **UI Components**: Modern responsive design

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

# Clear caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# System management
php artisan settings:update
php artisan settings:performance
php artisan system:info

# Database operations
php artisan migrate:fresh --seed
php artisan db:wipe
php artisan db:seed --class=PermissionSeeder
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

# Install additional packages
npm install sweetalert2
npm install @fortawesome/fontawesome-free

# Build for production
npm run build

# Development with hot reload
npm run dev
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

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## Changelog

### Version 1.0.0 (Latest)
- Initial release with core CMS functionality
- User management and role-based access control
- System update management
- Performance monitoring
- Comprehensive audit logging
- Modern UI with Tailwind CSS and SweetAlert2

## Support

For support and questions, please contact the development team or create an issue in the repository.

## System Requirements

- **PHP**: 8.1 or higher
- **Composer**: Latest version
- **Node.js**: 16.x or higher
- **Database**: MySQL 5.7+ or SQLite 3.8+
- **Web Server**: Apache/Nginx (for production)

## Recent Updates

- ✅ **System Update Management**: Laravel core, packages, and configuration updates
- ✅ **System Information**: Comprehensive system monitoring and diagnostics
- ✅ **Performance Settings**: System performance optimization and monitoring
- ✅ **SweetAlert2 Integration**: Modern notification system
- ✅ **Tailwind CSS**: Modern responsive UI design
- ✅ **Audit Logging**: Comprehensive system activity tracking