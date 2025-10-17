# CMS Backend System

<p align="center">
<img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="300" alt="Laravel Logo">
</p>

<p align="center">
<strong>Content Management System Backend</strong><br>
Built with Laravel Framework
</p>

## About This Project

This is a comprehensive Content Management System (CMS) backend built with Laravel framework. The system provides a robust foundation for managing users, roles, permissions, and content with a clean, organized database structure using `core_` prefixed tables.

## Features

- **User Management**: Complete user registration, authentication, and profile management
- **Role-Based Access Control**: Flexible role and permission system
- **Audit Logging**: Track all system activities and changes
- **Settings Management**: Dynamic configuration system
- **Login Security**: Track login attempts and implement security measures
- **RESTful API**: Clean API endpoints for frontend integration
- **Database Organization**: All tables use `core_` prefix for better organization

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

9. **Start development server**
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

### Frontend
- **CSS Framework**: Tailwind CSS
- **Icons**: FontAwesome
- **Notifications**: SweetAlert2
- **Build Tool**: Vite
- **JavaScript**: Vanilla JS / Vue.js (if applicable)

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
```

## Contributing

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add some amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## Support

For support and questions, please contact the development team or create an issue in the repository.