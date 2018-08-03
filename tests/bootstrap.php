<?php
namespace exussum12\CoverageChecker {

    require_once(__DIR__ . '/../src/functions.php');

    findAutoLoader();

    function error_log($message)
    {
        echo $message;
    }

}
namespace exussum12\CoverageChecker\Loaders {
    function error_log($message) {
        \exussum12\CoverageChecker\error_log($message);
    }
}
