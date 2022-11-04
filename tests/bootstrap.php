<?php

$apiKey = getenv('APIKEY');
$statKey = getenv('STATKEY');

if (empty($apiKey) || empty($statKey)) {
    fwrite(STDOUT, PHP_EOL);
    fwrite(STDOUT, '+------------------------------+' . PHP_EOL);
    if (empty($apiKey)) {
        fwrite(STDOUT, '|                              |' . PHP_EOL);
        fwrite(STDOUT, '|  Set APIKEY  in phpunit.xml  |' . PHP_EOL);
    }
    if (empty($apiKey)) {
        fwrite(STDOUT, '|                              |' . PHP_EOL);
        fwrite(STDOUT, '|  Set STATKEY in phpunit.xml  |' . PHP_EOL);
    }
    fwrite(STDOUT, '|                              |' . PHP_EOL);
    fwrite(STDOUT, '+------------------------------+' . PHP_EOL);
    fwrite(STDOUT, PHP_EOL);
}

require_once __DIR__ . '/../vendor/autoload.php';
