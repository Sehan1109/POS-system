#!/bin/bash

# DigitalOcean Deployment Script for POS System
# This script automates the deployment process to DigitalOcean

set -e

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

echo -e "${YELLOW}🚀 POS System - DigitalOcean Deployment Script${NC}"
echo ""

# Check if DigitalOcean CLI is installed
if ! command -v doctl &> /dev/null; then
    echo -e "${RED}❌ doctl (DigitalOcean CLI) is not installed.${NC}"
    echo "Please install it from: https://docs.digitalocean.com/reference/doctl/how-to/install/"
    exit 1
fi

# Check if Docker is installed
if ! command -v docker &> /dev/null; then
    echo -e "${RED}❌ Docker is not installed.${NC}"
    echo "Please install Docker from: https://docs.docker.com/get-docker/"
    exit 1
fi

# Variables
APP_NAME="pos-system"
REGISTRY="registry.digitalocean.com"
DOCKERFILE="./Dockerfile"
IMAGE_NAME="${APP_NAME}:latest"
DOCKER_REGISTRY_NAME="${REGISTRY}/${APP_NAME}/${IMAGE_NAME}"

echo -e "${YELLOW}📋 Deployment Configuration:${NC}"
echo "  App Name: $APP_NAME"
echo "  Registry: $REGISTRY"
echo "  Image Name: $DOCKER_REGISTRY_NAME"
echo ""

# Step 1: Build Docker image
echo -e "${YELLOW}🔨 Step 1: Building Docker image...${NC}"
docker build -t $IMAGE_NAME -f $DOCKERFILE .
if [ $? -eq 0 ]; then
    echo -e "${GREEN}✅ Docker image built successfully${NC}"
else
    echo -e "${RED}❌ Failed to build Docker image${NC}"
    exit 1
fi
echo ""

# Step 2: Tag image for DigitalOcean Registry
echo -e "${YELLOW}🏷️  Step 2: Tagging image for DigitalOcean Registry...${NC}"
docker tag $IMAGE_NAME $DOCKER_REGISTRY_NAME
echo -e "${GREEN}✅ Image tagged successfully${NC}"
echo ""

# Step 3: Login to DigitalOcean Registry
echo -e "${YELLOW}🔐 Step 3: Logging in to DigitalOcean Registry...${NC}"
doctl registry login
if [ $? -eq 0 ]; then
    echo -e "${GREEN}✅ Successfully logged in to DigitalOcean Registry${NC}"
else
    echo -e "${RED}❌ Failed to login to DigitalOcean Registry${NC}"
    exit 1
fi
echo ""

# Step 4: Push image to DigitalOcean Registry
echo -e "${YELLOW}📤 Step 4: Pushing image to DigitalOcean Registry...${NC}"
docker push $DOCKER_REGISTRY_NAME
if [ $? -eq 0 ]; then
    echo -e "${GREEN}✅ Image pushed successfully${NC}"
else
    echo -e "${RED}❌ Failed to push image${NC}"
    exit 1
fi
echo ""

# Step 5: Deploy using DigitalOcean CLI or App Platform
echo -e "${YELLOW}🚀 Step 5: Deploying application...${NC}"
echo "Choose deployment method:"
echo "1) DigitalOcean App Platform (Recommended)"
echo "2) DigitalOcean Kubernetes Cluster"
echo "3) Skip deployment (manual)"
read -p "Enter your choice (1-3): " choice

case $choice in
    1)
        echo "Deploying to App Platform..."
        doctl apps create-deployment --spec=app.yaml --wait
        echo -e "${GREEN}✅ Application deployed to App Platform${NC}"
        ;;
    2)
        echo "Deploy to Kubernetes manually using:"
        echo "  kubectl set image deployment/pos-system pos-system=$DOCKER_REGISTRY_NAME"
        ;;
    3)
        echo -e "${YELLOW}⏭️  Skipping deployment. Manual deployment required.${NC}"
        ;;
    *)
        echo -e "${RED}❌ Invalid choice${NC}"
        exit 1
        ;;
esac

echo ""
echo -e "${GREEN}🎉 Deployment process completed!${NC}"
echo ""
echo "Next steps:"
echo "1. Verify your application at: https://cloud.digitalocean.com/apps"
echo "2. Run database migrations: doctl apps exec <app-id> -- php artisan migrate"
echo "3. Monitor logs: doctl apps logs <app-id> --follow"
echo ""
