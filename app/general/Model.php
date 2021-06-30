<?php

namespace general;

use \general\Query;

abstract class Model {

    protected $table = '';
    protected $attributes = [];
    protected Query $query;

    public function __construct($tableName = '') {
        $this->query = new Query($tableName);
    }

    public function save() {
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

    public function getTable() {
        return $this->table;
    }

    public function setTable($tableName) {
        $this->table = $tableName;
        $this->query->setTable($tableName);
    }
}
