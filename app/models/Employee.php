<?php

namespace models;

use \general\Model;
use \models\Project;

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
    protected $projects = [];

    public function __construct() {
        $this->table = "EMPLOYEE";
        $this->attributes = ['primarykey' => 'Ssn', 'Fname', 'Minit', 'Lname',  'Bdate', 'Address', 'Sex', 'Salary', 'Super_ssn', 'Dno'];
        parent::__construct($this->table);
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
                ->select(implode(',', $tempProj->getAttributes()));

            foreach ($data as $projectData) {
                $project = new Project();
                $this->projects[] = $project->loadData($projectData);
            }
        }
        return $this->projects;
    }
}
