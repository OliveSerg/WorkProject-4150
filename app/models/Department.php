<?php

namespace models;

use general\Model;
use models\Employee;

class Department extends Model {
    private ?Employee $manager = null;
    private ?array $locations = null;

    public function __construct() {
        $this->table = 'DEPARTMENT';
        $this->attributes = ['primarykey' => 'Dnumber', 'Dname', 'MGR_SSN', 'MGR_START_DATE'];
        $this->rules = [
            'Dnumber' => [self::RULE_REQUIRED, self::RULE_UNIQUE],
            'Dname' => [self::RULE_REQUIRED, [self::RULE_MAX, 'max' => 15]],
            'MGR_SSN' => [self::RULE_REQUIRED, [self::RULE_MATCH, 'tableName' => 'EMPLOYEE', 'match' => 'Ssn']]
        ];
        parent::__construct($this->table);
    }

    public static function getLabels() {
        return [
            'Dnumber' => 'Department Number',
            'Dname' => 'Department Name',
            'MGR_SSN' => 'Manager Ssn'
        ];
    }

    public function getManager() {
        if (!$this->manager && $this->getData('MGR_SSN')) {
            $this->manager = Employee::find($this->getData('MGR_SSN'));
        }

        return $this->manager;
    }

    public function getLocations() {
        if (!$this->locations && $this->Dnumber) {
            $this->query->setType('collection');
            $this->locations = $this->query->join('inner', 'DEPT_LOCATIONS', 'DEPT_LOCATIONS.Dnumber = ' .  $this->getTable() . '.' . $this->attributes['primarykey'])
                ->where($this->getTable() . '.' . $this->attributes['primarykey'], $this->Dnumber)
                ->select('Dlocation');

            for ($i = 0; $i < count($this->locations); $i++) {
                $this->locations[$i] = $this->locations[$i]['Dlocation'];
            }
        }
        return $this->locations;
    }
}
