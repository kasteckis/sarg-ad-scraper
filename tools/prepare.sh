docker compose stop
docker compose up -d --build
docker compose exec web composer install
# todo fix this
sleep 10
docker compose exec web php bin/console d:s:u --force
docker exec -it sarg_web bash
