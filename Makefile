install:
		composer install
lint:
		composer phpcs -- --standard=PSR12 bin/ src/