<?php

namespace models;

use \general\Model;
use \models\Employee;

class Project extends Model {
    public $Pname = '';
    public $Pnumber = '';
    public $Plocation = '';
    public $Dnum = '';
    protected $employees = [];

    public function __construct() {
        $this->table = "PROJECT";
        $this->attributes = ['primarykey' => 'Pnumber', 'Pname', 'Plocation', 'Dnum'];
        parent::__construct($this->table);
    }

    public function getEmployees() {
        if ($this->Pnumber && !$this->employees) {
            $tempEmp = new Employee();
            $this->query->setType('collection');
            $data = $this->query->join('inner', 'WORKS_ON', 'WORKS_ON.Pno = ' . $this->attributes['primarykey'])
                ->join('inner', 'EMPLOYEE', 'WORKS_ON.Essn = ' . $tempEmp->getAttributes()['primarykey'])
                ->where($this->attributes['primarykey'], $this->Pnumber)
                ->select(implode(',', $tempEmp->getAttributes()));

            foreach ($data as $empData) {
                $employee = new Employee();
                $this->employees[] = $employee->loadData($empData);
            }
        }
        return $this->employees;
    }
}
