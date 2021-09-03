<?php

namespace models;

use general\Model;

class Department extends Model {

    public function __construct() {
        $this->table = 'DEPARTMENT';
        $this->attributes = ['primarykey' => 'Dnumber', 'Dname', 'MGR_SSN', 'MGR_START_DATE'];
        $this->rules = [
            'Dnumber' => [self::RULE_REQUIRED, self::RULE_UNIQUE],
            'Dname' => [self::RULE_REQUIRED, [self::RULE_MAX, 'max' => 15]],
            'MGR_SSN' => [self::RULE_REQUIRED, [self::RULE_MATCH, 'tablename' => 'EMPLOYEE', 'match' => "Ssn"]]
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
}
