<?php
spl_autoload_register(function ($classname) {
    include __DIR__ . '/app/' . str_replace('\\', '/', $classname) . '.php';
});

use \general\WebApp;
use \general\DotEnv;

(new DotEnv(__DIR__ . '/.env'))->load();
$config = [
    'uri_path' => '',
    'db' => [
        'host' => $_ENV['DB_HOST'],
        'name' => $_ENV['DB_NAME'],
        'user' => $_ENV['DB_USER'],
        'pass' => $_ENV['DB_PASSWORD']
    ]
];

$app = new WebApp(__DIR__, $config);

//Routes List
$app->router->add('/', [\controllers\Home::class, 'get']);
$app->router->add('/', [\controllers\Home::class, 'post']);
$app->router->add('/employee', [\controllers\Employees::class, 'get']);
$app->router->add('/employee', [\controllers\Employees::class, 'post']);
$app->router->add('/employees', [\controllers\Employees::class, 'get']);
$app->router->add('/project', [\controllers\Projects::class, 'get']);
$app->router->add('/project', [\controllers\Projects::class, 'post']);
$app->router->add('/project', [\controllers\Projects::class, 'update']);
$app->router->add('/projects', [\controllers\Projects::class, 'get']);
$app->router->add('/project/edit', [\controllers\Projects::class, 'get']);
$app->router->add('/department', [\controllers\Departments::class, 'get']);
$app->router->add('/department', [\controllers\Departments::class, 'post']);
$app->router->add('/departments', [\controllers\Departments::class, 'get']);


echo $app->router->run();
