#!/usr/bin/env bash

docker exec -i bot-mysql-db mysql -uroot -psecurerootpassword <<< "CREATE DATABASE mychatbot;"


docker exec -u root -it bot bash -c "sudo chown root:root storage/logs"
docker exec -u root -it bot bash -c "sudo chown -R devuser:devuser storage/logs"
docker exec -u devuser -it bot bash -c "composer install"
docker exec -u devuser -it bot bash -c "php artisan migrate"

