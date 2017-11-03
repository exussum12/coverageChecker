#!/bin/sh
set -e

[ -z "$UPDATE_COVERAGE" ] || composer require satooshi/php-coveralls:dev-master

composer install --dev
git diff $(git merge-base origin/master HEAD) > diff.txt
./vendor/bin/phpcs --standard=psr2 src
./vendor/bin/phpcs --standard=psr2 --ignore=bootstrap.php,fixtures/* tests
./vendor/bin/phpmd src xml cleancode,codesize,controversial,unusedcode > phpmd.xml || true
./vendor/bin/phpmd tests xml cleancode,codesize,controversial,unusedcode > phpmd-tests.xml || true
./bin/diffFilter --phpmd diff.txt phpmd.xml
./bin/diffFilter --phpmd diff.txt phpmd-tests.xml

./vendor/bin/phpunit

[ -z "$UPDATE_COVERAGE" ] || php vendor/bin/coveralls -v
