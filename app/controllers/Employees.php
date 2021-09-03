<?php

namespace controllers;

use \general\Controller;
use \general\WebApp;
use \models\Employee;

class Employees extends Controller {
    public function get($req, $res) {
        $body = $req->getBody();
        if (isset($body['Ssn'])) {
            $employee = Employee::find($body['Ssn']);
            return $this->render('employee', ['employee' => $employee]);
        } else {
            $employees = Employee::findAll();
            return $this->render(
                'list',
                [
                    'model' => Employee::class,
                    'title' => 'Employee',
                    'path' => '/employee',
                    'listItems' => $employees
                ]
            );
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

            if ($employee->errors) {
                $employees = Employee::findAll();
                return $this->render(
                    'list',
                    [
                        'model' => Employee::class,
                        'title' => 'Employee',
                        'path' => '/employee',
                        'listItems' => $employees,
                        'errors' => $employee->errors
                    ]
                );
            }
        }
        return $res->redirect(WebApp::getUrlPath('/employees'));
    }
}
