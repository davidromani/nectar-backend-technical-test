# Variables
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

## Doctrine migrations
migrations:
	$(DOCKER_COMPOSE) -f $(COMPOSE_FILE) -f $(COMPOSE_OVERRIDE_FILE) exec nectar-backend-technical-test-v1-www php bin/console doctrine:migrations:migrate
