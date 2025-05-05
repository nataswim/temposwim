setup:
	@make build
	@make up
	@make composer-update
dev:
	make -j 2 artisan-serve npm-dev
artisan-serve:
	cd back && php artisan serve
npm-dev:
	cd front && npm run dev
build:
	docker-compose build --no-cache --force-rm
stop:
	docker-compose stop
up:
	docker-compose up -d
composer-update:
	docker exec temposwim-laravel bash -c "composer update"
data:
	docker exec temposwim-laravel bash -c "php artisan migrate"
	docker exec temposwim-laravel bash -c "php artisan db:seed"