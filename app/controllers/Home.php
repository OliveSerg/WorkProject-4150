<?php

namespace controllers;

use \general\Controller;

class Home extends Controller {
    public function get($req, $res) {
        return $this->render('home');
    }

    public function post($req, $res) {
        $body = $req->getBody();
        if (isset($body['employee'])) {
            return $res->redirect('/employee?ssn=' . $body['employee']);
        }
        if (isset($body['project'])) {
            return $res->redirect('/project?Pnumber=' . $body['project']);
        }
        if (isset($body['department'])) {
            return $res->redirect('/department?Dnumber=' . $body['department']);
        }
        return $res->redirect('/');
    }
}
