.PHONY .SILENT: up
up:
	docker-compose up -d

.PHONY .SILENT: down
down:
	docker-compose down

.PHONY .SILENT: restart
restart: down up

.PHONY .SILENT: recreate
recreate:
	../docker/docker compose up --force-recreate -d

.PHONY .SILENT: migrate
migrate:
	docker exec fakturace_app php bin/console doctrine:migrations:migrate --no-interaction --quiet

.PHONY .SILENT: migration
migration:
	docker exec fakturace_app php bin/console make:migration

.PHONY .SILENT: install
install: ## Installs composer packages
	docker exec fakturace_app composer install

.PHONY .SILENT: update
update: ## Updates composer packages
	docker exec fakturace_app composer update
	docker exec fakturace_app composer audit

.PHONY .SILENT: font
font: ## Copy fonts
	cp fonts/* vendor/tecnickcom/tcpdf/fonts


.PHONY .SILENT: test
test: ## Runs phpunit tests
	docker exec fakturace_app php bin/console cache:clear --env=test
	docker exec fakturace_app php bin/console doctrine:database:drop --env=test --if-exists --force
	docker exec fakturace_app php bin/console doctrine:database:create --env=test
	docker exec fakturace_app php bin/console doctrine:schema:create --env=test --no-interaction
	docker exec fakturace_app php bin/phpunit

#.PHONY .SILENT: install
#fixtures: ## Load doctrine fixtures
#	docker exec fakturace_app php bin/console doctrine:fixtures:load --env=test --no-interaction --purger=PURGER

.PHONY .SILENT: check
check: ## Runs static analysis and formatter checks
	docker exec fakturace_app php bin/console doctrine:schema:validate
	#docker exec fakturace_app ./vendor/bin/phpcs
	docker exec fakturace_app php bin/console cache:clear --quiet
	docker exec fakturace_app php bin/console cache:clear --env=test --quiet
	docker exec fakturace_app php -d memory_limit=1G ./vendor/bin/phpstan analyze -vvv