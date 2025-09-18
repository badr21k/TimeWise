#!/bin/bash
set -e

# Force the UI to the visible web port
export PHP_PORT="${PORT:-5000}"
export CHAT_PORT="${CHAT_PORT:-3001}"

# Clean old procs so reruns don't double-start
pkill -f chat-server.js || true
pkill -f 'php -S' || true

echo "PORT=${PORT:-<unset>} | PHP_PORT=$PHP_PORT | CHAT_PORT=$CHAT_PORT"

echo "Starting Socket.IO chat on :$CHAT_PORT ..."
CHAT_PORT=$CHAT_PORT node chat-server.js &

sleep 1

echo "Starting PHP on :$PHP_PORT with router.php ..."
exec php -S 0.0.0.0:"$PHP_PORT" -t . router.php
