# Quick Start Guide

## Prerequisites
- PHP 8.2+
- Composer
- MySQL/PostgreSQL or SQLite for development
- Node.js & npm

## Installation (5 minutes)

```bash
# 1. Install dependencies
composer install
npm install

# 2. Setup environment
cp .env.example .env
php artisan key:generate

# 3. Configure database in .env
DB_CONNECTION=mysql
DB_DATABASE=your_db_name

# 4. Run migrations
php artisan migrate

# 5. Seed data
php artisan db:seed

# 6. Build frontend
npm run build

# 7. Start server
php artisan serve
```

## First Steps

1. **Access the app**: http://localhost:8000

2. **Login**:
   - Email: `admin@example.com`
   - Password: `password`

3. **Access an organization**:
   - Go to: http://localhost:8000/t/acme-corp
   - Or: http://localhost:8000/t/tech-startup

4. **API Documentation**:
   - Visit: http://localhost:8000/api/documentation
   - Generate docs first: `php artisan l5-swagger:generate`

## Testing API

1. Register/Login via API:
```bash
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{"email":"admin@example.com","password":"password"}'
```

2. Use the token:
```bash
curl -X GET http://localhost:8000/api/t/acme-corp/projects \
  -H "Authorization: Bearer YOUR_TOKEN"
```

## Testing

```bash
php artisan test
```

## Generate Swagger Docs

```bash
php artisan l5-swagger:generate
```

