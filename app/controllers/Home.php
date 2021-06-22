<?php

namespace controllers;

use \general\Controller;

class Home extends Controller {
    public function get($req, $res) {
        return $this->render('home');
    }

    public function post($req, $res) {
        if (isset($_POST['employee'])) {
            return $res->redirect('/employee?ssn=' . $_POST['employee']);
        }
        if (isset($_POST['project'])) {
            return $res->redirect('/project?ssn=' . $_POST['project']);
        }
        if (isset($_POST['department'])) {
            return $res->redirect('/department?ssn=' . $_POST['department']);
        }
        return $res->redirect('/');
    }
}
