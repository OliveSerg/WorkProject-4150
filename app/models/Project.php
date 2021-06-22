<?php

namespace models;

use general\Model;

class Project extends Model {
    public $Pname = '';
    public $Pnumber = '';
    public $Plocation = '';
    public $Dnum = '';

    public function __construct() {
        $this->table = "PROJECT";
        $this->attributes = ['primarykey' => 'Pnumber', 'Pname', 'Plocation', 'Dnum'];
        parent::__construct($this->table);
    }
}
