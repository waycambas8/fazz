#!/bin/bash


git pull origin main
docker rm -f bri-nginx bri db || true

docker-compose -f .docker/compose-dev.yml up -d --build
docker exec -it bri composer install
docker exec -it bri php artisan key:generate

echo "Docker containers are up and running"
