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
            return $this->render('employees', ['employees' => $employees]);
        }
    }

    public function post($req, $res) {
        if (isset($_POST['submit'])) {
            $employee = new Employee();
            $employee->loadData([
                "Ssn" => $_POST["Ssn"],
                "Fname" => $_POST["Fname"],
                "Lname" => $_POST["Lname"],
                "Address" => $_POST["Address"],
                "Salary" => $_POST["Salary"],
                "Dno" => $_POST["Dno"],
                "Bdate" => date("Y-m-d")
            ])->save();
        }
        return $res->redirect('/employees');
    }
}
