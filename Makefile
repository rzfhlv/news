up:
	docker-compose up -d

down:
	docker-compose down

migrate:
	docker-compose exec php php artisan migrate

tinker:
	docker-compose exec php php artisan tinker

compose:
	docker-compose exec php composer install

install:
	docker-compose exec php composer install \
	&& docker-compose exec php php artisan app:fresh-install
