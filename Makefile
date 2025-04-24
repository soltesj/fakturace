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
	docker-compose up --force-recreate -d

.PHONY .SILENT: migrate
migrate:
	docker exec fakturace_app php bin/console doctrine:migrations:migrate --no-interaction --quiet

.PHONY .SILENT: install
install: ## Installs composer packages
	docker exec fakturace_app composer install

.PHONY .SILENT: update
update: ## Updates composer packages
	docker exec fakturace_app composer update
	docker exec fakturace_app composer audit

.PHONY .SILENT:
font: ## Copy fonts
	cp fonts/* vendor/tecnickcom/tcpdf/fonts

