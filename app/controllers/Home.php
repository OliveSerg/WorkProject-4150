<?php

namespace controllers;

use \general\Controller;
use \general\WebApp;

class Home extends Controller {
    public function get($req, $res) {
        return $this->render('home');
    }

    public function post($req, $res) {
        $body = $req->getBody();
        if (isset($body['employee'])) {
            return $res->redirect(WebApp::getUrlPath('/employee?ssn=' . $body['employee']));
        }
        if (isset($body['project'])) {
            return $res->redirect(WebApp::getUrlPath('/project?Pnumber=' . $body['project']));
        }
        if (isset($body['department'])) {
            return $res->redirect(WebApp::getUrlPath('/department?Dnumber=' . $body['department']));
        }
        return $res->redirect(WebApp::getUrlPath('/'));
    }
}
