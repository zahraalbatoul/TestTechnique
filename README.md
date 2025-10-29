# Multi-Tenant Project Management Application

A professional multi-tenant web application for project and task management, built with Laravel. This application demonstrates advanced multi-tenancy architecture where each organization has its own isolated database, users, projects, and tasks.

## Features

- **Multi-Tenant Architecture**: Complete data isolation per organization with separate databases
- **User Management**: Users can belong to multiple organizations (like GitHub)
- **Project Management**: CRUD operations for projects within tenant context
- **Task Management**: Task tracking with status, priority, and due dates
- **RESTful API**: Full API with Swagger/OpenAPI documentation
- **Authentication**: Laravel Breeze for web, Sanctum for API
- **Path-Based Tenant Routing**: Access organizations via `/t/{organization-slug}`

## Technology Stack

### Core Framework
- **Laravel 12**: Latest Laravel framework with modern features

### Authentication & Authorization
- **Laravel Breeze**: Simple, beautiful authentication scaffolding for web
- **Laravel Sanctum**: Lightweight API token authentication

### Multi-Tenancy
- **stancl/tenancy**: Professional multi-tenancy package for Laravel
  - Database-per-tenant isolation
  - Automatic tenant database creation
  - Tenant-aware routing and middleware

### API Documentation
- **L5-Swagger (darkaonline/l5-swagger)**: OpenAPI/Swagger documentation
  - Interactive API documentation
  - Auto-generated from code annotations
  - Testing interface included

### Testing
- **PHPUnit**: Built-in testing framework
- Feature tests for multi-tenancy isolation
- API endpoint tests

## Installation

### Prerequisites

- PHP 8.2 or higher
- Composer
- Node.js and npm
- MySQL/PostgreSQL database
- Git

### Setup Steps

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd TestTechnique
   ```

2. **Install PHP dependencies**
   ```bash
   composer install
   ```

3. **Install JavaScript dependencies**
   ```bash
   npm install
   ```

4. **Environment Configuration**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Configure Database**
   
   Edit `.env` file:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=central_db
   DB_USERNAME=your_username
   DB_PASSWORD=your_password
   ```

6. **Run Migrations**
   ```bash
   php artisan migrate
   ```

7. **Seed Demo Data**
   ```bash
   php artisan db:seed
   ```

8. **Build Frontend Assets**
   ```bash
   npm run build
   ```

9. **Start Development Server**
   ```bash
   php artisan serve
   ```

10. **Generate Swagger Documentation**
    ```bash
    php artisan l5-swagger:generate
    ```

## Usage

### Accessing Organizations

Organizations are accessed via path-based routing:

- Web: `http://localhost:8000/t/{organization-slug}`
- API: `http://localhost:8000/api/t/{organization-slug}/projects`

### Demo Credentials

After seeding, you can use:

**Web Login:**
- Email: `admin@example.com`
- Password: `password`

**Demo Organizations:**
- `acme-corp` (Acme Corporation)
- `tech-startup` (Tech Startup Inc)

### API Authentication

1. **Register a user**
   ```bash
   POST /api/register
   {
     "name": "John Doe",
     "email": "john@example.com",
     "password": "password123",
     "password_confirmation": "password123"
   }
   ```

2. **Login**
   ```bash
   POST /api/login
   {
     "email": "john@example.com",
     "password": "password123"
   }
   ```

3. **Use token in requests**
   ```bash
   Authorization: Bearer {token}
   ```

### API Documentation

Access Swagger UI at: `http://localhost:8000/api/documentation`

## Dependency Justifications

### laravel/breeze
**Purpose**: Authentication scaffolding  
**Why**: Provides a minimal, customizable authentication system without the complexity of Jetstream. Perfect for this test as it offers clean authentication out of the box.

### stancl/tenancy
**Purpose**: Multi-tenancy implementation  
**Why**: Industry-standard package for Laravel multi-tenancy. Handles database-per-tenant isolation automatically, includes bootstrappers for cache, filesystem, and queues. Essential for proper data isolation.

