#!/bin/bash

ENVIRONMENT="$5"
APP_DIR="$3"

export ENVIRONMENT
export APP_DIR

# shellcheck disable=SC1009
if [ -z "$ENVIRONMENT" ]; then
  ENVIRONMENT="dev"  # Set a default value if ENVIRONMENT is empty or undefined
  cp /var/www/vpa/ianalytics-api/.env "$APP_DIR"/.env

else
  USER="$6"
  HOST_DEV="$7"

  export USER
  export HOST_DEV

  sudo scp -r "$APP_DIR" "$USER@$HOST_DEV:$APP_DIR"
fi

# Check the environment and conditionally log in to the Docker registry
  if [ -n "$ENVIRONMENT" ] && [ "$ENVIRONMENT" != "dev" ]; then
    # Assign values passed as arguments to local variables
    REGISTRY_USER="$1"
    REPOSITORY_NAME="$2"
    REGISTRY_PASSWORD="$4"

    # Set environment variables
    export REGISTRY_USER
    export REPOSITORY_NAME
    export REGISTRY_PASSWORD

    docker login -u "$REGISTRY_USER" -p "$REGISTRY_PASSWORD"
fi

DOCKER_IMAGE="$REGISTRY_USER/$REPOSITORY_NAME:api-latest"

# Pull the latest Docker image
docker pull "$DOCKER_IMAGE"

# Stop and remove the existing container (if it exists)
docker stop vpa-api-container || true
docker rm vpa-api-container || true

# Install composer dependencies
composer install --no-interaction --prefer-dist --optimize-autoloader

# Run the Docker container
docker run -d --name vpa-api-container -p 8001:80 -v "$APP_DIR":/var/www/html "$DOCKER_IMAGE"

# Run artisan optimize:clear (if Laravel project)
docker exec vpa-api-container php artisan optimize:clear
