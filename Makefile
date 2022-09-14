DOCKER_COMPOSE=@docker compose
PHP_EXEC=@$(DOCKER_COMPOSE) exec php
SF_CONSOLE=@$(PHP_EXEC) bin/console 

create-db:
	@$(SF_CONSOLE) doctrine:database:create --if-not-exists


update-db:
	@$(SF_CONSOLE) doctrine:schema:update --force


create-user:
	@$(eval u ?=)
	@$(eval p ?=)
	@$(SF_CONSOLE) app:create-user $(u) $(p)


generate-keys:
	@$(SF_CONSOLE) lexik:jwt:generate-keypair
