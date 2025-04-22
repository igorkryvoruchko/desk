.PHONY: test reset-db migrate fixtures phpunit

ENV=test
CONSOLE=php bin/console

test: reset-db migrate fixtures phpunit

reset-db:
	@echo "💣 Dropping and creating test database..."
	$(CONSOLE) doctrine:database:drop --force --env=$(ENV)
	$(CONSOLE) doctrine:database:create --env=$(ENV)

migrate:
	@echo "🚀 Running migrations..."
	$(CONSOLE) doctrine:migrations:migrate --no-interaction --env=$(ENV)

fixtures:
	@echo "🍭 Loading fixtures..."
	$(CONSOLE) doctrine:fixtures:load --no-interaction --env=$(ENV)

phpunit:
	@echo "🧪 Running PHPUnit tests..."
	php bin/phpunit
