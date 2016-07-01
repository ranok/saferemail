!#/bin/bash

wget http://getcomposer.org/composer.phar
php composer.phar install

cd vendor && ./bin/propel build
cd vendor && ./bin/propel build-sql

echo "Edit vendor/propel.yml to match your database information and then run: cd vendor && ./bin/propel config:convert && ../bin/propel insert-sql"
