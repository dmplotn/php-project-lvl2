install:
		composer install
lint:
		composer phpcs -- --standard=PSR12 bin/ src/
test:
		composer phpunit tests/
test-coverage:
		composer phpunit tests -- --coverage-clover build/logs/clover.xml