<?php

$apiKey = getenv('APIKEY');

if (empty($apiKey)) {
    fwrite(STDOUT, PHP_EOL);
    fwrite(STDOUT, '+------------------------------+' . PHP_EOL);
    fwrite(STDOUT, '|                              |' . PHP_EOL);
    fwrite(STDOUT, '|  Set APIKEY  in phpunit.xml  |' . PHP_EOL);
    fwrite(STDOUT, '|                              |' . PHP_EOL);
    fwrite(STDOUT, '+------------------------------+' . PHP_EOL);
    fwrite(STDOUT, PHP_EOL);
}

require_once __DIR__ . '/../vendor/autoload.php';
