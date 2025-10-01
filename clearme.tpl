#!/bin/bash
if [ "$1" == "sail" ]; then
	php="sail php"
	sail redis-cli FLUSHALL
	composer="sail composer"
else
	php="/usr/local/bin/php"
	redis-cli FLUSHALL
	composer="/usr/local/bin/composer"
fi
#php="/opt/plesk/php/8.0/bin/php"
#composer="$php /usr/local/psa/var/modules/composer/composer.phar"

echo "clear all caches"
if [ -f "./bootstrap/cache/packages.php" ]; then
	rm ./bootstrap/cache/packages.php
fi
if [ -f "./bootstrap/cache/services.php" ]; then
	rm ./bootstrap/cache/services.php
fi
$php artisan cache:clear
$php artisan config:clear
$php artisan route:clear
$php artisan view:clear
$composer dumpautoload
printf 'all DONE \360\237\230\216\n'
