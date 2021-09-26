#!/bin/sh
set -e

git log origin/master... | grep -q SKIP_BUILD && exit 0

[ -z "$UPDATE_COVERAGE" ] || composer require satooshi/php-coveralls:v1.1.0

composer install
git diff $(git merge-base origin/master HEAD) > diff.txt
phpcs --standard=psr2 src
phpcs --standard=psr2 --ignore=bootstrap.php,fixtures/* tests

phpmd src text cleancode,codesize,controversial,unusedcode
phpmd tests text cleancode,codesize,controversial,unusedcode --exclude fixtures

./vendor/bin/phpunit

bin/diffFilter --phpunit diff.txt report/coverage.xml

[ -z "$UPDATE_COVERAGE" ] || php vendor/bin/coveralls -v
