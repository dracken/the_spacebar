#!/bin/sh
#

php /var/www/api/bin/console doctrine:database:drop --force
php /var/www/api/bin/console doctrine:database:create -vvv
php /var/www/api/bin/console doctrine:schema:create -vvv

return 0