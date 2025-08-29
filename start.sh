#!/bin/bash
set -e

export PHP_PORT=8000
export NODE_PORT=3001

echo "Starting Socket.IO on :$NODE_PORT ..."
node server.js &

sleep 1

echo "Starting PHP on :$PHP_PORT ..."
php -S 0.0.0.0:$PHP_PORT -t .
