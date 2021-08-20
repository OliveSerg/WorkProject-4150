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
        $this->rules = [
            'Pnumber' => [self::RULE_REQUIRED, self::RULE_UNIQUE],
            'Pname' => [self::RULE_REQUIRED, [self::RULE_MAX, 'max' => 15]],
            'Plocation' => [self::RULE_REQUIRED, [self::RULE_MAX, 'max' => 15]],
            'Dnum' => [self::RULE_REQUIRED, [self::RULE_MATCH, 'tableName' => 'DEPARTMENT', 'match' => 'Dnumber']],
        ];
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

    public function addEmployee($employees = []) {
        foreach ($employees as $employee) {
            $this->query->setTable('WORKS_ON');
            $insertData = [
                'Essn' => $employee->Ssn,
                'Pno' => $this->Pnumber,
                'Hours' => 0
            ];
            $this->query->insert($insertData);
        }
    }

    public function removeEmployee($employee) {
        $this->query->setTable('WORKS_ON');
        $this->query->where('Essn', $employee)
            ->where('Pno', $this->Pnumber, 'AND')
            ->delete();
    }
}
