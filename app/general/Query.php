<?php

namespace general;

class Query {
    private $defautTable;
    protected $tableName;
    protected $sql;
    protected $params;
    protected $where;
    protected $join;
    protected $orderBy;
    protected $sqlType;

    public function __construct($tableName) {
        $this->tableName = $tableName;
        $this->defautTable = $tableName;
    }

    public function _init() {
        $this->sql = '';
        $this->tableName = $this->defautTable;
        $this->sqlType = '';
        $this->params = [];
        $this->where = [];
        $this->join = [];
        $this->orderBy = [];
    }

    private function _run() {
        $pdoStmt = WebApp::$app->db->prepare($this->sql);
        $result = $pdoStmt->execute($this->params);

        if ($this->sqlType == 'single') {
            $result = $pdoStmt->fetch();
        } elseif ($this->sqlType == 'collection') {
            $result = $pdoStmt->fetchAll();
        }

        $this->_init();
        return $result;
    }

    public function raw($sql, $params) {
        $this->sql = $sql;
        $this->params = $params;
        return $this->_run();
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

    public function delete() {
        $this->sql = 'DELETE FROM ' . $this->tableName;
        $this->_buildWhere();
        return $this->_run();
    }

    public function where($column, $value, $type = '', $operator = '=') {
        $this->where[] = [
            'column' => $column,
            'value' => $value,
            'type' => $type,
            'operator' => $operator
        ];
        return $this;
    }

    private function _buildWhere() {
        if ($this->where) {
            $this->sql .= ' WHERE';

            foreach ($this->where as $clause) {
                $varName = ':' . $clause['column'];
                if ($clause['operator'] == 'IN' || $clause['operator'] == 'NOT IN') {
                    $varName = '(';
                    $i = 0;
                    foreach ($clause['value'] as $value) {
                        $curName = ':' . $clause['column'] . $i++;
                        $this->params[$curName] = $value;
                        $varName .= $curName . ($i < count($clause['value']) ? ',' : '');
                    }
                    $varName .= ')';
                } else {
                    $this->params[$varName] = $clause['value'];
                }
                $this->sql .= ' ' . $clause['type'] . ' ' . $clause['column'] . ' ' . $clause['operator'] . ' ' . $varName;
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
                $this->sql .= ' ' . strtoupper($clause['type']) . ' JOIN ' . $clause['table'];
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
        return $this;
    }

    public function setType($type) {
        $this->sqlType = $type;
        return $this;
    }
}
