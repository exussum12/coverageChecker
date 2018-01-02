#!/bin/bash

# -e exits on no zero exit codes, This fails a build
set -e

# Get the diff (changes since the branch was created
git diff origin/master... > diff.txt

# Run phpunit (with any flags you normally use) and save the clover output
./vendor/bin/phpunit --report-clover=clover.xml

# This will make sure that at least 90% of the changed / added code has a test covering it
./vendor/bin/diffFilter --phpunit diff.txt clover.xml 90
