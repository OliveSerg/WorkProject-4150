<?php

namespace general;

use \general\Router;
use \general\Response;
use \general\Request;
use \general\Database;

class WebApp {
    public static $ROOT_DIR;
    public static WebApp $app;
    public $router;
    public $request;
    public $response;
    public $db;

    public function __construct($rootDir, $config) {
        self::$ROOT_DIR = $rootDir;
        self::$app = $this;
        $this->request = new Request();
        $this->response = new Response();
        $this->router = new Router($this->request, $this->response);
        $this->db = new Database($config);
    }
}
