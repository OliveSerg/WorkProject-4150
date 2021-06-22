<?php

namespace controllers;

use \general\Controller;
use \models\Employee;

class Home extends Controller {
    public function get($req, $res) {
        $employees = Employee::findAll();
        return $this->render('home', ['employees' => $employees]);
    }

    public function post($req, $res) {
        return $res->redirect('/');
    }
}
