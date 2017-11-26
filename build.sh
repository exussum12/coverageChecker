#!/bin/sh
set -e

git log origin/master... | grep -q SKIP_BUILD && exit 0

[ -z "$UPDATE_COVERAGE" ] || composer require satooshi/php-coveralls:dev-master

composer install --dev
git diff $(git merge-base origin/master HEAD) > diff.txt
./vendor/bin/phpcs --standard=psr2 src
./vendor/bin/phpcs --standard=psr2 --ignore=bootstrap.php,fixtures/* tests

./vendor/bin/phpmd src xml cleancode,codesize,controversial,unusedcode
./vendor/bin/phpmd tests xml cleancode,codesize,controversial,unusedcode

./vendor/bin/phpunit

bin/diffFilter --phpunit diff.txt report/coverage.xml

[ -z "$UPDATE_COVERAGE" ] || php vendor/bin/coveralls -v
