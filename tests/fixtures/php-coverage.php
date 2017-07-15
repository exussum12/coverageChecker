<?php
$coverage = new SebastianBergmann\CodeCoverage\CodeCoverage;
$coverage->setData(array (
  '/home/scott/code/coverageChecker/src/ArgParser.php' => 
  array (
    10 => 
    array (
      0 => 'exussum12\\CoverageChecker\\tests\\ArgParserTest::testNumericArgs',
      1 => 'exussum12\\CoverageChecker\\tests\\ArgParserTest::testAlphaArgs',
    ),
    11 => 
    array (
      0 => 'exussum12\\CoverageChecker\\tests\\ArgParserTest::testNumericArgs',
      1 => 'exussum12\\CoverageChecker\\tests\\ArgParserTest::testAlphaArgs',
    ),
    15 => 
    array (
      0 => 'exussum12\\CoverageChecker\\tests\\ArgParserTest::testNumericArgs',
    ),
    20 => NULL,
    24 => 
    array (
      0 => 'exussum12\\CoverageChecker\\tests\\ArgParserTest::testNumericArgs',
      1 => 'exussum12\\CoverageChecker\\tests\\GenericDiffFilterTest::testValid',
      2 => 'exussum12\\CoverageChecker\\tests\\GenericDiffFilterTest::testMissingHandler',
      3 => 'exussum12\\CoverageChecker\\tests\\PhpcsDiffFilterTest::testValid',
    ),
    25 => 
    array (
      0 => 'exussum12\\CoverageChecker\\tests\\ArgParserTest::testNumericArgs',
      1 => 'exussum12\\CoverageChecker\\tests\\GenericDiffFilterTest::testValid',
      2 => 'exussum12\\CoverageChecker\\tests\\GenericDiffFilterTest::testMissingHandler',
      3 => 'exussum12\\CoverageChecker\\tests\\PhpcsDiffFilterTest::testValid',
    ),
    45 => NULL,
  ),
));

$filter = $coverage->filter();
$filter->setWhitelistedFiles(array (
  '/home/scott/code/coverageChecker/src/ArgParser.php' => true,
));

return $coverage;