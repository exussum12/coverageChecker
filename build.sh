#!/bin/sh
set -e

git log origin/master... | grep -q SKIP_BUILD && exit 0

[ -z "$UPDATE_COVERAGE" ] || composer require satooshi/php-coveralls:v1.1.0

composer install --dev
git diff $(git merge-base origin/master HEAD) > diff.txt

./vendor/bin/phpunit

bin/diffFilter --phpunit diff.txt report/coverage.xml

[ -z "$UPDATE_COVERAGE" ] || php vendor/bin/coveralls -v
