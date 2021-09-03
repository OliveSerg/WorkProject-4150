<?php

namespace models;

use \general\Model;
use \models\Project;

class Employee extends Model {
    protected $projects = [];
    protected $dependents = [];

    public function __construct() {
        $this->table = "EMPLOYEE";
        $this->attributes = ['primarykey' => 'Ssn', 'Fname', 'Minit', 'Lname',  'Bdate', 'Address', 'Sex', 'Salary', 'Super_ssn', 'Dno'];
        $this->rules = [
            'Ssn' => [self::RULE_REQUIRED, self::RULE_UNIQUE, [self::RULE_MAX, 'max' => 9]],
            'Fname' => [self::RULE_REQUIRED, [self::RULE_MAX, 'max' => 15]],
            'Lname' => [self::RULE_REQUIRED, [self::RULE_MAX, 'max' => 15]],
            'Dno' => [self::RULE_REQUIRED, [self::RULE_MATCH, 'tableName' => 'DEPARTMENT', 'match' => 'Dnumber']],
        ];
        parent::__construct($this->table);
    }

    public static function getLabels() {
        return [
            'Ssn' => 'Social Security Number',
            'Fname' => 'First name',
            'Lname' => 'Last name',
            'Address' => 'Address',
            'Salary' => 'Salary',
            'Dno' => 'Department number'
        ];
    }

    public function getFullName() {
        return $this->Fname . ' ' . $this->Lname;
    }

    public function getProjects() {
        if ($this->Ssn && !$this->projects) {
            $tempProj = new Project();
            $this->query->setType('collection');
            $data = $this->query->join('inner', 'WORKS_ON', 'WORKS_ON.Essn = ' . $this->attributes['primarykey'])
                ->join('inner', 'PROJECT', 'WORKS_ON.Pno = ' . $tempProj->getAttributes()['primarykey'])
                ->where($this->attributes['primarykey'], $this->Ssn)
                ->select(implode(',', $tempProj->getAttributes()) . ',Hours');

            foreach ($data as $projectData) {
                $project = new Project();
                $project->hours = $projectData['Hours'];
                $this->projects[] = $project->loadData($projectData);
            }
        }
        return $this->projects;
    }

    public function getDependents() {
        // TODO:: Possibly create a model for dependent or filter dependent attributes
        if ($this->Ssn && !$this->dependents) {
            $this->query->setType('collection');
            $this->dependents = $this->query->join('inner', 'DEPENDENT', 'DEPENDENT.Essn = ' . $this->attributes['primarykey'])
                ->where($this->attributes['primarykey'], $this->Ssn)
                ->select();
        }
        return $this->dependents;
    }
}
