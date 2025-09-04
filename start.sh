#!/bin/bash
set -e

# Replit gives you one public port in $PORT. Use that for PHP.
export PHP_PORT="${PORT:-8000}"
# Socket.IO will live on its own port (Replit supports multiport via subdomain)
export CHAT_PORT="${CHAT_PORT:-3001}"

echo "Starting Socket.IO chat on :$CHAT_PORT ..."
CHAT_PORT=$CHAT_PORT node chat-server.js &

sleep 1

echo "Starting PHP on :$PHP_PORT ..."
php -S 0.0.0.0:$PHP_PORT -t .
