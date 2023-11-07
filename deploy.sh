#!/bin/bash

ENVIRONMENT="$5"
export ENVIRONMENT

# Check the environment and conditionally log in to the Docker registry
  if [ -n "$ENVIRONMENT" ] && [ "$ENVIRONMENT" != "dev" ]; then
    # Assign values passed as arguments to local variables
    REGISTRY_USER="$1"
    REPOSITORY_NAME="$2"
    APP_DIR="$3"
    REGISTRY_PASSWORD="$4"

    # Set environment variables
    export REGISTRY_USER
    export REPOSITORY_NAME
    export APP_DIR
    export REGISTRY_PASSWORD

    docker login -u "$REGISTRY_USER" -p "$REGISTRY_PASSWORD"
fi

DOCKER_IMAGE="$REGISTRY_USER/$REPOSITORY_NAME:api-latest"

# Pull the latest code from the repository
git checkout "$ENVIRONMENT"
git pull

# Pull the latest Docker image
docker pull "$DOCKER_IMAGE"

# Stop and remove the existing container (if it exists)
docker stop vpa-api-container || true
docker rm vpa-api-container || true

# Run the Docker container
docker run -d --name vpa-api-container -p 8001:80 -v "$APP_DIR":/var/www/html "$DOCKER_IMAGE"

# Set permissions for storage and bootstrap/cache directories
docker exec vpa-api-container chmod -R 775 storage bootstrap/cache

# Run artisan optimize:clear (if Laravel project)
docker exec vpa-api-container php artisan optimize:clear
