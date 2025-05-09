# Makefile amélioré

# Commandes de développement
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

# Commandes de production
prod-deploy:
	/opt/temposwim/deploy.sh

prod-rollback:
	/opt/temposwim/rollback.sh

prod-healthcheck:
	/opt/temposwim/healthcheck.sh

prod-backup:
	/opt/temposwim/backup.sh

# Commandes de maintenance
clear-cache:
	docker exec temposwim-laravel php artisan cache:clear
	docker exec temposwim-laravel php artisan config:cache
	docker exec temposwim-laravel php artisan route:cache
	docker exec temposwim-laravel php artisan view:cache

storage-link:
	docker exec temposwim-laravel php artisan storage:link

logs:
	docker logs temposwim-laravel
	docker logs temposwim-react