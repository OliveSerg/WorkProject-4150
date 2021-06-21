<?php

namespace general;

use \general\Request;
use \general\Response;
use \general\View;

class Router {
    private Request $request;
    private Response $response;
    private $routes = [];

    public function __construct(Request $request, Response $response) {
        $this->request = $request;
        $this->response = $response;
    }

    public function add($url, $method) {
        $this->routes[$url][$method[1]] = $method[0];
    }

    public function run() {
        $method = $this->request->getMethod();
        $url = $this->request->getUrl();

        $controller = $this->routes[$url][$method] ?? false;
        if (!$controller) {
            return View::renderView('404');
        }
        if (class_exists($controller)) {
            $controller = new $controller();
            return call_user_func([$controller, $method], $this->request, $this->response);
        }
        return View::renderView($controller);
    }
}
