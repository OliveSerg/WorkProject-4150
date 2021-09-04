<?php

namespace controllers;

use general\Controller;
use general\WebApp;
use models\Department;

class Departments extends Controller {
    public function get($req, $res) {
        $body = $req->getBody();
        if (isset($body['Dnumber'])) {
            $department = Department::find($body['Dnumber']);
            return $this->render('department', ['department' => $department]);
        } else {
            $departments = Department::findAll();
            return $this->render(
                'list',
                [
                    'model' => Department::class,
                    'title' => 'Department',
                    'path' => '/department',
                    'listItems' => $departments
                ]
            );
        }
    }

    public function post($req, $res) {
        $body = $req->getBody();
        if (isset($body['submit'])) {
            $department = new Department();
            $department->loadData([
                "Dnumber" => $body["Dnumber"],
                "Dname" => $body["Dname"],
                "MGR_SSN" => $body["MGR_SSN"],
                "MGR_START_DATE" => date("Y-m-d")
            ])->save();

            if ($department->errors) {
                $departments = Department::findAll();
                return $this->render(
                    'list',
                    [
                        'model' => Department::class,
                        'title' => 'Department',
                        'path' => '/department',
                        'listItems' => $departments,
                        'errors' => $department->errors
                    ]
                );
            }
        }
        return $res->redirect(WebApp::getUrlPath('/departments'));
    }
}