### laravel/sanctum
**Purpose**: API token authentication  
**Why**: Lightweight API authentication solution. Unlike Passport, Sanctum is simpler for SPA and mobile app authentication. Perfect for RESTful APIs.

### darkaonline/l5-swagger
**Purpose**: API documentation  
**Why**: Generates OpenAPI/Swagger documentation from code annotations. Provides interactive API testing interface, essential for professional API development.

### All other dependencies
Standard Laravel dependencies included for core functionality (routing, database, validation, etc.)

## Project Structure

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Auth/
│   │   │   └── ApiAuthController.php    # API authentication
│   │   ├── OrganizationController.php   # Organization management
│   │   ├── ProjectController.php         # Project CRUD (tenant-scoped)
│   │   └── TaskController.php            # Task CRUD (tenant-scoped)
│   └── Middleware/
│       └── InitializeTenancyByOrganizationSlug.php  # Custom tenant middleware
├── Models/
│   ├── Organization.php                  # Tenant model
│   ├── Project.php                       # Project model (tenant DB)
│   ├── Task.php                          # Task model (tenant DB)
│   └── User.php                          # User model (central DB)
database/
├── migrations/
│   ├── tenant/                           # Tenant-specific migrations
│   └── *.php                             # Central database migrations
└── seeders/
    ├── OrganizationSeeder.php            # Seed organizations and users
    └── Tenant/
        └── DatabaseSeeder.php             # Seed tenant data
routes/
├── api.php                               # API routes
└── web.php                               # Web routes
```

## Multi-Tenancy Architecture

### Data Isolation

- **Central Database**: Stores users, organizations (tenants), and organization-user relationships
- **Tenant Databases**: Each organization has its own database containing:
  - Projects
  - Tasks
  - Any other tenant-specific data

### Tenant Resolution

The application uses path-based tenant identification:

1. Route contains `{organization}` parameter (slug)
2. Middleware resolves organization from slug
3. Verifies user has access to organization
4. Initializes tenant context (switches database connection)
5. All subsequent queries are tenant-scoped automatically

### User Access Control

- Users can belong to multiple organizations
- Access is verified via `organization_user` pivot table
- Middleware ensures users can only access their organizations

## Testing

Run tests with:

```bash
php artisan test
```

### Test Coverage

- Multi-tenancy isolation tests
- API endpoint tests
- Tenant data separation verification
- Authentication and authorization tests

## Deployment

### Deploying to Render (Recommended)

#### 1. Prerequisites

- GitHub repository with your code
- Render account (free tier available)

#### 2. Create PostgreSQL Database

1. Go to Render Dashboard → New → PostgreSQL
2. Create a new PostgreSQL database (note the credentials)
3. Copy the Internal Database URL (for connection from your web service)

#### 3. Create Web Service

1. Go to Render Dashboard → New → Web Service
2. Connect your GitHub repository
3. Configure the service:

   **Basic Settings:**
   - **Name**: multi-tenant-pm (or your choice)
   - **Region**: Choose closest to your users
   - **Branch**: `main` (or your default branch)
   - **Root Directory**: Leave empty
   - **Runtime**: PHP
   - **Build Command**:
     ```bash
     composer install --no-dev --optimize-autoloader
     php artisan key:generate --force
     php artisan config:cache
     npm ci && npm run build
     ```
   - **Start Command**:
     ```bash
     php artisan migrate --force && php artisan tenants:migrate --force --all || true
     php artisan serve --host 0.0.0.0 --port $PORT
     ```

#### 4. Environment Variables

Add these in Render Dashboard → Environment:

**Required:**
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-service-name.onrender.com

# Database (Central DB)
DB_CONNECTION=pgsql
DB_HOST=<your-db-host>
DB_PORT=5432
DB_DATABASE=<your-db-name>
DB_USERNAME=<your-db-user>
DB_PASSWORD=<your-db-password>

# Session
SESSION_DRIVER=cookie
SESSION_SECURE_COOKIE=true

# Cache
CACHE_DRIVER=file

# Queue
QUEUE_CONNECTION=sync

# Logging
LOG_LEVEL=info
LOG_CHANNEL=stack

# Swagger (optional, to regenerate docs on deploy)
L5_SWAGGER_GENERATE_ALWAYS=true
```

