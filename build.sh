#!/bin/sh

[ -z "$UPDATE_COVERAGE" ] || composer require satooshi/php-coveralls:dev-master

composer install --dev
./vendor/bin/phpunit

[ -z "$UPDATE_COVERAGE" ] || php vendor/bin/coveralls -v
