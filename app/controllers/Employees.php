<?php

namespace controllers;

use \general\Controller;
use \models\Employee;

class Employees extends Controller {
    public function get($req, $res) {
        if ($req->getBody()) {
            $employee = Employee::find($req->getBody()['ssn']);
            return $this->render('employee', ['employee' => $employee]);
        } else {
            $employees = Employee::findAll();
            return $this->render('home', ['employees' => $employees]);
        }
    }
}
