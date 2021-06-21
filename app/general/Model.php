<?php

namespace general;

use \general\WebApp;

abstract class Model {

    protected $attributes = [];
    protected $query;

    public function __construct($tableName) {
        $this->query = new Query($tableName);
    }

    public static function find($id) {
        $this->query->where($this->)
    }

    public static function findAll($cond) {
    }

    public function loadData($data) {
        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                $this->{$key} = $value;
            }
        }
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
}
