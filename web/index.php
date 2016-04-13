<?php

include __DIR__ . '/../vendor/autoload.php';

$app = new \Silex\Application();
$app['debug'] = true;
$app['pimpledump.output_dir'] = dirname(__DIR__ );
$app->register(new Sorien\Provider\PimpleDumpProvider());

$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/../src/Resource/views',
));

$app['mongo_collection'] = function() {
    return new MongoCollection(new MongoDB(new MongoClient(), 'default'), 'ip');
};

$app->get('/', 'Controller\\IpController::indexAction');
$app->get('/query', 'Controller\\IpController::queryAction');
$app->get('/import', 'Controller\\IpController::importAction');
$app->post('/upload', 'Controller\\IpController::uploadAction');

$app->run();