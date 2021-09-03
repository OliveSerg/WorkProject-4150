<?php

namespace controllers;

use general\Controller;
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
}
