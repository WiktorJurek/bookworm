prepare:
	docker compose up -d --build

ssh:
	docker exec -it app_bookworm bash

xdebug:
	docker compose -f docker-compose.yml -f docker-compose.xdebug.yml up -d --build
