#!/bin/bash

# -e exits on no zero exit codes, This fails a build
# This may work on other shells, its a shell built in
set -e

# Get the diff (changes since the branch was created
git diff origin/master... > diff.txt
git diff origin/master... --name-only --diff-filter=ACMR -- '*.php' > files.txt


# Old versions of phpcs will need to use the syntax commented out.
# Note the || true is important, Without this phpcs failing will fail the build!

./vendor/bin/phpcs --file-list=files.txt --parallel=2 --standard=psr2 --report=json > phpcs.json || true

# This will make sure that all changed lines / added lines comply to psr2
./vendor/bin/diffFilter --phpcs diff.txt phpcs.json
