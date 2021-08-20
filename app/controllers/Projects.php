<?php

namespace controllers;

use \general\Controller;
use \general\WebApp;
use \models\Project;
use \models\Employee;

class Projects extends Controller {
    public function get($req, $res) {
        $body = $req->getBody();
        if (isset($body['Pnumber'])) {
            $project = Project::find($body['Pnumber']);
            if ($req->getUrl() == '/project/edit') {
                $projEmployees = $project->getEmployees();
                $conditions = [];
                if ($projEmployees) {
                    $ids = [];

                    foreach ($projEmployees as $employee) {
                        $ids[] = $employee->Ssn;
                    }
                    $conditions[] = [
                        'column' => 'Ssn',
                        'value' => $ids,
                        'operator' => 'NOT IN'
                    ];
                }
                $employees = Employee::findAll($conditions);
                return $this->render('update', [
                    'model' => $project,
                    'selectData' => $employees,
                    'title' => $project->Pname
                ]);
            }

            return $this->render('project', ['project' => $project]);
        } else {
            $projects = Project::findAll();
            return $this->render('projects', ['projects' => $projects]);
        }
    }

    public function post($req, $res) {
        $body = $req->getBody();
        if (isset($body['submit'])) {
            $project = new Project();
            $project->loadData([
                "Pnumber" => $body["Pnumber"],
                "Pname" => $body["Pname"],
                "Plocation" => $body["Plocation"],
                "Dnum" => $body["Dnum"]
            ])->save();
        }
        return $res->redirect(WebApp::getUrlPath('/projects'));
    }

    public function update($req, $res) {
        $body = $req->getBody();
        $project = Project::find($body['Pnumber']);
        if (isset($body['selectData'])) {
            $employees = [];
            foreach ($body['selectData'] as $employeeSsn) {
                $employees[] = Employee::find($employeeSsn);
            }
            $project->addEmployee($employees);
        }
        if (isset($body['employeeSsn'])) {
            $employeeSsn = $body['employeeSsn'];
            $project->removeEmployee($employeeSsn);
        }
        return $res->redirect(WebApp::getUrlPath('/project?Pnumber=' . $body['Pnumber']));
    }
}
