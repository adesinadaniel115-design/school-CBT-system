# Deploy to Render - Quick Guide

## Prerequisites
1. Push your code to GitHub (branch: main)
2. Create a Render account at https://render.com

## Deployment Steps

### 1. Connect GitHub Repository
- Go to https://dashboard.render.com
- Click **"New +"** → **"Blueprint"**
- Select repository: `adesinadaniel115-design/school-CBT-system`
- Branch: `main`

### 2. Apply Blueprint
- Render will automatically detect `render.yaml`
- Review the services:
  - ✅ Web Service: school-cbt-system
  - ✅ Database: school-cbt-db (PostgreSQL)
- Click **"Apply"**

### 3. Wait for Deployment
- First deployment takes 5-10 minutes
- Render will:
  - Build Docker image from Dockerfile
  - Create PostgreSQL database
  - Run migrations automatically
  - Generate SSL certificate

### 4. Access Your App
- URL: https://school-cbt-system.onrender.com
- Check logs if there are issues: Dashboard → school-cbt-system → Logs

## What's Configured

### Environment Variables (Auto-set)
- `APP_ENV=production`
- `APP_DEBUG=false`
- `DB_CONNECTION=pgsql` (PostgreSQL)
- Database credentials (auto-connected)
- `APP_KEY` (auto-generated on first boot)

### Persistent Storage
- 1GB disk mounted at `/var/www/html/storage`
- Stores uploaded files, logs, sessions

### Region
- Frankfurt, Germany (closest to Nigeria)

## Post-Deployment

### Create Admin User
Run via Render Shell (Dashboard → Shell):
```bash
php artisan db:seed --class=AdminUserSeeder
```

Or create manually in database.

### Check Application Status
- Health check: https://school-cbt-system.onrender.com
- Database: Render Dashboard → school-cbt-db → Info

## Troubleshooting

### App won't start
- Check logs: Dashboard → school-cbt-system → Logs
- Common issues:
  - Database not connected (wait 1-2 min)
  - Out of memory (upgrade from starter plan)

### Database connection failed
- Verify database is running: Dashboard → school-cbt-db
- Check environment variables are set

### Need to run migrations manually
Shell:
```bash
php artisan migrate --force
```

## Files Used for Deployment
- `render.yaml` - Render blueprint configuration
- `Dockerfile` - Container image definition
- `.dockerignore` - Files excluded from Docker build
- `docker/entrypoint.sh` - Startup script (migrations, storage setup)
- `docker/nginx/default.conf` - Nginx web server config
- `docker/supervisor/supervisord.conf` - Process manager
- `docker/php/php.ini` - PHP configuration

## Cost
- **Starter Plan**: Free tier (hobby projects)
- **Standard Plan**: $7/month (production apps)
- Database included in plan

## Support
If deployment fails, check Render docs: https://render.com/docs
