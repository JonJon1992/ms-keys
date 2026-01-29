#!/bin/bash
/var/www/scripts/setup-dirs.sh
exec /start.sh "$@"
