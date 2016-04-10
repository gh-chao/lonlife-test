<?php

include __DIR__ . '/../vendor/autoload.php';

use Symfony\Component\Console\Application;

$application = new Application();

$application->addCommands(array(
    new \Command\CrawlerCommand(),
    new \Command\CrawlerRangeCommand(),

));

$application->run();