SHELL := /bin/bash

tests:
	symfony console doctrine:fixtures:load -n
	symfony run bin/phpunit
.PHONY: tests

start:
	docker-compose up -d
	symfony serve -d --no-tls
	sleep 10
	symfony run -d --watch=config,src,templates,vendor symfony console messenger:consume async

stop:
	symfony server:stop
	docker-compose down
