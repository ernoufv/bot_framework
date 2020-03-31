#!/bin/bash
file=./initialized.bot
if test ! -f "$file"; then
    docker-compose up -d
    docker exec -i bot-mysql-db mysql -uroot -psecurerootpassword <<< "CREATE DATABASE mychatbot;"
    docker exec -u root -it bot bash -c "sudo chown root:root storage/logs"
    docker exec -u root -it bot bash -c "sudo chown -R devuser:devuser storage/logs"
    docker exec -u devuser -it bot bash -c "composer install"
    docker exec -u devuser -it bot bash -c "php artisan migrate"

    printf  "Your installation is initialized" -> ./initialized.bot

    printf  "#!/usr/bin/env bash\ndocker-compose -f docker-compose.yml up" -> ./run
    chmod +x ./run

    docker-compose down

    echo "Installation done ! Now, run \"./run\""
else
    echo "Your installation is already done !"
fi