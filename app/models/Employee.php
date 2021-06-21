<?php

namespace models;

use \general\Model;

class Employee extends Model {
    public $Ssn = '';
    public $Fname = '';
    public $Lname = '';
    public $Minit = '';
    public $Bdate = '';
    public $Address = '';
    public $Sex = '';
    public $Salary = '';
    public $Super_ssn = '';
    public $Dno = '';

    public function __construct() {
        $this->table = "EMPLOYEE";
        $this->attributes = ['Fname', 'Minit', 'Lname', 'Ssn', 'Bdate', 'Address', 'Sex', 'Salary', 'Super_ssn', 'Dno'];
    }
}
