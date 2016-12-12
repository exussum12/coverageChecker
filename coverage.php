<?php

$changeSet = file_get_contents("/tmp/diff.txt");
var_dump($changeSet);

$changes = [];

$separator = "\r\n";
$line = strtok($changeSet, $separator);
while($line !== false) {
    if ($line[0] == "+" && $line[3] == "+") {
        echo $line ."\n";
    }
    $line = strtok($separator);
}
