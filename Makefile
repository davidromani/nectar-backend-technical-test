# Variables
DOCKER = docker
DOCKER_COMPOSE = docker-compose
COMPOSE_FILE = compose.yaml
COMPOSE_OVERRIDE_FILE = compose.override.yaml

# Commands
## Start the containers in detached mode
up:
	$(DOCKER_COMPOSE) -f $(COMPOSE_FILE) -f $(COMPOSE_OVERRIDE_FILE) up -d

## Stop and remove the containers
down:
	$(DOCKER_COMPOSE) -f $(COMPOSE_FILE) -f $(COMPOSE_OVERRIDE_FILE) down

## Build or rebuild the services
build:
	$(DOCKER_COMPOSE) -f $(COMPOSE_FILE) -f $(COMPOSE_OVERRIDE_FILE) build

## Restart the services
restart: down up

## Composer install
install:
	$(DOCKER) exec nectar-backend-technical-test-v1-www composer install

## Doctrine migrations
migrations:
	$(DOCKER) exec nectar-backend-technical-test-v1-www php bin/console doctrine:migrations:migrate

## Doctrine fixtures
fixtures:
	$(DOCKER) exec nectar-backend-technical-test-v1-www php bin/console hautelook:fixtures:load --no-interaction

## Testing
testing:
	$(DOCKER) exec nectar-backend-technical-test-v1-www php bin/console cache:clear --env=test
	$(DOCKER) exec nectar-backend-technical-test-v1-www php bin/console doctrine:database:drop --force --env=test
	$(DOCKER) exec nectar-backend-technical-test-v1-www php bin/console doctrine:database:create --env=test
	$(DOCKER) exec nectar-backend-technical-test-v1-www php bin/console doctrine:schema:update --force --env=test
	$(DOCKER) exec nectar-backend-technical-test-v1-www php bin/console hautelook:fixtures:load --no-interaction --env=test
	$(DOCKER) exec nectar-backend-technical-test-v1-www php bin/phpunit

## Part 2
part2:
	$(DOCKER) exec nectar-backend-technical-test-v1-www php bin/console app:query:get-tasks-list-by-user --show-table
	$(DOCKER) exec nectar-backend-technical-test-v1-www php bin/console app:query:get-users-without-completed-tasks-list --show-table
