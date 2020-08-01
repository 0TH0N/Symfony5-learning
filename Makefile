SHELL := /bin/bash

tests:
	symfony console doctrine:fixtures:load -n
	symfony run bin/phpunit
.PHONY: tests

start:
	docker-compose up -d
	symfony serve -d --no-tls
	symfony server:start -d --passthru=./spa/public/index.html --no-tls
	API_ENDPOINT=`symfony var:export SYMFONY_DEFAULT_ROUTE_URL` symfony run -d --watch=./spa/webpack.config.js yarn encore dev --watch
	sleep 10
	symfony run -d --watch=config,src,templates,vendor symfony console messenger:consume async

stop:
	symfony server:stop
	docker-compose down

log:
	symfony server:log
