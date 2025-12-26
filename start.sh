#!/bin/sh

set -e

# Base directories
PHP_LOG_DIR="./backend/php/logs"
NGINX_LOG_DIR="./backend/nginx/logs"

# Log files
PHP_LOG_FILE="$PHP_LOG_DIR/php-error.log"
NGINX_ERROR_LOG="$NGINX_LOG_DIR/error.log"
NGINX_ACCESS_LOG="$NGINX_LOG_DIR/access.log"

# Create log files if they don't exist
[ ! -f "$PHP_LOG_FILE" ] && touch "$PHP_LOG_FILE"
[ ! -f "$NGINX_ERROR_LOG" ] && touch "$NGINX_ERROR_LOG"
[ ! -f "$NGINX_ACCESS_LOG" ] && touch "$NGINX_ACCESS_LOG"

echo "Log files ensured."

# Run docker-compose
if [ "$1" = "--build" ]; then
  echo "Running docker-compose up with --build..."
  docker-compose up --build
else
  echo "Running docker-compose up..."
  docker-compose up
fi

