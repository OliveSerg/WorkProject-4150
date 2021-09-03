<?php

namespace general;

use \general\Query;

abstract class Model {

    const RULE_REQUIRED = 'required';
    const RULE_EMAIL = 'email';
    const RULE_MIN = 'min';
    const RULE_MAX = 'max';
    const RULE_MATCH = 'match';
    const RULE_UNIQUE = 'unique';

    public $errors = [];

    protected $table = '';
    protected $attributes = ['primarykey' => ''];
    protected $rules = [];
    protected Query $query;

    public function __construct($tableName = '') {
        $this->query = new Query($tableName);
        $this->setAttributes();
    }

    public function save() {
        if (!$this->validate()) {
            return;
        }

        $insertData = [];
        foreach ($this->attributes as $attribute) {
            if ($this->{$attribute}) {
                $insertData[$attribute] = $this->{$attribute};
            }
        }

        $this->query->insert($insertData);
    }

    private function _find($id) {
        $this->query->setType('single');
        $data = $this->query->where($this->attributes['primarykey'], $id)
            ->select();
        $this->loadData($data);
        return $this;
    }

    public static function find($id) {
        $instance = new static;
        $instance->setTable($instance->getTable());
        return $instance->_find($id);
    }

    private function _findAll($conds = []) {
        $list = [];
        $this->query->setType('collection');
        if ($conds) {
            foreach ($conds as $cond) {
                $this->query->where($cond['column'], $cond['value'], $cond['type'], $cond['operator']);
            }
        }
        $data = $this->query->select();

        foreach ($data as $entity) {
            $obj = new $this($this->table);
            $list[] = $obj->loadData($entity);
        }
        return $list;
    }

    public static function findAll($cond = []) {
        $instance = new static;
        $instance->setTable($instance->getTable());
        return $instance->_findAll($cond);
    }

    public function loadData($data) {
        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                $this->{$key} = $value;
            }
        }
        return $this;
    }

    public function getData($key) {
        if (property_exists($this, $key)) {
            return $this->{$key};
        }
        return null;
    }

    public function getAttributes() {
        return $this->attributes;
    }

    private function setAttributes() {
        foreach ($this->attributes as $attribute) {
            $this->{$attribute} = '';
        }
    }

    public function getTable() {
        return $this->table;
    }

    public function setTable($tableName) {
        $this->table = $tableName;
        $this->query->setTable($tableName);
    }

    public static function getLabels() {
        return [];
    }

    private function validate() {
        foreach ($this->rules as $attribute => $attributeRules) {
            $value = $this->{$attribute};
            foreach ($attributeRules as $rule) {
                $ruleName = is_string($rule) ? $rule : $rule[0];

                if ($ruleName === self::RULE_REQUIRED && !$value) {
                    $this->addErrorByRule($attribute, self::RULE_REQUIRED);
                }

                if ($ruleName === self::RULE_EMAIL && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    $this->addErrorByRule($attribute, self::RULE_EMAIL);
                }

                if ($ruleName === self::RULE_MIN && strlen($value) < $rule['min']) {
                    $this->addErrorByRule($attribute, self::RULE_MIN, ['min' => $rule['min']]);
                }

                if ($ruleName === self::RULE_MAX && strlen($value) > $rule['max']) {
                    $this->addErrorByRule($attribute, self::RULE_MAX, ['max' => $rule['max']]);
                }

                if ($ruleName === self::RULE_MATCH) {
                    if ($rule['class'] || $rule['tableName']) {
                        $tableName =  $rule['tableName'] ?? $rule['class']::tableName();
                        $record = $this->query
                            ->setTable($tableName)
                            ->setType('single')
                            ->where($rule['match'], $value)
                            ->select();

                        if (!$record) {
                            $this->addErrorByRule($attribute, self::RULE_MATCH, ['match' => $rule['match']]);
                        }
                    } else {
                        if ($value !== $this->{$rule['match']}) {
                            $this->addErrorByRule($attribute, self::RULE_MATCH, ['match' => $rule['match']]);
                        }
                    }
                }

                if ($ruleName === self::RULE_UNIQUE) {
                    $uniqueAttr = $rule['attribute'] ?? $attribute;
                    $record = $this->query
                        ->setType('single')
                        ->where($uniqueAttr, $value)
                        ->select();

                    if ($record) {
                        $this->addErrorByRule($attribute, self::RULE_UNIQUE);
                    }
                }
            }
        }
        return empty($this->errors);
    }

    public function errorMessages() {
        return [
            self::RULE_REQUIRED => 'This field is required',
            self::RULE_EMAIL => 'This field must be valid email address',
            self::RULE_MIN => 'Min length of this field must be {min}',
            self::RULE_MAX => 'Max length of this field must be {max}',
            self::RULE_MATCH => 'This field must be the same as {match}',
            self::RULE_UNIQUE => 'Record with this {field} already exists',
        ];
    }

    protected function addErrorByRule($attribute, $rule, $params = []) {
        $params['field'] ??= $attribute;
        $errorMessage = $this->errorMessages()[$rule];

        foreach ($params as $key => $value) {
            $errorMessage = str_replace("{{$key}}", $value, $errorMessage);
        }
        $this->errors[$attribute][] = $errorMessage;
    }

    public function addError($attribute, $message) {
        $this->errors[$attribute][] = $message;
    }
}
