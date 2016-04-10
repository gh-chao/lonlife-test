<?php

include __DIR__ . '/../vendor/autoload.php';

$app = new \Silex\Application();
$app['debug'] = true;
$app['pimpledump.output_dir'] = dirname(__DIR__ );
$app->register(new Sorien\Provider\PimpleDumpProvider());

$app->register(new Silex\Provider\DoctrineServiceProvider(), array(
    'db.options' => array(
        'driver'   => 'pdo_sqlite',
        'path'     => __DIR__.'/../data/app.db',
    ),
));
$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/../src/Resource/views',
));

$app->before(function() use ($app) {
    $result = $app['db']->query("SELECT * FROM sqlite_master WHERE type='table' AND name='ip'")->fetch();
    if (!$result) {
        $sql = <<<SQL
CREATE TABLE ip (
   id       INT PRIMARY KEY NOT NULL,
   ip_left  DOUBLE          NOT NULL,
   ip_right DOUBLE          NOT NULL,
   mask     CHAR(18)        NOT NULL,
   address  char(128)       NOT NULL
);
SQL;
        $app['db']->query($sql);
    }
});

$app->get('/', 'Controller\\IpController::indexAction');
$app->get('/query', 'Controller\\IpController::queryAction');
$app->get('/import', 'Controller\\IpController::importAction');
$app->post('/upload', 'Controller\\IpController::uploadAction');

$app->run();