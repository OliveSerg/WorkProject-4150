<?php

namespace controllers;

use \general\Controller;
use \models\Employee;

class Employees extends Controller {
    public function get($req, $res) {
        $body = $req->getBody();
        if (isset($body['ssn'])) {
            $employee = Employee::find($body['ssn']);
            return $this->render('employee', ['employee' => $employee]);
        } else {
            $employees = Employee::findAll();
            return $this->render('employees', ['employees' => $employees]);
        }
    }

    public function post($req, $res) {
        $body = $req->getBody();
        if (isset($body['submit'])) {
            $employee = new Employee();
            $employee->loadData([
                "Ssn" => $body["Ssn"],
                "Fname" => $body["Fname"],
                "Lname" => $body["Lname"],
                "Address" => $body["Address"],
                "Salary" => $body["Salary"],
                "Dno" => $body["Dno"],
                "Bdate" => date("Y-m-d")
            ])->save();
        }
        return $res->redirect('/employees');
    }
}
