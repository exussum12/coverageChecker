# coverageChecker
Allows old code to use new standards

[![Build Status](https://travis-ci.org/exussum12/coverageChecker.svg?branch=master)](https://travis-ci.org/exussum12/coverageChecker)
[![Coverage Status](https://coveralls.io/repos/github/exussum12/coverageChecker/badge.svg?branch=master)](https://coveralls.io/github/exussum12/coverageChecker?branch=master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/exussum12/coverageChecker/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/exussum12/coverageChecker/?branch=master)

Coverage checker allows new standards to be implemented incrementally, by only enforcing them on new / edited code.

Tools like phpcs and phpmd are an all or nothing approach, coverage checker allows this to work with the diff i.e. enforce all of the pull request / change request.

This is sometimes called "Baselining"

Also working with PHPunit to allow, for example 90% of new/edited code to be covered. which will increase the overall coverage over time.

# Installing

## Composer
With composer simply

    composer require --dev exussum12/coverage-checker
    
then call the script you need

## Using Phar
Phar is a packaged format which should be a single download. The latest Phar can be found [Here](https://github.com/exussum12/coverageChecker/releases).

After downloading run `chmod +x diffFilter.phar` and then call as `./diffFilter.phar` followed by the normal options

## Manually
Clone this repository somewhere your your build plan can be accessed, composer install is preferred but there is a non composer class loader which will be used if composer is not installed. If composer is not used some PHP specific features will not work as expected.
Then call the script you need


# Usage

First of all a diff is needed 

    git diff origin/master... > diff.txt
     
See [here](https://github.com/exussum12/coverageChecker/wiki/Generating-a-diff) for a more in depth examples of what diff you should generate

Then the output for the tool you wish to check (such as phpcs, PHPUnit, phpmd etc) for example

     phpcs --standard=psr2 --report=json > phpcs.json || true 
     
Here the `|| true` ensures that the while build will not fail if phpcs fails.

Then call diffFilter

     ./vendor/bin/diffFilter --phpcs diff.txt phpcs.json 100

The last argument (100 in this case) is optional, the default is 100. This can be lowered to 90 for example to ensure that at least 90% of the changed code conforms to the standard.
diffFilter will exit with a `0` status if the changed code passes the minimum coverage. `2` otherwise

## Extended guide
A more in depth guide can be [found on the wiki](https://github.com/exussum12/coverageChecker/wiki) also some tips for speeding up the build.

## Installing as a git hook

There are 2 examples hooks in the GitHooks directory, if you symlink to these diffFilter will run locally.

pre-commit is before the commit happens
pre-receive will prevent you pushing

# Full list of available diff filters

Below is a list of all tools and a brief description

```
--buddy		Parses buddy (magic number detection) output
--checkstyle	Parses a report in checkstyle format
--clover	Parses text output in clover (xml) format
--codeclimate	Parse codeclimate output
--humbug	Parses the json report format of humbug (mutation testing)
--infecton	Parses the infection text log format
--jacoco	Parses xml coverage report produced by Jacoco
--phan		Parse the default phan(static analysis) output
--phanJson	Parses phan (static analysis) in json format
--phpcpd	Parses the text output from phpcpd (Copy Paste Detect)
--phpcs		Parses the json report format of phpcs, this mode only reports errors as violations
--phpcsStrict	Parses the json report format of phpcs, this mode reports errors and warnings as violations
--phpmd		Parses the xml report format of phpmd, this mode reports multi line violations once per diff, instead of on each line
		the violation occurs
--phpmdStrict	Parses the xml report format of phpmd, this mode reports multi line violations once per line they occur 
--phpmnd	Parses the text output of phpmnd (Magic Number Detection)
--phpstan	Parses the text output of phpstan
--phpunit	Parses text output in clover (xml) format generated with coverage-clover=file.xml
--pylint	Parses PyLint output
--psalm		Parses Psalm output
```


# Running in information mode
Simply pass the 3rd argument in as 0, this will give output showing failed lines but will not fail the build


# Why not run the auto fixers
Auto fixers do exist for some of these tools, but on larger code bases there are many instances where these can not be auto fixed. CoverageChecker allows to go to these new standards in the most used parts of the code by enforcing all changes to comply to the new standards

# What is a diff filtered test

A diff filtered test is a test where the execution and diffence (diff) is used from a known point.
This information can be used to only run the tests which have been changed. Saving in many cases minutes running tests.

A good workflow is to branch, run the tests with `--coverage-php=php-coverage.php`  and then when running your tests run `git diff origin/master... > diff.txt && ./composer/bin/phpunit`

This saves the coverage information in the first step which the diff then filters to runnable tests.

This one time effort saves running unnecessary tests on each run, tests for which the code has not changed.

Check the [Wiki](https://github.com/exussum12/coverageChecker/wiki/PHPUnit-or-Clover#speeding-up-builds-with-phpunit) for more information on installation and usage
