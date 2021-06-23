<?php
spl_autoload_register(function ($classname) {
    include dirname(__FILE__) . '/app/' . str_replace('\\', '/', $classname) . '.php';
});

use \general\WebApp;
use \general\DotEnv;

(new DotEnv(__DIR__ . '/.env'))->load();
$config = [
    'host' => $_ENV['DB_HOST'],
    'name' => $_ENV['DB_NAME'],
    'user' => $_ENV['DB_USER'],
    'pass' => $_ENV['DB_PASSWORD']
];

$app = new WebApp(dirname(__FILE__), $config);

//All the routes
$app->router->add('/', [\controllers\Home::class, 'get']);
$app->router->add('/', [\controllers\Home::class, 'post']);
$app->router->add('/employee', [\controllers\Employees::class, 'get']);
$app->router->add('/employee', [\controllers\Employees::class, 'post']);
$app->router->add('/employees', [\controllers\Employees::class, 'get']);
$app->router->add('/project', [\controllers\Projects::class, 'get']);
$app->router->add('/project', [\controllers\Projects::class, 'post']);
$app->router->add('/projects', [\controllers\Projects::class, 'get']);

echo $app->router->run();
