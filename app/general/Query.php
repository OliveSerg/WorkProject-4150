<?php

namespace general;

class Query {
    protected $tableName;
    protected $sql;
    protected $params;
    protected $where;
    protected $join;
    protected $orderBy;
    protected $sqlType;

    public function __construct($tableName) {
        $this->tableName = $tableName;
    }

    public function _init() {
        $this->sql = '';
        $this->tableName = '';
        $this->sqlType = '';
        $this->params = [];
        $this->where = [];
        $this->join = [];
        $this->orderBy = [];
    }

    private function _run() {
        $pdoStmt = WebApp::$app->db->prepare($this->sql);
        $pdoStmt->execute($this->params);
        $result = NULL;

        if ($this->sqlType == 'single') {
            $result = $pdoStmt->fetch();
        } elseif ($this->sqlType == 'collection') {
            $result = $pdoStmt->fetchAll();
        }

        $this->_init();
        return $result;
    }

    public function select($attr = '*') {
        $this->sql = 'SELECT ' . $attr . ' FROM ' . $this->tableName;
        $this->_buildJoin();
        $this->_buildWhere();
        $this->_buildOrderBy();
        return $this->_run();
    }

    public function insert($data) {
        $this->sql = 'INSERT INTO ' . $this->tableName .
            ' (' . implode(',', array_keys($data)) .
            ') VALUES (' . ':' . implode(',:', array_keys($data)) . ')';

        foreach ($data as $key => $value) {
            $this->params[':' . $key] = $value;
        }

        return $this->_run();
    }

    // public function update() {
    // }

    // public function delete() {
    // }

    public function where($column, $value, $type = '') {
        $this->where[] = [
            'column' => $column,
            'value' => $value,
            'type' => $type
        ];
        return $this;
    }

    private function _buildWhere() {
        if ($this->where) {
            $this->sql .= ' WHERE';

            foreach ($this->where as $clause) {
                $varName = ':' . $clause['column'];
                $this->sql .= ' ' . $clause['type'] . ' ' . $clause['column'] . ' = ' . $varName;
                $this->params[$varName] = $clause['value'];
            }
        }
    }

    public function join($type, $table, $onCond) {
        $this->join[] = [
            'type' => $type,
            'table' => $table,
            'onCond' => $onCond
        ];
        return $this;
    }

    private function _buildJoin() {
        if ($this->join) {
            foreach ($this->join as $clause) {
                $this->sql .= ' ' . strtoupper($clause['type']) . ' JOIN ' . $join['table'];
                if ($clause['onCond']) {
                    $this->sql .= ' ON (' . $clause['onCond'] . ')';
                }
            }
        }
    }

    public function orderBy($column, $direction) {
        $this->orderBy[] = [
            'column' => $column,
            'direction' => $direction
        ];
        return $this;
    }

    private function _buildOrderBy() {
        if ($this->orderBy) {
            $this->sql .= ' ORDER BY ';
            for ($i = 0; $i < sizeof($this->orderBy); $i++) {
                $this->sql .= $this->orderBy[$i]['column'] . ' ' . $this->orderBy[$i]['direction'];
                if ($i < sizeof($this->orderBy) - 1) {
                    $this->sql .= ', ';
                }
            }
        }
    }

    public function setTable($tableName) {
        $this->tableName = $tableName;
    }

    public function setType($type) {
        $this->sqlType = $type;
    }
}
