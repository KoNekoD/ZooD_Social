##################
# Variables
##################

DOCKER_COMPOSE = docker-compose -f ./docker/docker-compose.yml --env-file ./docker/.env
DOCKER_COMPOSE_PHP_FPM_EXEC = ${DOCKER_COMPOSE} exec -u www-data php-fpm

##################
# Docker compose
##################
# Команда Docker Compose up развёртывает сервисы веб-приложений и создаёт из docker-образа новые
#  контейнеры, а также сети, тома и все конфигурации, указанные в файле Docker Compose.
# Команда Docker Compose stop останавливает все сервисы, связанные с определённой конфигурацией
#  Docker Compose. Она НЕ удаляет ни контейнеры, ни связанные с ними внутренние тома и сети.
# Команда Docker Compose start запускает любые остановленные сервисы в соответствии с параметрами
#  остановленной конфигурации, указанными в том же файле Docker Compose.
# Команда Docker Compose down останавливает все сервисы, связанные с определённой конфигурацией
#  Docker Compose. В отличие от команды stop, она также удаляет все контейнеры и внутренние сети,
#  связанные с этими сервисами — но НЕ указанные внутри тома. Чтобы очистить и их, надо дополнить
#  команду down флагом -v.

# Base setup
setup_project_dev:
	@cat docs/ZoodMakefileLogo
	@cp ./docker/.env.dist ./docker/.env 													# Docker env prepare
	dc_up 								 													# Docker setup
	php_comp_dev																			# Install dev composer packages
	@cp .env.dev .env.local 			 													# Dev env prepare
	${DOCKER_COMPOSE_PHP_FPM_EXEC} bin/console secrets:decrypt-to-local --force				# Decrypt dev env secrets 1/2
	${DOCKER_COMPOSE_PHP_FPM_EXEC} composer dump-env dev									# Dump dev all variables  2/2
	db_migrate																				# Prepare database
	${DOCKER_COMPOSE_PHP_FPM_EXEC} bin/console lexik:jwt:generate-keypair --overwrite -n	# Generate JWT keypair

# Build docker container
dc_build:
	${DOCKER_COMPOSE} build

dc_start:
	${DOCKER_COMPOSE} start

dc_stop:
	${DOCKER_COMPOSE} stop

dc_restart:
	make dc_stop dc_start

dc_up:
	${DOCKER_COMPOSE} up -d --remove-orphans

# Show list containers
dc_ps:
	${DOCKER_COMPOSE} ps

# View output from containers
dc_logs:
	${DOCKER_COMPOSE} logs -f

dc_down:
	${DOCKER_COMPOSE} down -v --rmi=all --remove-orphans

##################
# Docker
##################

# очищающает все не связанные с контейнерами ресурсы, в том числе образы, контейнеры, тома и сети
# Clean docker
docker_clean:
	docker system prune

##################
# App
##################

# Drop into docker container
app_bash:
	${DOCKER_COMPOSE} exec -u www-data php-fpm bash

app_test_fixtures:
	${DOCKER_COMPOSE} exec -u www-data php-fpm php bin/console --env=test doctrine:fixtures:load -n

app_phpunit:
	${DOCKER_COMPOSE} exec -u www-data php-fpm php bin/phpunit

##################
# Composer
##################

php_comp_prod:
	${DOCKER_COMPOSE} exec -u www-data php-fpm composer install --no-dev

php_comp_dev:
	${DOCKER_COMPOSE} exec -u www-data php-fpm composer install


##################
# Database
##################

db_migrate:
	${DOCKER_COMPOSE} exec -u www-data php-fpm bin/console doctrine:migrations:migrate --no-interaction

# Alternative without Maker(bin/console make:migration)
db_diff:
	${DOCKER_COMPOSE} exec -u www-data php-fpm bin/console doctrine:migrations:diff --no-interaction

##################
# Static code analysis
##################

code_phpstan:
	${DOCKER_COMPOSE_PHP_FPM_EXEC} vendor/bin/phpstan analyse src tests -c phpstan.neon

code_deptrac:
	${DOCKER_COMPOSE_PHP_FPM_EXEC} vendor/bin/deptrac analyze --config-file=deptrac-layers.yaml
	${DOCKER_COMPOSE_PHP_FPM_EXEC} vendor/bin/deptrac analyze --config-file=deptrac-modules.yaml

code_cs_fix:
	${DOCKER_COMPOSE_PHP_FPM_EXEC} vendor/bin/php-cs-fixer fix

code_cs_fix_diff:
	${DOCKER_COMPOSE_PHP_FPM_EXEC} vendor/bin/php-cs-fixer fix --dry-run --diff
