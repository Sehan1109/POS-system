# DigitalOcean Deployment Guide

This guide covers deployment of the POS System to DigitalOcean App Platform.

## Prerequisites

- DigitalOcean account: https://www.digitalocean.com
- GitHub account with this repository
- DigitalOcean CLI (`doctl`) installed and authenticated

## 1. Install and Authenticate `doctl`

Install instructions: https://docs.digitalocean.com/reference/doctl/how-to/install/

Authenticate:

```bash
doctl auth init
```

## 2. Configure `app.yaml`

Update the GitHub repo value in `app.yaml`:

```yaml
services:
    - name: pos-system-web
        github:
            branch: main
            repo: YOUR_GITHUB_USERNAME/POS-system
```

## 3. Deploy

```bash
doctl apps create-deployment --spec=app.yaml --wait
```

## 4. CI/CD Workflows

The repository includes GitHub Actions workflows:

- `.github/workflows/ci.yml`: runs tests, migrations, and asset builds on push/PR.
- `.github/workflows/deploy.yml`: deploys to DigitalOcean App Platform on pushes to `main`.

### Required GitHub Secret

Add this secret in your repository settings:

- `DIGITALOCEAN_ACCESS_TOKEN`

Create token here: https://cloud.digitalocean.com/account/api/tokens

## 5. Post-Deployment

Run migrations:

```bash
doctl apps exec <app-id> -- php artisan migrate --force
```

Generate app key if needed:

```bash
doctl apps exec <app-id> -- php artisan key:generate
```

Seed database (optional):

```bash
doctl apps exec <app-id> -- php artisan db:seed
```

Monitor logs:

```bash
doctl apps logs <app-id> --follow
```

Check app status:

```bash
doctl apps describe <app-id>
```
