# coverageChecker
Allows tools to conditionally allow standards to new code

[![Build Status](https://travis-ci.org/exussum12/coverageChecker.svg?branch=master)](https://travis-ci.org/exussum12/coverageChecker)
[![Coverage Status](https://coveralls.io/repos/github/exussum12/coverageChecker/badge.svg?branch=master)](https://coveralls.io/github/exussum12/coverageChecker?branch=master)


Coverage checker allows new standards to be implemented incrementally, by only enforcing them on new / edited code.

Tools like phpcs and phpmd are an all or nothing approach, coverage checker allows this to work with the diff i.e. enforce all of the pull request / change request.

Also working with PHPunit to allow, for example 90% of new/edited code to be covered. which will increase the overall coverage over time.

#Usage

##Composer
With composer simply

    composer require exussum12/coverage-checker
    
then call the script you need

##Manually
Clone this repository somewhere your your build plan can be accessed, composer install is prefered but there is a non composer class loader which will be used if composer is not installed.
Then call the script you need


#Scripts

First of all a diff is needed

    git diff master...branch > /tmp/diff.txt
or 

    git diff master...HJEAD > /tmp/diff.txt
assuming your on the branch 

This diff will be used in all of the examples

the 3 dots between gets all changes on branch (ie from the branch point) and not from the current master.

exit code 0 and no output indicates success

##phpunit

    php vendor/bin/phpunitDiffFilter /tmp/diff.txt report/coverage.xml  90
    
Will fail (exit status 2) if less than 90% of the code committed is covered by a test.
This requires phpunit to be run on the branch first!

##phpcs

All of the commands can read from stdin with placeholder `-`

    phpcs --report=json | php vendor/bin/phpcsDiffFilter /tmp/diff.txt -
    
phpcs can be run with any options you normally have for example `--standard=PSR2`

This will exit with code 2 if any of the new/edited code fails the code standards check. The output is kept so you can see what the offending lines are and what the error is.


##phpmd

All of the commands can read from stdin with placeholder `-`

    phpmd src/ xml cleancode | php vendor/bin/phpmdDiffFilter /tmp/diff.txt -
    
phpcs can be run with any options you normally have for example `cleancode,codesize,controversial`

This will exit with code 2 if any of the new/edited code fails the code standards check. The output is kept so you can see what the offending lines are and what the error is.
