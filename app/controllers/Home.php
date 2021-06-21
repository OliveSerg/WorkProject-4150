<?php

namespace controllers;

use \general\Controller;
use \models\Employee;
use \general\Query;

class Home extends Controller {
    public function get($req, $res) {
        // $employee = new Employee()->loadById(); This may work better
        // $employees = new Employee()->loadAll(); What about 
        // $query = new Query();
        // $data = $query->select();
        // $employees = [];
        // foreach ($data as $empData) {
        //     $employee = new Employee();
        //     $employee->loadData($empData);
        //     array_push($employees, $employee);
        // }
        $employees = [];
        return $this->render('home', ['employees' => $employees]);
    }

    public function post($req, $res) {
        return $res->redirect('/');
    }
}
