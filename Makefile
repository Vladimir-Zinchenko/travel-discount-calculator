.PHONY: help
.DEFAULT_GOAL := help

help: ## This help.
	@awk 'BEGIN {FS = ":.*?## "} /^[a-zA-Z_-]+:.*?## / {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}' $(MAKEFILE_LIST)

build: ## Build services.
	docker-compose -f docker-compose.yml build

rebuild: ## Rebuild services.
	docker-compose -f docker-compose.yml build --no-cache

up: ## Create and start services.
	docker-compose -f docker-compose.yml up -d

start: ## Start services.
	docker-compose -f docker-compose.yml start

restart: stop up ## Restart services.

down: ## Stop and remove containers.
	docker-compose -f docker-compose.yml down

destroy: ## Stop and remove containers and volumes.
	docker-compose -f docker-compose.yml down -v

stop: ## Stop cervices.
	docker-compose -f docker-compose.yml stop

ps: ## Show started services.
	docker-compose ps

logs: ## Display logs.
	docker-compose -f docker-compose.yml logs --tail=100 -f

console: ## Login in backend console.
	docker-compose -f docker-compose.yml exec backend /bin/bash

console-web: ## Login in backend console.
	docker-compose -f docker-compose.yml exec web /bin/sh

