# coverageChecker
Allows old code to use new standards

[![Build Status](https://travis-ci.org/exussum12/coverageChecker.svg?branch=master)](https://travis-ci.org/exussum12/coverageChecker)
[![Coverage Status](https://coveralls.io/repos/github/exussum12/coverageChecker/badge.svg?branch=master)](https://coveralls.io/github/exussum12/coverageChecker?branch=master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/exussum12/coverageChecker/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/exussum12/coverageChecker/?branch=master)

Coverage checker allows new standards to be implemented incrementally, by only enforcing them on new / edited code.

Tools like phpcs and phpmd are an all or nothing approach, coverage checker allows this to work with the diff i.e. enforce all of the pull request / change request.

Also working with PHPunit to allow, for example 90% of new/edited code to be covered. which will increase the overall coverage over time.

# Installing

## Composer
With composer simply

    composer require --dev exussum12/coverage-checker
    
then call the script you need

## Manually
Clone this repository somewhere your your build plan can be accessed, composer install is prefered but there is a non composer class loader which will be used if composer is not installed.
Then call the script you need


# Usage

First of all a diff is needed 
    git diff origin/master... > diff.txt
See [here](https://github.com/exussum12/coverageChecker/wiki/Generating-a-diff) for a more in depth examples of what diff you should generate

Then the outut for the tool you wish to check (such as phpcs, PHPUnit, phpmd etc) for example
    phpcs --standard=psr2 --report=json || true > phpcs.json
Here the `|| true` ensures that the while build will not fail if phpcs fails.

Then call diffFilter
    ./vendor/bin/diffFilter --phpcs diff.txt phpcs.json 100

The last argument (100 in this case) is optional, the default is 100. This can be lowered to 90 for example to ensure that at least 90% of the changed code conforms to the standard.

## Extended guide
A more in depth guide can be [found on the wiki](https://github.com/exussum12/coverageChecker/wiki) also some tips for speeding up the build.


# Full list of available diff filters

Below is a list of all tools and a breif description

```
--checkstyle    Parses a report in checkstyle format
--clover        Parses text output in clover (xml) format
--codeclimate   Parse codeclimate output
--jacoco        Parses xml coverage report produced by Jacoco
--phan          Parse the default phan(static analysis) output
--phanJson      Parses phan (static analysis) in json format
--phpcpd        Parses the text output from phpcpd (Copy Paste Detect)
--phpcs         Parses the json report format of phpcs, this mode only reports errors as violations
--phpcsStrict   Parses the json report format of phpcs, this mode only reporst errors and warnings as violations
--phpmd         Parses the xml report format of phpmd, this mode reports multi line violations once per diff, instead of on each line the violation occurs
--phpmdStrict   Parses the xml report format of phpmd, this mode reports multi line violations once per line they occur 
--phpmnd        Parses the text output of phpmnd (Magic Number Detection)
--phpstan       Parses the text output of phpstan
--phpunit       Parses text output in clover (xml) format generated with coverage-clover=file.xml
--pylint        Parses PyLint output

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


# Running PHPUnit diff filtered tests

Adding the following code to your phpunit.xml, and adding a diff and the phpunit coverage output in php format (`--coverage-php`) will run only the tests necessary for the change 


    <listeners>
      <listener class="exussum12\CoverageChecker\DiffFilter" >
          <arguments>
              <string>php-coverage.php</string>
              <string>diff.txt</string>
          </arguments>
      </listener>
    </listeners>

A basic webserver for storing the coverage file between runs is https://gist.github.com/exussum12/5af41e6de404c9ab293093c24ca8ce81

This should allow you to save output from a completed run and get it back in the future, There is no clean up process in the script so that should be implemented seperatly
