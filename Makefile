up:
	docker-compose up -d

down:
	docker-compose down

migrate:
	docker-compose exec php php artisan migrate

tinker:
	docker-compose exec php php artisan tinker

install:
	docker-compose exec php php artisan app:fresh-install
