<?php

namespace controllers;

use \general\Controller;
use \models\Project;

class Projects extends Controller {
    public function get($req, $res) {
        if ($req->getBody()) {
            $project = Project::find($req->getBody()['Pnumber']);
            return $this->render('project', ['project' => $project]);
        } else {
            $projects = Project::findAll();
            return $this->render('projects', ['projects' => $projects]);
        }
    }

    public function post($req, $res) {
        if (isset($_POST['submit'])) {
            $project = new Project();
            $project->loadData([
                "Pnumber" => $_POST["Pnumber"],
                "Pname" => $_POST["Pname"],
                "Plocation" => $_POST["Plocation"],
                "Dnum" => $_POST["Dnum"]
            ])->save();
        }
        return $res->redirect('/projects');
    }
}