#### 5. First Deployment

After the first deployment:

1. **Seed the database** (optional, for demo data):
   - Go to Render Shell or use Render's "Run Command" feature
   - Run: `php artisan db:seed --force`

2. **Create your first organization**:
   - Visit: `https://your-app.onrender.com/register`
   - Create an account
   - Go to Organizations → Create Organization

#### 6. Access Your Application

- **Web App**: `https://your-service-name.onrender.com`
- **API Docs**: `https://your-service-name.onrender.com/api/documentation`

### Alternative: Railway Deployment

Railway is similar to Render but with automatic PostgreSQL provisioning:

1. **Connect Repository**: Import from GitHub
2. **Add Database**: Railway automatically creates PostgreSQL
3. **Configure Variables**: Use Railway's database connection string
4. **Deploy**: Railway detects Laravel and deploys automatically

### Production Considerations

1. **Database Configuration**
   - Use PostgreSQL for production (recommended for multi-tenancy)
   - Each tenant gets its own database automatically
   - Monitor database count and sizes

2. **Tenant Database Creation**
   - Tenant databases are created automatically on first access
   - Ensure sufficient database limits on your hosting provider
   - Consider using Render's PostgreSQL add-on or external managed PostgreSQL

3. **Queue Configuration**
   - For production with many tenants, consider enabling queues:
   ```env
   QUEUE_CONNECTION=database  # or 'redis'
   ```
   - Configure queue worker in `TenancyServiceProvider.php`

4. **Cache Configuration**
   - Use Redis for production (optional but recommended):
   ```env
   CACHE_DRIVER=redis
   REDIS_HOST=your-redis-host
   ```
   - Tenant-aware cache tags are automatically handled

5. **Storage**
   - For file storage, configure S3 or similar:
   ```env
   FILESYSTEM_DISK=s3
   AWS_ACCESS_KEY_ID=your-key
   AWS_SECRET_ACCESS_KEY=your-secret
   AWS_DEFAULT_REGION=us-east-1
   AWS_BUCKET=your-bucket
   ```

6. **HTTPS**
   - Both Render and Railway provide HTTPS automatically
   - Ensure `APP_URL` uses `https://` in production

### Troubleshooting Deployment

**Issue: Migrations fail**
- Solution: Ensure database credentials are correct and database exists

**Issue: Tenant databases not created**
- Solution: Check logs for tenancy errors. Ensure DB user has CREATE DATABASE permissions

**Issue: 500 errors after deployment**
- Solution: Check `storage/logs/laravel.log` in Render shell
- Ensure `APP_KEY` is set (will be auto-generated on first build)

**Issue: Assets not loading**
- Solution: Ensure `npm run build` completed successfully in build logs
- Check that `public/build` directory exists

## CI/CD

GitHub Actions workflow is configured for:
- Automated testing
- Code quality checks
- Deployment preparation

## API Endpoints

### Public Endpoints
- `POST /api/register` - Register new user
- `POST /api/login` - User login
- `GET /api/organizations` - List organizations

### Tenant-Scoped Endpoints
All require authentication and tenant context:

**Projects:**
- `GET /api/t/{organization}/projects` - List projects
- `POST /api/t/{organization}/projects` - Create project
- `GET /api/t/{organization}/projects/{id}` - Get project
- `PUT /api/t/{organization}/projects/{id}` - Update project
- `DELETE /api/t/{organization}/projects/{id}` - Delete project

**Tasks:**
- `GET /api/t/{organization}/tasks` - List tasks
- `POST /api/t/{organization}/tasks` - Create task
- `GET /api/t/{organization}/tasks/{id}` - Get task
- `PUT /api/t/{organization}/tasks/{id}` - Update task
- `DELETE /api/t/{organization}/tasks/{id}` - Delete task
- `GET /api/t/{organization}/projects/{id}/tasks` - Get tasks for project

## License

This project is a technical test/demo application.

## Author

Created as a technical test demonstrating Laravel multi-tenancy expertise.
