all: help


## _ .-') _     ('-.                   .-')              _   .-')       ('-.
##( (  OO) )   ( OO ).-.              ( OO ).           ( '.( OO )_   _(  OO)
## \     .'_   / . --. /  ,--.   ,--.(_)---\_)    .---.  ,--.   ,--.)(,------.
## ,`'--..._)  | \-.  \    \  `.'  / /    _ |    / .  |  |   `.'   |  |  .---'
## |  |  \  '.-'-'  |  | .-')     /  \  :` `.   / /|  |  |         |  |  |
## |  |   ' | \| |_.'  |(OO  \   /    '..`''.) / / |  |_ |  |'.'|  | (|  '--.
## |  |   / :  |  .-.  | |   /  /\_  .-._)   \/  '-'    ||  |   |  |  |  .--'
## |  '--'  /  |  | |  | `-./  /.__) \       /`----|  |-'|  |   |  |  |  `---.
## `-------'   `--' `--'   `--'       `-----'      `--'  `--'   `--'  `------'

.PHONY : help
help : Makefile
	@sed -n 's/^##\s//p' $<

SHELL := /bin/bash
ROOT_DIR := $(dir $(abspath $(lastword $(MAKEFILE_LIST))))
UID=$(shell id -u)

##    deploy:			starts web server containers (nginx + PHP fpm) in production environment
.PHONY : deploy
deploy:
	@docker-compose -f docker-compose.yml up --build -d
	@docker-compose exec php-fpm bash -c "composer global require hirak/prestissimo && composer update && composer dump-autoload -o";
	@docker-compose exec php-fpm bash -c "/app/bin/console cache:clear -e prod";
	@docker-compose exec php-fpm bash -c "/app/bin/console cache:warmup -e prod";
	@docker-compose exec php-fpm bash -c "/app/bin/console doctrine:database:drop --if-exists --force -e prod";
	@docker-compose exec php-fpm bash -c "/app/bin/console doctrine:database:create --if-not-exists -e prod"
	@docker-compose exec php-fpm bash -c "/app/bin/console doctrine:migrations:migrate --no-interaction -e prod"
	@docker-compose exec php-fpm bash -c "/app/bin/console doctrine:database:import ops/database/holidays.sql --no-interaction -e prod"
	@docker-compose exec php-fpm bash -c "yarnpkg";
	@docker-compose exec php-fpm bash -c "yarnpkg encore production";

##    start:			starts web server containers (nginx + PHP fpm)
.PHONY : start
start:
	@docker-compose up -d

##    stop:			stops webserver containers
.PHONY : stop
stop:
	@docker-compose -f docker-compose.yml stop

##    build:			Build services
.PHONY : build
build:
	@docker-compose build

##    remove:			stops all containers and delete them
.PHONY : remove
remove:
	@docker-compose -f docker-compose.yml rm -s -f

##    logs:			shows all containers logs
.PHONY : logs
logs:
	@docker-compose -f docker-compose.yml logs -f -t

##    logs@php:			just shows PHP fpm logs
.PHONY : logs@php
logs@php:
	@docker-compose -f docker-compose.yml logs -f -t php-fpm

##    composer:			Install composer dependencies
.PHONY : composer
composer:
	@docker-compose exec php-fpm bash -c "composer global require hirak/prestissimo && composer update && composer dump-autoload -o"

##    interactive:			runs a container with an interactive shell
.PHONY : interactive
interactive:
	-@docker-compose exec php-fpm bash


