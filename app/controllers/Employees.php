<?php

namespace controllers;

use \general\Controller;
use \models\Employee;

class Employees extends Controller {
    public function get($req, $res) {
        $emp = Employee::find('123456789');
        var_dump($emp);
        exit;
    }
}
