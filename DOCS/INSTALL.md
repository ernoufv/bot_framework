# Environment install

> docker image build --tag bot-framework ./docker

When image is created :
- create bot database
- set rights in logs directories
- install project dependencies with composer 
- migrate databae structure


You can use this script :
> chmod +x ./init.sh
> ./init.sh


or type manually these commands :
```
docker-compose -f docker-compose.yml up
docker exec -i bot-mysql-db mysql -uroot -psecurerootpassword <<< "CREATE DATABASE mychatbot;"
docker exec -u root -it bot bash -c "sudo chown root:root storage/logs"
docker exec -u root -it bot bash -c "sudo chown -R devuser:devuser storage/logs"
docker exec -u devuser -it bot bash -c "composer install"
docker exec -u devuser -it bot bash -c "php artisan migrate"
```

# Run : 

> docker-compose -f docker-compose.yml up


You're ready, I think