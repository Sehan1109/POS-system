# DigitalOcean Deployment Guide

This guide walks you through deploying the POS System to DigitalOcean.

## Prerequisites

- DigitalOcean account (https://www.digitalocean.com)
- GitHub account with this repository
- Local machine with Docker, Node.js, PHP, and Composer installed
- DigitalOcean CLI (`doctl`) installed and configured

## Installation

### 1. Install DigitalOcean CLI

**macOS (with Homebrew):**

```bash
brew install doctl
```

**Linux:**

```bash
cd ~
wget https://github.com/digitalocean/doctl/releases/download/v1.98.4/doctl-1.98.4-linux-x86_64.tar.gz
tar xf ~/doctl-1.98.4-linux-x86_64.tar.gz
sudo mv ~/doctl /usr/local/bin
```

**Windows:**
Download the binary from: https://github.com/digitalocean/doctl/releases

### 2. Authenticate with DigitalOcean

```bash
doctl auth init
```

Follow the prompts to enter your DigitalOcean API token. You can generate one at:
https://cloud.digitalocean.com/account/api/tokens

## Deployment Methods

### Method 1: Using DigitalOcean App Platform (Recommended)

**Step 1: Update app.yaml**

Edit the `app.yaml` file and replace `YOUR_GITHUB_USERNAME` with your actual GitHub username:

```yaml
services:
    - name: pos-system-web
      github:
          branch: main
          repo: YOUR_GITHUB_USERNAME/POS-system # Update this
```

**Step 2: Set up GitHub Integration**

1. Go to https://cloud.digitalocean.com/apps
2. Click "Create App"
3. Select GitHub as the source
4. Authorize DigitalOcean with GitHub
5. Select your POS-system repository
6. Choose the `main` branch

**Step 3: Configure Environment Variables**

In the DigitalOcean App Platform interface, set the following environment variables:

```
APP_KEY=                          # Leave blank - generated at runtime
DB_HOST=${db.HOSTNAME}
DB_DATABASE=${db.DATABASE}
DB_USERNAME=${db.USERNAME}
DB_PASSWORD=${db.PASSWORD}
MAIL_USERNAME=your_mail_service_username
MAIL_PASSWORD=your_mail_service_password
REDIS_HOST=${redis.HOSTNAME}
REDIS_PASSWORD=${redis.PASSWORD}
```

**Step 4: Deploy**

```bash
doctl apps create-deployment --spec=app.yaml --wait
```

### Method 2: Using Docker with App Platform

**Step 1: Create a Container Registry**

```bash
doctl registry create pos-system-registry
```

**Step 2: Build and Push Docker Image**

```bash
# Authenticate Docker with DigitalOcean Registry
doctl registry login

# Build the image
docker build -t pos-system:latest .

# Tag for DigitalOcean Registry
docker tag pos-system:latest registry.digitalocean.com/pos-system-registry/pos-system:latest

# Push to registry
docker push registry.digitalocean.com/pos-system-registry/pos-system:latest
```

**Step 3: Run Deployment Script**

```bash
chmod +x digitalocean-deploy.sh
./digitalocean-deploy.sh
```

### Method 3: Manual Kubernetes Deployment

If you have a Kubernetes cluster on DigitalOcean (DOKS):

```bash
# Create a namespace
kubectl create namespace pos-system

# Create database secret
kubectl create secret generic db-credentials \
  --from-literal=DB_HOST=your-db-host \
  --from-literal=DB_USERNAME=your-db-user \
  --from-literal=DB_PASSWORD=your-db-password \
  -n pos-system

# Apply Kubernetes manifests (create k8s-deployment.yaml for this)
kubectl apply -f k8s-deployment.yaml
```

## GitHub Actions CI/CD Setup

The repository includes GitHub Actions workflows for automatic CI/CD:

### CI Workflow (.github/workflows/ci.yml)

Runs on every push and pull request:

- PHP Unit Tests (Pest)
- Database Migrations
- Build Asset Compilation

### Deployment Workflow (.github/workflows/deploy.yml)

Automatically deploys to DigitalOcean when pushing to `main` branch:

**Setup:**

1. Generate DigitalOcean Personal Access Token:
    - Visit: https://cloud.digitalocean.com/account/api/tokens
    - Create a new token with read/write access

2. Add token to GitHub Secrets:
    - Go to your GitHub repository
    - Settings → Secrets and variables → Actions
    - Click "New repository secret"
    - Name: `DIGITALOCEAN_ACCESS_TOKEN`
    - Value: Your DigitalOcean token

## Post-Deployment

### 1. Run Database Migrations

```bash
# For App Platform
doctl apps exec <app-id> -- php artisan migrate --force

# For Kubernetes
kubectl exec -it deployment/pos-system -n pos-system -- php artisan migrate --force
```

### 2. Generate Application Key

The app key should be auto-generated during deployment, but if needed:

```bash
# For App Platform
doctl apps exec <app-id> -- php artisan key:generate

# For Kubernetes
kubectl exec -it deployment/pos-system -n pos-system -- php artisan key:generate
```

### 3. Seed Database (Optional)

```bash
# For App Platform
doctl apps exec <app-id> -- php artisan db:seed

# For Kubernetes
kubectl exec -it deployment/pos-system -n pos-system -- php artisan db:seed
```

### 4. Monitor Application

**View Logs:**

```bash
# For App Platform
doctl apps logs <app-id> --follow

# For Kubernetes
kubectl logs -f deployment/pos-system -n pos-system
```

**Check Status:**

```bash
# For App Platform
doctl apps describe <app-id>

# For Kubernetes
kubectl get pods -n pos-system
kubectl describe pod <pod-name> -n pos-system
```

## Environment Configuration

### Database Setup

DigitalOcean provides managed MySQL databases. To use one:

1. Create a managed MySQL database in DigitalOcean Console
2. Note the connection details
3. Update environment variables in App Platform

Connection string format:

```
mysql://username:password@hostname:port/database
```

### Redis Cache (Optional)

For better performance with Livewire, use Redis:

1. Create a managed Redis cluster
2. Update `.env` with Redis connection details
3. Set `CACHE_DRIVER=redis`

### Storage

For file uploads, use DigitalOcean Spaces:

1. Create a Space
2. Configure AWS SDK in Laravel:

```php
// config/filesystems.php
'spaces' => [
    'driver' => 's3',
    'key' => env('AWS_ACCESS_KEY_ID'),
    'secret' => env('AWS_SECRET_ACCESS_KEY'),
    'region' => env('AWS_DEFAULT_REGION'),
    'bucket' => env('AWS_BUCKET'),
    'endpoint' => env('AWS_ENDPOINT'),
    'use_path_style_endpoint' => false,
],
```

## Troubleshooting

### Application Won't Start

```bash
# Check logs
doctl apps logs <app-id>

# Verify environment variables are set correctly
doctl apps describe <app-id>
```

### Database Connection Issues

```bash
# Verify database is running and accessible
# Check firewall rules in DigitalOcean Console
# Ensure DB_HOST is correct
```

### Asset Build Failures

```bash
# Check that npm run build succeeds locally
npm install
npm run build

# Verify public/build directory exists
ls -la public/build
```

### Performance Issues

- Enable Redis for caching
- Use managed database for better performance
- Consider scaling to multiple instances
- Monitor resource usage in DigitalOcean Console

## Scaling

To scale your application:

### App Platform

Update `app.yaml`:

```yaml
services:
    - name: pos-system-web
      instance_count: 3 # Scale to 3 instances
      instance_size_slug: basic-l # Increase instance size
```

### Kubernetes

```bash
# Scale deployment
kubectl scale deployment/pos-system --replicas=3 -n pos-system
```

## Security Best Practices

1. ✅ Always use HTTPS (DigitalOcean provides free SSL)
2. ✅ Rotate your `APP_KEY` periodically
3. ✅ Use strong database passwords
4. ✅ Enable two-factor authentication on your DigitalOcean account
5. ✅ Keep dependencies updated
6. ✅ Use environment variables for sensitive data
7. ✅ Regularly backup your database

## Rollback Procedure

If you need to rollback to a previous version:

**App Platform:**

```bash
doctl apps deployments list <app-id>
doctl apps deployments rollback <app-id> <deployment-id>
```

**Kubernetes:**

```bash
kubectl rollout history deployment/pos-system -n pos-system
kubectl rollout undo deployment/pos-system -n pos-system --to-revision=<revision-number>
```

## Support

For issues or questions:

- DigitalOcean Docs: https://docs.digitalocean.com
- Laravel Docs: https://laravel.com/docs
- Livewire Docs: https://livewire.laravel.com
- GitHub Issues: Create an issue in your repository

## Additional Resources

- DigitalOcean App Platform: https://www.digitalocean.com/products/app-platform/
- DigitalOcean Database: https://www.digitalocean.com/products/managed-databases/
- DigitalOcean Kubernetes: https://www.digitalocean.com/products/kubernetes/
- DigitalOcean Spaces: https://www.digitalocean.com/products/spaces/
