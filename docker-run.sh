#!/bin/bash

cd backend
cp .env.example .env || exit 1

cd ..

docker compose up -d --build || exit 1

docker exec -it backend composer install || exit 1

docker exec -it backend php artisan migrate --seed || exit 1

cd frontend
npm install || exit 1

sudo rm -rf dist/
npm run build || exit 1