.DEFAULT_GOAL := help

help:
	@echo "\033[32mWelcome to the project CLI!\033[0m"
	@echo "\033[33mThis Makefile is the default CLI for the project. With it, you can run all the available commands with just one dependency: Docker.\033[0m"
	@echo ""
	@echo "To get started, make sure Docker is installed on your system and run the desired command using the following syntax:"
	@echo ""
	@echo " \033[1mmake <command>\033[0m"
	@echo ""
	@echo "\033[36mAvailable commands:\033[0m"

check-docker:
	@if [ -x "$(command -v docker)" ]; then \
		echo "Docker is installed on the system."; \
	else \
		echo "Docker is not installed on the system."; \
	fi

composer:
	@docker run -v $PWD:/app -w /app -it --user $(id -u):$(id -g) dumptec/php-fpm:dev-8.2-latest composer

install:
	docker run --rm -v $(PWD):/app -w /app dumptec/php-fpm:dev-8.2-latest composer install

test:
	docker run --rm -v $(PWD):/app -w /app dumptec/php-fpm:dev-8.2-latest composer test
