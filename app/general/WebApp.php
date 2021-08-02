<?php

namespace general;

use \general\Router;
use \general\Response;
use \general\Request;
use \general\Database;
use \general\Session;

class WebApp {
    public static $ROOT_DIR;
    public static $ROOT_URI;
    public static WebApp $app;
    public $router;
    public $request;
    public $response;
    public $db;
    public Session $session;

    public function __construct($rootDir, $config) {
        self::$ROOT_DIR = $rootDir;
        self::$ROOT_URI = $config['uri_path'];
        self::$app = $this;
        $this->request = new Request();
        $this->response = new Response();
        $this->router = new Router($this->request, $this->response);
        $this->db = new Database($config['db']);
    }
}
